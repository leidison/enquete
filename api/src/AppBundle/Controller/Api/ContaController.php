<?php
namespace AppBundle\Controller\Api;

use AppBundle\Entity\Enquete;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/conta")
 */
class ContaController extends FOSRestController
{

    /**
     * Usado para criar um novo usuário
     * @Post("")
     */
    public function postAction(Request $request)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $serializer = $this->get('serializer');

        // Converto o json passado para objeto User
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setUsername($user->getEmail());
        $validator = $this->get('validator');
        // valido o formulário de cadastro
        $errors = $validator->validate($user, array('Registration'));

        if (count($errors)) {
            // preparo a resposta de erro
            $view = $this->view($errors, 400);
        } else {
            $manipulator = $this->container->get('fos_user.util.user_manipulator');
            // Criando usuário na base de dados
            $resultado = $manipulator->create($user->getUsername(), $user->getPassword(), $user->getEmail(), 1, 0);
            // preparando resposta de sucesso
            $view = $this->view(['success' => true]);
        }
        $view->setFormat('json');
        return $this->handleView($view);
    }


}