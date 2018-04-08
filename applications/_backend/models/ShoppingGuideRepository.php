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
 
 class ShoppingGuideRepository extends EntityRepository {
      
      
    private $relations = array(
        'id'           => 'g.id', 
        'title'        => 'g.title',
        'status'       => 'g.status',   
        );
         
    public function getGuides( $criteria ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('g')
            ->from('models\Entities\ShoppingGuide\Guide', 'g')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
        
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        
        if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $guides ) {
                
                $status = $guides->getStatus() ? 'check' : 'delete';
                
                $data['record_items'][] = array(
                    $guides->getID(),
                    $guides->getTitle(),
                    '<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('shopping_guides/change_status/'.$guides->getID()).'\');">Status</a>',
                    '<a class="table-icon details" href="'.site_url('shopping_guides/details/'.$guides->getID()).'">Detalji</a>',
                );
            }
        }
        
        return $data;
    }
    
    public function deleteGuides( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('n')
            ->from('models\Entities\ShoppingGuide\Guide', 'n')
            ->where($qb->expr()->in('n.id', $id_list));
        
        $query = $qb->getQuery();
        $guides = $query->getResult();
        
        foreach( $guides as $guide ) {
            foreach ($guide->getImages() as $image) {
                unlink(SERVER_PATH . 'assets/img/shopping_guide/' . $image->getName());
            }
                $this->_em->remove($guide);
        }
        $this->_em ->flush();
    }
    
    public function getGuideImages( $guide_id ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('i')
            ->from('models\Entities\ShoppingGuide\GuideImage', 'i')
            ->where($qb->expr()->eq('i.guide', $guide_id));
        
        $query = $qb->getQuery();
        
        return $query->getResult();
    }
 }
 
 /* End of file ShoppingGuideRepository.php */
 /* Location: ./system/applications/_backend/models/ShoppingGuideRepository.php */