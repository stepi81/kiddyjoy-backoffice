<?php

/**
 * ...
 * @author Damir Mozar [ ABC Design ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class CouponRepository extends EntityRepository {
 	
		private $CI;
		
        private $relations = array(
                   'title' => 'cp.title',
                   'status'=> 'cp.status',
         ); 
    
       public function getPromotions( $criteria ) {
       		
       	$data['record_items'] = array();
       	$qb = $this->_em->createQueryBuilder();
       	
       	$qb->select('cp')
       		->from('models\Entities\Order\Coupon', 'cp')
            ->orderBy('cp.'.$criteria->sortname, $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
        
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
		
		if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $promotions ) {
                
                $status = $promotions->getStatus() ? 'check' : 'delete';
                
                $data['record_items'][] = array(
                    $promotions->getID(),
                    $promotions->getTitle(),
                    $promotions->getCode(),
                    '<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('promotion_code/change_status/'.$promotions->getID()).'\');">Status</a>',
                    '<a class="table-icon details" href="'.site_url('promotion_code/edit/'.$promotions->getID()).'">Detalji</a>',
                );
            }
        }
        return $data;
    }


	public function deletePromotions( $id_list )
	{
		 $qb = $this->_em->createQueryBuilder();
        
         $qb->select('cp')
            ->from('models\Entities\Order\Coupon', 'cp')
            ->where($qb->expr()->in('cp.id', $id_list));
        
	        $promotions = $qb->getQuery()->getResult();
			
			foreach($promotions as $promo)
			{
				$this->_em->remove($promo);
	        	$this->_em->flush();
			}
	}
	
	
	public function getCouponByID($id)
	{
		 $qb = $this->_em->createQueryBuilder();
        
         $qb->select('cp')
            ->from('models\Entities\Order\Coupon', 'cp')
            ->where('cp.id = :id')
			->setParameter('id',$id);
        
	   try 
		{
            return $qb->getQuery()->getSingleResult();
        }
        catch( \Doctrine\ORM\NoResultException $e ) {
            return FALSE;
      	}
	}

 }
 
 /* End of file CouponRepository.php */
 /* Location: ./system/applications/_backend/models/CouponRepository.php */