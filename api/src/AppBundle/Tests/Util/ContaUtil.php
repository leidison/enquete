<?php

namespace AppBundle\Tests\Util;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;

class ContaUtil
{
    private $client;
    private $header;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->header = new HeaderUtil($client);
    }

    private function getHeader()
    {
        return $this->header;
    }


    /**
     * Função que autentica o usuário e retorna o token de acesso
     * @param $usuarioNovo
     * @param $credencial
     * @return string
     */
    public function login($usuarioNovo = false, $credencial = null)
    {
        $credencial = $credencial ? $credencial : $this->getCredencial();

        if ($usuarioNovo) {
            $usuario = array(
                'email' => uniqid('teste.') . '@gmail.com',
                'password' => 123456,
                'plainPassword' => 123456
            );
            $this->cadastro($usuario);
            $credencial['username'] = $usuario['email'];
        } else {
            // verifica se usuário default existe
            $usuario = $this->getRepository()->findOneBy(array('email' => $credencial['username']));
            if (!$usuario) {
                $usuario = array(
                    'email' => $credencial['username'],
                    'password' => 123456,
                    'plainPassword' => 123456
                );
                $this->cadastro($usuario);
            }
        }
        $this->client->request(
            'POST',
            '/api/oauth/v2/token',
            // parameters
            array(),
            // files
            array(),
            // headers
            $this->getHeader()->getHeaders(),
            // body request (json)
            json_encode($credencial)
        );

        $response = json_decode($this->client->getResponse()->getContent());

        return $response;
    }

    public function getCredencial()
    {
        return array(
            "username" => "teste.default@gmail.com",
            "password" => "123456",
            "grant_type" => "password",
            "client_id" => "1_3bcbxd9e24g0gk4swg0kwgcwg4o8k8g4g888kwc44gcc0gwwk4",
            "client_secret" => "4ok2x70rlfokc8g0wws8c8kwcokw80k44sg48goc0ok4w0so0k"
        );
    }

    public function cadastro($conta)
    {
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
            json_encode($conta)
        );

        $resposne = json_decode($this->client->getResponse()->getContent());

        return $resposne;
    }

    public function getRepository()
    {
        return $this->client->getContainer()->get('doctrine')->getRepository(User::class);
    }
}
