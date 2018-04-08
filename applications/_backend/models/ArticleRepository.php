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
 
 class ArticleRepository extends EntityRepository {
	  
    private $CI;
	  
    private $relations = array(
        'date'         => 'a.date',
        'title'        => 'a.title',
        'status'       => 'a.status', 
        'appliance_id' => 'a.appliance_id',   
        );
         
	public function getArticles( $criteria, $category_id ) {
		
		$category = $this->_em->getRepository('models\Entities\Article\Category')->find( $category_id );
		
		$category_list = array();
		$category_list[] = $category->getID();
		if( $category->getChildren() ) {
			foreach( $category->getChildren() as $children ) {
				$category_list[] = $children->getID();
			}
		}
		
		$data['record_items'] = array();
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('a')
			->from('models\Entities\Article', 'a')
			->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit)
            ->where($qb->expr()->in('a.category', $category_list))
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
					'<img border="0" src="'.$record->getThumbURL().'">',
					$record->getFormatedDate(),
					$record->getTitle(),
					'<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('articles/change_status/'.$record->getID()).'\');">Status</a>',
	                '<a class="table-icon comments" href="' . site_url('comments/listing_by_record/3/' . $record->getID()) . '">Komentari</a>',
	                '<a class="table-icon details" href="'.site_url('articles/details/'.$record->getID()).'">Detalji</a>',
				);
			}
		}
		
		return $data;
	}
	
	public function deleteArticles( $id_list ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('a')
			->from('models\Entities\Article', 'a')
			->where($qb->expr()->in('a.id', $id_list));
		
		$query = $qb->getQuery();
		$records = $query->getResult();
		
		foreach( $records as $record ) {
            if( $record->getThumb() ) {
            	if(file_exists(SERVER_IMAGE_PATH.'articles/thumb/'.$record->getThumb())) {
                	unlink(SERVER_IMAGE_PATH.'articles/thumb/'.$record->getThumb());
				}
            }
			
			if( $record->getImage() ) {
				if(file_exists(SERVER_IMAGE_PATH.'articles/large/'.$record->getImage())) {
                	unlink(SERVER_IMAGE_PATH.'articles/large/'.$record->getImage());
				}
				if(file_exists(SERVER_IMAGE_PATH.'articles/medium/'.$record->getImage())) {
					unlink(SERVER_IMAGE_PATH.'articles/medium/'.$record->getImage());
				}
            }
			
			if( $record->getContentImages() ) {
				$images = $record->getContentImages();
	 			foreach( $images as $image ) {
	 				if(file_exists(SERVER_IMAGE_PATH.'articles/content/'.$image->getName())) {	
	        			unlink(SERVER_IMAGE_PATH.'articles/content/'.$image->getName());
					}
					$this->_em->remove($image);
				}	
			}
            $this->_em->remove($record); 
		}
		
		$this->_em->flush();
	}
	
	public function getArticleImages( $article_id ) {
		
		$qb = $this->_em->createQueryBuilder();
		
		$qb->select('i')
			->from('models\Entities\Article\Image', 'i')
			->where($qb->expr()->eq('i.article', $article_id));
		
		$query = $qb->getQuery();
		
		return $query->getResult();
	}

	public function getArticleProducts( $id_list ) {
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Product', 'p INDEX BY p.id')
            ->where($qb->expr()->in('p.id', array_unique( $id_list )));
        
        return count( $qb->getQuery()->getResult() );
    }
 }
 
 /* End of file NewsRepository.php */
 /* Location: ./system/applications/_backend/models/NewsRepository.php */