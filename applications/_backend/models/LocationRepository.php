<?php

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */

 namespace models;
 
 use Doctrine\ORM\EntityRepository;
 use Doctrine\ORM\Query;
 use Doctrine\ORM\Tools\Pagination\Paginator;
 
 use models\Entities\LocationData;
 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 class LocationRepository extends EntityRepository {
    
    private $relations = array(
        'id'            => 'l.location_id',
        'name'          => 'l.name',
    ); 
    
    public function getLocations( $criteria ) {
        
        $data['record_items'] = array();
		
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('l')
            ->from('models\Entities\LocationData', 'l')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);

        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        
        if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $location ) {
				
                $data['record_items'][] = array(
                    $location->getID(),
                    '<img border="0" src="'.$location->getIconURL().'">',
                    $location->getAlias(),
                    $location->getLatitude(),
                    $location->getLongitude(),
                    //'<a href="'.site_url( 'settings/locations/gallery/'.$location->getID() ).'"><img border="0" src="'.layout_url('flexigrid/gallery.png').'"></a>',  
                    '<a href="'.site_url( 'settings/locations/details/'.$location->getID() ).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
                );
            }
        }
        
        return $data;
    }
    
    public function getImagesByLocation($location_id){
       
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('i')
            ->from('models\Entities\Images\LocationImage', 'i')
            ->orderBy('i.position', 'ASC')
            ->where($qb->expr()->eq('i.location', ':location')) 
            ->setParameter('location', $location_id);

        $query = $qb->getQuery();
        return $query->getResult();
    }
    
    public function getMaxImagePosition($location_id){
        
        $qb = $this->_em->createQueryBuilder();

        $qb->select('MAX(i.position)')
            ->from('models\Entities\Images\LocationImage', 'i')
            ->where($qb->expr()->eq('i.location', ':location')) 
            ->setParameter('location', $location_id);

        $query = $qb->getQuery();
        return $query->getSingleResult(); 
    }
    
    public function setImagesPosition($id){
        
        for( $i=0; $i<sizeof($id); $i++){
            
            $qb = $this->_em->createQueryBuilder();
            
            $image = $this->_em->getRepository('models\Entities\Images\LocationImage')->find($id[$i]);
            $image->setPosition( $i + 1 );
            $this->_em->flush();

        }
    }
    
    public function getLocationByID( $id ) {

        $qb = $this->_em->createQueryBuilder();

        $qb->select('l, i')
            ->from('models\Entities\LocationData', 'l')
            ->where('l.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getSingleResult();

    }
    
    public function getImagesAfterDelete($location_id, $position){
       
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('i')
            ->from('models\Entities\Images\LocationImage', 'i')
            ->orderBy('i.position', 'ASC')
            ->where('i.location = :location')
            ->andWhere('i.position > :position')
            ->setParameters(array('location' => $location_id, 'position' => $position));

        $query = $qb->getQuery();
        return $query->getResult();
    }
 }
 
 /* End of file LocationRepository.php */
 /* Location: ./system/applications/_backend/models/LocationRepository.php */
