<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Marko Stepanovic [ Codeion ]
 */
 use models\Entities\Order;
 
 class Orders extends MY_Controller {
     
     public $gridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 40,
        'rpOptions'             => '[40,100,200,300,400]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} porudžbina.',
        'pagetext'              => 'Stranica', 
        'outof'                 => 'od', 
        'findtext'              => 'Pronađi', 
        'procmsg'               => 'Obrada u toku, molimo sačekajte...', 
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
     );
     
     public $gridDetailsParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 700,
        'height'                => 250,
        'rp'                    => 40,
        'rpOptions'             => '[40,100,200,300,400]',
        'pagestat'              => 'Displaying: {from} to {to} of {total} items.',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
     );
     
     function __construct() {
         
         parent::__construct();

         $this->load->helper(array('form', 'url'));

         $this->load->helper('flexigrid');
         $this->load->helper('upload');
         $this->load->helper('tinymce');

         $this->load->library('Flexigrid');

         $this->resources['css'] = array();
         $this->resources['js'] = array();
     }
     
     public function listing($order_status) {
         
         $this->session->set_userdata('order_status',$order_status);
         $this->session->set_userdata('return_uri_order',uri_string());

         $this->resources['css'][] = 'flexigrid';
         $this->resources['js'][] = 'flexigrid';
        
         $this->gridParams['title'] = 'Pregled';
         
         $colModel['order_id']  = array( 'ID', 40, TRUE, 'center', 1 );
         $colModel['reference_id']  = array( 'Reference ID', 100, FALSE, 'center', 0 );
         $colModel['order_type']  = array( 'Tip', 60, FALSE, 'center', 0 );
         $colModel['user_name']  = array( 'Kupac', 130, TRUE, 'center', 1 );
         $colModel['payment_type']  = array( 'Način plaćanja', 90, TRUE, 'center', 1 );
         $colModel['card_type']  = array( 'Tip kartice', 80, FALSE, 'center', 0 );
         $colModel['auth_code']  = array( 'Identifikacioni kod', 90, FALSE, 'center', 0 );
         $colModel['payment_id']  = array( 'ID plaćanja', 130, TRUE, 'center', 1 );
         $colModel['transaction_id']  = array( 'ID transakcije', 130, TRUE, 'center', 1 );
         $colModel['delivery_id']  = array( 'Isporuka', 200, TRUE, 'center', 1 );
         $colModel['discount']  = array( 'Popust', 80, TRUE, 'center', 1 ); 
         $colModel['total_price']  = array( 'Ukupna cena', 100, TRUE, 'center', 1 );
         $colModel['date']  = array( 'Datum narudžbe', 90, TRUE, 'center', 1 );
		 $colModel['invoice_pdf']   = array( 'Faktura', 50, FALSE, 'center', 0 );
         if( $order_status == 1 ) {
            $colModel['status']  = array( 'Kontaktiran / Arhiva', 100, FALSE, 'center', 1 );    
         } else if ( $order_status == 2 ) {
            $colModel['status']  = array( 'Aktivna / Arhiva', 80, FALSE, 'center', 1 );    
         } else {
            $colModel['status']  = array( 'Aktivna', 80, FALSE, 'center', 1 );    
         }
         
         $colModel['actions']   = array( 'Detalji', 50, FALSE, 'center', 0 );

         switch( $order_status ){
             case 1:
                $data['grid_title'] = "Aktivne Narudžbe";
                $buttons[] = array( 'Obriši', 'delete', 'grid_commands', site_url("orders/delete"));
                $buttons[] = array('separator');
                $buttons[] = array( 'Selektuj sve', 'select_all', 'grid_commands', '/select');
				$buttons[] = array('separator');
                $buttons[] = array( 'Deselektuj sve', 'deselect_all', 'grid_commands', '/desel');
                $buttons[] = array('separator');
             break;
             case 2:
                $data['grid_title'] = "Kontaktirane Narudžbe";
                $buttons[] = array( 'Obriši', 'delete', 'grid_commands', site_url("orders/delete"));
                $buttons[] = array('separator');
                $buttons[] = array( 'Selektuj sve', 'select_all', 'grid_commands', '/select');
				$buttons[] = array('separator');
                $buttons[] = array( 'Deselektuj sve', 'deselect_all', 'grid_commands', '/desel');
                $buttons[] = array('separator');
             break;
             case 3:
                $data['grid_title'] = "Arhivirane Narudžbe";
                $buttons = NULL;
             break;
             default:
                $data['grid_title'] = "Narudžbe";
                $buttons = NULL;
             break;
         }
         if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
         $data['order_status'] = $order_status; 
         $data['grid'] = build_grid_js('grid', site_url("orders/grid/" . $order_status), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

         $this -> _render_view("master/grid_view", $data);
     }
     
     public function grid($order_status) {
         
         $valid_fields = array( 'order_id', 'user_name', 'transaction_id', 'date', 'delivery_id', 'discount', 'total_price' );
         if( $order_status == 1 ) {
            $this->flexigrid->validate_post($this->gridParams['id'], 'date', 'asc', $valid_fields);    
         } else {
            $this->flexigrid->validate_post($this->gridParams['id'], 'date', 'desc', $valid_fields);    
         }
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Order') -> getOrders($criteria, $order_status);

         $this->session->unset_userdata('edit_visited');    
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     function status_activation( $order_id, $order_status ){

         $order = $this->em->getRepository('models\Entities\Order')->find($order_id);
		 $old_status = $order->getStatus();
         if ( $order_status == 1 ) {
            $order->setStatus(1); 
            $this->em->persist($order);
            $this->em->flush();   
         } else if ( $order_status == 2 ) {
            $order->setStatus(2);
            try {
                $communicator = new models\Entities\Communicator\TransferOrder();
                $communicator->setRecord($order);
                
                $this->em->persist($order);
                $this->em->persist($communicator);
                $this->em->flush();
            }
            catch( PDOException $e ) {
            }     
         } else {
            $order->setStatus(3);
            foreach( $order->getItems() as $item ) {
                $item->getProduct()->setStatisticSold( $item->getProduct()->getStatisticSold() + 1 );   
            }
            $this->em->persist($order);
            $this->em->flush();     
         }
         $this->listing($this->session->userdata('order_status'));
		 redirect( 'orders/listing/'.$old_status );
     }
     
     function details_status_activation( $order_id, $order_status ){
         
         $order = $this->em->getRepository('models\Entities\Order')->find($order_id);
         $old_status = $order->getStatus();
         if ( $order_status == 1 ) {
            $order->setStatus(1); 
            $this->em->persist($order);
            $this->em->flush();   
         } else if ( $order_status == 2 ) {
            $order->setStatus(2);
            try {
                $communicator = new models\Entities\Communicator\TransferOrder();
                $communicator->setRecord($order);
                
                $this->em->persist($order);
                $this->em->persist($communicator);
                $this->em->flush();
            }
            catch( PDOException $e ) {
            }     
         } else {
            $order->setStatus(3);
            foreach( $order->getItems() as $item ) {
                $item->getProduct()->setStatisticSold( $item->getProduct()->getStatisticSold() + 1 );   
            }
            $this->em->persist($order);
            $this->em->flush();     
         } 
         
         redirect( 'orders/details/'.$order_id );   
         
     }
     
     public function delete() {
        
        $id_list = explode( ',', $this->input->post('items') );
        
        $this->em->getRepository('models\Entities\Order')->deleteOrder($id_list);
        $this->output->set_output( TRUE );
     }
     
     public function delete_order( $id ) {
         
         $order = $this->em->getRepository('models\Entities\Order')->find($id);
         $order_status = $order->getStatus();
         $this->em->remove($order);  
         $this->em->flush();
         
         $this->listing($order_status);    
     }
     
     public function details( $order_id ){

         $data['order'] = $this->em->getRepository('models\Entities\Order')->find($order_id);
         $data['prev'] = $this->em->getRepository('models\Entities\Order')->getPrev($order_id, $data['order']->getStatus());
         $data['next'] = $this->em->getRepository('models\Entities\Order')->getNext($order_id, $data['order']->getStatus());

         $data['bundle_items'] = array();
         $data['order']->getItems();
         foreach( $data['order']->getItems() as $item ) {
            if( $item->getBundle() ) {
                $data['bundle_items'][] = $item->getBundle();
            }    
         }
         
         $this->resources['css'][] = 'flexigrid';
         $this->resources['js'][] = 'flexigrid';
         
         $this->gridDetailsParams['title'] = 'Proizvodi';

         $colModel['product_id']  = array( 'ID', 70, TRUE, 'center', 1 );
         $colModel['product_name']  = array( 'Proizvod', 320, FALSE, 'center', 0 );
		 $colModel['location_id']  = array( 'Lokacija', 90, TRUE, 'center', 1 );
         $colModel['price']  = array( 'Cena', 90, TRUE, 'center', 1 );
         $colModel['quantity']  = array( 'Količina', 70, TRUE, 'center', 1 );                                                 

         $data['grid'] = build_grid_js('grid', site_url("orders/details_grid/".$order_id), $colModel, 'id', 'asc', $this->gridDetailsParams);
       
         $this->gridDetailsParams['title'] = 'Bundle proizvodi';

         $colModelBundle['product_id']  = array( 'ID', 80, TRUE, 'center', 1 );
         $colModelBundle['product_name']  = array( 'Proizvod', 350, FALSE, 'center', 0 );
         $colModelBundle['price']  = array( 'Cena', 100, TRUE, 'center', 1 );                                                 
         $colModelBundle['quantity']  = array( 'Kolicina', 80, TRUE, 'center', 1 );                                                 

         $data['bundle_grid'] = build_grid_js('bundle_grid', site_url("orders/bundle_products_grid/".$order_id), $colModelBundle, 'id', 'asc', $this->gridDetailsParams);
         
         if ($this->em->getRepository('models\Entities\Order\Configuration')->findBy(array('order' => $order_id))){
         
         $this->gridDetailsParams['title'] = 'Pregled konfiguracija';

         $colConfigurationModel['id']  = array( 'ID', 80, TRUE, 'center', 1 );
         $colConfigurationModel['configuration_name']  = array( 'Konfiguracije', 350, TRUE, 'center', 1 );
         $colConfigurationModel['configuration_price']  = array( 'Cena', 120, TRUE, 'center', 1 );
         $colConfigurationModel['quantity']  = array( 'Količina', 80, TRUE, 'center', 1 );
         
         $data['configurations_grid'] = build_grid_js('configuration_grid', site_url("orders/configurations_grid/" . $order_id), $colConfigurationModel, 'id', 'ASC', $this->gridDetailsParams);
         }
         $data['grid_title'] = "KiddyJoy Shop - Detalji narudžbe";

         $this->_render_view( "order/order_details", $data );    
     }
     
     public function details_grid($order_id) {

         $valid_fields = array( 'product_id', 'location_id', 'product_name', 'price', 'quantity' );
         $this->flexigrid->validate_post($this->gridDetailsParams['id'], 'product_id', 'desc', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Order')->getOrderRecords($criteria, $order_id);

         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function bundle_products_grid($order_id) {

         $valid_fields = array( 'product_id', 'product_name' );
         $this->flexigrid->validate_post($this->gridDetailsParams['id'], 'id', 'desc', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Order')->getOrderBundleProducts($criteria, $order_id);
             
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function configurations_grid($order_id) {

         $valid_fields = array( 'id', 'configuration_price', 'quantity' );
         $this->flexigrid->validate_post('id', 'desc', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Order\Configuration')->getOrderConfigurations($criteria, $order_id);

         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }

    public function configuration_details($configuration_id){
         
         $this->resources['css'][] = 'flexigrid';
         $this->resources['js'][] = 'flexigrid';
        
         $configuration = $this->em->getRepository('models\Entities\Order\Configuration')->find( $configuration_id );
         
         $data['order_id'] = $configuration->getID();
         
         $this->gridParams['title'] = 'Pregled komponenti';
         
         $colConfigurationModel['component_category']  = array( 'Kategorija', 120, TRUE, 'center', 1 );
         $colConfigurationModel['component_name']  = array( 'Naziv', 600, TRUE, 'center', 1 );
         $colConfigurationModel['component_price']  = array( 'Cena', 120, TRUE, 'center', 1 );
         $colConfigurationModel['quantity']  = array( 'Količina', 80, TRUE, 'center', 1 );
         
         $data['grid'] = build_grid_js('grid', site_url("orders/components_grid/".$configuration_id), $colConfigurationModel, 'id', 'asc', $this->gridParams);
         
         $data['grid_title'] = "KiddyJoy Shop - Detalji narudžbe";
         $data['route'] = "orders/details/" . $configuration->getOrder()->getID();
         $data['params_id'] = 'productsGrid';
         
         $this->_render_view( "master/grid_view", $data );   
    }

    public function components_grid( $configuration_id ) {
            
         $configuration = $this->em->getRepository('models\Entities\Order\Configuration')->find( $configuration_id );

         $components = explode( ',', $configuration->getData() );
         
         $product_ids = array();
         $conf_data = array();
         
         $i=0;
         foreach( $components as $component ) {

               $data = explode( '-', $component );
               
               $product_ids[] = (int)$data[0];
               $conf_data[$i]['product_id'] = $data[0];
               $conf_data[$i]['quantity'] = $data[1];
               $i++;
         }

         $valid_fields = array( 'component_category', 'component_name', 'component_price' );
         $this->flexigrid->validate_post('component_category', 'asc', $valid_fields);
         $criteria = $this->flexigrid->get_criteria();
         $records = $this->em->getRepository('models\Entities\Order\Configuration')->getComponents($criteria, $product_ids, $conf_data);
             
         $this->output->set_header($this->config->item('json_header'));
         $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
     }
     
     public function edit( $order_id ) {
         
         $order = $this->em->getRepository('models\Entities\Order')->find($order_id);
         $order->setInfo( $this->input->post( 'info' ) );
         
         $this->em->persist($order);
         $this->em->flush();

         $this->session->set_flashdata('order', '<p class="message_success">Sve izmene su uspešno izvršene!</p>'); 
         $this->details($order_id);  
     }
     
     public function delete_order_product(){
         
         $items = explode( ',', $this->input->post('items') );
         
         foreach( $items as $id ) {
             
             $order_product = $this->em->getRepository('models\Entities\Order\Item')->find($id); 
            
             $order = $this->em->getRepository('models\Entities\Order')->find($order_product->getOrder()->getID()); 
             $order->setTotalPrice( $order->getTotalPrice() - $product_data->getPrice() * $order_product->getQuantity() );
             $this->em->persist($order);
             $this->em->flush();
             $this->em->remove($order_product);   
         }
         $this->output->set_output( true );
     }
 }
 
 /* End of file orders.php */
 /* Location: ./system/applications/_backend/controllers/orders.php */
