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
 
 class ProductCategoryRepository extends EntityRepository {
    
    private $relations = array(
        'position'  => 'c.position',
        'name'     	=> 'c.name',
        'id'		=> 'c.id'
    ); 
	
	private $brand_relations = array(
        'id'           => 'b.id',
        'name'         => 'b.name',
    );
     
    public function getCategories( $criteria, $type = FALSE ) {

        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('c')
            ->from('models\Entities\Product\Category', 'c')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder);

            if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }
        
        $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);
        
        $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);

          $data['record_count'] = count($paginator);
            foreach ($paginator as $category) {
                 
               $data['record_items'][] = array(
                    $category->getID(),
                    $category->getPosition(),
                    $category->getID(),
                    $category->getName(),
                    '<a href="'.site_url('product/subcategories/groups_listing/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/sub-category.png').'"></a>',
                    '<a href="'.site_url('products/listing/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-product.png').'"></a>',
                    '<a href="'.site_url('product/categories/category_brand_listing/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-brand.png').'"></a>',
                    '<a href="'.site_url('product/categories/details/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
                    );               
             }
         return $data;   
    }

	public function getCategoryBrands($criteria, $category_id){
    	
		$category = $this->_em->getRepository('models\Entities\Product\Category')->find($category_id);
       
		$brand_list = array();
		foreach( $category->getBrands() as $brand ) {
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
    


 
 /* End of file ProductRepository.php */
 /* Location: ./system/applications/_backend/models/ProductRepository.php */