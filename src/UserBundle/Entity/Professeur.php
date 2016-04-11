<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Professeur
 *
 * @ORM\Table(name="professeur")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\ProfesseurRepository")
 */
class Professeur extends User
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
     * @ORM\OneToMany(targetEntity="EDTBundle\Entity\ProfMatiere", mappedBy="professeur")
     * Utilisation d'une table Intermédiaire ProfMatiere.
     * cette table en plus des clefs étrangères contiendra un attribut pour signaler
     * si le prof est le responsable de la matière ou pas.
     * et une matière peut être enseignée par plusieurs enseignants.
     * relation bidirectionnelle = le professeur peut accèder aux manières qu'il enseigne.
     * + Possibilité d'acceder aux professeurs depuis la matiere
     */
     private $prof_matieres;


     public function __construct(){
       $this->prof_matieres = new ArrayCollection();
       parent::__construct();
     }

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
     * Add prof_matieres
     *
     * @param \EDTBundle\Entity\ProfMatiere $profMatieres
     * @return Professeur
     */
    public function addProfMatiere(\EDTBundle\Entity\ProfMatiere $profMatieres)
    {
        $this->prof_matieres[] = $profMatieres;
        $profMatieres->setProfesseur($this);
        return $this;
    }

    /**
     * Remove prof_matieres
     *
     * @param \EDTBundle\Entity\ProfMatiere $profMatieres
     */
    public function removeProfMatiere(\EDTBundle\Entity\ProfMatiere $profMatieres)
    {
        $this->prof_matieres->removeElement($profMatieres);
    }

    /**
     * Get prof_matieres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfMatieres()
    {
        return $this->prof_matieres;
    }
}
