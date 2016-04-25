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
     * @var string URL Relative to current path.
     * @ORM\Column(name="url", type="string", length=255)
     */
    protected $url;
    
    /**
     * @var string HTML color code for the bg color of the event label.
     * @ORM\Column(name="bgColor", type="string", length=255)
     */
    protected $bgColor;
    
    /**
     * @var string HTML color code for the foregorund color of the event label.
     * @ORM\Column(name="fgColor", type="string", length=255)
     */
    protected $fgColor;
    
    /**
     * @var string css class for the event label
     * @ORM\Column(name="cssClass", type="string", length=255)
     */
    protected $cssClass;

    /**
     * @var \DateTime DateTime object of the event start date/time.
     * @ORM\Column(type="datetime", name="datetime")
     */
    protected $datetime;
    
    /**
     * @var \DateTime DateTime object of the event start date/time.
     * @ORM\Column(type="datetime", name="startDatetime")
     */
    protected $startDatetime;
    
    /**
     * @var \DateTime DateTime object of the event end date/time.
     * @ORM\Column(type="datetime", name="endDatetime")
     */
    protected $endDatetime;
    
    /**
     * @var boolean Is this an all day event?
     * @ORM\Column(type="boolean", name="allDay")
     */
    protected $allDay = false;

    /**
     * @var array Non-standard fields
     */
    protected $otherFields = array();
    
    public function __construct($title, \DateTime $startDatetime, \DateTime $endDatetime = null, $allDay = false)
    {
        $this->title = $title;
        $this->startDatetime = $startDatetime;
        $this->setAllDay($allDay);
        
        if ($endDatetime === null && $this->allDay === false) {
            throw new \InvalidArgumentException("Must specify an event End DateTime if not an all day event.");
        }
        
        $this->endDatetime = $endDatetime;
    }

    /**
     * Convert calendar event details to an array
     * 
     * @return array $event 
     */
    public function toArray()
    {
        $event = array();
        
        if ($this->id !== null) {
            $event['id'] = $this->id;
        }
        
        $event['title'] = $this->title;
        $event['start'] = $this->startDatetime->format("Y-m-d\TH:i:sP");
        
        if ($this->url !== null) {
            $event['url'] = $this->url;
        }
        
        if ($this->bgColor !== null) {
            $event['backgroundColor'] = $this->bgColor;
            $event['borderColor'] = $this->bgColor;
        }
        
        if ($this->fgColor !== null) {
            $event['textColor'] = $this->fgColor;
        }
        
        if ($this->cssClass !== null) {
            $event['className'] = $this->cssClass;
        }

        if ($this->endDatetime !== null) {
            $event['end'] = $this->endDatetime->format("Y-m-d\TH:i:sP");
        }
        
        $event['allDay'] = $this->allDay;

        foreach ($this->otherFields as $field => $value) {
            $event[$field] = $value;
        }
        
        return $event;
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

    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function setBgColor($color)
    {
        $this->bgColor = $color;
    }
    
    public function getBgColor()
    {
        return $this->bgColor;
    }
    
    public function setFgColor($color)
    {
        $this->fgColor = $color;
    }
    
    public function getFgColor()
    {
        return $this->fgColor;
    }
    
    public function setCssClass($class)
    {
        $this->cssClass = $class;
    }
    
    public function getCssClass()
    {
        return $this->cssClass;
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
    
    public function setAllDay($allDay = false)
    {
        $this->allDay = (boolean) $allDay;
    }
    
    public function getAllDay()
    {
        return $this->allDay;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addField($name, $value)
    {
        $this->otherFields[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function removeField($name)
    {
        if (!array_key_exists($name, $this->otherFields)) {
            return;
        }

        unset($this->otherFields[$name]);
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return Evenement
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime 
     */
    public function getDatetime()
    {
        return $this->datetime;
    }
}
