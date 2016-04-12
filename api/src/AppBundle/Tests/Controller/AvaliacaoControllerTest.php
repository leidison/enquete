<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Util\ContaUtil;
use AppBundle\Tests\Util\HeaderUtil;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class AvaliacaoControllerTest extends WebTestCase
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
     * Objetiva o sucesso da avaliacao
     */
    public function testLegacyAvaliar()
    {
        $response = $this->cadastro();

        $enquete = $this->getRepository()->find($response->enquete);

        $headers = $this->getHeader()->getHeaders();

        $perguntas = $enquete->getPerguntas();
        $qtd = count($perguntas);

        $avaliacoes = array();

        for ($i = 0; $i < $qtd; $i++) {
            $pergunta = $perguntas[$i];
            $respostas = $pergunta->getRespostas();
            $iRespo = rand(0, count($pergunta->getRespostas()) - 1);
            $resposta = $respostas[$iRespo];
            $avaliacoes[] = array(
                'pergunta' => $pergunta->getId(),
                'resposta' => $resposta->getId()
            );
        }
        
        $this->client->request(
            'POST',
            "/api/enquete/{$response->enquete}/avaliacao",
            // parameters
            array(),
            // files
            array(),
            // headers
            $headers,
            json_encode($avaliacoes)
        );
        $restponse = json_decode($this->client->getResponse()->getContent());
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue($restponse->success);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

    }

    public function cadastro()
    {

        $token = $this->getConta()->login();

        $headers = $this->getHeader()->getHeaders($token);

        $this->client->request(
            'POST',
            '/api/enquete',
            // parameters
            array(),
            // files
            array(),
            // headers
            $headers
            ,
            // body request (json)
            json_encode($this->getTemplate())
        );
        $restponse = json_decode($this->client->getResponse()->getContent());
        return $restponse;
    }

    public function getTemplate()
    {
        return array(
            'titulo' => uniqid('TESTE enquete '),
            'perguntas' => array_fill(0, rand(5, 10), array(
                'descricao' => uniqid('TESTE pergunta '),
                'respostas' => array_fill(0, rand(5, 10), array('descricao' => uniqid('TESTE resposta ')))
            ))
        );
    }

    public function getRepository()
    {
        return $this->client->getContainer()->get('doctrine')->getRepository('AppBundle\Entity\Enquete');
    }
}
