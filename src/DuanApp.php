<?php

namespace Duan;

use Cicada\Application;
use Duan\Providers\AuthProviders\TokenAuthProvider;
use Duan\Providers\AuthProviders\WebAuthProvider;
use Duan\Providers\CSRFProvider;
use Duan\Providers\HashProvider;
use Duan\Providers\JWTProvider;
use Duan\Providers\LoggerProvider;
use Duan\Providers\TwigProvider;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Phormium\DB;
use Symfony\Component\Yaml\Parser;

class DuanApp extends Application
{
    private $env;
    private $projectRoot;
    private $config;

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

    public function boot()
    {
        $this->setupLogger();
        $this->setupCSRF();
        $this->setupTwig();
        $this->setupAuth();
        $this->setupJWT();
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
