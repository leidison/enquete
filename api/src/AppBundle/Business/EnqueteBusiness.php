<?php

namespace AppBundle\Business;

use  AppBundle\Entity\Enquete;
use FOS\UserBundle\Model\User;

class EnqueteBusiness extends Base
{

    public function cadastro(Enquete $enquete, User $user)
    {
        $enquete->setUser($user);

        $this->getManager()->merge($enquete);

        $this->getManager()->flush();

    }

    public function edicao(Enquete $enquete, Enquete $enqueteEdicao)
    {
        $this->sincronizaPerguntaResposta($enquete, $enqueteEdicao);

        $enqueteEdicao->setUser($enquete->getUser());

        $this->getManager()->merge($enqueteEdicao);

        $this->getManager()->flush();

    }

    public function paginado($filtro, $pagina, $porPagina)
    {
        return $this->getRepository()->paginado($filtro, $pagina, $porPagina);
    }

    /**
     * Sincroniza as perguntas e respostas
     *
     * @param Enquete $enquete
     * @param Enquete $enqueteEdicao
     */
    private function sincronizaPerguntaResposta(Enquete $enquete, Enquete $enqueteEdicao)
    {
        $em = $this->getManager();

        // busca as perguntas originais para poder sincroniza-las
        foreach ($enquete->getPerguntas() as $pergunta) {
            $perguntasEditadas = $enqueteEdicao->getPerguntas();
            $index = false;
            $existe = $perguntasEditadas->exists(function ($key, $objeto) use ($pergunta, &$index) {
                $index = $key;
                return $objeto->getId() == $pergunta->getId();
            });
            if ($index !== false && $existe) {

                // pego a pergunta que está para ser alterada
                $perguntaEditada = $perguntasEditadas[$index];
                // busco as respostas originais para poder sincroniza-las
                foreach ($pergunta->getRespostas() as $resposta) {
                    // verifica se a resposta foi mantida durante a edição
                    $existe = $perguntaEditada->getRespostas()->exists(function ($key, $objeto) use ($resposta) {
                        return $objeto->getId() == $resposta->getId();
                    });
                    // a resposta não foi encontrada?
                    if (!$existe) {
                        // removo a resposta, pois ela não está no objeto de edição
                        $em->remove($resposta);
                    }
                }
            } else {
                // como a pergunta pois ela não está no objeto de edicao
                $em->remove($pergunta);
            }
        }
    }
}
