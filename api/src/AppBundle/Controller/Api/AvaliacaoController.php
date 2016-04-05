<?php
namespace AppBundle\Controller\Api;

use AppBundle\Entity\Enquete;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

/**
 * @Route("/avaliacao")
 */
class AvaliacaoController extends FOSRestController
{

    /**
     * Gera uma nova avaliação de enquete
     *
     * @Post("/")
     */
    public function postAction()
    {

    }

    /**
     * Retorna a avaliação de uma enquete
     *
     * @Get("/{enquete}")
     */
    public function getAction(Enquete $enquete, Request $request)
    {

        var_dump($enquete->getId());die;

        $data = array("hello" => "world");
        $view = $this->view($data);
        return $this->handleView($view);
    }

}