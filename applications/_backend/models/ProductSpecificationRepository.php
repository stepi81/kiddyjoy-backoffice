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
 
 class ProductSpecificationRepository extends OrdinalEntityRepository {
    
    private $relations = array(
        'id'           => 's.id',
        'position'     => 's.position',
        'position_info'=> 's.position_info',
        'position_klirit'=> 's.position_klirit',
        'tech_icon_visibility' => 's.techicon_visibility',
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
				$visibility = $specification->getVisibility() ? 'check' : 'delete';
				$filter_visibility 		= $specification->getFilterVisibility() ? 'check' : 'delete';
				$bundle_visibility 		= $specification->getBundleVisibility() ? 'check' : 'delete';
				$tech_icon_visibility 	= $specification->getTechIconVisibility() ? 'check' : 'delete'; 
				
                if ($specification->getTypeID()==1){
                     $details = '<a href="'.site_url('product/filters/listing/'.$specification->getID()).'"><img border="0" src="'.layout_url('flexigrid/icon-specification.png').'"></a>';   
                } elseif($specification->getTypeID()==2) {
                     $details = '<img border="0" src="'.layout_url('flexigrid/archive.png').'"></a>';
                }     

                $data['record_items'][] = array(
                     $specification->getID(),
                     $specification->getPosition(),
                     $specification->getPositionInfo(),  
                     $specification->getPositionKlirit(),
                     $specification->getName(),
                     '<a class="table-icon ' . $status . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('product/specifications/change_status/' . $specification->getID()) . '\');">Status</a>',
                     '<a class="table-icon ' . $visibility . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('product/specifications/change_visibility/' . $specification->getID()) . '\');">Visibility</a>',
                     '<a class="table-icon ' . $filter_visibility . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('product/specifications/change_filter_visibility/' . $specification->getID()) . '\');">Filter Visibility</a>', 
                     '<a class="table-icon ' . $bundle_visibility . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('product/specifications/change_bundle_visibility/' . $specification->getID()) . '\');">Filter Visibility</a>',
                     //$specification->getPositionKlirit() > 0 ? 
                 	 '<a class="table-icon ' . $tech_icon_visibility . '" href="javascript:void(0);" onclick="changeItemStatus(this, \'' . site_url('product/specifications/change_techicon_visibility/' . $specification->getID()) . '\');">Tech Icon Visibility</a>' 
					 //: 
                     //'<a class="table-icon" href="javascript:void(0);">Tech Icon Visibility</a>'
                     ,
                     $details,
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
   
    public function getAllSpecificationsBySubcategory( $subcategory_id ){
                    
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('s')
           ->from('models\Entities\Product\Specification', 's')
           ->leftJoin('s.subcategory', 'u')
           ->leftJoin('u.children', 'c')
           ->orWhere ('c.id = :group_id')
           //->add('orderBy', 'c.position DESC')
           ->add('orderBy', 's.position ASC')
           ->setParameters (array('group_id' => $subcategory_id));
        
        $query = $qb->getQuery();
        $spec = $query->getResult();
        
        $qb2 = $this->_em->createQueryBuilder();
        $qb2->select('s')
           ->from('models\Entities\Product\Specification', 's')
           ->where('s.subcategory=' . $subcategory_id)
           ->add('orderBy', 's.position ASC');
        
        $query2 = $qb2->getQuery();
        $spec_children = $query2->getResult();
        
        foreach ($spec_children as $x){
            array_push ($spec, $x );
        }
        return $spec;
        
    }
        
}
    
 /* End of file ProductSpecificationRepository.php */
 /* Location: ./system/applications/_backend/models/ProductSpecificationRepository.php */