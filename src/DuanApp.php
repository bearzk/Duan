<?php

namespace Duan;

use Cicada\Application;
use Cicada\Routing\Route;
use Cicada\Routing\Router;
use Duan\Providers\AuthProviders\TokenAuthProvider;
use Duan\Providers\AuthProviders\WebAuthProvider;
use Duan\Providers\CSRFProvider;
use Duan\Providers\HashProvider;
use Duan\Providers\JWTProvider;
use Duan\Providers\LoggerProvider;
use Duan\Providers\TwigProvider;
use Evenement\EventEmitter;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Phormium\DB;
use Symfony\Component\Yaml\Parser;

class DuanApp extends Application
{
    private $env;
    private $projectRoot;
    private $config;
    private $sessionToken;

    public static $envs = [
        'local',
        'prod',
        'test'
    ];

    public function __construct($env)
    {
        parent::__construct();

        $this->env = $env;

        $this->projectRoot = rtrim(getcwd(), '/public');
    }

    public function setSessionToken(Token $token)
    {
        $this->sessionToken = $token;
    }

    public function getSessionToken()
    {
        return $this->sessionToken;
    }

    public function boot()
    {
        $this->setupLogger();
        $this->setupCSRF();
        $this->setupTwig();
        $this->setupAuth();
        $this->setupJWT();
        $this->setupEvents();
    }

    public function getEnv()
    {
        return $this->env;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getProjectRoot()
    {
        return $this->projectRoot;
    }

    public function setupLogger()
    {
        $lp = new LoggerProvider;
        $lp->register($this);
    }

    public function setupTwig()
    {
        $tp = new TwigProvider;
        $tp->register($this);
    }

    public function setupCSRF()
    {
        $csrfp = new CSRFProvider;
        $csrfp->register($this);
    }

    public function setupJWT()
    {
        $signer = new Sha256;
        $jwtp = new JWTProvider();
        $jwtp->register($this, $signer);
    }

    public function setupAuth()
    {
        $tokenAuthProvider = new TokenAuthProvider;
        $tokenAuthProvider->register($this);

        $webAuthProvider = new WebAuthProvider;
        $webAuthProvider->register($this);
    }

    public function setupEvents()
    {
        /** @var EventEmitter $emitter */
        $emitter = $this['emitter'];

        $emitter->on(Router::EVENT_MATCH, function ($app, $request, Route $route) {
            $this['logger']->info($route->getName());
        });
    }

    public function configure()
    {
        $configPath = $this->projectRoot . "/etc/$this->env.yml";
        $parser = new Parser();

        $this->config = $parser->parse(file_get_contents($configPath));

        $dbConfig = [
            'databases' => [
                'duan' => $this->config['database']
            ]
        ];

        DB::configure($dbConfig);
    }
}
