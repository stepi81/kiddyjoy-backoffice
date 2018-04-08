<?php

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */

 namespace models\Entities\User\Group;
 
 use Doctrine\ORM\Mapping as ORM;
 
 use models\Entities\Section;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @ORM\Entity
  * @ORM\Table(name="backoffice_user_groups")
  */
 class AdminGroup {
 	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @ORM\Column(type="string", length=60, nullable=false) */
	private $name;
	
	/**
     * @ORM\ManyToMany(targetEntity="models\Entities\Section")
     * @ORM\JoinTable(name="backoffice_user_groups_sections",
     *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="section_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
	private $sections;
	
	public function __construct() {
        $this->sections = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    public function getID() { return $this->id; }
	public function setName($value) { $this->name = $value; }
	public function getName() { return $this->name; }
	public function addSection( Section $value ) { $this->sections->add($value); }
	public function getSections() { return $this->sections; }
	public function deleteSections() { $this->sections->clear(); }
 }
 
 /* End of file AdminGroup.php */
 /* Location: ./system/applications/_backend/models/Entities/User/Group/AdminGroup.php */