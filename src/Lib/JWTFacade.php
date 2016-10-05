<?php
namespace Duan\Lib;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

class JWTFacade
{
    public $config;
    public $signer;

    public function __construct($config, Signer $signer = null)
    {
        $this->config = $config;
        $this->signer = $signer;
    }

    public function build($claims)
    {
        $builder = new Builder;

        $builder->setIssuer($this->config['issuer']);

        if (!empty($this->config['audiences'])) {
            foreach ($this->config['audiences'] as $audience) {
                $builder->setAudience($audience);
            }
        }

        $time = time();

        $builder->setIssuedAt($time);

        $days = !empty($this->config['exp']) ? $this->config['exp'] : 90;
        $exp = 3600 * 24 * $days;
        $builder->setExpiration($time + $exp);

        foreach ($claims as $k => $v) {
            $builder->set($k, $v);
        }

        if (!empty($this->config['sign_key'])) {
            $builder->sign($this->signer, $this->config['sign_key']);
        }

        return $builder->getToken();
    }

    public function parse($tokenString)
    {
        return (new Parser)->parse($tokenString);
    }

    public function validate(Token $token)
    {
        $data = new ValidationData;

        $data->setIssuer($this->config['issuer']);

        if (!empty($this->config['audiences'])) {
            foreach ($this->config['audiences'] as $audience) {
                $data->setAudience($audience);
            }
        }

        $result = $token->validate($data);

        if (!empty($this->config['sign_key'])) {
            $result &= $token->verify($this->signer, $this->config['sign_key']);
        }

        return $result;
    }
}
