<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class EnqueteControllerTest extends WebTestCase
{

    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * Objetiva o cadastro com sucesso
     */
    public function testLegacyCadastro()
    {
        $response = $this->cadastro();

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_object($response));
        $this->assertTrue($response->success == true);
        $this->assertTrue(is_int($response->enquete) && $response->enquete > 0);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * Objetiva mensagem de conteudo restrito
     */
    public function testLegacyCadastroSemToken()
    {
        $this->manterSemToken();
    }

    /**
     * Objetiva mensagens de validação quando a enquete está sem perguntas
     * ou quando uma das perguntas está sem respostas
     */
    public function testLegacyCadastroSemRespostaOuPergunta()
    {
        $this->manterSemRespostaOuPergunta();
    }

    /**
     * Objetiva mensagens de validação quando não existe titulo ou descricoes das perguntas/respostas,
     * quando o tamanho das descricoes ou titulo for maior que o tamanho correto.
     * Almeja também, a mensagem de sucesso quando o titulo ou as descricoes das perguntas/resposta
     * estiverem com tamanho máximo permitido
     */
    public function testLegacyCadastroTratandoDescricoesETitulo()
    {
        $this->manterTratandoDescricoesETitulo();
    }

    /**
     * Objetiva o sucesso da edição
     */
    public function testLegacyEdicao()
    {
        $template = $this->getTemplate();
        $response = $this->cadastro();

        $template['id'] = $response->enquete;

        $contentEdicao = $this->manter($template, true, true);
        $responseEdicao = json_decode($contentEdicao);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(is_object($responseEdicao));
        $this->assertTrue($responseEdicao->success == true);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * Objetiva o erro da edição sem passar um id ou com um id com tipo invalido
     */
    public function testLegacyEdicaoTratandoId()
    {
        $template = $this->getTemplate();
        // faço o cadastro para uma pessoa diferente
        $contentCadastro = $this->manter($template, true, false, array(
            'username' => uniqid() . 'email.teste@gmail.com',
            'password' => 123456,
        ));

        $responseCadastro = json_decode($contentCadastro);

        $template['id'] = $responseCadastro->enquete;

        // tento editar com o usuário default dos nossos testes
        $contentEdicaoIdPessoaDiferente = $this->manter($template, true, true);

        // verificando a edicao de uma enquete com id no formato incorreto
        $response = $this->cadastro();
        $template['id'] = 0;
        $contentEdicaoIdErrado = $this->manter($template, true, true);

        $responseEdicaoIdPessoaDiferente = json_decode($contentEdicaoIdPessoaDiferente);
        $responseEdicaoIdErrado = json_decode($contentEdicaoIdErrado);

        $this->assertTrue($responseEdicaoIdErrado == null);
        $this->assertTrue($responseEdicaoIdPessoaDiferente == null);
    }

    /**
     * Objetiva mensagem de conteudo restrito
     */
    public function testLegacyEdicaoSemToken()
    {
        $response = $this->cadastro();
        $this->manterSemToken($response->enquete);
    }

    /**
     * Objetiva mensagens de validação quando a enquete está sem perguntas
     * ou quando uma das perguntas está sem respostas
     */
    public function testLegacyEdicaoSemRespostaOuPergunta()
    {
        $response = $this->cadastro();
        $this->manterSemRespostaOuPergunta($response->enquete);
    }

    /**
     * Objetiva mensagens de validação quando não existe titulo ou descricoes das perguntas/respostas,
     * quando o tamanho das descricoes ou titulo for maior que o tamanho correto.
     * Almeja também, a mensagem de sucesso quando o titulo ou as descricoes das perguntas/resposta
     * estiverem com tamanho máximo permitido
     */
    public function testLegacyEdicaoTratandoDescricoesETitulo()
    {
        $response = $this->cadastro();
        $this->manterTratandoDescricoesETitulo($response->enquete);
    }

    /**
     * Objetiva mensagens de valicaçao quando os ids das perguntas
     * não pertencerem (ou não existirem) para a enquete.
     * Ou quando a resposta não existir ou não pertencer a pergunta
     */
    public function testLegacyEdicaoComIdsIncorretos()
    {

        /**
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         */
    }


    /**
     * Objetiva o sucesso da exclusao
     */
    public function testLegacyExclusao()
    {

        /**
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         */
    }

    /**
     * Objetiva a mensagem de erro quando utilizado um id incorreto
     */
    public function testLegacyExclusaoTrantandoIds()
    {

        /**
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         */
    }


    public function cadastro()
    {
        $template = $this->getTemplate();
        $content = $this->manter($template);
        $response = json_decode($content);
        return $response;
    }

    public function manterTratandoDescricoesETitulo($id = null)
    {
        $template = $this->getTemplate();

        if ($id) {
            $template['id'] = $id;
            $edicao = true;
        } else {
            $edicao = false;
        }

        $str256 = str_repeat('a', 256);
        $str255 = str_repeat('a', 255);

        $template['titulo'] = $str256;
        $template['perguntas'][0]['descricao'] = $str256;
        $template['perguntas'][0]['respostas'][0]['descricao'] = $str256;
        $contentString256 = $this->manter($template, true, $edicao);

        $template['titulo'] = $str255;
        $template['perguntas'][0]['descricao'] = $str255;
        $template['perguntas'][0]['respostas'][0]['descricao'] = $str255;
        $contentString255 = $this->manter($template, true, $edicao);

        unset($template['titulo']);
        unset($template['perguntas'][0]['descricao']);
        unset($template['titulo']);
        unset($template['perguntas'][0]['respostas'][0]['descricao']);
        $contentSemDescricaoTitulo = $this->manter($template, true, $edicao);

        $response256 = json_decode($contentString256);
        $response255 = json_decode($contentString255);
        $responseSemDescricaoTitulo = json_decode($contentSemDescricaoTitulo);

        $this->assertTrue(is_array($response256));
        $this->assertTrue(is_object($response255));
        $this->assertTrue($response255->success == true);

        $this->assertTrue($response256[0]->property_path == 'titulo');
        $this->assertTrue($response256[1]->property_path == 'perguntas[0].descricao');
        $this->assertTrue($response256[2]->property_path == 'perguntas[0].respostas[0].descricao');

        $this->assertTrue($responseSemDescricaoTitulo[0]->property_path == 'titulo');
        $this->assertTrue($responseSemDescricaoTitulo[1]->property_path == 'perguntas[0].descricao');
        $this->assertTrue($responseSemDescricaoTitulo[2]->property_path == 'perguntas[0].respostas[0].descricao');

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function manterSemRespostaOuPergunta($id = null)
    {
        $templateOriginal = $this->getTemplate();

        if ($id) {
            $templateOriginal['id'] = $id;
            $edicao = true;
        } else {
            $edicao = false;
        }

        $template = $templateOriginal;
        unset($template['perguntas']);
        $contentSemPergunta = $this->manter($template, true, $edicao);

        $template = $templateOriginal;
        unset($template['perguntas'][0]['respostas']);
        $contentSemResposta = $this->manter($template, true, $edicao);

        $responseSemPergunta = json_decode($contentSemPergunta);
        $responseSemResposta = json_decode($contentSemResposta);

        $this->assertTrue(is_array($responseSemPergunta));
        $this->assertTrue(is_array($responseSemResposta));

        $this->assertTrue($responseSemPergunta[0]->property_path == 'perguntas');
        $this->assertTrue($responseSemResposta[0]->property_path == 'perguntas[0].respostas');

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function manterSemToken($id = null)
    {
        $template = $this->getTemplate();

        if ($id) {
            $template['id'] = $id;
            $edicao = true;
        } else {
            $edicao = false;
        }

        $content = $this->manter($template, false, $edicao);
        $response = json_decode($content);

        $this->assertTrue(is_object($response));
        $this->assertTrue($response->error == 'access_denied');

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }


    /**
     * Função que autentica o usuário e retorna o token de acesso
     * @param $credencial
     * @return string
     */
    public function login($credencial)
    {

        $this->client->request(
            'POST',
            '/api/oauth/v2/token',
            // parameters
            array(),
            // files
            array(),
            // headers
            $this->getHeaders(),
            // body request (json)
            json_encode($credencial)
        );

        return $this->client->getResponse()->getContent();
    }

    /**
     * Para facilitar o cadastro de enquetes
     *
     * @param $enquete
     * @param bool $comToken
     * @return string
     */
    public function manter($enquete, $comToken = true, $edicao = false, $dadosUsuario = null)
    {
        $credencial = $this->getCredencial();
        if ($dadosUsuario) {
            $content = $this->cadastrarUsuario($dadosUsuario);
            $credencial['username'] = $dadosUsuario['username'];
            $credencial['password'] = $dadosUsuario['password'];
        }

        if ($comToken) {
            $content = $this->login($credencial);
            $token = json_decode($content);
            $headers = $this->getHeaders($token);
        } else {
            $headers = $this->getHeaders();

        }
        $this->client->request(
            $edicao ? 'PUT' : 'POST',
            // verifico se tem o id também, pq posso testar uma edicao sem passar o id
            '/api/enquete' . ($edicao && isset($enquete['id']) ? "/{$enquete['id']}" : ''),
            // parameters
            array(),
            // files
            array(),
            // headers
            $headers
            ,
            // body request (json)
            json_encode($enquete)
        );
        return $this->client->getResponse()->getContent();
    }

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

    public function cadastrarUsuario($user)
    {
        // verificando a edição de uma enquete feita por outra pessoa
        $password = 123456;
        $dados = array(
            'email' => $user['username'],
            'password' => $user['password'],
            'plainPassword' => $user['password']
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
        $usuarioCadastrado = json_decode($this->client->getResponse()->getContent());

        $this->assertTrue($usuarioCadastrado->success);
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

    public function getHeaderToken($token)
    {
        return array('HTTP_Authorization' => ucfirst($token->token_type) . " {$token->access_token}");
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
}
