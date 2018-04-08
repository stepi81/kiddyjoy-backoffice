<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models\Entities\Advertising;
 
 use models\Entities\Product\Category; 
 use models\Entities\Product;
 use models\Entities\Product\Filter;
 use models\Entities\Advertising\AdCategory;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 /**
  * @Entity (repositoryClass="models\AdvertisingRepository")
  * @Table(name="ecom_ads")
  */
 class Ad {
 	
	private $CI;
 	
	/**
	 * @Id
	 * @Column(type="integer", length=10, nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @Column(type="string", length=120, nullable=false) */
	private $title;
    
     /**
     * @ManyToOne(targetEntity="LinkType")
     * @JoinColumn(name="link_type", referencedColumnName="id", nullable=true)
     */    
	private $link_type;
    
	/** @Column(type="integer", length=10, nullable=false) */
	private $app_id;
	/** @Column(type="integer", length=10, nullable=true) */
	private $menu_id;
	/** @Column(type="string", name="link", length=120, nullable=true) */
	private $ads_link;
	/** @Column(type="integer", length=4, nullable=false) */
	private $source_type;
	/** @Column(type="string", length=60, nullable=false) */
	private $source;
	/** @Column(type="string", length=60, nullable=true) */
	private $source_mobile;
	/** @Column(type="integer", length=4, nullable=false) */
	private $position;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status;
	/** @Column(type="smallint", length=2, nullable=false) */
	private $status_mobile;
    /** @Column(type="integer", length=2, nullable=true) */
	private $category_id;
    
	/**
     * @ManyToOne(targetEntity="models\Entities\Product\Category", inversedBy="ads")
     * @JoinColumn(name="category", referencedColumnName="id")
     */
    private $category;
	
	/**
     * @ManyToOne(targetEntity="models\Entities\Product\Subcategory", inversedBy="ads")
     * @JoinColumn(name="subcategory", referencedColumnName="id")
     */
    private $subcategory;
       
	/**
	 * @ManyToOne(targetEntity="AdCategory", inversedBy="ads")
	 * @JoinColumn(name="category_id", referencedColumnName="id")
	 */
	private $ad_category;
    
    /**
     * @ManyToOne(targetEntity="models\Entities\Vendor")
     * @JoinColumn(name="vendor_id", referencedColumnName="id")
     **/
    private $vendor;
    
    /**
    * @OneToOne(targetEntity="models\Entities\Advertising\AdCampaing", mappedBy="ad", cascade={"remove"})
    */
    private $campaing;
    
    /**
     * @ManyToMany(targetEntity="models\Entities\Product")
     * @JoinTable(name="ecom_products_ads",
     *      joinColumns={@JoinColumn(name="ad_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     */
    private $products;
	
	/** @Column(type="text", nullable=true) */
	private $text;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $min_price;
	/** @Column(type="decimal", precision=2, scale=1, nullable=false) */
    private $max_price;
	/** @Column(type="date") */
    protected $start_date;
	/** @Column(type="date") */
    protected $end_date;
	
	/**
     * @ManyToMany(targetEntity="models\Entities\Product\Filter")
     * @JoinTable(name="ecom_ads_filters",
     *      joinColumns={@JoinColumn(name="ad_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="filter_id", referencedColumnName="id")}
     *      )
     */
    private $filters;
	
    public function __construct() {
        
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getID() { return $this->id; }
	public function setAPPID( $value ) { $this->app_id = $value; } 
    public function getAPPID() { return $this->app_id; }
    public function setVendor( $value ) { $this->vendor = $value; } 
    public function getVendor() { return $this->vendor; }
    public function setTitle( $value ) { $this->title = $value; }
	public function getTitle() { return $this->title; }
	public function setMenuID( $value ) { $this->menu_id = $value; }
	public function getMenuID() { return $this->menu_id; }
    
    public function setAdsLink( $value ) { $this->ads_link = $value; }
    public function getAdsLink() { return $this->ads_link; }
    
    public function setLinkType( $value ) { $this->link_type = $value; }
    public function getLinkType() { return $this->link_type; }
    public function setSourceType( $value ) { $this->source_type = $value; }
	public function getSourceType() { return $this->source_type; }
    public function setCategoryId( AdCategory $value ) { $this->ad_category = $value; }   
	public function getCategoryId() { return $this->category_id; }
    public function setStatus( $value ) { $this->status = $value; }
    public function getStatus() { return $this->status; }
	public function setStatusMobile( $value ) { $this->status_mobile = $value; }
    public function getStatusMobile() { return $this->status_mobile; }
    public function setPosition( $value ) { $this->position = $value; }
    public function getPosition() { return $this->position; }
    public function setSource( $value ) { $this->source = $value; }
	public function setSourceMobile( $value ) { $this->source_mobile = $value; }
    public function getImageName() {return $this->source;}
	public function getImageMobileName() {return $this->source_mobile;}
    public function setCategory( $value ) { $this->category = $value; }
	public function getCategory() { return $this->category ? $this->category : NULL; }
	public function setSubcategory( $value ) { $this->subcategory = $value; }
	public function getSubcategory() { return $this->subcategory ? $this->subcategory : NULL; }
	
	public function setText( $value ) { $this->text = $value; } 
    public function getText() { return $this->text; }
	public function setMinPrice( $value ) { $this->min_price = $value; } 
    public function getMinPrice() { return $this->min_price; }
	public function setMaxPrice( $value ) { $this->max_price = $value; } 
    public function getMaxPrice() { return $this->max_price; }
	public function setStartDate( $value ) { $this->start_date = $value; }
	public function getStartDate() { return $this->start_date; }
	public function getFormatedStartDate() { return $this->start_date ? $this->start_date->format('d.m.Y') : ''; }
	public function setEndDate( $value ) { $this->end_date = $value; }
	public function getEndDate() { return $this->end_date; }
	public function getFormatedEndDate() { return $this->end_date ? $this->end_date->format('d.m.Y') : ''; }
	
    public function getCampaing() { return $this->campaing; }
    
    public function setProduct( Product $value ) { $this->products[] = $value; }
    public function getProducts() { return $this->products; }
	
	public function setFilter( Filter $value ) { $this->filters[] = $value; }
	public function getFilters() { return $this->filters; }
    
	public function getSource() {
		
		$this->CI =& get_instance();
		
		switch( $this->ad_category->getID() ) {
			
			case 1: //AD_CATEGORY_SLIDESHOW
				if( $this->CI->session->userdata('application_id') != 'mobile' ) {
					$src = assets_url('img/ads/slideshow/'.$this->source);
				} else {
					$src = assets_url('img/ads/slideshow/mobile/'.$this->source);	
				}
				break;
			case 2: //AD_CATEGORY_SIDE:
				$src = assets_url('img/ads/body/'.$this->source);
				break;
			case 3: //AD_CATEGORY_POPUP:
				$src = assets_url('img/ads/footer/'.$this->source);
				break; 
            case 4: //AD_CATEGORY_CENTRAL:
                $src = assets_url('img/ads/popup/'.$this->source);
                break;
            case 5: //AD_CATEGORY_PRODUCT:
                $src = assets_url('img/ads/catalog_menu/'.$this->source);
                break;
			case 6: //AD_CATEGORY_PRODUCT:
                $src = assets_url('img/ads/bestbuy/'.$this->source);
                break;
            case 7: //AD_CATEGORY_TOP:
                $src = assets_url('img/ads/popup/'.$this->source);
                break;
			case 8: //AD_CATEGORY_FILTER:
				$src = assets_url('img/ads/product/'.$this->source);
				break;
			case 9: //AD_CATEGORY_TOP:
                $src = assets_url('img/ads/filter/'.$this->source);
                break;
		}
		return $src;
	}

	public function getSourceMobile() {
		
		$this->CI =& get_instance();
		
		switch( $this->ad_category->getID() ) {
			
			case 1: //AD_CATEGORY_SLIDESHOW
				$src = assets_url('img/ads/slideshow/mobile/'.$this->source_mobile);	
				break;
			case 2: //AD_CATEGORY_SIDE:
				$src = assets_url('img/ads/body/mobile/'.$this->source_mobile);
				break;
			case 3: //AD_CATEGORY_POPUP:
				$src = assets_url('img/ads/footer/mobile/'.$this->source_mobile);
				break; 
            case 4: //AD_CATEGORY_CENTRAL:
                $src = assets_url('img/ads/popup/mobile/'.$this->source_mobile);
                break;
            case 5: //AD_CATEGORY_PRODUCT:
                $src = assets_url('img/ads/catalog_menu/mobile/'.$this->source_mobile);
                break;
			case 6: //AD_CATEGORY_PRODUCT:
                $src = assets_url('img/ads/bestbuy/mobile/'.$this->source_mobile);
                break;
            case 7: //AD_CATEGORY_TOP:
                $src = assets_url('img/ads/popup/mobile/'.$this->source_mobile);
                break;
			case 8: //AD_CATEGORY_FILTER:
				$src = assets_url('img/ads/product/mobile/'.$this->source_mobile);
				break;
			case 9: //AD_CATEGORY_TOP:
                $src = assets_url('img/ads/filter/mobile/'.$this->source_mobile);
                break;
		}
		return $src;
	}
	
	public function getLink() {
		
		if( $this->source_type == AD_SOURCE_TYPE_SWF ) {
			//$link = '<script type="text/javascript">swfobject.embedSWF("'.$this->getSource().'", "footer_banner'.$this->id.'", "580", "120", "9.0.0", false, flashvars, params, attributes);</script>'."\n";
			//$link .= '<div id="footer_banner'.$this->id.'"></div>';
		}
		else {
			switch( $this->link_type ) {
				
				case AD_LINK_TYPE_PRODUCT:
					//$url = site_url( $this->link_category->getUrlTitle().'/'.url_title($this->name, 'underscore', TRUE).'-'.$this->link );
					//$link = '<a href="'.$url.'"><img src="'.$this->getSource().'" alt="'.$this->name.'" /></a>';
					break;
				case AD_LINK_TYPE_PAGE:
                    $link = '<a href="'.site_url($this->link).'"><img src="'.$this->getSource().'" alt="'.$this->title.'" /></a>'; 
					break;
				case AD_LINK_TYPE_URL:
					$link = '<a href="'.prep_url($this->link).'" target="_blank"><img src="'.$this->getSource().'" alt="'.$this->title.'" /></a>';
					break;
				case AD_LINK_TYPE_FILE:
					break;
				default:
					$link = '<img src="'.$this->getSource().'" alt="'.$this->title.'" />';
			}
		}
		return ''; //$link;
	}
 }
 
 /* End of file Ad.php */
 /* Location: ./system/applications/_backend/models/Entities/Advertising/Ad.php */