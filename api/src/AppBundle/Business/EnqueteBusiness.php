<?php

namespace AppBundle\Business;

use  AppBundle\Entity\Enquete;

class EnqueteBusiness extends Base
{
    public function cadastro(Enquete $enquete)
    {
        $this->getDoctrine()->getManager()->persist($enquete);
        $this->getDoctrine()->getManager()->flush();

    }
}
