<?php
namespace AppBundle\Controller\Api;

use AppBundle\Entity\Enquete;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Post("")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function postAction(Request $request)
    {
        $enquete = $this->get('serializer')
            ->deserialize($request->getContent(), Enquete::class, 'json');

        $errors = $this->get('validator')
            ->validate($enquete);
        if (count($errors)) {
            // preparo a resposta de erro
            $view = $this->view($errors, 400);
        } else {
            $resultado = $this->get('enquete_business')->cadastro($enquete, $this->container->get('security.context')->getToken()->getUser());
            $view = $this->view(['success' => true]);
        }
        $view->setFormat('json');
        return $this->handleView($view);

    }

    /**
     * Edita os dados da enquete
     *
     * @Put("/{id}", requirements={"id":"\d+"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function putAction(Enquete $enquete, Request $request)
    {
        $enqueteEdicao = $this->get('serializer')
            ->deserialize($request->getContent(), Enquete::class, 'json');
        // popula o id da enquete
        $enqueteEdicao->setId($request->get('id'));

        $errors = $this->get('validator')
            ->validate($enqueteEdicao);
        if (count($errors)) {
            // preparo a resposta de erro
            $view = $this->view($errors, 400);
        } else {
            $resultado = $this->get('enquete_business')->edicao($enquete, $enqueteEdicao);

            $view = $this->view(['success' => true]);
        }
        $view->setFormat('json');
        return $this->handleView($view);
    }

    /**
     * Exclui a enquete
     *
     * @Delete("")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function deleteAction()
    {

    }

    /**
     * Retorna as enquetes
     *
     * @Get("/{id}", requirements={"id"="\d+"})
     */
    public function getAction(Enquete $enquete, Request $request)
    {
        $view = $this->view($enquete)
            ->setFormat('json');
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
        $filtro = array(
            'usuario' => $this->container->get('security.context')->getToken()->getUser()->getId()
        );

        $resultado = $this->get('enquete_business')->paginado($filtro, $request->get('pagina', 1), $request->get('por_pagina', 10));

        $view = $this->view($resultado)
            ->setFormat('json');
        return $this->handleView($view);
    }

}