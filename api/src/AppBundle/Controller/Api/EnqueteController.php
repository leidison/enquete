<?php
namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/enquete")
 */
class EnqueteController extends FOSRestController
{

    /**
     * Cria uma nova enquete
     *
     * @Post("/")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function postAction()
    {

    }

    /**
     * Edita os dados da enquete
     *
     * @Put("/")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function putAction()
    {

    }

    /**
     * Exclui a enquete
     *
     * @Delete("/")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function deleteAction()
    {

    }

    /**
     * Retorna as enquetes
     *
     * @Get("/")
     */
    public function getAction(Request $request)
    {
        $data = array("hello" => "world");
        $view = $this->view($data);
        return $this->handleView($view);
    }

    /**
     * Retorna as enquetes do usuário logado
     *
     * @Get("/minhas")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function getMinhasAction(Request $request)
    {

    }

}