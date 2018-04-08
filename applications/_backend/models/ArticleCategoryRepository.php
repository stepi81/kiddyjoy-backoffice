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
 
 class ArticleCategoryRepository extends EntityRepository {
    
    private $relations = array(
        'position' => 'c.id',
        'name'     => 'c.name',
    ); 
     
    public function getCategories( $criteria, $category_id = FALSE ) {

        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('c')
            ->from('models\Entities\Article\Category', 'c');
			if( $category_id ) {
				$qb->where('c.parent='. $category_id);	
			} else {
				$qb->where('c.parent is null');
			}
            $qb->orderBy($this->relations[$criteria->sortname], $criteria->sortorder);

            if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }
        
        $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);
        
        $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);
        $data['record_count'] = count($paginator);
		
        foreach ($paginator as $category) {
        	
		    $status = $category->getStatus() ? 'check' : 'delete';
            
			if( !$category_id ) { 
	            $data['record_items'][] = array(
	                $category->getID(),
	                $category->getPosition(),
	                $category->getName(),
	                '<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('article/article_categories/change_status/'.$category->getID()).'\');">Status</a>',
	                '<a href="'.site_url('articles/listing/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-article.png').'"></a>',
	                '<a href="'.site_url('article/article_highlights/listing/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-article-highlight.png').'"></a>',
	                '<a href="'.site_url('article/article_categories/listing/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/sub-category.png').'"></a>',
	                '<a href="'.site_url('article/article_categories/details/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
					); 
			} else {
				$data['record_items'][] = array(
	                $category->getID(),
	                $category->getPosition(),
	                $category->getName(),
	                '<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('article/article_categories/change_status/'.$category->getID()).'\');">Status</a>',
	                '<a href="'.site_url('articles/listing/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-article.png').'"></a>',
	                '<a href="'.site_url('article/article_highlights/listing/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-article-highlight.png').'"></a>',
	                '<a href="'.site_url('article/article_categories/details/'.$category->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
					);	
			}             
         }
         
         return $data;   
    }

	public function deleteCategories( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('c')
            ->from('models\Entities\Article\Category', 'c')
            ->where($qb->expr()->in('c.id', $id_list));
        
        $query = $qb->getQuery();
        $records = $query->getResult();
        
        foreach( $records as $record ) {
        	
			$article_list = array();
			
			if( $record->getChildren() ) {
				foreach( $record->getChildren() as $children ) {		
						
					if($children->getImage()) { unlink(SERVER_IMAGE_PATH.'articles/categories/'.$children->getImage()); }
					
					if( $children->getArticles() ) {

						foreach( $children->getArticles() as $article ) {	
							
							if( $article->getThumb() ) {
				            	if(file_exists(SERVER_IMAGE_PATH.'articles/thumb/'.$article->getThumb())) {
				                	unlink(SERVER_IMAGE_PATH.'articles/thumb/'.$article->getThumb());
								}
				            }
							
							if( $article->getImage() ) {
								if(file_exists(SERVER_IMAGE_PATH.'articles/large/'.$article->getImage())) {
				                	unlink(SERVER_IMAGE_PATH.'articles/large/'.$article->getImage());
								}
								if(file_exists(SERVER_IMAGE_PATH.'articles/medium/'.$article->getImage())) {
									unlink(SERVER_IMAGE_PATH.'articles/medium/'.$article->getImage());
								}
				            }
							
							if( $article->getContentImages() ) {
								$images = $article->getContentImages();
					 			foreach( $images as $image ) {
					 				if(file_exists(SERVER_IMAGE_PATH.'articles/content/'.$image->getName())) {	
					        			unlink(SERVER_IMAGE_PATH.'articles/content/'.$image->getName());
									}
									$this->_em->remove($image);
								}	
							}
						}	
					}	
				}
			}
			
			if( $record->getArticles() ) {
				foreach( $record->getArticles() as $article ) {
					
					if( $article->getThumb() ) {
		            	if(file_exists(SERVER_IMAGE_PATH.'articles/thumb/'.$article->getThumb())) {
		                	unlink(SERVER_IMAGE_PATH.'articles/thumb/'.$article->getThumb());
						}
		            }
					
					if( $article->getImage() ) {
						if(file_exists(SERVER_IMAGE_PATH.'articles/large/'.$article->getImage())) {
		                	unlink(SERVER_IMAGE_PATH.'articles/large/'.$article->getImage());
						}
						if(file_exists(SERVER_IMAGE_PATH.'articles/medium/'.$article->getImage())) {
							unlink(SERVER_IMAGE_PATH.'articles/medium/'.$article->getImage());
						}
		            }
					
					if( $article->getContentImages() ) {
						$images = $article->getContentImages();
			 			foreach( $images as $image ) {
			 				if(file_exists(SERVER_IMAGE_PATH.'articles/content/'.$image->getName())) {	
			        			unlink(SERVER_IMAGE_PATH.'articles/content/'.$image->getName());
							}
							$this->_em->remove($image);
						}	
					}
				}
			}
			
            if($record->getImage()) { unlink(SERVER_IMAGE_PATH.'articles/categories/'.$record->getImage()); }
			$this->_em->remove($record);
        }
        
        $this->_em->flush();
    }
	
	public function getMaxPosition( $category_id = null ) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(c.position)')->from('models\Entities\Article\Category', 'c');
        if( $category_id ) {
			$qb->where('c.parent='. $category_id);	
		} else {
			$qb->where('c.parent is null');
		}
	
        $query = $qb->getQuery();
        $records = $query->getResult();
        return $records;
    }
    
    public function getAllCategories( $category_id = null ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('c')->from('models\Entities\Article\Category', 'c');
		if( $category_id ) {
			$qb->where('c.parent='. $category_id);	
		} else {
			$qb->where('c.parent is null');
		}
		
        $query = $qb->getQuery();
        $records = $query->getResult();
        return $records;
    }
}
    


 
 /* End of file ArticleCategoryRepository.php */
 /* Location: ./system/applications/_backend/models/ArticleCategoryRepository.php */