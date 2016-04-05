<?php
namespace AppBundle\Controller\Api;

use AppBundle\Entity\Enquete;
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
     * @Route("/")
     */
    public function postAction(Request $request)
    {
        $username   = $request->get('username');
        $email      = $request->get('email');
        $password   = $request->get('password');

        $data = json_decode($request->getContent(), true);


        var_dump($data);die;

        $manipulator = $this->container->get('fos_user.util.user_manipulator');
        $manipulator->create($username, $password, $email, 1, 0);
        var_dump('sucesso');die;
    }


}