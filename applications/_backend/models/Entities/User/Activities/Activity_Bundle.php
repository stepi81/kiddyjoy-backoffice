<?php

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */

 namespace models\Entities\User\Activities;
 
 use models\Entities\User\Admin_Activity;
 use models\Entities\Product\Bundle;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  */
 class Activity_Bundle extends Admin_Activity {
 	
	/**
	 * @OneToOne(targetEntity="models\Entities\Product\Bundle")
	 * @JoinColumn(name="record_id", referencedColumnName="id")
	 */
	private $bundle;
	
	public function setRecord( Bundle $value ) { $this->bundle = $value; }
	public function getRecord() { return $this->bundle; }
	
 	public function getRecordURL() { return $this->bundle->getURL(); }
 	public function getRecordName() { return $this->bundle->getName(); }
 }
 
 /* End of file Activity_Bundle */
 /* Location: ./system/applications/_frontend/models/Entities/User/Activities/Activity_Bundle */