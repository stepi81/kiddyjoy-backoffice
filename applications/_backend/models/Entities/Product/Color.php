<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 use models\Entities\Product\ProductColor;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\ColorRepository") 
  * @Table(name="ecom_colors")
  */
 class Color {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=4, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=250, nullable=false) */
	private $name;
	/** @Column(type="string", length=20, nullable=true) */
	private $code;
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	
	/**
     * @OneToMany(targetEntity="models\Entities\Product\ProductSize", mappedBy="color", cascade={"remove"})
     * @OrderBy({"position" = "ASC"})
     */
    private $product_colors;
	
	public function __construct() {
        
		$this->product_colors	= new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setCode( $value ) { $this->code = $value; }
    public function getCode() { return $this->code; }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
	public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
	
	public function getProductColors() { return $this->product_colors; }
    
 }
 
 /* End of file Size.php */
 /* Location: ./system/applications/_backend/models/Entities/Product/Size.php */