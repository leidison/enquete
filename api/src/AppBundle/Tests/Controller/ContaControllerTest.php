<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Util\ContaUtil;
use AppBundle\Tests\Util\HeaderUtil;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class ContaControllerTest extends WebTestCase
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ContaUtil
     */
    private $contaUtil;
    /**
     * @var HeaderUtil
     */
    private $headerUtil;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->contaUtil = new ContaUtil($this->client);
        $this->headerUtil = new HeaderUtil($this->client);
    }

    public function getConta()
    {
        return $this->contaUtil;
    }

    public function getHeader()
    {
        return $this->headerUtil;
    }

    /**
     * Objetiva o sucesso do cadastro
     */
    public function testLegacyCadastro()
    {

        $password = 123456;
        $dados = array(
            'email' => uniqid('email.teste') . '@gmail.com',
            'password' => $password,
            'plainPassword' => $password
        );

        $response = $this->getConta()->cadastro($dados);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_object($response));
        $this->assertTrue($response->success);

    }

    /**
     * Objetiva o sucesso do login
     */
    public function testLegacyLogin()
    {

        $response = $this->getConta()->login();
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertTrue(!empty($response->access_token));
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * Objetiva mensagem de erro no login
     */
    public function testLegacyLoginError()
    {
        $credencial = $this->getConta()->getCredencial();
        unset($credencial['grant_type']);
        $response = $this->getConta()->login(false, $credencial);

        $this->assertTrue(!empty($response->error));
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }
}
