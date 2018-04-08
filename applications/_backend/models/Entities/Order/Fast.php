<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Order;
 
 use models\Entities\Order;
 use models\Entities\User\Customer\Instant;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  */
 class Fast extends Order {
     
    /**
     * @OneToOne(targetEntity="models\Entities\User\Customer\Instant")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    public function setUser( models\Entities\User\Customer\Instant $value ) { $this->user = $value; }
    public function getUser() { return $this->user; }
 }
 
 /* End of file Fast.php */
 /* Location: ./system/applications/_frontend/models/Entities/Order/Fast.php */