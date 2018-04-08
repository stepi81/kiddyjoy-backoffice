<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class AdvertisingRepository extends EntityRepository {
    
         private $relations = array(
                 'title'    => 'a.title',
                 'position' => 'a.position',
                 'status'   => 'a.status'
         ); 
    
    private $ads;
	
	private $CI;
 
    public function init() {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('c, a')
            ->from('models\Entities\Advertising\AdCategory', 'c INDEX BY c.id')
            ->leftJoin('c.ads', 'a')
            ->andWhere("a.status = :status")
            ->orderBy('a.position', 'ASC')
            ->setParameter("status", 1);
        
        $query = $qb->getQuery();
        $this->ads = $query->getResult();
    }
    
  
    public function getAd($criteria, $category_id) {
        
        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
           ->from('models\Entities\Advertising\Ad', 'a')
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
           ->where('a.category_id=' . $category_id)
		   ->setFirstResult($criteria->offset)
           ->setMaxResults($criteria->limit);

		   
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$this->CI =& get_instance();
		if( $this->CI->session->userdata('application_id') != 'mobile' ) {
			
			$qb->andWhere('a.app_id != 3');
		}
		
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        
        if( $data['record_count'] = $data['records']->count() ) {
             
            foreach ($data['records'] as $ad) {
                
                $status = $ad->getStatus() ? 'check' : 'delete';
				$status_mobile = $ad->getStatusMobile() ? 'check' : 'delete';

                if ($ad->getCategoryId() == 5) {
					if($this->CI->session->userdata('application_id'))
						$category_or_position = $ad->getMenuID();
					else 
						$category_or_position = $ad->getCategory()->getName();
                }else{
                    $category_or_position = $ad->getPosition(); 
                }
				
				if( $ad->getLinkType() && $ad->getLinkType()->getID() == 1 ) {
					$product = $this->_em->getRepository('models\Entities\Product')->find( $ad->getAdsLink() );
					$price = $product->getPrice();
					if(!$product->getStatus() || $product->getArchive()) $price = 0; 
				}
				else  $price = '';

                $data['record_items'][] = array(
                    $ad->getID(),
                    
                    $category_or_position,
                    ($ad->getSourcetype() == 1) ? '<img border="0" height="100px" src="'.$ad->getSource().'">' : (($ad->getSourcetype() == 2) ? 'SWF' : 'Tekst'),
                    $price,
                    $ad->getTitle(),
                    '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('ads/change_status/' . $ad->getID()) . '\');">Status</a>',  
                    '<a class="table-icon ' . $status_mobile . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('ads/change_status_mobile/' . $ad->getID()) . '\');">Status mobile</a>',  
                    '<a class="table-icon details" href="' . site_url('ads/details/' . $ad->getID()) . '">Detalji</a>',  
                    $category_id == 6 ? is_object($ad->getCampaing()) ? 'Kampanja' : 'Proizvod' : '', 
                );
            }
        }

        return $data;        
    }

    public function getMaxAdPosition( $category_id ){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(a.position)')->from('models\Entities\Advertising\Ad', 'a')->where('a.category_id=' . $category_id);

		$this->CI =& get_instance();
	
        $query = $qb->getQuery();
        $ads = $query->getResult();
        return $ads;
    }
    
    public function getAdsByCategory( $category_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('a')->from('models\Entities\Advertising\Ad', 'a')->where('a.category_id=' . $category_id);
        
		$this->CI =& get_instance();
		
        $query = $qb->getQuery();
        $ads = $query->getResult();
        return $ads;
    }
    public function getAllLinkTypes(){
       
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('l')->from('models\Entities\Advertising\LinkType', 'l');
        
        $query = $qb->getQuery();
        $types = $query->getResult();
        return $types;
    }
    

     public function deleteAd($id_list) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
           ->from('models\Entities\Advertising\Ad', 'a')
           ->where($qb->expr()->in('a.id', $id_list));

        $query = $qb->getQuery();
        $ads = $query->getResult();

        foreach ($ads as $ad) {
            
            $category_id = $ad-> getCategoryId();
            switch ($category_id) {
                case ($category_id =='1') :
                    $category = 'slideshow';
                    break;
                case ($category_id == '2') :
                    $category = 'body';
                    break;                          
                case ($category_id == '3') :
                    $category = 'footer';
                    break;
                case ($category_id == '4') :
                    $category = 'central';
                    break;
	            case ($category_id == '5') :
	                $category = 'menu';
	                break;
                case ($category_id == '6') :
                    $category = 'product';
                    break;
                case ($category_id == '7') :
                    $category = 'top';
                    break;
				case ($category_id == '8') :
                    $category = 'filter';
                    break;
				case ($category_id == '9') :
                    $category = 'filter_small';
                    break;
            }

            $this->CI =& get_instance();
			if($this->CI->session->userdata('application_id') == 'mobile') {
				$category = $category.'/mobile';	
			} else {
				$category = $category;
			}
			if( file_exists(SERVER_IMAGE_PATH . 'ads/' . $category . '/' . $ad->getImageName()) ) {
				unlink(SERVER_IMAGE_PATH . 'ads/' . $category . '/' . $ad->getImageName());
			}
			if( file_exists(SERVER_IMAGE_PATH . 'ads/' . $category . '/mobile/' . $ad->getImageMobileName()) ) {
				unlink(SERVER_IMAGE_PATH . 'ads/' . $category . '/mobile/' . $ad->getImageMobileName());
			}
            $this->_em->remove($ad);   
        }

        $this->_em->flush();
    }
    
        
    public function getSlideshowAds() {
        
        return isset($this->ads[AD_CATEGORY_SLIDESHOW]) ? $this->ads[AD_CATEGORY_SLIDESHOW]->getAds() : NULL;
    }
    
    public function getFooterAds() {
        
        return isset($this->ads[AD_CATEGORY_FOOTER]) ? $this->ads[AD_CATEGORY_FOOTER]->getAds() : NULL;
    }
	
    public function getAdProducts( $id_list ) {
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Product', 'p INDEX BY p.id')
            ->where($qb->expr()->in('p.id', array_unique( $id_list )));
        
        return count( $qb->getQuery()->getResult() );
    }
	
 }
 
 /* End of file AdvertisingRepository.php */
 /* Location: ./system/applications/_backend/models/AdvertisingRepository.php */