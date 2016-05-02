<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etudiant
 *
 * @ORM\Table(name="etudiant")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\EtudiantRepository")
 */
class Etudiant extends User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="num_etudiant", type="string", length=255, unique=true)
     */
    protected $numEtudiant;

    /**
     * @var Groupe
     *
     * @ORM\ManyToOne(targetEntity="EDTBundle\Entity\Groupe",  inversedBy ="etudiants")
     */
     private $groupe;

    /**
     * Get id
     *
     * @return integer

      *public function getId()
      *{
      *    return $this->id;
      *}
    */

    /**
     * Set numEtudiant
     *
     * @param string $numEtudiant
     * @return Etudiant
     */
    public function setNumEtudiant($numEtudiant)
    {
        $this->numEtudiant = $numEtudiant;

        return $this;
    }

    /**
     * Get numEtudiant
     *
     * @return string
     */
    public function getNumEtudiant()
    {
        return $this->numEtudiant;
    }



    /**
     * Set groupe
     *
     * @param \EDTBundle\Entity\Groupe $groupe
     * @return Etudiant
     */
    public function setGroupe(\EDTBundle\Entity\Groupe $groupe = null)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return \EDTBundle\Entity\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }
}
