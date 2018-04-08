<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models\Entities\Vendor;
 
 use models\Entities\Product;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity(repositoryClass="models\VendorRepository")
  * @Table(name="ecom_vendor_videos")
  */
 class Video {
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=60, nullable=false) */
	private $code;
	/** @Column(type="string", length=60, nullable=false) */
	private $title;
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Vendor")
     * @JoinColumn(name="vendor_id", referencedColumnName="id")
     **/
    private $vendor;
	
	public function getID() { return $this->id; }
	public function setCode( $value ) { $this->code = $value; }
    public function getCode() { return $this->code; }
	public function setTitle( $value ) { $this->title = $value; }
    public function getTitle() { return $this->title; }
	public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
	
	public function setVendor( $value ) { $this->vendor = $value; } 
    public function getVendor() { return $this->vendor; }
	
	public function getSource( $width, $height ) {
        if( VENDOR == 'LOGITECH' ) {
		    return '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$this->code.'?rel=0" frameborder="0" allowfullscreen></iframe>';
		} else if( VENDOR == 'SAMSUNG' ) {
            return '<object id="flashObj" width="'.$width.'" height="'.$height.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,47,0"><param name="movie" value="http://c.brightcove.com/services/viewer/federated_f9?isVid=1&isUI=1" /><param name="bgcolor" value="#FFFFFF" /><param name="flashVars" value="videoId='.$this->code.'&playerID=926210534001&playerKey=AQ~~,AAAA1vDIiyE~,1l625wgL4J5inOw1R1uIHQ2AI0mBhVFr&domain=embed&dynamicStreaming=true" /><param name="base" value="http://admin.brightcove.com" /><param name="seamlesstabbing" value="false" /><param name="allowFullScreen" value="true" /><param name="swLiveConnect" value="true" /><param name="allowScriptAccess" value="always" /><embed src="http://c.brightcove.com/services/viewer/federated_f9?isVid=1&isUI=1" bgcolor="#FFFFFF" flashVars="videoId='.$this->code.'&playerID=926210534001&playerKey=AQ~~,AAAA1vDIiyE~,1l625wgL4J5inOw1R1uIHQ2AI0mBhVFr&domain=embed&dynamicStreaming=true" base="http://admin.brightcove.com" name="flashObj" width="'.$width.'" height="'.$height.'" seamlesstabbing="false" type="application/x-shockwave-flash" allowFullScreen="true" allowScriptAccess="always" swLiveConnect="true" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></object>';
	    } else {
			return '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$this->code.'?rel=0" frameborder="0" allowfullscreen></iframe>';
		}
	}
 }
 
 /* End of file Video.php */
 /* Location: ./system/applications/_backend/models/Entities/Video.php */