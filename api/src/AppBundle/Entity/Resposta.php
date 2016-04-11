<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * Resposta
 *
 * @ORM\Table(name="resposta", indexes={@ORM\Index(name="fk_resposta_pergunta1_idx", columns={"pergunta_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Resposta
{
    /**
     * @var integer
     *
     * @Assert\Blank(groups={"cadastro"})
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"cadastro","edicao"})
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @ORM\Column(name="descricao", type="string", length=255, nullable=false)
     */
    private $descricao;

    /**
     * @var integer
     *
     *
     * @ORM\Column(name="quantidade", type="integer", nullable=false)
     */
    private $quantidade;

    /**
     * @var Pergunta
     *
     * @ORM\ManyToOne(targetEntity="Pergunta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pergunta_id", referencedColumnName="id")
     * })
     */
    private $pergunta;


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
     * @return Resposta
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
     * Set quantidade
     *
     * @param integer $quantidade
     * @return Resposta
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    /**
     * Get quantidade
     *
     * @return integer
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Set pergunta
     *
     * @param \AppBundle\Entity\Pergunta $pergunta
     * @return Resposta
     */
    public function setPergunta(\AppBundle\Entity\Pergunta $pergunta = null)
    {
        $this->pergunta = $pergunta;

        return $this;
    }

    /**
     * Get pergunta
     *
     * @return \AppBundle\Entity\Pergunta
     */
    public function getPergunta()
    {
        return $this->pergunta;
    }

    /**
     * @ORM\PreFlush
     */
    public function quantidadeDefaultPersist()
    {
        if ($this->getQuantidade() == null) {
            $this->setQuantidade(0);
        }
    }
}
