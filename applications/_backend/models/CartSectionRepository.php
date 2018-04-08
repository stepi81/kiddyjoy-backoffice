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
 
 class CartSectionRepository extends EntityRepository {
	
    private $relations = array(
        'id' => 's.id',
        'title' => 's.title', 
    );  
    
	public function getSections( $criteria ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('s')
			->from('models\Entities\Cart\Section', 's')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

			foreach( $data['records'] as $record ) {
				
				$data['record_items'][] = array(
					$record->getID(),
					$record->getID(),
					$record->getTitle(),
	                '<a href="'.site_url('cart/sections/details/'.$record->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
				);
			}
		}
		
		return $data;
	}

 }
 
 /* End of file CartSectionRepository.php */
 /* Location: ./system/applications/_backend/models/CartSectionRepository.php */