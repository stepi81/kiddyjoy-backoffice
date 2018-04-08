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
 
 class BenchmarkRepository extends EntityRepository {
      
      
    private $relations = array(
        'benchmark_date'        => 'b.benchmark_date',
        'title'          => 'b.title',
        'status'                => 'b.status',   
        );
         
    public function getBenchmarks( $criteria ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Benchmark', 'b')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit);
        
        if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                ->setParameter( 'keyword', '%'.$criteria->search_keyword.'%' );    
        }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        
        if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $benchmark ) {
                
                $status = $benchmark->getStatus() ? 'check' : 'delete';
                
                $data['record_items'][] = array(
                    $benchmark->getID(),
                    $benchmark->getFormatedDate(),
                    $benchmark->getTitle(),
                    $benchmark->getCategory()->getName(),
                    '<a class="table-icon '.$status.'" href="javascript:void(0);" onclick="changeItemStatus(this, \''.site_url('benchmarks/change_status/'.$benchmark->getID()).'\');">Status</a>',
                    '<a class="table-icon details" href="'.site_url('benchmarks/details/'.$benchmark->getID()).'">Detalji</a>',
                );
            }
        }
        
        return $data;
    }
    
    public function deleteBenchmark( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Benchmark', 'b')
            ->where($qb->expr()->in('b.id', $id_list));
        
        $query = $qb->getQuery();
        $benchmarks = $query->getResult();
        
        foreach( $benchmarks as $banchmark) {
            if( $banchmark->getThumb() ) {
                if( unlink(SERVER_IMAGE_PATH.'benchmark/'.$banchmark->getThumb()) ) {
                     foreach( $banchmark->getImages() as $image ) {    
                        unlink(SERVER_IMAGE_PATH.'benchmark/pages/'.$image->getName());
                        //$this->_em->remove($image);
                    }
                    $this->_em->remove($banchmark);
                }
            } else {
                $this->_em->remove($banchmark); 
            }
        }
        
        $this->_em->flush();
    }
    
    public function getBenchmarkImages( $benchmark_id ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('i')
            ->from('models\Entities\Benchmark\Image', 'i')
            ->where($qb->expr()->eq('i.benchmark', $benchmark_id));
        
        $query = $qb->getQuery();
        
        return $query->getResult();
    }
 }
 
 /* End of file NewsRepository.php */
 /* Location: ./system/applications/_backend/models/NewsRepository.php */
