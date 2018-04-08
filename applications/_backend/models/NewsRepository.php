<?php

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class NewsRepository extends EntityRepository {
	  
    private $CI;
	  
    private $relations = array(
        'date'         => 'n.date',
        'title'        => 'n.title',
        'status'       => 'n.status',   
        );
         
	public function getNews( $criteria, $type_id ) {
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('n')
			->from('models\Entities\News\Info', 'n')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit)
            ->where ('n.type_id = :type_id') 
            ->setParameters (array('type_id'=> $type_id));
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
		
		$this->CI =& get_instance();
		if($this->CI->session->userdata('application_id'))
			$qb->andWhere($qb->expr()->eq('n.vendor', $qb->expr()->literal( $this->CI->session->userdata('application_id') )));
		else
			$qb->andWhere('n.vendor is NULL');
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        
        if( $data['record_count'] = $data['records']->count() ) {
			
			foreach( $data['records'] as $news ) {
				
				$status = $news->getStatus() ? 'check' : 'delete';
				
				$data['record_items'][] = array(
					$news->getID(),
					'<img border="0" src="'.$news->getThumbURL().'">',
					$news->getFormatedDate(),
					$news->getTitle(),
					'<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('news/change_status/'.$news->getID()).'\');">Status</a>',
	                '<a class="table-icon comments" href="' . site_url('comments/listing_by_record/2/' . $news->getID()) . '">Komentari</a>',
	                '<a class="table-icon details" href="'.site_url('news/details/'.$news->getID()).'">Detalji</a>',
				);
			}
		}
		
		return $data;
	}
	
	public function deleteNews( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('n')
			->from('models\Entities\News\Info', 'n')
			->where($qb->expr()->in('n.id', $id_list));
		
		$query = $qb->getQuery();
		$news = $query->getResult();
		
		foreach( $news as $info ) {
            if( file_exists(SERVER_IMAGE_PATH.'news/thumb/'.$info->getThumb()) ) {
                unlink(SERVER_IMAGE_PATH.'news/thumb/'.$info->getThumb());
			}
			$images = $this->getPageImages($info->getID());
 			foreach( $images as $image ) {
 				if( file_exists(SERVER_IMAGE_PATH.'news/pages/'.$image->getName()) ) {
 					unlink(SERVER_IMAGE_PATH.'news/pages/'.$image->getName());	
 				}	
				$this->_em->remove($image);
			}
            $this->_em->remove($info); 
		}
		
		$this->_em->flush();
	}
	
	public function getPageImages( $news_id ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('i')
			->from('models\Entities\News\InfoImage', 'i')
			->where($qb->expr()->eq('i.info', $news_id));
		
		$query = $qb->getQuery();
		
		return $query->getResult();
	}
	
	public function getNewsProducts( $id_list ) {
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Product', 'p INDEX BY p.id')
            ->where($qb->expr()->in('p.id', array_unique( $id_list )));
        
        return count( $qb->getQuery()->getResult() );
    }
 }
 
 /* End of file NewsRepository.php */
 /* Location: ./system/applications/_backend/models/NewsRepository.php */