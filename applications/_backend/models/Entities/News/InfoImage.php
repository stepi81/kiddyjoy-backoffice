<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\News;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity
  * @Table(name="ecom_news_images")
  */
 class InfoImage {
 	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $name;
	
	/**
     * @ManyToOne(targetEntity="Info", inversedBy="images")
     * @JoinColumn(name="news_id", referencedColumnName="id")
     */
	private $info;
	
    public function getID() { return $this->id; }
	public function setName( $value ) { $this->name = $value; }
    public function getName() { return $this->name; }
	public function setInfo( Info $value ) { $this->info = $value; }
    public function getInfo() { return $this->info; }
	public function getURL() { return assets_url('img/news/pages/'.$this->name); }
 }
 
 /* End of file InfoImage.php */
 /* Location: ./system/applications/_backend/models/Entities/News/InfoImage.php */