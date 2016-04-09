<?php

namespace AppBundle\Business;

use  AppBundle\Entity\Enquete;
use FOS\UserBundle\Model\User;

class EnqueteBusiness extends Base
{

    public function cadastro(Enquete $enquete, User $user)
    {
        $enquete->setUser($user);

        $this->getDoctrine()->getManager()->merge($enquete);

        $this->getDoctrine()->getManager()->flush();

    }

    public function edicao(Enquete $enquete)
    {
        $this->getDoctrine()->getManager()->merge($enquete);

        $this->getDoctrine()->getManager()->flush();

    }

    public function paginado($filtro, $pagina, $porPagina)
    {
        return $this->getRepository()->paginado($filtro, $pagina, $porPagina);
    }
}
