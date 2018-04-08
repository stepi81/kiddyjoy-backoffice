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
 
 class PromotionRepository extends EntityRepository {
    	
		private $CI;
		
        private $relations = array(
                   'title' => 'p.title',
                   'status'=> 'p.status',
         ); 
    
    public function getPromotions( $criteria ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Promotion\Page', 'p')
            ->orderBy('p.'.$criteria->sortname, $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
        
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
		
		$this->CI =& get_instance();
		if($this->CI->session->userdata('application_id'))
			$qb->andWhere($qb->expr()->eq('p.vendor', $qb->expr()->literal( $this->CI->session->userdata('application_id') )));
		else
			$qb->andWhere('p.vendor is NULL');
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
		
		if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $promotions ) {
                
                $status = $promotions->getStatus() ? 'check' : 'delete';
                
                $data['record_items'][] = array(
                    $promotions->getID(),
                    $promotions->getTitle(),
                    '<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('promotions/change_status/'.$promotions->getID()).'\');">Status</a>',
                    '<a class="table-icon details" href="'.site_url('promotions/details/'.$promotions->getID()).'">Detalji</a>',
                );
            }
        }
        
        return $data;
    }
    
    public function deletePromotions( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Promotion\Page', 'p')
            ->where($qb->expr()->in('p.id', $id_list));
        
        $query = $qb->getQuery();
        $promotions = $query->getResult();
        
        foreach( $promotions as $info ) {
           
          foreach ($info->getImages() as $image) {
            unlink(SERVER_IMAGE_PATH.'promotions/pages/'.$image->getName());  
          }   
        $this->_em->remove($info);
        }
        $this->_em->flush();
    }
    
    public function getPageImages( $promotion_id ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('i')
            ->from('models\Entities\Promotion\PageImage', 'i')
            ->where($qb->expr()->eq('i.page', $promotion_id));
        
        $query = $qb->getQuery();
        
        return $query->getResult();
    }
    
    public function getPromotionProducts( $id_list ) {
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Product', 'p INDEX BY p.id')
            ->where($qb->expr()->in('p.id', array_unique( $id_list )));
        
        return count( $qb->getQuery()->getResult() );
    }
 }
 
 /* End of file PromotionRepository.php */
 /* Location: ./system/applications/_backend/models/PromotionRepository.php */