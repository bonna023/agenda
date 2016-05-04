<?php
namespace EDTBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="EDTBundle\Repository\EvenementRepository")
 */
class Evenement
{
    /**
     * @var mixed Unique identifier of this event (optional).
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string Title/label of the calendar event.
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;
    /**
     * @var \DateTime DateTime object of the event end date/time.
     * @ORM\Column(type="datetime", name="startDatetime")
     */
    protected $startDatetime;
    /**
     * @var \DateTime DateTime object of the event end date/time.
     * @ORM\Column(type="datetime", name="endDatetime")
     */
    protected $endDatetime;

    /**
     *
     * @ORM\ManyToMany(targetEntity="EDTBundle\Entity\Groupe")
     */
    protected $groupes;
    /**
     * @ORM\ManyToOne(targetEntity="EDTBundle\Entity\Salle")
     */
    protected $salle;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Professeur")
     * un évènement peut possèder plusieurs intervenants
     */
    protected $professeur;
    /**
     * @ORM\ManyToOne(targetEntity="EDTBundle\Entity\Matiere")
     * un événement est attributée à une seule matière, mais une matière peut AVOIR
     * plusieurs événement
     */
     protected $matiere;

     /**
      * @ORM\ManyToOne(targetEntity="EDTBundle\Entity\Type")
      * il faut également précisé le type du cours (Cm, tp,...)
      */
     protected $type;

    public function __construct()
    {

        $this->groupes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->endDateTime= new \DateTime();
        $this->startDateTime = new \DateTime();
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function getTitle()
    {
        return $this->title;
    }

    public function setStartDatetime(\DateTime $start)
    {
        $this->startDatetime = $start;
    }
    public function getStartDatetime()
    {
        return $this->startDatetime;
    }
    public function setEndDatetime(\DateTime $end)
    {
        $this->endDatetime = $end;
    }
    public function getEndDatetime()
    {
        return $this->endDatetime;
    }

    public function getAllDayEvent(){
      return false;
    }
    /**
     * Add groupe
     *
     * @param \EDTBundle\Entity\Groupe $groupe
     * @return Evenement
     */
    public function addGroupe(\EDTBundle\Entity\Groupe $groupe)
    {
        $this->groupe[] = $groupe;
        return $this;
    }
    /**
     * Remove groupe
     *
     * @param \EDTBundle\Entity\Groupe $groupe
     */
    public function removeGroupe(\EDTBundle\Entity\Groupe $groupe)
    {
        $this->groupe->removeElement($groupe);
    }
    /**
     * Get groupe
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupe()
    {
        return $this->groupe;
    }
    /**
     * Set salle
     *
     * @param \EDTBundle\Entity\Salle $salle
     * @return Evenement
     */
    public function setSalle(\EDTBundle\Entity\Salle $salle = null)
    {
        $this->salle = $salle;
        return $this;
    }
    /**
     * Get salle
     *
     * @return \EDTBundle\Entity\Salle
     */
    public function getSalle()
    {
        return $this->salle;
    }
    /**
     * Get professeur
     */
    public function getProfesseur()
    {
        return $this->professeur;
    }
    /**
     * Set professeur
     *
     * @param \UserBundle\Entity\Professeur $professeur
     * @return Evenement
     */
    public function setProfesseur(\UserBundle\Entity\Professeur $professeur = null)
    {
        $this->professeur = $professeur;
        return $this;
    }
    /**
     * Get groupes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupes()
    {
        return $this->groupes;
    }
    /**
     * Set matiere
     *
     * @param \EDTBundle\Entity\Matiere $matiere
     * @return Evenement
     */
    public function setMatiere(\EDTBundle\Entity\Matiere $matiere = null)
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

    /**
     * Set type
     *
     * @param \EDTBundle\Entity\Type $type
     * @return Evenement
     */
    public function setType(\EDTBundle\Entity\Type $type = null)
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
