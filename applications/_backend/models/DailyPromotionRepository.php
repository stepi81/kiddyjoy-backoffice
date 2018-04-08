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
 
 class DailyPromotionRepository extends EntityRepository {
      
      
    private $relations = array(
        'product'       => 'd.product',           
        'start_date'    => 'd.start_date',
        'end_date'      => 'd.end_date',
        'price'         => 'd.price',
        'status'        => 'n.status',   
        );
         
    public function getPromotions( $criteria ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('d')
            ->from('models\Entities\DailyPromotion', 'd')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
        
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $promotion ) {
                
                $status = $promotion->getStatus() ? 'check' : 'delete';
                
                $data['record_items'][] = array(
                    $promotion->getID(),
                    '<a target="_blank" href="'. WEB_APP_URL.url_title($promotion->getProduct()->getSubcategory()->getName(), 'underscore', TRUE).'/'.url_title($promotion->getProduct()->getName(), 'underscore', TRUE).'-'.$promotion->getProduct()->getID().'">'.$promotion->getProduct()->getName().'</a>',
                    $promotion->getFormatedStartDate(),
                    $promotion->getFormatedEndDate(),
                    $promotion->getProduct()->getPrice(),
                    '<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('daily_promotions/change_status/'.$promotion->getID()).'\');">Status</a>',
                    '<a class="table-icon details" href="'.site_url('daily_promotions/details/'.$promotion->getID()).'">Detalji</a>',
                );
            }
        }
        
        return $data;
    }
    
    public function deletePromotions( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('d')
            ->from('models\Entities\DailyPromotion', 'd')
            ->where($qb->expr()->in('d.id', $id_list));
        
        $query = $qb->getQuery();
        $promotions = $query->getResult();
        
        foreach( $promotions as $promotion ) {
            $this->_em->remove($promotion); 
        }
        
        $this->_em->flush();
    }
 }
 
 /* End of file DailyPromotionRepository.php */
 /* Location: ./system/applications/_backend/models/DailyPromotionRepository.php */