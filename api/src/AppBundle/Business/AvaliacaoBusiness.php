<?php

namespace AppBundle\Business;

use  AppBundle\Entity\Enquete;
use FOS\UserBundle\Model\User;

class AvaliacaoBusiness extends Base
{

    public function gerar(Enquete $enquete, $avaliacoes)
    {
        if ($this->validaVarias($enquete, $avaliacoes)) {
            $this->getRepository()->gerarAvaliacao($enquete, $avaliacoes);
            return true;
        }
        return false;
    }

    private function validaVarias($enquete, $avaliacoes)
    {
        $perguntas = array();
        foreach ($avaliacoes as $avaliacao) {
            // verifica se tem pergunta repetida
            if (in_array($avaliacao['pergunta'], $perguntas)) {
                return false;
            }
            $perguntas[] = $avaliacao['pergunta'];
            $valido = $this->valida($enquete, $avaliacao);
            if ($valido == false) {
                return false;
            }
        }
        return true;
    }

    private function valida($enquete, $avaliacao)
    {
        if (!empty($avaliacao['pergunta']) || !empty($avaliacao['resposta'])) {
            $perguntas = $enquete->getPerguntas()->filter(function ($pergunta) use ($avaliacao) {
                return $pergunta->getId() == $avaliacao['pergunta'];
            });
            if (count($perguntas)) {
                $pergunta = $perguntas->first();
                $exists = $pergunta->getRespostas()
                    ->exists(function ($key, $resposta) use ($avaliacao) {
                        return $resposta->getId() == $avaliacao['resposta'];
                    });
                return $exists;
            }
        }
        return false;
    }
}
