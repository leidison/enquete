<?php

namespace AppBundle\Business;

use  AppBundle\Entity\Enquete;

class EnqueteBusiness extends Base
{

    public function cadastro(Enquete $enquete)
    {
        $enquete->setUser($this->getUser());

        $this->getDoctrine()->getManager()->merge($enquete);

        $this->getDoctrine()->getManager()->flush();

    }
}
