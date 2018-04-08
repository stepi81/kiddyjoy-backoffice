<?php

/**
 * ...
 * @author Aleksandar Milas [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class PreorderPagesRepository extends EntityRepository {
	  
    private $CI;
	  
    private $relations = array(
        'date'         => 'n.date',
        'title'        => 'n.title',
        'status'       => 'n.status',   
        );
         
	public function getPreorders( $criteria, $type_id ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('n')
			->from('models\Entities\Preorder', 'n')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit)
            ->where ('n.type_id = :type_id') 
            ->setParameters (array('type_id'=> $type_id));
		
			
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
		/*
		$this->CI =& get_instance();
		if($this->CI->session->userdata('application_id'))
			$qb->andWhere($qb->expr()->eq('n.vendor', $qb->expr()->literal( $this->CI->session->userdata('application_id') )));
		else
			$qb->andWhere('n.vendor is NULL');
        */
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        
        if( $data['record_count'] = $data['records']->count() ) {
			
			foreach( $data['records'] as $preorder ) {
				
				$status = $preorder->getStatus() ? 'check' : 'delete';
                
                //if( $preorder->getVendor() ) { $vendor = $preorder->getVendor()->getID(); } else { $vendor = ''; } 
				
				$data['record_items'][] = array(
					$preorder->getID(),
					'<img border="0" src="'.$preorder->getThumbURL().'">',
                    //$vendor,
					$preorder->getFormatedDate(),
					$preorder->getTitle(),
					'<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('preorder_pages/change_status/'.$preorder->getID()).'\');">Status</a>',
	                '<a class="table-icon details" href="'.site_url('preorder_pages/details/'.$preorder->getID()).'">Detalji</a>',
				);
			}
		}
		
		return $data;
	}
	
	public function deletePreorders( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('n')
			->from('models\Entities\Preorder', 'n')
			->where($qb->expr()->in('n.id', $id_list));
		
		$query = $qb->getQuery();
		$preorders = $query->getResult();
		
		foreach( $preorders as $preorder ) {
			
			if($items = $preorder->getItems()){
	 			foreach( $items as $item ) {	
					$this->_em->remove($item);
				}
			}
            if( $preorder->getThumb() ) {
                if( unlink(SERVER_IMAGE_PATH.'preorders/'.$preorder->getThumb()) ) {
                	$images = $this->getPageImages($preorder->getID());
    	 			foreach( $images as $image ) {	
		        		unlink(SERVER_IMAGE_PATH.'preorders/pages/'.$image->getName());
						$this->_em->remove($image);
					}
                	$this->_em->remove($preorder);
				}
            } else {
                $this->_em->remove($preorder);
            }
		}
		
		$this->_em->flush();
	}
	
	public function getPageImages( $preorder_id ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('i')
			->from('models\Entities\Images\PreorderImage', 'i')
			->where($qb->expr()->eq('i.preorder', $preorder_id));
		
		$query = $qb->getQuery();
		
		return $query->getResult();
	}
 }
 
 /* End of file PreorderPagesRepository.php */
 /* Location: ./system/applications/_backend/models/PreorderPagesRepository.php */