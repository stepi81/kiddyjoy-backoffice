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
 
 class ProductFilterRepository extends EntityRepository {
    
    private $relations = array(
        'id'           => 'f.id',
        'position'     => 'f.position',
        'name'         => 'f.name',
        'technology'   => 'f.technology' 
    ); 
     
    public function getSpecificationFilters($criteria, $specification_id){
       

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('f') 
           ->from('models\Entities\Product\Filter', 'f') 
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
           ->where ('f.specification = :specification_id') 
           ->setParameters (array('specification_id' => $specification_id));

            if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }

           $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);
            
            $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);
    
            $data['record_count'] = count($paginator);
            foreach ($paginator as $filter) {
            	
				$status = $filter->getStatus() ? 'check' : 'delete';
                
                $data['record_items'][] = array(
                    $filter->getID(),
                    $filter->getPosition(),
                    $filter->getID(),
                    $filter->getName(),
                    '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('product/filters/change_status/' . $filter->getID()) . '\');">Status</a>', 
                    '<a href="'.site_url('product/filters/details/'.$filter->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                    );               
            }
            return $data;   
    }

    public function deleteSpecificationFilters( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('f')
            ->from('models\Entities\Product\Filter', 'f')
            ->where($qb->expr()->in('f.id', $id_list));
        
        $query = $qb->getQuery();
        $filters = $query->getResult();
        
        foreach( $filters as $filter) {

        $this->_em->remove($filter);
        }
        $this->_em->flush();
    }
    
    public function getMaxFilterPosition ($specification_id){
        
        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(f.position)')
           ->from('models\Entities\Product\Filter', 'f')
           ->where('f.specification=' . $specification_id);

        $query = $qb->getQuery();
        $maxPos = $query->getResult();
        return $maxPos;
    }
    
    public function getFiltersBySpecification( $specification_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('f')
           ->from('models\Entities\Product\Filter', 'f')
           ->where('f.specification=' . $specification_id)
           ->add('orderBy', 'f.position ASC');
        
        $query = $qb->getQuery();
        $filter = $query->getResult();
        return $filter;
    }
   
   public function deleteTextFilter( $product_id ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('t')
            ->from('models\Entities\Product\TextFilter', 't')
            ->where('t.product = :id')
            ->setParameters(array('id' => $product_id));
        
        $query = $qb->getQuery();
        $filters = $query->getResult();
        
        foreach( $filters as $filter ) {

        $this->_em->remove($filter);
        }
        $this->_em->flush();
    }   
}
 /* End of file ProductFilterRepository.php */
 /* Location: ./system/applications/_backend/models/ProductFilterRepository.php */