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
 
 class CartShippingRepository extends EntityRepository {
	
    private $relations = array(
        'id' => 's.id',
        'title' => 's.title', 
    );  
    
	public function getShippingOptions( $criteria ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('s')
			->from('models\Entities\Cart\ShippingOption', 's')
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
				
				$status = $record->getStatus() ? 'check' : 'delete';
				
				$data['record_items'][] = array(
					$record->getID(),
					$record->getID(),
					$record->getTitle(),
					'<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('cart/shipping/change_status/'.$record->getID()).'\');">Status</a>',
	                '<a href="'.site_url('cart/shipping/details/'.$record->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
				);
			}
		}
		
		return $data;
	}

	public function deleteShipping( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('s')
			->from('models\Entities\Cart\ShippingOption', 's')
			->where($qb->expr()->in('s.id', $id_list));
		
		$query = $qb->getQuery();
		$records = $query->getResult();
		
		foreach( $records as $record ) {
			if(file_exists(SERVER_IMAGE_PATH.'cart/shipping/'.$record->getIcon())) {
				unlink(SERVER_IMAGE_PATH.'cart/shipping/'.$record->getIcon());
			}
        	$this->_em->remove($record);
		}
		
		$this->_em->flush();
	}

 }
 
 /* End of file CartShippingRepository.php */
 /* Location: ./system/applications/_backend/models/CartShippingRepository.php */