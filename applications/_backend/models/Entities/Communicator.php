<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */
 
 namespace models\Entities;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="backoffice_communicator")
  * @InheritanceType("SINGLE_TABLE")
  * @DiscriminatorColumn(name="type", type="integer")
  * @DiscriminatorMap({"1" = "models\Entities\Communicator\TransferUser", "2" = "models\Entities\Communicator\TransferOrder"})
  */
 class Communicator {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    public function getID() { return $this->id; }
 }
 
 /* End of file Communicator.php */
 /* Location: ./system/applications/_frontend/models/Entities/Communicator.php */