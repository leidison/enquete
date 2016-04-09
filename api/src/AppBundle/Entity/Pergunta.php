<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @ORM\Column(name="descricao", type="string", length=255, nullable=false)
     */
    private $descricao;

    /**
     * @var Enquete
     *
     * @ORM\ManyToOne(targetEntity="Enquete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="enquete_id", referencedColumnName="id")
     * })
     */
    private $enquete;

    /**
     * @var Resposta[]
     * @Type("ArrayCollection<AppBundle\Entity\Resposta>")
     * @ORM\OneToMany(targetEntity="Resposta", mappedBy="pergunta", cascade={"persist", "merge"})
     */
    private $respostas;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->respostas = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    /**
     * Add pergunta
     *
     * @param \AppBundle\Entity\Resposta $pergunta
     * @return Pergunta
     */
    public function addResposta(\AppBundle\Entity\Resposta $pergunta)
    {
        $this->respostas[] = $pergunta;

        return $this;
    }

    /**
     * Remove pergunta
     *
     * @param \AppBundle\Entity\Resposta $pergunta
     */
    public function removeResposta(\AppBundle\Entity\Resposta $pergunta)
    {
        $this->respostas->removeElement($pergunta);
    }

    /**
     * Get pergunta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRespostas()
    {
        return $this->respostas;
    }
}
