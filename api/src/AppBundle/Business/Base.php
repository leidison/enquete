<?php

namespace AppBundle\Business;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Base
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getRepository()
    {
        return $this->getDoctrine()
            ->getRepository('AppBundle:Product');
    }
}
