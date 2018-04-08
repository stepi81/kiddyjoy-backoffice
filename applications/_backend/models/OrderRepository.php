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
 
 class OrderRepository extends EntityRepository {
     
    private $relations  = array(
        'date'          => 'o.date',
        'order_id'      => 'o.id',
        'discount'      => 'o.discount',
        'total_price'   => 'o.total_price',
        'delivery_id'   => 'o.delivery_id', 
        'payment_id'    => 'o.payment_id',
        'transaction_id'=> 'o.transaction_id',
        'user_name'     => 'u.first_name'
    );

    private $details_relations = array(
        'product_id'    => 'o.product_id',
        'price'         => 'o.price',
        'quantity'      => 'o.quantity' 
    ); 

    private $bundle_relations = array(
        'id'           => 'p.id',
        'master_id'    => 'm.master_id',
        'status'       => 'p.status',
        'brand'        => 'b.name',
        'description'  => 'p.description',
        'archive'      => 'm.archive',
        'name'         => 'p.name'
    ); 
    
    public function getOrders( $criteria, $order_status ) {
        
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('o')
            ->from('models\Entities\Order', 'o')
            //->leftJoin('o.user', 'u', 'WITH', 'o.id = u.user_id')
            ->where ('o.status = :order_status')
            ->orderBy($this->relations[$criteria->sortname], $criteria->sortorder)
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit)
            ->setParameters (array('order_status' => $order_status));
            
            if( $criteria->search_keyword != '' ) {
                $qb->andWhere($qb->expr()->like($this->relations[$criteria->search_field], ':keyword'))
                    ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
            }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false); 
        
        if( $data['record_count'] = $data['records']->count() ) { 
            
            $deliveries = unserialize(DELIVERY);
            $payment_types = unserialize(PAYMENT_TYPE);
            $card_types = unserialize(CARD_TYPE);
            
            foreach( $data['records'] as $order ) {
                
                if( $order->getLocation() ) { $location = $order->getLocation()->getName(); } else { $location = ''; }
                
                if( $order->getDelivery() ) { 
                    if( $order->getDelivery() == 3 ) {
                        $delivery = $deliveries[$order->getDelivery()].' - '.$location;    
                    } else {
                        $delivery = $deliveries[$order->getDelivery()];    
                    } 
                } else { 
                    $delivery = ''; 
                }
                
                if( $order->getStatus() == 3 ){
                    $status = '<a href="javascript:void(0);" rel="'.site_url('orders/status_activation/'.$order->getID().'/1').'" onclick="confirmOrderActivation(this)"><img border="0" src="'.layout_url('flexigrid/order.png').'"></a>';
                    $actions = $status; 
                } else if ( $order->getStatus() == 2 ) {
                    $contacted = '<a href="'.site_url('orders/status_activation/'.$order->getID().'/1').'"><img border="0" src="'.layout_url('flexigrid/order.png').'"></a>'; 
                    $status = '<a href="'.site_url('orders/status_activation/'.$order->getID().'/3').'"><img border="0" src="'.layout_url('flexigrid/archive.png').'"></a>'; 
                    $actions = $contacted.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$status;
                } else {
                    $contacted = '<a href="'.site_url('orders/status_activation/'.$order->getID().'/2').'"><img border="0" src="'.layout_url('flexigrid/order_contact.png').'"></a>';
                    $status = '<a href="'.site_url('orders/status_activation/'.$order->getID().'/3').'"><img border="0" src="'.layout_url('flexigrid/archive.png').'"></a>';
                    $actions = $contacted.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$status;
                }
                
                switch( get_class($order) ) {
                
                    case 'models\Entities\Order\Regular':
                        $order_type = 'Obična';
                        switch( get_class( $order->getUser() ) ) {
                            case 'models\Entities\User\Customer\Personal':
                                $user = '<a href="'.site_url('orders/details/'.$order->getID()).'">'.$order->getUser()->getFirstName().' '.$order->getUser()->getLastName().'</a>';
                                break;
                            case 'models\Entities\User\Customer\Business':
                                $user = '<a href="'.site_url('orders/details/'.$order->getID()).'">'.$order->getUser()->getCompanyName().'</a>'; 
                                break; 
                        }
                        break;
                    case 'models\Entities\Order\Fast':
                        $user = '<a href="'.site_url('orders/details/'.$order->getID()).'">'.$order->getUser()->getFirstName().' '.$order->getUser()->getLastName().'</a>';
                        $order_type = 'Brza'; 
                        break;
                }

				if($order->getInvoicePDF()) {
					$invoice_pdf = '<a href="'.APP_URL.'protected/invoice/'.$order->getInvoicePDF().'" target="_blank"><img border="0" src="'.layout_url('flexigrid/icon-pdf.png').'"></a>';
				} else {
					$invoice_pdf = '<img border="0" src="'.layout_url('flexigrid/icon-pdf.png').'">';
				}

                $data['record_items'][] = array(
                    $order->getID(),
                    $order->getID(),
                    $order->getReferenceID(),
                    $order_type,
                    $user,
                    $payment_types[$order->getPaymentType()],
                    $order->getCardType() != '' ? $card_types[$order->getCardType()] : '',
                    $order->getAuthCode(),
                    $order->getPaymentID(),
                    $order->getTransactionID(),
                    $delivery,
                    $order->getDiscount() ? $order->getDiscount().'%' : '',
                    $order->getTotalPrice(),
                    $order->getFormatedDate(),
                    $invoice_pdf,
                    $actions,
                    '<a href="'.site_url('orders/details/'.$order->getID()).'"><img border="0" src="'.layout_url('flexigrid/details.png').'"></a>'
                );
            }
        }
        
        return $data;
    }
    
    public function getOrderByID( $order_id, $type ) {
    	
    	$qb = $this->_em->createQueryBuilder();
    	
    	switch( $type ) {
    		case 1:
    			$entity = 'models\Entities\OrderFinal';
    			break;
    		case 2:
    			$entity = 'models\Entities\OrderShopFinal';
    			break;
    	}
    	
    	$qb->select('o')
            ->from($entity, 'o')
            ->leftJoin('o.user', 'u')
            ->where($qb->expr()->eq('o.id', $order_id));

		try {
			return $qb->getQuery()->getSingleResult();
		}
		catch( \Doctrine\ORM\NoResultException $e ) {
			// TODO
		}
    }
    
    public function deleteOrder( $id_list ) {
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Order', 'p')
            ->where($qb->expr()->in('p.id', $id_list));
        
        $query = $qb->getQuery();
        $orders = $query->getResult();
        
        foreach( $orders as $info ) {
            $this->_em->remove($info);
        }
        
        $this->_em->flush();
    }
    
    public function getOrderRecords( $criteria, $order_id ) {
 
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('o')
            ->from('models\Entities\Order\Item', 'o') 
            ->where('o.order = :order_id')
            ->orderBy($this->details_relations[$criteria->sortname], $criteria->sortorder)
            ->setFirstResult($criteria->offset)
            ->setMaxResults($criteria->limit) 
            ->setParameter('order_id', $order_id);

         if( $criteria->search_keyword != '' ) {
            $qb->andWhere($qb->expr()->like($this->details_relations[$criteria->search_field], ':keyword'))
                ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
        }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);

        if( $data['record_count'] = $data['records']->count() ) {

            foreach( $data['records'] as $order_product ) {
                
                $data['record_items'][] = array(
                    $order_product->getID(),
                    $order_product->getProduct()->getID(),
                    '<a style="color:black;" href="'.site_url('products/details/'.$order_product->getProduct()->getID()).'">'.$order_product->getProduct()->getName().'</a>',
                    $order_product->getLocationID(),
                    $order_product->getPrice(),
                    $order_product->getQuantity()
                );
            }
        }
        
        
        return $data;
    }
    
    public function getPrev($order_id, $order_status){
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Order', 'p')
            ->where ('p.status = :order_status')
            ->andWhere('p.id < :order_id')
            ->orderBy('p.id', 'DESC') 
            ->setParameters (array('order_status' => $order_status, 'order_id' => $order_id))
            ->setMaxResults(1); 
        
        $query = $qb->getQuery();
        $prev = $query->getResult();

        return $prev;

    }
    
    public function getNext($order_id, $order_status){

        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Order', 'p')
            ->where ('p.status = :order_status')
            ->andWhere('p.id > :order_id') 
            ->setParameters (array('order_status' => $order_status, 'order_id' => $order_id))
            ->setMaxResults(1); 
        
        $query = $qb->getQuery();
        $next = $query->getResult();

        return $next;
    }
    
    public function getOrderBundleProducts( $criteria, $order_id ) {
 
        $order = $this->_em->getRepository('models\Entities\Order')->find($order_id);
        
        $product_list = array();
        foreach( $order->getItems() as $item ) { 
            if( is_array( $item->getBundle() ) ) {
                foreach( $item->getBundle() as $value ){
                    for ($i = 0; $i < $item->getQuantity(); $i++)    
                        $product_list[] = $value;   
                }    
            }
        }
        $product_duplicate = array_count_values($product_list);
       // print_r ($product_duplicate);
        $data['record_items'] = array();
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('p')
            ->from('models\Entities\Product', 'p') 
            ->leftJoin('p.master', 'm')
            ->leftJoin('m.brand', 'b') 
            ->leftJoin('p.subcategory', 's')
            ->leftJoin('s.parent', 'a')
            ->where($qb->expr()->in('p.id', $product_list ));   
        
        if( $criteria->search_keyword != '' ) {
               $qb->where($qb->expr()->like($this->bundle_relations[$criteria->search_field], ':keyword'))
                   ->setParameter('keyword', '%'.$criteria->search_keyword.'%');    
        }
        
        $data['records'] = new Paginator($qb->getQuery(), $fetchJoin = false);

        if( $data['record_count'] = $data['records']->count() ) {

            foreach( $data['records'] as $bundle_product ) {
                foreach ($product_duplicate as $key => $duplicate) {
                        if ( $key == $bundle_product->getID() ) $quantity = $duplicate;
                } 
                
                $data['record_items'][] = array(
                    $bundle_product->getID(),
                    $bundle_product->getID(),
                    '<a style="color:black;" href="'.site_url('products/details/'.$bundle_product->getID()).'">'.$bundle_product->getName().'</a>',
                    $bundle_product->getPrice(),
                    $quantity,
                );
            }
        }

        return $data;
    }
 }
 
 /* End of file OrderRepository.php */
 /* Location: ./system/applications/_backend/models/OrderRepository.php */