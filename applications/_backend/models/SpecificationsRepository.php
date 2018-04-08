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
 
 class SpecificationsRepository extends EntityRepository {
    
    private $relations = array(
        'id'           => 's.id',
        'position'     => 's.position',
        'name'         => 's.name',
    ); 
     
    public function getSubcategorySpecifications($criteria, $subcategory_id){
       

        $data['record_items'] = array();

        $qb = $this->_em->createQueryBuilder();

        $qb->select('s') 
           ->from('models\Entities\Product\Specification', 's') 
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
           ->where ('s.subcategory = :subcategory_id') 
           ->setParameters (array('subcategory_id' => $subcategory_id));

            if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }

            $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);
            
            $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);

            $data['record_count'] = count($paginator);
            foreach ($paginator as $specification) {
                
                $status = $specification->getStatus() ? 'check' : 'delete';   
                    
                $data['record_items'][] = array(
                    $specification->getID(),
                    $specification->getPosition(),
                    $specification->getName(),
                    '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('product/specifications/change_status/' . $specification->getID()) . '\');">Status</a>', 
                    '<a href="'.site_url('product/filters/listing/'.$specification->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-specification.png').'"></a>',
                    '<a href="'.site_url('product/specifications/details/'.$specification->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>',
                    );               
            }
            return $data;   
    }

    public function deleteSubcategorySpecifications( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('s')
            ->from('models\Entities\Product\Specification', 's')
            ->where($qb->expr()->in('s.id', $id_list));
        
        $query = $qb->getQuery();
        $specifications = $query->getResult();
        
        foreach( $specifications as $specification ) {

        $this->_em->remove($specification);
        }
        $this->_em->flush();
    }
    
    public function getMaxSpecificationPosition ($subcategory_id){
        
        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(p.position)')
           ->from('models\Entities\Product\Specification', 'p')
           ->where('p.subcategory=' . $subcategory_id);

        $query = $qb->getQuery();
        $maxPos = $query->getResult();
        return $maxPos;
    }
    
    public function getSpecificationsBySubcategory( $subcategory_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('s')
           ->from('models\Entities\Product\Specification', 's')
           ->where('s.subcategory=' . $subcategory_id)
           ->add('orderBy', 's.position ASC');
        
        $query = $qb->getQuery();
        $spec = $query->getResult();
        return $spec;
    }
        
}
    
 
 /* End of file SpecificationsRepository.php */
 /* Location: ./system/applications/_backend/models/SpecificationsRepository.php */