<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class ContaControllerTest extends WebTestCase
{

    /**
     * @var Client
     */
    private $client;

    public function getCredencial()
    {
        return array(
            "grant_type" => "password",
            "client_id" => "1_3bcbxd9e24g0gk4swg0kwgcwg4o8k8g4g888kwc44gcc0gwwk4",
            "client_secret" => "4ok2x70rlfokc8g0wws8c8kwcokw80k44sg48goc0ok4w0so0k",
            "username" => "leidison.sb@gmail.com",
            "password" => "123456"
        );
    }

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testLegacyCadastro()
    {

        $password = 123456;
        $dados = array(
            'email' => uniqid('email.teste') . '@gmail.com',
            'password' => $password,
            'plainPassword' => $password
        );

        $this->client->request(
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
        $response = json_decode($this->client->getResponse()->getContent());

        $session = $this->client->getContainer()->get('session');
        $session->set('credenciais', $dados);
        $session->save();

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(array('success' => true), $response);

    }

    public function testLegacyLogin()
    {

        $content = $this->login($this->getCredencial());

        $response = json_decode($content);
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertTrue(!empty($response->access_token));
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function testLegacyLoginError()
    {

        $credencial = $this->getCredencial();
        unset($credencial['grant_type']);

        $content = $this->login($credencial);

        $response = json_decode($content);

        $this->assertTrue(!empty($response->error));
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function login($credencial)
    {
        $credencial = $credencial ? $credencial : $credencial;

        $this->client->request(
            'POST',
            '/api/oauth/v2/token',
            // parameters
            array(),
            // files
            array(),
            // headers
            array(
                'CONTENT_TYPE' => 'application/json',
            ),
            // body request (json)
            json_encode($credencial)
        );

        return $this->client->getResponse()->getContent();
    }
}
