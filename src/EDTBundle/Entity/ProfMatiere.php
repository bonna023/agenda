<?php

namespace EDTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EDTBundle\Entity\Matiere;
use UserBundle\Entity\User;
/**
 * ProfMatiere
 *
 * @ORM\Table(name="prof_matiere")
 * @ORM\Entity(repositoryClass="EDTBundle\Repository\ProfMatiereRepository")
 */
class ProfMatiere
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
     * @var bool
     *
     * @ORM\Column(name="responsable", type="boolean")
     */
    private $responsable = false;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Professeur", inversedBy="prof_matieres")
     * @ORM\JoinColumn(nullable = false)
     */
    private $professeur;

    /**
     * @ORM\ManyToOne(targetEntity="EDTBundle\Entity\Matiere", inversedBy="matiere_profs")
     * @ORM\JoinColumn(nullable = false)
     */
     private $matiere;

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
     * Set responsable
     *
     * @param boolean $responsable
     * @return ProfMatiere
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return boolean
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set professeur
     *
     * @param \UserBundle\Entity\Professeur $professeur
     * @return ProfMatiere
     */
    public function setProfesseur(\UserBundle\Entity\User $professeur)
    {
        $this->professeur = $professeur;

        return $this;
    }

    /**
     * Get professeur
     *
     * @return \UserBundle\Entity\User
     */
    public function getProfesseur()
    {
        return $this->professeur;
    }

    /**
     * Set matiere
     *
     * @param \EDTBundle\Entity\Matiere $matiere
     * @return ProfMatiere
     */
    public function setMatiere(\EDTBundle\Entity\Matiere $matiere)
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get matiere
     *
     * @return \EDTBundle\Entity\Matiere
     */
    public function getMatiere()
    {
        return $this->matiere;
    }
}
