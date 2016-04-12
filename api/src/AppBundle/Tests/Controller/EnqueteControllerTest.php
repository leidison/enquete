<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Util\ContaUtil;
use AppBundle\Tests\Util\HeaderUtil;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class EnqueteControllerTest extends WebTestCase
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

    public function cadastro($enquete, $autenticado = true, $credencial = false)
    {

        if ($autenticado) {
            $token = $this->getConta()->login($credencial);
        } else {
            $token = null;
        }

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
            json_encode($enquete)
        );
        $restponse = json_decode($this->client->getResponse()->getContent());
        return $restponse;
    }

    public function edicao($enquete, $autenticado = true, $outroUsuario = false)
    {

        if ($autenticado) {
            $token = $this->getConta()->login($outroUsuario);
        } else {
            $token = null;
        }
        $headers = $this->getHeader()->getHeaders($token);

        $this->client->request(
            'PUT',
            // verifico se tem o id também, pq posso testar uma edicao sem passar o id
            '/api/enquete' . (isset($enquete['id']) ? "/{$enquete['id']}" : ''),
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
        $restponse = json_decode($this->client->getResponse()->getContent());
        return $restponse;
    }

    public function getEnquete($id = null, $token = null)
    {
        if ($token) {
            $complementoUrl = '/minhas';
        } else if ($id) {
            $complementoUrl = "/{$id}";
        } else {
            $complementoUrl = '';
        }
        $this->client->request(
            'GET',
            // verifico se tem o id também, pq posso testar uma edicao sem passar o id
            '/api/enquete' . $complementoUrl,
            // parameters
            array(),
            // files
            array(),
            // headers
            $this->getHeader()->getHeaders($token)
        );
        $response = json_decode($this->client->getResponse()->getContent(), true);
        return $response;
    }

    /**
     * Objetiva o cadastro com sucesso
     */
    public function testLegacyCadastro()
    {
        $response = $this->cadastro($this->getTemplate());
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
        $response = $this->cadastro($this->getTemplate(), false);
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
     * Objetiva mensagens de validação quando a enquete está sem perguntas
     * ou quando uma das perguntas está sem respostas
     */
    public function testLegacyCadastroSemRespostaOuPergunta()
    {
        $this->manterSemRespostaOuPergunta($this->getTemplate());
    }

    /**
     * Objetiva mensagens de validação quando não existe titulo ou descricoes das perguntas/respostas,
     * quando o tamanho das descricoes ou titulo for maior que o tamanho correto.
     * Almeja também, a mensagem de sucesso quando o titulo ou as descricoes das perguntas/resposta
     * estiverem com tamanho máximo permitido
     */
    public function testLegacyCadastroTratandoDescricoesETitulo()
    {
        $this->manterTratandoDescricoesETitulo($this->getTemplate());
    }

    /**
     * Objetiva o sucesso da listagem paginada de enquetes
     */
    public function testLegacyGet()
    {
        $template = $this->getTemplate();
        // 10 é o valor default da paginação.
        // então eu estou cadastrando 11 para testar
        for ($i = 0; $i < 11; $i++) {
            $this->cadastro($template);
        }
        $response = $this->getEnquete();
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(count($response) == 10);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * Objetiva o sucesso da listagem paginada de enquetes
     */
    public function testLegacyGetOne()
    {
        $template = $this->getTemplate();

        $response = $this->cadastro($template);

        $response = $this->getEnquete($response->enquete);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(isset($response['id']));
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    /**
     * Objetiva o sucesso da listagem paginada de enquetes de um usuário
     */
    public function testLegacyMinhasEnquetes()
    {
        $template = $this->getTemplate();
        // 10 é o valor default da paginação.
        // então eu estou cadastrando 11 para testar

        $credencial = $this->getConta()->getCredencial();
        $credencial['username'] = uniqid('teste.minhas') . '@gmail.com';

        $token = $this->getConta()->login($credencial);

        // cadastro so 4 para ver se está trazendo apenas as enquetes do novo usuário
        for ($i = 0; $i < 4; $i++) {
            $this->cadastro($template, true, $credencial);
        }
        $response = $this->getEnquete(null, $token);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(count($response) == 4);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        // cadastro mais 7 para ver se está trazendo paginado. Pois o limite é 10
        for ($i = 0; $i < 7; $i++) {
            $this->cadastro($template, true, $credencial);
        }
        $response = $this->getEnquete(null, $token);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertTrue(count($response) == 10);
        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );


    }

    /**
     * Objetiva o sucesso da edição
     */
    public function testLegacyEdicao()
    {
        $response = $this->cadastro($this->getTemplate());

        $enquete = $this->getEnquete($response->enquete);

        $responseEdicao = $this->edicao($enquete);

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
     * Objetiva o erro da edição com id invalido ou inxistente
     */
    public function testLegacyEdicaoTratandoId()
    {
        // cadastra uma enquete para um usuário diferente do default
        $response = $this->cadastro($this->getTemplate(), true, true);
        // pego um template
        $enquete = $this->getTemplate();

        // seto o id no template
        $enquete['id'] = $response->enquete;
        // tento editar a enquete com o usuário default
        $responseOutroUsu = $this->edicao($enquete);

        // id incorreto
        $enquete['id'] = 0;
        $responseIdIncorreto = $this->edicao($enquete);

        $this->assertTrue(is_null($responseOutroUsu));
        $this->assertTrue(is_null($responseIdIncorreto));
    }

    /**
     * Objetiva mensagem de conteudo restrito
     */
    public function testLegacyEdicaoSemToken()
    {
        $enquete = $this->getTemplate();
        $response = $this->cadastro($enquete);
        // seto o id no template
        $enquete['id'] = $response->enquete;
        $this->manterSemToken($enquete);
    }

    /**
     * Objetiva mensagens de validação quando a enquete está sem perguntas
     * ou quando uma das perguntas está sem respostas
     */
    public function testLegacyEdicaoSemRespostaOuPergunta()
    {
        $enquete = $this->getTemplate();
        $response = $this->cadastro($enquete);
        // seto o id no template
        $enquete['id'] = $response->enquete;
        $this->manterSemRespostaOuPergunta($enquete);
    }

    /**
     * Objetiva mensagens de validação quando não existe titulo ou descricoes das perguntas/respostas,
     * quando o tamanho das descricoes ou titulo for maior que o tamanho correto.
     * Almeja também, a mensagem de sucesso quando o titulo ou as descricoes das perguntas/resposta
     * estiverem com tamanho máximo permitido
     */
    public function testLegacyEdicaoTratandoDescricoesETitulo()
    {
        $enquete = $this->getTemplate();
        $response = $this->cadastro($enquete);
        // seto o id no template
        $enquete['id'] = $response->enquete;
        $this->manterTratandoDescricoesETitulo($enquete);
    }

    /**
     * Objetiva o sucesso da exclusao
     */
    public function testLegacyExclusao()
    {
        $token = $this->getConta()->login();

        $response = $this->cadastro($this->getTemplate());

        $this->client->request(
            'DELETE',
            '/api/enquete/' . $response->enquete,
            // parameters
            array(),
            // files
            array(),
            // headers
            $this->getHeader()->getHeaders($token)
        );
        $responseExclusao = json_decode($this->client->getResponse()->getContent());

        $enquete = $this->getRepository()->find($response->enquete);

        $this->assertTrue(is_object($responseExclusao));
        $this->assertTrue($responseExclusao->success);
        $this->assertTrue(is_null($enquete));

    }

    /**
     * Objetiva a mensagem de erro quando ocorre a tentativa de excluir uma enquete de outro usuário
     */
    public function testLegacyExclusaoTrantandoIds()
    {
        $token = $this->getConta()->login();

        $response = $this->cadastro($this->getTemplate(), true, true);

        $this->client->request(
            'DELETE',
            '/api/enquete/' . $response->enquete,
            // parameters
            array(),
            // files
            array(),
            // headers
            $this->getHeader()->getHeaders($token)
        );
        $responseExclusao = json_decode($this->client->getResponse()->getContent());
        $enquete = $this->getRepository()->find($response->enquete);

        $this->assertTrue(is_null($responseExclusao));
        $this->assertTrue(is_object($enquete));

    }

    public function manterTratandoDescricoesETitulo($enquete)
    {
        // limite maximo
        $str255 = str_repeat('a', 255);
        // extrapola o limite
        $str256 = str_repeat('a', 256);
        $enquete255 = $enquete;
        $enquete256 = $enquete;
        $enqueteSDescTit = $enquete;

        $enquete255['titulo'] = $str255;
        $enquete255['perguntas'][0]['descricao'] = $str255;
        $enquete255['perguntas'][0]['respostas'][0]['descricao'] = $str255;

        $enquete256['titulo'] = $str256;
        $enquete256['perguntas'][0]['descricao'] = $str256;
        $enquete256['perguntas'][0]['respostas'][0]['descricao'] = $str256;

        unset($enqueteSDescTit['titulo']);
        unset($enqueteSDescTit['perguntas'][0]['descricao']);
        unset($enqueteSDescTit['perguntas'][0]['respostas'][0]['descricao']);

        if (empty($enquete['id'])) {
            $response255 = $this->cadastro($enquete255);
            $response256 = $this->cadastro($enquete256);
            $responseSDescTit = $this->cadastro($enqueteSDescTit);
        } else {
            $response255 = $this->edicao($enquete255);
            $response256 = $this->edicao($enquete256);
            $responseSDescTit = $this->edicao($enqueteSDescTit);
        }

        // successo
        $this->assertTrue(is_object($response255));
        $this->assertTrue($response255->success == true);

        // erro
        $this->assertTrue(is_array($response256));
        $this->assertTrue(is_array($responseSDescTit));

        // verificação das validações
        $this->assertTrue($response256[0]->property_path == 'titulo');
        $this->assertTrue($response256[1]->property_path == 'perguntas[0].descricao');
        $this->assertTrue($response256[2]->property_path == 'perguntas[0].respostas[0].descricao');

        $this->assertTrue($responseSDescTit[0]->property_path == 'titulo');
        $this->assertTrue($responseSDescTit[1]->property_path == 'perguntas[0].descricao');
        $this->assertTrue($responseSDescTit[2]->property_path == 'perguntas[0].respostas[0].descricao');

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function manterSemRespostaOuPergunta($enquete)
    {
        $enqueteSemPergunta = $enquete;
        unset($enqueteSemPergunta['perguntas']);
        $enqueteSemResposta = $enquete;
        unset($enqueteSemResposta['perguntas'][0]['respostas']);

        if (empty($enquete['id'])) {
            $responseSemPergunta = $this->cadastro($enqueteSemPergunta);
            $responseSemResposta = $this->cadastro($enqueteSemResposta);
        } else {
            $responseSemPergunta = $this->edicao($enqueteSemPergunta);
            $responseSemResposta = $this->edicao($enqueteSemResposta);
        }


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

    public function manterSemToken($enquete = null)
    {
        if (empty($enquete['id'])) {
            $response = $this->cadastro($enquete, false);
        } else {
            $response = $this->edicao($enquete, false);
        }
        $this->assertTrue(is_object($response));
        $this->assertTrue($response->error == 'access_denied');

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }
}
