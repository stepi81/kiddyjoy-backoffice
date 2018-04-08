<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models\Entities\Order;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ConfigurationRepository")
  * @Table(name="ecom_configurations")
  */
 class Configuration {
    
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Order")
     * @JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;

    /** @Column(type="text", nullable=false) */
    private $data;
    /** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $price;
    /** @Column(type="integer", length=10, nullable=false) */
    private $quantity;
    
    public function getID() { return $this->id; }
    public function setOrder( $value ) { $this->order = $value; }
    public function getOrder() { return $this->order; }
    public function setData( $value ) { $this->data = $value; }
    public function getData() { return $this->data; }
    public function setPrice( $value ) { $this->price = $value; }
    public function getPrice() { return $this->price;  }
    public function setQuantity( $value ) { $this->quantity= $value; }
    public function getQuantity() { return $this->quantity; }
 }
 
 /* End of file Configuration.php */
 /* Location: ./system/applications/_backend/models/Entities/Order/Configuration.php */