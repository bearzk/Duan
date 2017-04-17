<?php
namespace Duan\Lib;

use Duan\DuanApp;
use Duan\Models\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class WebAuthenticator
{
    private $app;
    /** @var JWTFacade $jwt */
    private $jwt;

    public function __construct(DuanApp $app)
    {
        $this->app = $app;
        $this->jwt = $app['jwt'];
    }

    /**
     * Authenticate by email password combination
     * return corresponding user when found,
     * false when NOT found.
     *
     * @param Request $request
     * @return bool|User
     */
    public function auth(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        /** @var User $user */
        $user = User::objects()
            ->filter('email', '=', $email)
            ->single(true);

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            $this->app->storeUser($user);
            return $user;
        }

        return false;
    }

    public function cookieAuth(Request $request)
    {
        $session = $request->cookies->get('session');

        if (!empty($session)) {
            try {
                $token = $this->jwt->parse($session);
                if (!$this->jwt->validate($token)) {
                    return new RedirectResponse('/signin');
                }
                $this->app->setSessionToken($token);
            } catch (\Exception $ex) {
                return new RedirectResponse('/signin');
            }

            $user = User::objects()
                ->filter('email', '=', $token->getClaim('email'))
                ->single(true);

            if ($user) {
                $this->app->storeUser($user);
                /** @var Twig_Environment $twig */
                $twig = $this->app['twig'];
                $twig->addGlobal('user', $user);
            }
        } else {
            return new RedirectResponse('/signin');
        }
    }

    public function refreshToken(Response $response, $expiresIn = 14)
    {
        $user = $this->app->getUser();
        if (!empty($user) && !$this->responseHasSession($response)) {
            $token = (string) $this->jwt->build(['email' => $user->email, 'login' => true], $expiresIn);
            $cookie = new Cookie('session',
                $token,
                time() + 3600 * 24 * $expiresIn,
                '/',
                '.',
                $this->app->getConfig()['base_domain']
            );
            $response->headers->setCookie($cookie);
        }
        return $response;
    }

    private function responseHasSession(Response $response)
    {
        foreach ($response->headers->getCookies() as $cookie) {
            /** @var Cookie $cookie */
            if ('session' == $cookie->getName()) {
                return true;
            }
        }
    }
}
