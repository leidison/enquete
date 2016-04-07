<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pergunta
 *
 * @ORM\Table(name="pergunta", indexes={@ORM\Index(name="fk_pergunta_enquete1_idx", columns={"enquete_id"})})
 * @ORM\Entity
 */
class Pergunta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=45, nullable=false)
     */
    private $descricao;

    /**
     * @var \Enquete
     *
     * @ORM\ManyToOne(targetEntity="Enquete", inversedBy="perguntas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="enquete_id", referencedColumnName="id")
     * })
     */
    private $enquete;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     * @return Pergunta
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string 
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set enquete
     *
     * @param \AppBundle\Entity\Enquete $enquete
     * @return Pergunta
     */
    public function setEnquete(\AppBundle\Entity\Enquete $enquete = null)
    {
        $this->enquete = $enquete;

        return $this;
    }

    /**
     * Get enquete
     *
     * @return \AppBundle\Entity\Enquete 
     */
    public function getEnquete()
    {
        return $this->enquete;
    }
}
