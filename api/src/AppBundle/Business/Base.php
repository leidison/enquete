<?php

namespace AppBundle\Business;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Base
{
    protected $container;
    protected $entityName;

    public function __construct(ContainerInterface $container, $entityName)
    {
        $this->container = $container;
        $this->entityName = $entityName;
    }

    public function getUser()
    {
        return $this->container->get('security.context')->getToken()->getUser();
    }

    public function getManager()
    {
        return $this->getDoctrine()->getManager();
    }

    public function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }

    public function getRepository()
    {
        return $this->getDoctrine()
            ->getRepository($this->entityName);
    }
}
