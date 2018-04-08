<?php

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */

 namespace models\Entities\User\Activities;
 
 use models\Entities\User\Admin_Activity;
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  */
 class Activity_Product extends Admin_Activity {
 	
	/**
	 * @OneToOne(targetEntity="models\Entities\Product")
	 * @JoinColumn(name="record_id", referencedColumnName="id")
	 */
	private $product;
	
	public function setRecord( Product $value ) { $this->product = $value; }
	public function getRecord() { return $this->product; }
	
	public function getRecordURL() { return $this->product->getURL(); }
	public function getRecordName() { return $this->product->getName(); }
 }
 
 /* End of file Activity_Product */
 /* Location: ./system/applications/_frontend/models/Entities/User/Activities/Activity_Product */