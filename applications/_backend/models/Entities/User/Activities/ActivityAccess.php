<?php

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */

 namespace models\Entities\User\Activities;
 
 use Doctrine\ORM\Mapping as ORM;
 
 use models\Entities\User\AdminActivity;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @ORM\Entity
  */
 class ActivityAccess extends AdminActivity {
 	
 	public function getRecordURL() { return '#'; }
	public function getRecordName() { return ''; }
 }
 
 /* End of file ActivityAccess */
 /* Location: ./system/applications/_frontend/models/Entities/User/Activities/ActivityAccess */