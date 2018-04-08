<?php

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class WarrantyRepository extends EntityRepository {
    
    private $relations = array(
        'id'           => 'w.id',
        'duration'     => 'w.name',
        'position'     => 'w.position',
    );  
    
    public function getWarranties( $criteria ) {
         
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('w')
           ->from('models\Entities\Product\Warranty', 'w')
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder);

	    if( $criteria->search_keyword != '' ) {
	         $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
	            ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
	    }

        $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);
        
        $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);

          $data['record_count'] = count($paginator);
            
            foreach( $paginator as $warranty) {
                
                $data['record_items'][] = array(
                    $warranty->getID(),
                    $warranty->getName(),
                    $warranty->getPosition(),
                    '<a href="'.site_url('settings/warranties/details/'.$warranty->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
                );
            }
        return $data;
    }
    
    public function getWarrantiesList() {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('w')
           ->from('models\Entities\Product\Warranty', 'w')
           ->orderBy('w.position', 'ASC');
           
        $query = $qb->getQuery();
        $warranties = $query->getResult();
        
        return $warranties;    
    }
    
    public function deleteWarranties( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('w')
            ->from('models\Entities\Product\Warranty', 'w')
            ->where($qb->expr()->in('w.id', $id_list));
        
        $query = $qb->getQuery();
        $warranties = $query->getResult();
        
        foreach( $warranties  as $warranty ) {
             $products = $this->_em->getRepository('models\Entities\Product')->findBy(array('warranty' => $warranty->getID()));
             foreach ($products as $product){
                 $product->setWarranty(NULL);
             }
             $this->_em->remove($warranty);
        }
        
        $this->_em->flush();
    }
	
	public function getMaxWarrantyPosition(){

        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(w.position)')
           ->from('models\Entities\Product\Warranty', 'w');
           //->where ('a.id = :id');
           //->setParameters (array('id' => $question_id));

        $query = $qb->getQuery();
        $answer = $query->getResult();
        return $answer;
    }
 }
 
 /* End of file WarrantyRepository.php */
 /* Location: ./system/applications/_backend/models/WarrantyRepository.php */