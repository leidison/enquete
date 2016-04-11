<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;
use Proxies\__CG__\AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * Enquete
 *
 * @ORM\Table(name="enquete", indexes={@ORM\Index(name="fk_enquete_user1_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EnqueteRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Enquete
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @ORM\Column(name="titulo", type="string", length=255, nullable=false)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="ajudas", type="integer", nullable=false)
     */
    private $ajudas;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="datetime", nullable=false)
     */
    private $data;

    /**
     * @var User
     *
     * @JMS\Exclude
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var Pergunta[]
     *
     * @Assert\Valid
     * @Type("ArrayCollection<AppBundle\Entity\Pergunta>")
     * @ORM\OneToMany(targetEntity="Pergunta", mappedBy="enquete", cascade={"persist", "merge", "remove"})
     */
    private $perguntas;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->perguntas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * Set titulo
     *
     * @param string $titulo
     * @return Enquete
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set ajudas
     *
     * @param integer $ajudas
     * @return Enquete
     */
    public function setAjudas($ajudas)
    {
        $this->ajudas = $ajudas;

        return $this;
    }

    /**
     * Get ajuda
     *
     * @return integer
     */
    public function getAjudas()
    {
        return $this->ajudas;
    }

    /**
     * Set data
     *
     * @param \DateTime $data
     * @return Enquete
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Enquete
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add pergunta
     *
     * @param \AppBundle\Entity\Pergunta $pergunta
     * @return Enquete
     */
    public function addPergunta(\AppBundle\Entity\Pergunta $pergunta)
    {
        $this->perguntas[] = $pergunta;

        return $this;
    }

    /**
     * Remove pergunta
     *
     * @param \AppBundle\Entity\Pergunta $pergunta
     */
    public function removePergunta(\AppBundle\Entity\Pergunta $pergunta)
    {
        $this->perguntas->removeElement($pergunta);
    }

    /**
     * Get pergunta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerguntas()
    {
        return $this->perguntas;
    }

    /**
     * @ORM\PreFlush
     */
    public function dadosDefaultPersist()
    {
        if ($this->getAjudas() == null) {
            $this->setAjudas(0)
                ->setData(new \DateTime('now'));
        }
    }

}