<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class ProductSubcategoryRepository extends EntityRepository {
    
    private $relations = array(
        'id'           => 's.id',
        'position'     => 's.position',
        'name'         => 's.name',
    );
	
	private $brand_relations = array(
        'id'           => 'b.id',
        'name'         => 'b.name',
    );
     
    public function getCategorySubcategories($criteria, $parent_id, $listing_product = NULL){ // $listing_product useing same method and giving parametar for using for new product listing
       
       $CI =& get_instance(); 
       $user = $CI->auth_manager->user();
       
        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();


        $qb->select('s') 
           ->from('models\Entities\Product\Subcategory', 's') 
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
           ->where ('s.parent = :parent') 
           ->setParameters (array('parent' => $parent_id));
       
        if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }

           $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);
            
            $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);
    
              $data['record_count'] = count($paginator);
                
                if ($listing_product == 1) {
                    foreach ($paginator as $subcategory) {

                        
                        $back_button = $parent_id; // make availble back button to right place

                         $status_subcategory_highlight = $subcategory->getHighlight() ? 'subcategory_highlight_on' : 'subcategory_highlight_off';
                         $highlight = '<a class="table-icon '.$status_subcategory_highlight.'" href="javascript:void(0);" onclick="changeSubcategoryHighlight(this, \''.site_url('products/set_highlighted_subcategory/'.$subcategory->getID()).'\');"></a>';

                         if( $user->getGroup()->getID() != 7 ) {
                            $data['record_items'][] = array(
                            $subcategory->getID(),
                            $subcategory->getPosition(),
                            '<a href="'.$subcategory->getURL().'" target="_blank">'.$subcategory->getName().'</a>',
                            //$CI->session->userdata('application_id') ? '-' : $highlight,  
                            '<a href="'.site_url('products/listing/'.$subcategory->getID() . '/' . $back_button).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            //'<a href="'.site_url('products/listing/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            '<a href="'.site_url('reviews/specifications/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-specification.png').'"></a>'
                            );    
                         } else {
                            $data['record_items'][] = array(
                            $subcategory->getID(),
                            $subcategory->getPosition(),
                            '<a href="'.$subcategory->getURL().'" target="_blank">'.$subcategory->getName().'</a>',
                            '<a href="'.site_url('products/listing/'.$subcategory->getID() . '/' . $back_button).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            '<a href="'.site_url('products/listing/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            );     
                         }    
                         
                    }
                } else {
                     foreach ($paginator as $subcategory) {
                     	
                     	 $status = $subcategory->getStatus() ? 'check' : 'delete';
                         $status_subcategory_highlight = $subcategory->getHighlight() ? 'subcategory_highlight_on' : 'subcategory_highlight_off';
                         $highlight = '<a class="table-icon '.$status_subcategory_highlight.'" href="javascript:void(0);" onclick="changeSubcategoryHighlight(this, \''.site_url('products/set_highlighted_subcategory/'.$subcategory->getID()).'\');"></a>';
                         
                         $data['record_items'][] = array(
                            $subcategory->getID(),
                            $subcategory->getPosition(),
                            $subcategory->getID(),
                            '<img border="0" src="'.$subcategory->getImageURL().'">',
                            '<a href="'.$subcategory->getURL().'" target="_blank">'.$subcategory->getName().'</a>',
                            $CI->session->userdata('application_id') ? '-' : $highlight,
                            '<a href="'.site_url('product/specifications/listing/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-specification.png').'"></a>',
                            '<a href="'.site_url('product/subcategories/price_ranges/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-price-range.png').'"></a>',
                            '<a href="'.site_url('settings/sizes/listing/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/size_icon.png').'"></a>',
                            //'<a href="'.site_url('product/subcategories/subcategory_brand_listing/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-brand.png').'"></a>',
                            '<a href="'.site_url('reviews/specifications/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-specification.png').'"></a>',
                            '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('product/subcategories/change_subcategory_status/' . $subcategory->getID()) . '\');">Status</a>',
                            '<a href="'.site_url('product/subcategories/subcategory_details/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            );               
                    }
                }
             return $data;   
    }

    public function deleteSubcategory( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('s')
            ->from('models\Entities\Product\Subcategory', 's')
            ->where($qb->expr()->in('s.id', $id_list));
        
        $query = $qb->getQuery();
        $subcategories = $query->getResult();
        
        foreach( $subcategories as $subcategory ) {
			if( $subcategory->getImage() ) unlink(SERVER_IMAGE_PATH.'icons/subcategories/'.$subcategory->getImage());        	
            $this->_em->remove($subcategory);
        }
        $this->_em->flush();
    }
    
    public function deleteGroup( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('s')
            ->from('models\Entities\Product\Subcategory', 's')
            ->where($qb->expr()->in('s.id', $id_list));
        
        $query = $qb->getQuery();
        $subcategories = $query->getResult();
        
        foreach( $subcategories as $subcategory ) {
			if( $subcategory->getImage() ) unlink(SERVER_IMAGE_PATH.'icons/subcategories/'.$subcategory->getImage());
            foreach( $subcategory->getChildren() as $child ) {
                if( $child->getImage() ) unlink(SERVER_IMAGE_PATH.'icons/subcategories/'.$child->getImage());
                $this->_em->remove($child);     
            }
            $this->_em->remove($subcategory);
        }
        $this->_em->flush();
    }
    
    /*public function getMaxSubcategoryPosition ($category_id){
        
        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(s.position)')
           ->from('models\Entities\Product\Subcategory', 's')
           ->where('s.category=' . $category_id)
           ->andWhere('s.parent is not NULL');

        $query = $qb->getQuery();
        $maxPos = $query->getResult();
        return $maxPos;
    }
    
    public function getSubcategoryByCategory( $category_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('s')
           ->from('models\Entities\Product\Subcategory', 's')
           ->where('s.category=' . $category_id)
           ->add('orderBy', 's.position ASC')
           ->andWhere('s.parent is not NULL');
        
        $query = $qb->getQuery();
        $subcategory = $query->getResult();
        return $subcategory;
    }*/
    
    public function getMaxSubcategoryPosition ($parent_id){
        
        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(s.position)')
           ->from('models\Entities\Product\Subcategory', 's')
		   ->leftJoin('s.parent', 'p')
           ->where('p.id=' . $parent_id);

        $query = $qb->getQuery();
        $maxPos = $query->getResult();
        return $maxPos;
    }
    
    public function getSubcategoryByCategory( $parent_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('s')
           ->from('models\Entities\Product\Subcategory', 's')
		   ->leftJoin('s.parent', 'p')
           ->where('p.id=' . $parent_id)
           ->add('orderBy', 's.position ASC');
        
        $query = $qb->getQuery();
        $subcategory = $query->getResult();
        return $subcategory;
    }
    
    public function getMaxGroupPosition ($category_id){
        
        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(s.position)')
           ->from('models\Entities\Product\Subcategory', 's')
           ->where('s.category=' . $category_id)
           ->andWhere('s.parent is NULL');

        $query = $qb->getQuery();
        $maxPos = $query->getResult();
        return $maxPos;
    }
    
    public function getGroupsByCategory( $category_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('s')
           ->from('models\Entities\Product\Subcategory', 's')
           ->where('s.category=' . $category_id)
           ->add('orderBy', 's.position ASC')
           ->andWhere('s.parent is NULL');
        
        $query = $qb->getQuery();
        $subcategory = $query->getResult();
        return $subcategory;
    }
    
    public function getCategoryGroups($criteria, $category_id, $listing_product = NULL ){ // $listing_product useing same method and giving parametar for using for product listing
       
        $CI =& get_instance(); 
        $user = $CI->auth_manager->user();
       
        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('s') 
           ->from('models\Entities\Product\Subcategory', 's') 
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
           ->where ('s.category = :category_id')
           ->andWhere('s.parent is null') 
           ->setParameters (array('category_id' => $category_id));

        if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }

           $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);
            
            $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);
    
              $data['record_count'] = count($paginator);
                
                if ($listing_product == 1) {
                    foreach ($paginator as $group) {
                         $status_subcategory_highlight = $group->getHighlight() ? 'subcategory_highlight_on' : 'subcategory_highlight_off';
                         $highlight = '<a class="table-icon '.$status_subcategory_highlight.'" href="javascript:void(0);" onclick="changeSubcategoryHighlight(this, \''.site_url('products/set_highlighted_subcategory/'.$group->getID()).'\');"></a>';
                         
                         if( count($group->getChildren()) ) {
                         	$subcategory = '<a href="'.site_url('products/listing_subcategories/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/sub-category.png').'"></a>' ;
                         	$reviews = '<a class="table-icon delete">Unavailable</a>';
                         }else {
                         	$subcategory = '<a class="table-icon delete">Status</a>';
                         	$reviews = '<a href="'.site_url('reviews/specifications/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-specification.png').'"></a>';
                         }
                         
                         if( $user->getGroup()->getID() != 7 ) {
                             $data['record_items'][] = array(
                                $group->getID(),
                                $group->getPosition(),
                                '<a href="'.$group->getURL().'" target="_blank">'.$group->getName().'</a>',
                                $CI->session->userdata('application_id') ? '-' : $highlight,
                                '<a href="'.site_url('products/listing/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                                $subcategory,
                                $reviews
                             );
                         } else {
                            $data['record_items'][] = array(
                                $group->getID(),
                                $group->getPosition(),
                                '<a href="'.$group->getURL().'" target="_blank">'.$group->getName().'</a>',
                                '<a href="'.site_url('products/listing/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                                $subcategory,
                             );    
                         }
                    }
                } else {
                     foreach ($paginator as $group) {
                     	
						 $status = $group->getStatus() ? 'check' : 'delete';
                     	 $status_subcategory_highlight = $group->getHighlight() ? 'subcategory_highlight_on' : 'subcategory_highlight_off';
                         $highlight = '<a class="table-icon '.$status_subcategory_highlight.'" href="javascript:void(0);" onclick="changeSubcategoryHighlight(this, \''.site_url('products/set_highlighted_subcategory/'.$group->getID()).'\');"></a>';
                         
                         $data['record_items'][] = array(
                            $group->getID(),
                            $group->getPosition(),
                            $group->getID(),
                            '<img border="0" src="'.$group->getImageURL().'">',
                            '<a href="'.$group->getURL().'" target="_blank">'.$group->getName().'</a>',
                            $CI->session->userdata('application_id') ? '-' : $highlight,
                            '<a href="'.site_url('product/specifications/listing/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-specification.png').'"></a>',
                            '<a href="'.site_url('product/subcategories/listing/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/sub-category.png').'"></a>',
                            '<a href="'.site_url('product/subcategories/price_ranges/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-price-range.png').'"></a>',
                            '<a href="'.site_url('settings/sizes/listing/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/size_icon.png').'"></a>',
                            //'<a href="'.site_url('product/subcategories/subcategory_brand_listing/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-brand.png').'"></a>',
                            '<a href="'.site_url('reviews/specifications/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-specification.png').'"></a>',
                            '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('product/subcategories/change_subcategory_status/' . $group->getID()) . '\');">Status</a>',
                            '<a href="'.site_url('product/subcategories/details/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            );               
                    }
                }
             return $data;   
    }

	public function getVendorCategoryGroups($criteria, $category_id, $listing_product = NULL ){
       
        $CI =& get_instance(); 
        $user = $CI->auth_manager->user();
       
        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('s') 
           ->from('models\Entities\Product\Subcategory', 's') 
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
           ->where ('s.category = :category_id')
           ->andWhere('s.parent is null') 
           ->setParameters (array('category_id' => $category_id));

        if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }

           $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);
            
            $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);
    
              $data['record_count'] = count($paginator);
                
                if ($listing_product == 1) {
                    foreach ($paginator as $group) {
                         $status_subcategory_highlight = $group->getHighlight() ? 'subcategory_highlight_on' : 'subcategory_highlight_off';
                         //$highlight = '<a class="table-icon '.$status_subcategory_highlight.'" href="javascript:void(0);" onclick="changeSubcategoryHighlight(this, \''.site_url('products/set_highlighted_subcategory/'.$group->getID()).'\');"></a>';
                         if (count ($group->getChildren())) {
                              $subcategory = '<a href="'.site_url('products/listing_subcategories/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/sub-category.png').'"></a>' ;
                         } else {
                              $subcategory = '<a class="table-icon delete">Status</a>';
                         }
                         
                         if( $user->getGroup()->getID() != 7 ) {
                             $data['record_items'][] = array(
                                $group->getID(),
                                $group->getPosition(),
                                '<a href="'.$group->getURL().'" target="_blank">'.$group->getName().'</a>',
                                //$highlight,
                                '<a href="'.site_url('vendor_highlights/details/'.$category_id.'/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                                $subcategory,
                             );
                         } else {
                            $data['record_items'][] = array(
                                $group->getID(),
                                $group->getPosition(),
                                '<a href="'.$group->getURL().'" target="_blank">'.$group->getName().'</a>',
                                '<a href="'.site_url('products/listing/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                                $subcategory,
                             );    
                         }
                    }
                } else {
                     foreach ($paginator as $group) {
                     	 $status_subcategory_highlight = $group->getHighlight() ? 'subcategory_highlight_on' : 'subcategory_highlight_off';
                         $highlight = '<a class="table-icon '.$status_subcategory_highlight.'" href="javascript:void(0);" onclick="changeSubcategoryHighlight(this, \''.site_url('products/set_highlighted_subcategory/'.$group->getID()).'\');"></a>';
                         $data['record_items'][] = array(
                            $group->getID(),
                            $group->getPosition(),
                            '<img border="0" src="'.$group->getImageURL().'">',
                            '<a href="'.$group->getURL().'" target="_blank">'.$group->getName().'</a>',
                            $highlight,
                            '<a href="'.site_url('product/specifications/listing/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-specification.png').'"></a>',
                            '<a href="'.site_url('product/subcategories/listing/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/sub-category.png').'"></a>',
                            '<a href="'.site_url('product/subcategories/details/'.$group->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            );               
                    }
                }
             return $data;   
    }

	public function getVendorCategorySubcategories($criteria, $parent_id, $listing_product = NULL){ 
       
       $CI =& get_instance(); 
       $user = $CI->auth_manager->user();
       
        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();


        $qb->select('s') 
           ->from('models\Entities\Product\Subcategory', 's') 
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
           ->where ('s.parent = :parent') 
           ->setParameters (array('parent' => $parent_id));
       
        if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }

           $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);
            
            $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);
    
              $data['record_count'] = count($paginator);
                
                if ($listing_product == 1) {
                    foreach ($paginator as $subcategory) {

                        
                        $back_button = $parent_id; // make availble back button to right place
                        

                         $status_subcategory_highlight = $subcategory->getHighlight() ? 'subcategory_highlight_on' : 'subcategory_highlight_off';
                         $highlight = '<a class="table-icon '.$status_subcategory_highlight.'" href="javascript:void(0);" onclick="changeSubcategoryHighlight(this, \''.site_url('products/set_highlighted_subcategory/'.$subcategory->getID()).'\');"></a>';

                         if( $user->getGroup()->getID() != 7 ) {
                            $data['record_items'][] = array(
                            $subcategory->getID(),
                            $subcategory->getPosition(),
                            '<a href="'.$subcategory->getURL().'" target="_blank">'.$subcategory->getName().'</a>',
                            //$highlight,  
                            '<a href="'.site_url('vendor_highlights/details/'.$subcategory->getID() . '/' . $back_button).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            '<a href="'.site_url('vendor_highlights/details/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            );    
                         } else {
                            $data['record_items'][] = array(
                            $subcategory->getID(),
                            $subcategory->getPosition(),
                            '<a href="'.$subcategory->getURL().'" target="_blank">'.$subcategory->getName().'</a>',
                            '<a href="'.site_url('products/listing/'.$subcategory->getID() . '/' . $back_button).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            '<a href="'.site_url('products/listing/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            );     
                         }    
                         
                    }
                } else {
                     foreach ($paginator as $subcategory) {
                         $status_subcategory_highlight = $subcategory->getHighlight() ? 'subcategory_highlight_on' : 'subcategory_highlight_off';
                         $highlight = '<a class="table-icon '.$status_subcategory_highlight.'" href="javascript:void(0);" onclick="changeSubcategoryHighlight(this, \''.site_url('products/set_highlighted_subcategory/'.$subcategory->getID()).'\');"></a>';
                         $data['record_items'][] = array(
                            $subcategory->getID(),
                            $subcategory->getPosition(),
                            '<img border="0" src="'.$subcategory->getImageURL().'">',
                            '<a href="'.$subcategory->getURL().'" target="_blank">'.$subcategory->getName().'</a>',
                            $highlight,
                            '<a href="'.site_url('product/specifications/listing/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-specification.png').'"></a>',
                            '<a href="'.site_url('product/subcategories/subcategory_details/'.$subcategory->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                            );               
                    }
                }
             return $data;   
    }

	public function getSubcategoryBrands($criteria, $subcategory_id){
    	
		$subcategory = $this->_em->getRepository('models\Entities\Product\Subcategory')->find($subcategory_id);
       
		$brand_list = array();
		foreach( $subcategory->getBrands() as $brand ) {
			$brand_list[] = $brand->getID();	
		}

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('b') 
           ->from('models\Entities\Product\Brand', 'b') 
           ->orderBy($this->brand_relations[$criteria->sortname], $criteria->sortorder);
           if( !empty( $brand_list ) ) {
		   		$qb->where($qb->expr()->in('b.id', $brand_list ));
		   } else {
				$qb->where('b.id is NULL');	
		   }
		   $qb->setFirstResult($criteria->offset)
           ->setMaxResults($criteria->limit);

            if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->brand_relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }

    		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
			if( $data['record_count'] = $data['records']->count() ) {

				foreach( $data['records'] as $record ) {
	
	                $data['record_items'][] = array(
	                    $record->getID(),
	                    $record->getName(),
	                );               
	            }
			}
            return $data;  
    }
	
}
     
 /* End of file ProductSubcategoryRepository.php */
 /* Location: ./system/applications/_backend/models/ProductSubcategoryRepository.php */