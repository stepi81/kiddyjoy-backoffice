<?php

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */

 namespace models\Entities\Order\Points;
 
 use models\Entities\OrderShopFinal;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_points_shop_transactions")
  */
 class ShopTransaction {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    public function getID() { return $this->id; }
 }
 
 /* End of file ShopTransaction.php */
 /* Location: ./system/applications/_backend/models/Entities/Order/Points/ShopTransaction.php */