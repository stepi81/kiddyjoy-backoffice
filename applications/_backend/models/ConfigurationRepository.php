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
 
 class ConfigurationRepository extends EntityRepository {
     
    private $relations = array(
       'id'            => 'c.id',
       'name'          => 'c.name',
       'price'         => 'c.price'
    ); 
    
    private $component_relations = array(
        'id'           => 'p.id',
        'master_id'    => 'm.master_id',
        'status'       => 'p.status',
        'brand'        => 'b.name',
        'description'  => 'p.description',
        'archive'      => 'm.archive',
        'name'         => 'p.name'
        ); 
    
    public function getOrderConfigurations( $criteria, $order_id ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('c')
            ->from('models\Entities\Order\Configuration', 'c')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->where ('c.order = :order_id') 
            ->setParameters (array('order_id' => $order_id));
            
            if( $criteria->search_keyword != '' ) {
                $qb->where($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
         
        if( $data['record_count'] = $data['records']->count() ) {
            
              foreach( $data['records'] as $configuration ) {
                
                $data['record_items'][] = array(
                    $configuration->getID(),
                    $configuration->getID(),
                    '<a style="color:black;" href="'.site_url('orders/configuration_details/'.$configuration->getID()).'">Detalji</a>',
                    $configuration->getPrice(),
                    $configuration->getQuantity(),
                ); 
             }
        }
        return $data;
    }

    public function getComponents( $criteria, $product_ids, $conf_data ) {
            
       $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
           ->from('models\Entities\Product', 'p')
           ->orderBy($this->component_relations[$criteria->sortname], $criteria->sortorder)
           ->leftJoin('p.brand', 'b')
           ->leftJoin('p.master', 'm')
           ->leftJoin('p.subcategory', 's')
           ->leftJoin('s.parent', 'a')
           ->where($qb->expr()->in('d.product', $product_ids));            
            
           if( $criteria->search_keyword != '' ) {
               $qb->where($qb->expr()->like($this->component_relations[$criteria->search_field], ':keyword'))
                   ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
           }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
         
        if( $data['record_count'] = $data['records']->count() ) {
            
              foreach( $data['records'] as $component) {
                  
                $component_number = count($conf_data);
                for ($i = 0; $i < $component_number; $i++) {
                    if( $component->getID()== $conf_data[$i]['product_id'] ){
                        $quantity = $conf_data[$i]['quantity'];
                    }
                }
                
                $data['record_items'][] = array(
                    $component->getID(),
                    $component->getData()->getSubcategory()->getParent() == NULL ? $component->getData()->getSubcategory()->getName() :  $component->getData()->getSubcategory()->getParent()->getName() . '/' . $component->getData()->getSubcategory()->getName(),
                    '<a href="'. site_url('products/details/'.$component->getID()) .'">'.$component->getName().'</a>',
                    $component->getPrice(),
                    $quantity
                ); 
             }
        }
        
        return $data;
    }
            
        /*    $data['record_count'] = count($conf_data);
            $data['record_items'] = array();
            
            $query = Doctrine_Query::create()
                    ->select('p.*, c.*')
                    ->from('Product p, p.Product_Category c')
                    ->whereIn( 'p.id', $product_ids )
                    ->orderBy(''.$this->components_relations[$criteria->sortname].' '.$criteria->sortorder.'');
            
            $data['records'] = $query->execute( array(), Doctrine::HYDRATE_ARRAY );
            $query->free();
    
            foreach( $data['records'] as $component ) {
                
                $component_number = count($conf_data);
                for ($i = 0; $i < $component_number; $i++) {
                    if( $component['id'] == $conf_data[$i]['product_id'] ){
                        $quantity = $conf_data[$i]['quantity'];
                    }
                }
                
                $data['record_items'][] = array(
                    $component['id'],
                    //$component['id'],
                    $component['Product_Category']['name'],
                    '<a href="'. site_url('products/'.get_product_controller($component['category_id']).'/details/'.$component['id']) .'">'.$component['name'].'</a>',
                    $component['price'],
                    $quantity
                );
            }
            
            return $data;
        }*/
 /*   
    public function deleteBundles( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Product\Bundle', 'b')
            ->where($qb->expr()->in('b.id', $id_list));
        
        $query = $qb->getQuery();
        $bundles = $query->getResult();
        
        foreach( $bundles as $bundle ) {
            $this->_em->remove($bundle);
        }
        
        $this->_em->flush();
    }
    
    public function deleteBundleProducts( $id_list, $id ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Product\Bundle', 'b')
            ->where ('b.id = :bundle') 
            ->setParameters (array('bundle' => $id));
            
        $query = $qb->getQuery();
        $bundle = $query->getSingleResult();
        
        foreach( $id_list as $product_id ) {
            
            $bundle->getProducts()->removeElement( $this->_em->getReference('models\Entities\Product', $product_id) );
            $this->_em->flush();
        }
    }
    
    public function getBundleProducts( $criteria, $bundle_id ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Product\Bundle', 'b')
            ->orderBy($this->product_relations[$criteria->sortname], $criteria->sortorder)
            ->where ('b.id = :bundle') 
            ->setParameters (array('bundle' => $bundle_id));
            
            if( $criteria->search_keyword != '' ) {
                 $qb->andWhere($qb->expr()->like($this->product_relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        
        if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $bundle ) {
                
                foreach( $bundle->getProducts() as $product  )
                $data['record_items'][] = array(
                    $product->getID(),
                    $product->getName(),
                    $product->getPrice(),
                );
            }
        }
        
        return $data;
    }
    
    public function getProductBundles( $criteria, $product_id ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('b')
            ->from('models\Entities\Product', 'b')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->where ('b.id = :product') 
            ->setParameters (array('product' => $product_id));
            
            if( $criteria->search_keyword != '' ) {
                 $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);
                
        
        if( $data['record_count'] = $data['records']->count() ) {
            
            foreach( $data['records'] as $product ) {
                
                foreach( $product->getBundles() as $bundle  )
                $data['record_items'][] = array(
                    $bundle->getID(),
                    $bundle->getName(),
                    $bundle->getPrice(),
                    '<a href="'.site_url('bundles/details/'.$bundle->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
                );
            }
        }
        
        return $data;
    }*/
 }
 
 /* End of file ConfigurationRepository.php */
 /* Location: ./system/applications/_backend/models/Order/ConfigurationRepository.php */