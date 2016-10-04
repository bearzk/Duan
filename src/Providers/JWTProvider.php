<?php
namespace Duan\Providers;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class JWTProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $jwtConfig = $container->getConfig()['jwt'];
        $container['jwt_builder'] = $this->createBuilder($jwtConfig);
        $container['jwt_parser'] = $this->createParser($jwtConfig);
        $container['jwt_validator'] = $this->createValidator($jwtConfig);
    }

    public function createBuilder($config)
    {
        return function ($claims) use ($config) {

            $builder = new Builder();

            if ($config['sign']) {
                $signer = new Sha256();
                $builder->sign($signer, $config['sign_key']);
            }

            $builder->setIssuer($config['issuer']);

            if ($config['audiences']) {
                foreach ($config['audiences'] as $audience) {
                    $builder->setAudience($audience);
                }
            }

            $builder->setIssuedAt(time());

            $exp = $config['exp'] ? $config['exp'] : 3600 * 24 * 90;

            $builder->setExpiration($exp);

            foreach ($claims as $k => $v) {
                $builder->set($k, $v);
            }

            return $builder->getToken();
        };
    }

    public function createParser($config)
    {

    }

    public function createValidator($config)
    {

    }
}