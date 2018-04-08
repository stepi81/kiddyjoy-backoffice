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
 
 class ArticleHighlightRepository extends EntityRepository {
	
    private $relations = array(
        'id' 		=> 'h.id',
        'type' 		=> 'h.type', 
        'article'	=> 'a.title',
        'position'	=> 'h.position'
    );  
    
	public function getHighlights( $criteria, $category_id ) {
		
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
		
		$qb->select('h, a')
			->from('models\Entities\Article\Highlight', 'h')
			->leftJoin('h.article', 'a')
			->where($qb->expr()->in('a.category', $category_list))
			->orderBy('h.type, h.position', 'ASC');
			//->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
			//->setFirstResult($criteria->offset)
            //->setMaxResults($criteria->limit);
		
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
			
		if( $data['record_count'] = $data['records']->count() ) {

			foreach( $data['records'] as $record ) {
				
				$types = unserialize(ARTICLE_HIGHLIGHT_TYPE);
				
				$data['record_items'][] = array(
					$record->getID(),
					$record->getPosition(),
					$types[$record->getType()],
					$record->getArticle()->getTitle(),
				);
			}
		}
		
		return $data;
	}

	public function getMaxPosition( $category_id, $type ){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(h.position)')
        	->from('models\Entities\Article\Highlight', 'h')
			->leftJoin('h.article', 'a')
        	->where('a.category=' . $category_id)
			->andWhere('h.type='.$type);
	
        $query = $qb->getQuery();
        $records = $query->getResult();
        return $records;
    }
    
    public function getHighlightsByCategory( $category_id, $type ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('h')
			->from('models\Entities\Article\Highlight', 'h')
			->leftJoin('h.article', 'a')
        	->where('a.category=' . $category_id)
			->andWhere('h.type='.$type);
		
        $query = $qb->getQuery();
        $records = $query->getResult();
        return $records;
    }

	public function deleteHighlights( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('h')
            ->from('models\Entities\Article\Highlight', 'h')
            ->where($qb->expr()->in('h.id', $id_list));
        
        $query = $qb->getQuery();
        $records = $query->getResult();
        
        foreach( $records as $record ) {
            $this->_em->remove($record);
        }
        
        $this->_em->flush();
    }

 }
 
 /* End of file ArticleHighlightRepository.php */
 /* Location: ./system/applications/_backend/models/ArticleHighlightRepository.php */