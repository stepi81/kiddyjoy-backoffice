<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Communicator;
 
 use models\Entities\Communicator;
 use models\Entities\User\Customer;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  */
 class TransferUser extends Communicator {
 	
	/**
     * @OneToOne(targetEntity="models\Entities\User\Customer")
     * @JoinColumn(name="record_id", referencedColumnName="id")
     */
    private $record;
	
	public function setRecord( Customer $value ) { $this->record = $value; }
    public function getRecord() { return $this->record; }
 }
 
 /* End of file TransferUser.php */
 /* Location: ./system/applications/_frontend/models/Entities/Communicator/TransferUser.php */