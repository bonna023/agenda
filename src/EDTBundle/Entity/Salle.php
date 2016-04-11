<?php

namespace EDTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Salle
 *
 * @ORM\Table(name="salle")
 * @ORM\Entity(repositoryClass="EDTBundle\Repository\SalleRepository")
 */
class Salle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="num_salle", type="string", length=255)
     */
    private $numSalle;

    /**
     * @var string
     *
     * @ORM\Column(name="num_batiment", type="string", length=255)
     */
    private $numBatiment;

    /**
     * @var int
     *
     * @ORM\Column(name="capacite", type="integer")
     */
    private $capacite;

    /**
     * @var Type
     *
     * @ORM\ManyToOne(targetEntity="EDTBundle\Entity\Type")
     * @ORM\JoinColumn(nullable=false)
     * PLusieurs salle peuvent avoir le même type, (ex, tous les amphis sont pour les CM)
     */
     private $type;



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
     * Set numSalle
     *
     * @param string $numSalle
     * @return Salle
     */
    public function setNumSalle($numSalle)
    {
        $this->numSalle = $numSalle;

        return $this;
    }

    /**
     * Get numSalle
     *
     * @return string
     */
    public function getNumSalle()
    {
        return $this->numSalle;
    }

    /**
     * Set numBatiment
     *
     * @param string $numBatiment
     * @return Salle
     */
    public function setNumBatiment($numBatiment)
    {
        $this->numBatiment = $numBatiment;

        return $this;
    }

    /**
     * Get numBatiment
     *
     * @return string
     */
    public function getNumBatiment()
    {
        return $this->numBatiment;
    }

    /**
     * Set capacite
     *
     * @param integer $capacite
     * @return Salle
     */
    public function setCapacite($capacite)
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * Get capacite
     *
     * @return integer
     */
    public function getCapacite()
    {
        return $this->capacite;
    }

    /**
     * Set type
     *
     * @param \EDTBundle\Entity\Type $type
     * @return Salle
     */
    public function setType(\EDTBundle\Entity\Type $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \EDTBundle\Entity\Type
     */
    public function getType()
    {
        return $this->type;
    }
}
