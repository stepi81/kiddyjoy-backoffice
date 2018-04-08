<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

namespace models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CommentRepository extends EntityRepository {
    
private $data;
    
    private $relations = array(
        'user_name'    => 'c.user_name',
        'message'      => 'c.message',
        'date'         => 'c.date',
        'status'       => 'c.status',
        
        //'subcategory'  => 's.id',
        //'product'      => 'p.name'
    ); 
    
    public function getComment( $criteria ) {

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('c') 
           ->from('models\Entities\Comment', 'c') 
           //->leftJoin('c.record', 'r')
           //->leftJoin('r.product', 'p')
		   //->leftJoin('p.subcategory', 's')
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
		   ->setFirstResult($criteria->offset)
           ->setMaxResults($criteria->limit);

        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
               ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {

            foreach ($data['records'] as $comment) {

                $status = $comment->getStatus() ? 'check' : 'delete';
                $user_name = $comment->getUserName();                        // TODO za ovo moze da se koristi jedna metoda

                switch( get_class($comment)) {
                
                    case 'models\Entities\Comment\ProductComment':

                        $data['record_items'][] = array(
                            $comment->getID(),
                            $user_name,
                            'Proizvod',
                            //$comment->getProduct()->getSubcategory()->getName(),
                            '<a href="'.site_url('products/details').'/'.$comment->getProduct()->getID().'" target="_blank">'.$comment->getProduct()->getName().'</a>',
                            $comment->getMessage(),
                            $comment->getDate(), 
                            '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('comments/change_status/' . $comment->getID()) . '\');">Status</a>', 
                            '<a class="table-icon details" href="' . site_url('comments/details/' . $comment->getID()) . '">Detalji</a>',
                            '<a class="table-icon comments" href="' . site_url('comments/listing_responses/' . $comment->getID()) . '">Odgovori</a>',
                            '<a class="table-icon discussion" href="' . site_url('comments/listing_by_record/1/' . $comment->getProduct()->getID()) . '">Diskusije</a>' );
                     break;
					 
					 case 'models\Entities\Comment\NewsComment':

                        $data['record_items'][] = array(
                            $comment->getID(),
                            $user_name,
                            'Vest/Akcija',
                            '<a href="'.site_url('news/details').'/'.$comment->getNews()->getID().'" target="_blank">'.$comment->getNews()->getTitle().'</a>',
                            $comment->getMessage(),
                            $comment->getDate(), 
                            '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('comments/change_status/' . $comment->getID()) . '\');">Status</a>', 
                            '<a class="table-icon details" href="' . site_url('comments/details/' . $comment->getID()) . '">Detalji</a>',
                            '<a class="table-icon comments" href="' . site_url('comments/listing_responses/' . $comment->getID()) . '">Odgovori</a>',
                            '<a class="table-icon discussion" href="' . site_url('comments/listing_by_record/2/' . $comment->getNews()->getID()) . '">Diskusije</a>' );
                     break;
					 
					 case 'models\Entities\Comment\ArticleComment':

                        $data['record_items'][] = array(
                            $comment->getID(),
                            $user_name,
                            'Blog',
                            '<a href="'.site_url('articles/details').'/'.$comment->getArticle()->getID().'" target="_blank">'.$comment->getArticle()->getTitle().'</a>',
                            $comment->getMessage(),
                            $comment->getDate(), 
                            '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('comments/change_status/' . $comment->getID()) . '\');">Status</a>', 
                            '<a class="table-icon details" href="' . site_url('comments/details/' . $comment->getID()) . '">Detalji</a>',
                            '<a class="table-icon comments" href="' . site_url('comments/listing_responses/' . $comment->getID()) . '">Odgovori</a>',
                            '<a class="table-icon discussion" href="' . site_url('comments/listing_by_record/3/' . $comment->getArticle()->getID()) . '">Diskusije</a>' );
                     break;
                 } 
            }
        }
        return $data;
    }
    
    
    public function deleteComments( $id_list ){
        
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c') 
           ->from('models\Entities\Comment', 'c') 
           ->where($qb->expr()->in('c.id', $id_list));
    
        $query = $qb->getQuery();
        $comments = $query->getResult();

        foreach ($comments as $comment) {
        	
			switch( get_class($comment)) {
        		
	        	case 'models\Entities\Comment\ProductComment':
	        		if( $comment->getStatus() ) { $comment->getProduct()->setStatisticComments( $comment->getProduct()->getStatisticComments() - 1 ); } 
	        	break;
				case 'models\Entities\Comment\NewsComment':
	        		if( $comment->getStatus() ) { $comment->getNews()->setStatisticComments( $comment->getNews()->getStatisticComments() - 1 ); } 
	        	break;
				case 'models\Entities\Comment\ArticleComment':
	        		if( $comment->getStatus() ) { $comment->getArticle()->setStatisticComments( $comment->getArticle()->getStatisticComments() - 1 ); } 
	        	break;
					
			}
			$this->_em->persist($comment);  
            $this->_em->remove($comment);
        }
        $this->_em->flush();
    }     

    public function getCommentByRecord( $criteria, $type, $record_id ){
            
        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();
		
		switch($type) {
        		
        	case 1:
        		$qb->select('c') 
		           ->from('models\Entities\Comment\ProductComment', 'c') 
		           ->orderBy('c.' . $criteria->sortname, $criteria->sortorder)
		           ->where ('c.product = :product_id') 
		           ->setParameters (array('product_id' => $record_id))
					->setFirstResult($criteria->offset)
		            ->setMaxResults($criteria->limit);
        	break;
			case 2:
        		$qb->select('c') 
		           ->from('models\Entities\Comment\NewsComment', 'c') 
		           ->orderBy('c.' . $criteria->sortname, $criteria->sortorder)
		           ->where ('c.news = :news_id') 
		           ->setParameters (array('news_id' => $record_id))
					->setFirstResult($criteria->offset)
		            ->setMaxResults($criteria->limit);
        	break;
			case 3:
        		$qb->select('c') 
		           ->from('models\Entities\Comment\ArticleComment', 'c') 
		           ->orderBy('c.' . $criteria->sortname, $criteria->sortorder)
		           ->where ('c.article = :article_id') 
		           ->setParameters (array('article_id' => $record_id))
					->setFirstResult($criteria->offset)
		            ->setMaxResults($criteria->limit);
        	break;
				
		}

		$data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
        
        if( $data['record_count'] = $data['records']->count() ) {

        //$query = $qb->getQuery();

        //if ($data['record_count'] = Paginate::getTotalQueryResults($query)) {

            //$paginateQuery = Paginate::getPaginateQuery($query, $criteria->offset, $criteria->limit);
            //$data['records'] = $paginateQuery->getResult();

            foreach ($data['records'] as $comment) {

                $status = $comment->getStatus() ? 'check' : 'delete';
                $user_name = $comment->getUserName();

                switch( get_class($comment->getUser()) ) {
                
                    case 'models\Entities\User\Customer\Personal':
                        if ($user_name == NULL){ $user_name = $comment->getUser()->getNickname();}
                        if ($user_name == NULL){ $user_name = $comment->getUser()->getFirstName();}
                        break;
                        
                    case 'models\Entities\User\Customer\Business': 
                        if ($user_name == NULL){ $user_name = $comment->getUser()->getContactPerson();}
                        break;}    
                
                        $data['record_items'][] = array(
                            $comment->getID(),
                            $user_name,
                            //$comment->getSubcategory()->getSubcategory()->getName(),
                            //$comment->getProduct()->getName(),
                            $comment->getMessage(),
                            $comment->getDate(), 
                            '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('comments/change_status/' . $comment->getID()) . '\');">Status</a>', 
                            '<a class="table-icon details" href="' . site_url('comments/details/' . $comment->getID()) . '">Detalji</a>',
                            '<a class="table-icon comments" href="' . site_url('comments/listing_responses/' . $comment->getID()) . '">Odgovori</a>', 
                        );
            }
        }
        return $data;
    }

    public function getCommentsById($comment_id) {

        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('c') 
       ->from('models\Entities\Comment', 'c') 
       ->where ('c.id = :comment_id') 
       ->setParameters (array('comment_id' => $comment_id));
           
            $query = $qb->getQuery();
            $result = $query->getSingleResult();
            return $result;
    }
    
    public function getLatestComments() {
    
        $qb = $this->_em->createQueryBuilder();

        $qb->select('c') 
           ->from('models\Entities\Comment', 'c') 
           ->orderBy('c.date', 'DESC')
           ->setMaxResults(5);
           
           $result = $qb->getQuery()->getResult();
           return $result;    
    }

}

/* End of file CommentRepository.php */
/* Location: ./system/applications/_backend/models/CommentRepository.php */
