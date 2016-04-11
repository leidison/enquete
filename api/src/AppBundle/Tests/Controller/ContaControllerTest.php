<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContaControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();

        $password = 123456;
        $dados = array(
            'email' => uniqid('email.teste') . '@gmail.com',
            'password' => $password,
            'plainPassword' => $password
        );

        $crawler = $client->request(
            'POST',
            '/api/conta',
            // parameters
            array(),
            // files
            array(),
            // headers
            array(),
            // body request (json)
            json_encode($dados)
        );

    }

    public function testeLogin()
    {

    }
}
