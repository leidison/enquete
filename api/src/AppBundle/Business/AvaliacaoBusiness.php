<?php

namespace AppBundle\Business;

use  AppBundle\Entity\Enquete;
use FOS\UserBundle\Model\User;

class AvaliacaoBusiness extends Base
{

    public function gerar(Enquete $enquete, $avaliacoes)
    {
        $valido = $this->valido($enquete, $avaliacoes);
        if ($valido) {
            
        }
        return false;
    }

    private function valido($enquete, $avaliacoes)
    {
        if (count($avaliacoes)) {
            foreach ($avaliacoes as $avaliacao) {
                if (empty($avaliacao['pergunta']) || empty($avaliacao['resposta'])) {
                    return false;
                }
                // verifica se a pergunta está vinculada à enquete
                $vinculada = $enquete->getPerguntas()->exists(function ($key, $pergunta) use ($avaliacao) {
                    return $avaliacao['pergunta'] == $pergunta->getId();
                });
                if ($vinculada == false) {
                    return false;
                } else {
                    // verifica se a resposta está vinculada a pergunta
                    $avaliacao['resposta'];
                    foreach ($enquete->getPerguntas() as $pergunta) {
                        // se a pergunta for diferente a avaliação então pulo para proxima pergunta.
                        // Pois, ou ela foi marcada e vai aparecer no proximo loop ou ela não foi marcada
                        if ($pergunta->getId() != $avaliacao['pergunta']) {
                            continue;
                        } else {
                            // verifica se a respota está vinculada à pergunta
                            $vinculada = $pergunta->getRespostas()->exists(function ($key, $resposta) use ($avaliacao) {
                                return $avaliacao['resposta'] == $resposta->getId();
                            });
                            // se estiver vinculada passa para a proxima
                            if ($vinculada) {
                                continue;
                            } else {
                                return false;
                            }
                        }
                    }
                }
            }
        }
        // como não foi retornado false nos fluxos acima, então, devo retornar true.
        // Pois está tudo vinculado corretamente.
        return true;
    }
}
