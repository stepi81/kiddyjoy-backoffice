<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models\Entities\ShoppingGuide;
 use models\Entities\ShoppingGuide\Guide;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_shopping_guide_images")
  */
 class GuideImage {
     
    /**
     * @Id
     * @Column(type="integer", nullable=false)
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /** @Column(type="string", length=120, nullable=false) */
    private $name;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\ShoppingGuide\Guide", inversedBy="images")
     * @JoinColumn(name="shopping_guide_id", referencedColumnName="id")
     */
    private $guide;
    
    public function getID() { return $this->id; }
    public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
    public function setGuide( Guide $value ) { $this->guide = $value; }
    public function getGuide() { return $this->guide; }
    public function getURL() { return assets_url('img/shopping_guide/pages/'.$this->name); }
 }
 
 /* End of file GuideImage.php */
 /* Location: ./system/applications/_backend/models/Entities/ShoppingGuide/GuideImage.php */