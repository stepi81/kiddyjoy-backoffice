<?php

/**
 * ...
 * @author Marko Stepanovic [ ABC Design ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class BundleRepository extends EntityRepository {
     
    private $relations = array(
        'id'            => 'b.id',
        'name'          => 'b.name',
        'price'         => 'b.price'
    ); 
    
    private $product_relations = array(
        'name'          => 'b.id',
    ); 
	
	private $products_relations = array(
        'name'          => 'p.id',
        'subcategory'	=> 's.name'  
    ); 
    
    public function getBundles( $criteria ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Product\Bundle', 'b')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder);
            
            if( $criteria->search_keyword != '' ) {
                $qb->where($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $bundle ) {
                
            	$items = '';
				foreach( $bundle->getItems() as $item ) $items .= '<br />'.$item->getProduct()->getName();
            	
                $data['record_items'][] = array(
                    $bundle->getID(),
                    '<b>'.$bundle->getName().'</b>'.$items,
                    $bundle->getPrice(TRUE),
                    '<a href="'.site_url('bundles/details/'.$bundle->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
                );
            }
        }
        
        return $data;
    }
    
    public function deleteBundles( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Product', 'p')
            ->leftJoin('p.bundles', 'b')
            ->where($qb->expr()->in('b.id', $id_list));
        
        $products = $qb->getQuery()->getResult();
        
    	foreach( $products as $product ) $product->removeBundles($id_list);
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Product\Bundle', 'b')
            ->where($qb->expr()->in('b.id', $id_list));
        
        $bundles = $qb->getQuery()->getResult();
        
        foreach( $bundles as $bundle ) {
        	// TODO if bundle sold do not delete
        	$bundle->removeItems();
            $this->_em->remove($bundle);
        }
    	
    	$this->_em->flush();
    }
    
    public function deleteBundleProducts( $id_list, $id ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Product\Bundle', 'b')
            ->where ('b.id = :bundle') 
            ->setParameters (array('bundle' => $id));
            
        $query = $qb->getQuery();
        $bundle = $query->getSingleResult();
        
        foreach( $id_list as $item_id )
            $bundle->getItems()->removeElement( $this->_em->getReference('models\Entities\Product\Bundle\BundleItem', $item_id) );
        
         $this->_em->flush();
    }
    
    public function getBundleItems( $criteria, $bundle_id ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Product\Bundle', 'b')
            ->orderBy($this->product_relations[$criteria->sortname], $criteria->sortorder)
            ->where ('b.id = :bundle') 
            ->setParameters (array('bundle' => $bundle_id));
            
		if( $criteria->search_keyword != '' ) {
			$qb->andWhere($qb->expr()->like($this->product_relations[$criteria->search_field], ':keyword'))
				->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
		}
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $bundle ) {
                
                foreach( $bundle->getItems() as $item  )
                $data['record_items'][] = array(
                    $item->getID(),
                    '<a href="'.$item->getProduct()->getURL().'" target="_blank">'.$item->getProduct()->getName().'</a>',
                    $item->getProduct()->getFormatedPrice(),
                    $item->getDiscount().' %',
                    $item->getPrice(TRUE)
                );
            }
        }
        
        return $data;
    }
    
    public function getProductBundles( $criteria, $product_id ) {
        
        $product = $this->_em->getRepository('models\Entities\Product')->find($product_id);
        
        $bundle_list = array();

        foreach( $product->getBundles() as $bundle )
			$bundle_list[] = $bundle->getID();
		
        $data['record_items'] = array();
 
        $qb = $this->_em->createQueryBuilder();
        
		if(empty($bundle_list)) {
	        $data['records'] = array();
			$data['record_count'] = 0;
			return $data;
		}
		
        $qb->select('b, i, ip')
            ->from('models\Entities\Product\Bundle', 'b')
            ->leftJoin('b.items', 'i')
            ->leftJoin('i.product', 'ip')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->where($qb->expr()->in('b.id', $bundle_list))
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);

        if( $criteria->search_keyword != '' ) {
             $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
        }
        
        $data['records'] = new Paginator($qb->getQuery(), TRUE);
        
        if( $data['record_count'] = $data['records']->count() ) {
			
			foreach( $data['records'] as $bundle ) {
				
				$items = '';
				foreach( $bundle->getItems() as $item )
					$items .= '<br />'.$item->getProduct()->getName();
				
                $data['record_items'][] = array(
                    $bundle->getID(),
                    '<b>'.$bundle->getName().'</b>'.$items,
                    $bundle->getSalePrice($product->getPrice(), TRUE),
                    '<a href="'.site_url('bundles/details/'.$bundle->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
                );
            }
        }
        
        return $data;
    }

	public function getProductsWithBundle( $criteria ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select(array(
            'partial p.{id, name}',
            'partial s.{id, name}',
            'partial b.{id}'
            ))
            ->from('models\Entities\Product', 'p')
			->leftJoin('p.subcategory', 's')
			->innerJoin('p.bundles', 'b')
            ->orderBy($this->products_relations[$criteria->sortname], $criteria->sortorder);
            
            if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->products_relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {
            
	        foreach( $data['records'] as $product ) {
	            
	            $data['record_items'][] = array(
	                $product->getID(),
	                '<a href="'.$product->getURL().'" target="_blank">'.$product->getName().'</a>',
	                $product->getSubcategory()->getName(),
	                '<a href="'.site_url( 'product/bundles/listing/'.$product->getID() ).'"><img border="0" src="'.layout_url( count( $product->getBundles() ) ? 'flexigrid/icon-bundle-y.png' : 'flexigrid/icon-bundle-n.png' ).'"></a>',
	                '<a href="'.site_url('products/details/'.$product->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
	            );               
	        }
		}
        return $data;  
    }
 }
 
 /* End of file BundleRepository.php */
 /* Location: ./system/applications/_backend/models/BundleRepository.php */
?>