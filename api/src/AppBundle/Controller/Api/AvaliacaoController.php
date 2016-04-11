<?php
namespace AppBundle\Controller\Api;

use AppBundle\Entity\Enquete;
use AppBundle\Util\AvaliacaoUtil;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * @Route("/enquete/{id}/avaliacao")
 */
class AvaliacaoController extends FOSRestController
{

    /**
     * Gera uma nova avaliação de enquete
     *
     * @Post("")
     */
    public function postAction(Enquete $enquete, Request $request)
    {
        $avaliacoes = $this->get('serializer')
            ->deserialize($request->getContent(), 'array', 'json');

        $sucesso = $this->get('avaliacao_business')->gerar($enquete, $avaliacoes);
        if ($sucesso) {
            $view = $this->view(array('success' => true));
        } else {
            $view = $this->view(array('success' => false), 400);
        }
        $view->setFormat('json');
        return $this->handleView($view);

    }

}