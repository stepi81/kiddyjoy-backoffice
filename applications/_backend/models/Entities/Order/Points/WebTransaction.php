<?php

/**
 * ...
 * @author Andrej The Savic [ ABC Design ]
 */

 namespace models\Entities\Order\Points;
 
 use models\Entities\OrderFinal;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_points_web_transactions")
  */
 class WebTransaction {
     
    /**
     * @Id
     * @Column(type="integer", length=10, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $order_id;
    
    /** @Column(type="integer", length=10, nullable=false) */
    private $points;
    
    public function getOrderID() { return $this->order_id; }
    public function setPoints( $value ) { $this->points = $value; }
    public function getPoints() { return $this->points; }
 }
 
 /* End of file WebTransaction.php */
 /* Location: ./system/applications/_backend/models/Entities/Order/Points/WebTransaction.php */