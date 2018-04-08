<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Communicator;
 
 use models\Entities\Communicator;
 use models\Entities\Order;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  */
 class TransferOrder extends Communicator {
 	
	/**
     * @OneToOne(targetEntity="models\Entities\Order")
     * @JoinColumn(name="record_id", referencedColumnName="id")
     */
    private $record;
	
	public function setRecord( Order $value ) { $this->record = $value; }
    public function getRecord() { return $this->record; }
 }
 
 /* End of file TransferOrder.php */
 /* Location: ./system/applications/_frontend/models/Entities/Communicator/TransferOrder.php */