<?php

namespace AppBundle\Tests\Util;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;

class HeaderUtil
{

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getHeaders($token = null)
    {
        $headers = array(
            'CONTENT_TYPE' => 'application/json',
        );
        if ($token) {
            $headers = array_merge($headers, $this->getHeaderToken($token));
        }
        return $headers;
    }

    private function getHeaderToken($token)
    {
        return array('HTTP_Authorization' => ucfirst($token->token_type) . " {$token->access_token}");
    }
}
