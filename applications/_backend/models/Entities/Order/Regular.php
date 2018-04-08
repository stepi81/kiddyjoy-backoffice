<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Order;
 
 use models\Entities\Order;
 use models\Entities\User\Customer;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  */
 class Regular extends Order {
     
    /**
     * @OneToOne(targetEntity="models\Entities\User\Customer")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    public function setUser( User $value ) { $this->user = $value; }
    public function getUser() { return $this->user; }
 }
 
 /* End of file Regular.php */
 /* Location: ./system/applications/_frontend/models/Entities/Order/Regular.php */