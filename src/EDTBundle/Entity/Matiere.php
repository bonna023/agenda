<?php

namespace EDTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matiere
 *
 * @ORM\Table(name="matiere")
 * @ORM\Entity(repositoryClass="EDTBundle\Repository\MatiereRepository")
 */
class Matiere
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="EDTBundle\Entity\ProfMatiere", mappedBy="matiere",cascade={"remove"})
     */
    private $matiere_profs;

    /**
     * @ORM\OneToMany(targetEntity="EDTBundle\Entity\Seance", mappedBy="matiere",  cascade={"remove"})
     * une matiere possède plusieurs séances : 30 h de Cm, 10 h de TD, ....
     * // suppression de la persistance en cascade le 12/04/16 --> voir le formulaire de la matiere pour plus de précision
     */
     private $seances;

     /*
      * toString()
      * @return string

      public function __toString(){
        return $this->getName();
      }*/
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
     * Set nom
     *
     * @param string $nom
     * @return Matiere
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matiere_profs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seances=new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add matiere_profs
     *
     * @param \EDTBundle\Entity\ProfMatiere $matiereProfs
     * @return Matiere
     */
    public function addMatiereProf(\EDTBundle\Entity\ProfMatiere $matiereProfs)
    {
        $this->matiere_profs[] = $matiereProfs;
        $matiereProfs->setMatiere($this);
        /*C'est un relation bidirectionnelle, il faut donc lier les deux entités de manière
        à ce qu'elle garde une cohérence entre elles.
        */
        return $this;
    }

    /**
     * Remove matiere_profs
     *
     * @param \EDTBundle\Entity\ProfMatiere $matiereProfs
     */
    public function removeMatiereProf(\EDTBundle\Entity\ProfMatiere $matiereProfs)
    {
        $this->matiere_profs->removeElement($matiereProfs);
    }

    /**
     * Get matiere_profs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatiereProfs()
    {
        return $this->matiere_profs;
    }

    /**
     * Add seances
     *
     * @param \EDTBundle\Entity\Seance $seance
     * @return Matiere
     */
    public function addSeance(\EDTBundle\Entity\Seance $seance)
    {
        $this->seances[] = $seance;
        $seance->setMatiere($this);
        return $this;
    }

    /**
     * Remove seances
     *
     * @param \EDTBundle\Entity\Seance $seance
     */
    public function removeSeance(\EDTBundle\Entity\Seance $seance)
    {
        $this->seances->removeElement($seance);
    }

    /**
     * Get seances
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeances()
    {
        return $this->seances;
    }
}
