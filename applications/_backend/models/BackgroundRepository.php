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
 
 class BackgroundRepository extends EntityRepository {
		
      private $CI;
	  
      private $relations = array(
        'id'             => 'b.id',
        'name'           => 'b.name',
        'status'         => 'b.status',
        ); 
    
    public function getBackgrounds( $criteria ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Background', 'b')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder);
        
        if( $criteria->search_keyword != '' ) {
            $qb->where($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
               ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' )
               ->setFirstResult($criteria->offset)
               ->setMaxResults($criteria->limit);
        }
		
		$this->CI =& get_instance();
		if($this->CI->session->userdata('application_id'))
			$qb->andWhere($qb->expr()->eq('b.vendor', $qb->expr()->literal( $this->CI->session->userdata('application_id') )));
		else
			$qb->andWhere('b.vendor is NULL');
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
		
        if( $data['record_count'] = $data['records']->count() ) {		
        	
            foreach( $data['records'] as $background ) {
                
                $status = $background->getStatus() ? 'check' : 'delete';
                
				if( $background->getVendor() ) { $vendor = $background->getVendor()->getID(); } else { $vendor = ''; }
				
                $data['record_items'][] = array(
                    $background->getID(),
                    $background->getName(),
                    $vendor,
                    '<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('settings/backgrounds/change_status/'.$background->getID()).'\');">Status</a>',
                    '<a href="'.site_url('settings/backgrounds/details/'.$background->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
                );
            }
        }
        
        return $data;
    }
    
    public function deleteBackgrounds( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Background', 'b')
            ->where($qb->expr()->in('b.id', $id_list));
        
        $query = $qb->getQuery();
        $backgrounds = $query->getResult();
        
        foreach( $backgrounds as $background ) {
			foreach( $background->getImages() as $background_image ) {
	        	unlink(SERVER_IMAGE_PATH.'backgrounds/'.$background_image->getName());
				$this->_em->remove($background_image);
	        }
            $this->_em->remove($background);
        }
        
        
         
        $this->_em->flush();
    }
 }
 
 /* End of file BackgroundRepository.php */
 /* Location: ./system/applications/_backend/models/BackgroundRepository.php */
