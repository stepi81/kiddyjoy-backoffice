<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Product;
 
 use models\Entities\Product\Bundle\BundleItem;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\BundleRepository")
  * @Table(name="ecom_bundles")
  */
 class Bundle {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=240, nullable=false) */
	private $name;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
	private $price;
	
	/**
     * @ManyToMany(targetEntity="models\Entities\Product\Bundle\BundleItem")
     * @JoinTable(name="ecom_bundles_items",
     *      joinColumns={@JoinColumn(name="bundle_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="item_id", referencedColumnName="id")}
     *      )
     */
	private $items;
	
	public function __construct() {
		
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setPrice( $value ) { $this->price =  is_numeric($value) && $value > 0 ? $value : NULL; }
	public function setItem( BundleItem $value ) { $this->items[] = $value; }
    public function getItems() { return $this->items; }
	public function getTotalItems() { return count($this->items)+1; }
	
 	public function getPrice( $format = FALSE ) {
 		
 		if( $this->price ) return $format ? number_format($this->price, 2, ',', '.').' RSD' : $this->price;
 		else return '';
 	}
 	
 	public function getSalePrice( $product_price, $format = FALSE ) {
 		
 		if( $this->price ) $total = $this->price;
		else {
			$total = $product_price;
			foreach( $this->items as $item ) $total += $item->getPrice();
		}
		
		return $format ? number_format($total, 2, ',', '.').' RSD' : $total;
 	}
	
	public function productExists( $id ) {
		
		foreach($this->items as $item) {
			if( $item->getProduct()->getID() == $id ) return TRUE;
		}
		return FALSE;
	}
	
	public function removeItems() {
		foreach( $this->items as $item ) $this->items->removeElement($item);
	}
	
	public function getURL() { return site_url('bundles/details/'.$this->id); }
 }
 
 /* End of file Bundle.php */
 /* Location: ./system/applications/_frontend/models/Entities/Product/Bundle.php */