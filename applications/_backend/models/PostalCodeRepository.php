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
 
 class PostalCodeRepository extends EntityRepository {
    
    private $relations = array(
        'postal_code'   => 'p.postal_code',
        'city'          => 'p.city',
        'longitude'     => 'p.longitude',
        'latitude'      => 'p.latitude',
    );  
    
    public function getPostalCodes( $criteria ) {
         
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
           ->from('models\Entities\PostalCode', 'p')
           ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder);

        if( $criteria->search_keyword != '' ) {
             $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
        }

        $qb->setFirstResult($criteria->offset)->setMaxResults($criteria->limit);
        
        $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = TRUE);

        $data['record_count'] = count($paginator);
            
        foreach( $paginator as $postal_code) {
            
            $data['record_items'][] = array(
                $postal_code->getPostalCode(),
                $postal_code->getPostalCode(),
                $postal_code->getCity(),
                $postal_code->getLongitude(),
                $postal_code->getLatitude(), 
                '<a href="'.site_url('settings/postal_codes/details/'.$postal_code->getPostalCode()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
            );
        }
        return $data;
    }

 }
 
 /* End of file PostalCodeRepository.php */
 /* Location: ./system/applications/_backend/models/PostalCodeRepository.php */