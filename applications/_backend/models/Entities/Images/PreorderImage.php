<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models\Entities\Images;
 
 use models\Entities\Preorder;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_preorder_images")
  */
 class PreorderImage {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $name;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Preorder", inversedBy="images")
     * @JoinColumn(name="preorder_id", referencedColumnName="id")
     */
	private $preorder;
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setPreorder( Preorder $value ) { $this->preorder = $value; }
    public function getPreorder() { return $this->preorder; }
	public function getURL() { return assets_url('img/preorders/pages/'.$this->name); }
 }
 
 /* End of file PreorderImage.php */
 /* Location: ./system/applications/_backend/models/Entities/Images/PreorderImage.php */