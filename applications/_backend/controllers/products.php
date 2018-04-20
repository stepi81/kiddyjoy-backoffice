<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ...
 * @author Ivan Despic [ Codeion ]
 */

 use models\Entities\Product;
 use models\Entities\Images\ProductImage;
 use models\Entities\Product\Master;
 use models\Entities\Product\TextFilter;
 use models\Entities\Product\ProductColor;
 use models\Entities\Product\ProductSize;

 class Products extends MY_Controller {

    public $gridParams = array(
        'id'                    => 'productsGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 50,
        'rpOptions'             => '[50,100,200,500]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} proizvoda.',
        'pagetext'              => 'Stranica',
        'outof'                 => 'od',
        'findtext'              => 'Pronađi',
        'procmsg'               => 'Obrada u toku, molimo sačekajte...',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
    );

    public $subcategoryGridParams = array(
        'id'                    => 'subcategoryGrid',
        'width'                 => 'auto',
        'height'                => 400,
        'rp'                    => 15,
        'rpOptions'             => '[10,15,20,25,40,100]',
        'pagestat'              => 'Prikaz: {from} do {to} / Ukupno: {total} podkategorija.',
        'pagetext'              => 'Stranica',
        'outof'                 => 'od',
        'findtext'              => 'Pronađi',
        'procmsg'               => 'Obrada u toku, molimo sačekajte...',
        'blockOpacity'          => 0.5,
        'showTableToggleBtn'    => true
    );

    public function __construct() {

        parent::__construct();

        $this->load->helper('flexigrid');
        $this->load->library('Flexigrid');
        $this->load->helper('tinymce');
        $this->load->helper('product');

        $this->resources['css'] = array();
        $this->resources['js'][] = 'tiny_mce';

		ini_set('memory_limit', '256M');
    }

    public function listing_group( $category_id ) {

        $user = $this->auth_manager->user();

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->subcategoryGridParams['title']='Grupe';

        $colModel['position'] = array( 'Position', 50, TRUE, 'center', 1 );
        $colModel['name'] = array( 'Naziv', 200, TRUE, 'center', 1 );
        //if( $user->getGroup()->getID() != 7 ) $colModel['highlights'] = array( 'Izdvajamo', 60, FALSE, 'center', 0 );
        $colModel['products'] = array( 'Proizvodi', 80, FALSE, 'center', 0);
        $colModel['subcategories'] = array( 'Podkategorije', 80, FALSE, 'center', 0);
        $colModel['reviews'] = array( 'Utisci', 80, FALSE, 'center', 0);

		if( $this->input->post('page') ) $this->subcategoryGridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("products/grid_group/" . $category_id), $colModel, 'position', 'ASC', $this->subcategoryGridParams, $buttons = null);
        $data['category'] = $this->em->getRepository('models\Entities\Product\Category')->find($category_id);
        $data['grid_title'] = 'Grupe:: ' . $data['category']->getName();
        $this->_render_view("master/grid_view", $data);
    }

    public function grid_group( $category_id ){

        $valid_fields = array( 'name' );
        $this->flexigrid->validate_post($this->subcategoryGridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();

        $records = $this->em->getRepository('models\Entities\Product\Subcategory')->getCategoryGroups($criteria, $category_id, $listing_product = 1); // $listing_product useing same method and giving parametar for using for product listing

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
    }

    public function listing_subcategories( $group_id ) {

        $user = $this->auth_manager->user();

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->subcategoryGridParams['title']='Podkategorije';

        $colModel['position'] = array( 'Position', 50, TRUE, 'center', 1 );
        $colModel['name'] = array( 'Naziv', 200, TRUE, 'center', 1 );
        if( $user->getGroup()->getID() != 7  ) $colModel['highlights'] = array( 'Izdvajamo', 60, FALSE, 'center', 0 );
        $colModel['products'] = array( 'Proizvodi', 80, FALSE, 'center', 0);
        $colModel['reviews'] = array( 'Utisci', 80, FALSE, 'center', 0);

        if( $this->input->post('page') ) $this->subcategoryGridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("products/grid_subcategories/" . $group_id), $colModel, 'position', 'ASC', $this->subcategoryGridParams);
        $data['group'] = $this->em->getRepository('models\Entities\Product\Subcategory')->find($group_id);
        $data['grid_title'] = 'Podkategorije:: ' . $data['group']->getName();
        $data['route'] = "products/listing_group/" . $data['group']->getCategory()->getID();
        $data['params_id'] = 'subcategoryGrid';
        $this->_render_view("master/grid_view", $data);
    }

    public function grid_subcategories( $group_id ){

        $valid_fields = array( 'name' );
        $this->flexigrid->validate_post($this->subcategoryGridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();

        $records = $this->em->getRepository('models\Entities\Product\Subcategory')->getCategorySubcategories($criteria, $group_id, $listing_product = 1); // $listing_product useing same method and giving parametar for using for product listing

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
    }

    public function listing_new( ) {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Proizvodi';

        $colModel['id'] = array( 'ID', 30, TRUE, 'center', 1 );
        $colModel['master_id'] = array( 'Master ID', 70, TRUE, 'center', 1 );
        $colModel['category'] = array( 'Kategorija', 160, TRUE, 'center', 1 );
        $colModel['brand'] = array( 'Brend', 160, TRUE, 'center', 1 );
        $colModel['name'] = array( 'Naziv', 230, TRUE, 'center', 2 );
        $colModel['description'] = array('Opis', 200, TRUE, 'center', 0);
        $colModel['price'] = array( 'Cena', 100, TRUE, 'center', 0 );
        $colModel['price_type'] = array( 'Tip cene', 100, TRUE, 'center', 0 );
        $colModel['archive'] = array( 'Arhiva', 50, TRUE, 'center', 0 );
        $colModel['gafas'] = array('Galerija', 50, FALSE, 'center', 0);
        $colModel['details'] = array('Detalji', 50, FALSE, 'center', 0);

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("products/grid_new"), $colModel, 'id', 'ASC', $this->gridParams, $buttons=NULL);

        $data['grid_title'] = 'Novi proizvodi' ;

        $this->_render_view( "master/grid_view", $data );
    }

    public function grid_new( ){

        $valid_fields = array('id', 'master_id', 'name', 'category', 'brand', 'archive', 'price', 'price_type');

        $this->flexigrid->validate_post($this->gridParams['id'], 'id', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->em->getRepository('models\Entities\Product')->getNewProducts( $criteria );

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
    }


    public function listing($category_id, $back_button = NULL) {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Proizvodi';

        $colModel['id'] = array( 'ID', 30, TRUE, 'center', 1 );
        $colModel['master_id'] = array( 'Master ID', 70, TRUE, 'center', 1 );
        $colModel['vendor'] = array( 'Dobavljač', 120, TRUE, 'center', 1 );
        $colModel['brand'] = array( 'Brend', 120, TRUE, 'center', 1 );
        $colModel['name'] = array( 'Naziv', 230, TRUE, 'center', 2 );
        $colModel['subcategory'] = array( 'Podkategorija', 150, TRUE, 'center', 1 );
        $colModel['price'] = array( 'Cena', 100, TRUE, 'center', 1 );

		$colModel['galery'] = array('Galerija', 50, FALSE, 'center', 0);
        $colModel['details'] = array('Detalji', 50, FALSE, 'center', 0);

        $colModel['statistic_sold'] = array( 'Prodato', 50, TRUE, 'center', 0 );
        $colModel['statistic_visits'] = array( 'Posete', 50, TRUE, 'center', 0 );
        $colModel['statistic_votes'] = array( 'Ocene', 50, TRUE, 'center', 0 );
        $colModel['statistic_rating'] = array( 'Rejting', 50, TRUE, 'center', 0 );

        $colModel['highlights'] = array( 'Izdvajamo', 60, FALSE, 'center', 0 );
		$colModel['featured'] = array( 'Preporučujemo', 80, TRUE, 'center', 0 );
        $colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );
        $colModel['comments'] = array('Komentari', 50, FALSE, 'center', 0);
        $colModel['bundles'] = array( 'Paketi', 50, FALSE, 'center', 0 );
		$colModel['sizes'] = array('Veličine', 50, FALSE, 'center', 0);
		$colModel['colors'] = array('Boje', 50, FALSE, 'center', 0);
        $colModel['video'] = array('Video', 50, FALSE, 'center', 0);

		$buttons[] = array('Export proizvoda', 'url', 'grid_commands', 'url:' .site_url("products/export_products"));
		$buttons[] = array('separator');
		$buttons[] = array('Nov proizvod', 'add', 'grid_commands', site_url("products/create/".$category_id));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši proizvod', 'delete', 'grid_commands', site_url("products/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("products/grid/" . $category_id), $colModel, 'id', 'ASC', $this->gridParams, $buttons);

        $data['category'] = $this->em->getRepository('models\Entities\Product\Category')->find($category_id);

        $data['grid_title'] = 'Proizvodi:: '. $data['category']->getName();

        $data['route'] = "product/categories/listing";
        $data['params_id'] = 'categoriesGrid';

        $this->_render_view( "master/grid_view", $data );
    }

	public function grid($group_id) {

        $valid_fields = array('id', 'vendor', 'name', 'subcategory', 'brand', 'status', 'price', 'statistic_sold', 'statistic_visits', 'statistic_votes', 'statistic_rating');

        $this->flexigrid->validate_post($this->gridParams['id'], 'id', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();

        $records = $this->em->getRepository('models\Entities\Product')->getProducts( $criteria, $group_id );

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
    }

	public function create($category_id) {

		$data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
		$data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll();
        $data['subcategories'] = $this->em->getRepository('models\Entities\Product\Subcategory')->findAll();
        $data['stickers'] = $this->em->getRepository('models\Entities\Product\Sticker')->findAll();
        $data['warranties'] = $this->em->getRepository('models\Entities\Product\Warranty')->getWarrantiesList();
		$data['category'] = $this->em->getRepository('models\Entities\Product\Category')->find($category_id);

        $this->resources['js'][] = 'checkbox';
        $this->resources['css'][] = 'multiselect';
        $this->resources['js'][] = 'multiselect';

		$data['category_id'] = $category_id;

		$this->_render_view( 'product/create_product', $data );
	}

	public function save() {

		$data['product'] = new Product();

		//$this->input->post('manufacturer_id') ? $data['product']->setManufacturerID($this->input->post('manufacturer_id')) : $data['product']->setManufacturerID(NULL);
        $data['product']->setVendor($this->input->post('vendor'));
        $data['product']->setName($this->input->post('name'));
		$data['product']->setPrice( $this->input->post('price') );
		$data['product']->setPriceRetail( $this->input->post('old_price') );
		$data['product']->setOldPrice( $this->input->post('old_price') );

		$data['product']->setCategory($this->em->getReference('models\Entities\Product\Category', $this->input->post('category')));
		$data['product']->setBrand($this->em->getReference('models\Entities\Product\Brand', $this->input->post('brand')));

         if ($this->input->post('subcategory') != ""){
            $data['product']->setSubcategory($this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('subcategory')));
         } else {
            $data['product']->setSubcategory($this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('group')));
         }

		$this->input->post('warranty') ? $data['product']->setWarranty($this->em->getReference('models\Entities\Product\Warranty', $this->input->post('warranty'))) : $data['product']->setWarranty(NULL);
		//$this->input->post('sticker') ? $data['product']->setSticker($this->em->getReference('models\Entities\Product\Sticker', $this->input->post('sticker'))) : $data['product']->setSticker(NULL);
		$data['product']->setPromotion($this->input->post('promotion'));
        $data['product']->setSale($this->input->post('sale'));
        $data['product']->setOutlet($this->input->post('outlet'));
        $data['product']->setStatus($this->input->post('status'));

		$this->em->persist($data['product']);
        $this->em->flush();

		redirect( 'products/details/'.$data['product']->getID() );
	}

     public function out_of_stock($group_id) {

         //$group = $this->em->getRepository('models\Entities\Product\Subcategory')->find($group_id);
         //$group_name = $group->getName();


         $list = array();
         $products = $this->em->getRepository('models\Entities\Product')->getOutOfStocks($group_id);

         $subcategory = '';

         foreach($products as $product)
         {
             $product_name 	= $product->getName();
             $product_brand 	= $product->getBrand();
             $subcategory = $product->getSubcategory()->getName();

             if($product_name != ''){

                 $brand = ($product_brand)? $product_brand->getName(): null;
                 array_push($list, array('brand'=>$brand, 'name' => $product_name, 'id'=>$product->getID(), 'masterid'=> $product->getMaster()->getMasterID(), 'price'=>number_format($product->getPrice(),2)));

             }
         }

         $date = date("d-m-Y");


         /* EXCEL */
         $csv 	= 'Datum:' . $date . "\t" . strtoupper($subcategory) . "\t". '' . "\t" . ''. "\t" . ''. "\n";
         $csv 	.= '' . "\t" . '' . "\t" . '' . "\t" . '' . "\t" . '' . "\n";
         $csv 	.= 'ID' . "\t" . 'MASTER ID' . "\t" . 'BRAND' . "\t" . 'NAME' . "\t" . 'PRICE' . "\n";

         foreach($list as $p) {
             $csv .= $p['id'] . "\t" . $p['masterid'] . "\t" . $p['brand'] . "\t" . $p['name'] . "\t" . $p['price'] . "\n";
         }

         $encoded_csv = mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');
         header('Content-Description: File Transfer');
         header("Content-Type: application/vnd.ms-excel");
         header('Content-Disposition: attachment; filename="'.$subcategory.'_'.$date.'_outofstock.xls"');
         header('Content-Transfer-Encoding: binary');
         header('Expires: 0');
         header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
         header('Pragma: public');

         echo chr(255) . chr(254) . $encoded_csv;
     }

     public function export_products() {

         $list = array();
         $products = $this->em->getRepository('models\Entities\Product')->getExportProducts();

         foreach($products as $product)
         {
             $product_name = $product->getName();
             $product_brand = $product->getBrand();
             $subcategory = $product->getSubcategory();

             if($product_name != ''){

                 $brand = ($product_brand) ? $product_brand->getName() : null;
                 $product_subcategory = ($subcategory) ? $subcategory->getName() : null;
                     array_push($list, array('brand'=>$brand, 'vendor' => $product->getVendor(), 'subcategory' => $product_subcategory, 'name' => $product_name, 'id'=>$product->getID(), 'masterid'=> $product->getManufacturerID(), 'visits' => $product->getStatisticVisits(), 'votes' => $product->getStatisticVotes(), 'rating' => $product->getStatisticRating(), 'price' => $product->getPrice(), 'status' => $product->getStatus()));

             }
         }

         $date = date("d-m-Y");


         /* EXCEL */
         $csv 	= 'Datum:' . $date . "\t" . "\t" . "\t". '' . "\t" . ''. "\t" . ''. "\n";
         $csv 	.= '' . "\t" . '' . "\t" . '' . "\t" . '' . "\t" . '' . "\n";
         $csv 	.= 'ID' . "\t" . 'DOBAVLJAC' . "\t" . 'MASTER ID' . "\t" . 'BREND' . "\t" . 'PODKATEGORIJA' . "\t" . 'NAZIV' . "\t" . 'CENA' . "\t" . 'POSETE' . "\t" . 'REJTING' . "\t" . 'OCENE' . "\t" . 'STATUS' . "\n";

         foreach($list as $p) {
             if($p['status'] == 1) {
                 $status = 'Activan';
             } else {
                 $status = 'Neaktivan';
             }
             $csv .= $p['id'] . "\t" . $p['vendor'] . "\t" . $p['masterid'] . "\t" . $p['brand'] . "\t" . $p['subcategory'] . "\t" . $p['name'] . "\t" . $p['price'] . "\t" . $p['visits'] . "\t" . $p['rating'] . "\t" . $p['votes'] . "\t" . $status . "\n";
         }

         $encoded_csv = mb_convert_encoding($csv, 'UTF-16LE', 'UTF-8');
         header('Content-Description: File Transfer');
         header("Content-Type: application/vnd.ms-excel");
         header('Content-Disposition: attachment; filename="'.'lista_proizvoda_'.$date.'.xls"');
         header('Content-Transfer-Encoding: binary');
         header('Expires: 0');
         header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
         header('Pragma: public');

         echo chr(255) . chr(254) . $encoded_csv;
     }

    public function details( $id ) {

		$data['brands'] = $this->em->getRepository('models\Entities\Product\Brand')->findAll();
        $data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll();
        $data['subcategories'] = $this->em->getRepository('models\Entities\Product\Subcategory')->findAll();
        $data['stickers'] = $this->em->getRepository('models\Entities\Product\Sticker')->findAll();
        $data['warranties'] = $this->em->getRepository('models\Entities\Product\Warranty')->getWarrantiesList();

        $this->resources['js'][] = 'checkbox';
        $this->resources['css'][] = 'multiselect';
        $this->resources['js'][] = 'multiselect';
        $data['tinymce_other'] = build_tinymce_js('other', 500, 400, "");
		$data['tinymce_information'] = build_tinymce_js('information', 500, 400, "");

        if( $data['product'] = $this->em->getRepository('models\Entities\Product')->find($id) ) {

            //$price_type = unserialize(PRICE_TYPE);
            //$data['price_type'] = $price_type[$data['product']->getPriceType()];

            $data['specifications'] = $this->em->getRepository('models\Entities\Product\Specification')->getAllSpecificationsBySubcategory($data['product']->getSubcategory()->getID());
            $data['new_product'] = FALSE;

            $data['filters'] = $data['product']->getProductFilters();
            $data['textFilter'] = $data['product']->getProductTextFilters();

            $this->_render_view( 'product/master_view', $data );
        } else show_404();
    }

    public function edit( $id ) {

        if( $data['product'] = $this->em->getRepository('models\Entities\Product')->find($id) ) {
            $data['categories'] = $this->em->getRepository('models\Entities\Product\Category')->findAll();

            $subcategoryID = $data['product']->getSubcategory()->getID();

            $data['specifications'] = $this->em->getRepository('models\Entities\Product\Specification')->getAllSpecificationsBySubcategory($subcategoryID);
            $data['filters'] = $data['product']->getProductFilters();

			$data['product']->setName($this->input->post('name'));
			$data['product']->setPrice( $this->input->post('price') );
			$data['product']->setPriceRetail( $this->input->post('old_price') );
			$data['product']->setOldPrice( $this->input->post('old_price') );
			$data['product']->setVat( $this->input->post('vat') );

            $data['product']->setCategory($this->em->getReference('models\Entities\Product\Category', $this->input->post('category')));

            if ( $subcategoryID != $this->input->post('subcategory') && $subcategoryID != $this->input->post('group') ) {
                 if ($this->input->post('subcategory') != ""){
                    $data['product']->setSubcategory($this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('subcategory')));
                 } else {
                    $data['product']->setSubcategory($this->em->getReference('models\Entities\Product\Subcategory', $this->input->post('group')));
                 }
            }

			//$this->input->post('manufacturer_id') ? $data['product']->setManufacturerID($this->input->post('manufacturer_id')) : $data['product']->setManufacturerID(NULL);
            $data['product']->setVendor($this->input->post('vendor'));
            $data['product']->setName($this->input->post('name'));
            $this->input->post('warranty') ? $data['product']->setWarranty($this->em->getReference('models\Entities\Product\Warranty', $this->input->post('warranty'))) : $data['product']->setWarranty(NULL);

			//$this->input->post('sticker') ? $data['product']->setSticker($this->em->getReference('models\Entities\Product\Sticker', $this->input->post('sticker'))) : $data['product']->setSticker(NULL);

            if( $this->input->post('sticker') ) {
				$data['product']->getStickers()->clear();
				$data['product']->setSticker($this->em->getReference('models\Entities\Product\Sticker', $this->input->post('sticker')));
				$data['product']->setStickers($this->em->getReference('models\Entities\Product\Sticker', $this->input->post('sticker')));
			} else {
				$data['product']->getStickers()->clear();
				$data['product']->setSticker(NULL);
			}

            $data['product']->setOther($this->input->post('other'));
            $data['product']->setDescription($this->input->post('other'));
			$data['product']->setInformation($this->input->post('information'));
            //$data['product']->setPriceList($this->input->post('price_list'));
            $data['product']->setPromotion($this->input->post('promotion'));
            $data['product']->setSale($this->input->post('sale'));
            $data['product']->setOutlet($this->input->post('outlet'));
			$data['product']->setFeatured($this->input->post('featured'));
            $data['product']->setStatus($this->input->post('status'));

            if ($this->input->post('status') == 1 && $data['product']->getReleaseDate() == NULL){
                $data['product']->setReleaseDate();
            }

            $data['product']->getProductFilters()->clear();
            $this->em->getRepository('models\Entities\Product\TextFilter')->deleteTextFilter($id);

            if ( $subcategoryID == $this->input->post('subcategory') || $subcategoryID == $this->input->post('group') ) {

                foreach ($data['specifications'] as $specification ){
                   if ($this->input->post($specification->getID() . 't') != ''){
                        $data['textFilter'] = new TextFilter();
                        $data['textFilter']->setProduct($this->em->getReference('models\Entities\Product', $id ));
                        $data['textFilter']->setDescription($this->input->post($specification->getID() . 't') );
                        $data['textFilter']->setSpecification($this->em->getReference('models\Entities\Product\Specification', $specification->getID()));
                        $this->em->persist($data['textFilter']);
                        $this->em->flush();
                   }
                   if ($this->input->post($specification->getID() . 's') != ''){
                        $data['product']->setProductFilter($this->em->getReference('models\Entities\Product\Filter', $this->input->post($specification->getID() . 's')));
                   }
                }
            }

            $this->em->persist($data['product']);
            $this->em->flush();

			if( $data['product']->getStatus() ) {
        		// ACTIVITY MONITORING
	         	$evtArgs = new \Doctrine\Common\EventArgs();
	         	$evtArgs->type = ACTIVITY_PRODUCT;
	         	$evtArgs->operation = ACTIVITY_OPERATION_PRODUCT;
	         	$evtArgs->process = ACTIVITY_PROCESS_PRODUCT_ACTIVE;
	         	$evtArgs->record = $data['product'];
	         	$this->evm->dispatchEvent(ACTIVITY_EVENT, $evtArgs);
	        } else {
	        	// ACTIVITY MONITORING
	         	$evtArgs = new \Doctrine\Common\EventArgs();
	         	$evtArgs->type = ACTIVITY_PRODUCT;
	         	$evtArgs->operation = ACTIVITY_OPERATION_PRODUCT;
	         	$evtArgs->process = ACTIVITY_PROCESS_PRODUCT_INACTIVE;
	         	$evtArgs->record = $data['product'];
	         	$this->evm->dispatchEvent(ACTIVITY_EVENT, $evtArgs);
	        }

            $data['new_product'] = FALSE;
            $this->resources['js'][] = 'checkbox';
            $data['message'] = '<p class="message_success">Proizvod je izmenjen!</p>';

            redirect( 'products/details/'.$id );
        } else show_404();
    }

    public function change_status($id) {

        $product = $this->em->getRepository('models\Entities\Product')->find($id);

        if( $product->getStatus() ) {
        	$product->setStatus(0);

        	// ACTIVITY MONITORING
         	$evtArgs = new \Doctrine\Common\EventArgs();
         	$evtArgs->type = ACTIVITY_PRODUCT;
         	$evtArgs->operation = ACTIVITY_OPERATION_PRODUCT;
         	$evtArgs->process = ACTIVITY_PROCESS_PRODUCT_INACTIVE;
         	$evtArgs->record = $product;
         	$this->evm->dispatchEvent(ACTIVITY_EVENT, $evtArgs);
        }
        else {
        	$product->setStatus(1);

        	// ACTIVITY MONITORING
         	$evtArgs = new \Doctrine\Common\EventArgs();
         	$evtArgs->type = ACTIVITY_PRODUCT;
         	$evtArgs->operation = ACTIVITY_OPERATION_PRODUCT;
         	$evtArgs->process = ACTIVITY_PROCESS_PRODUCT_ACTIVE;
         	$evtArgs->record = $product;
         	$this->evm->dispatchEvent(ACTIVITY_EVENT, $evtArgs);
        }

        if ($product->getReleaseDate() == NULL) {
            $product->setReleaseDate();
        }
        $this->em->flush();

        $this->output->set_output($product->getStatus());
    }

	public function change_featured($id) {

        $product = $this->em->getRepository('models\Entities\Product')->find($id);

        if( $product->getFeatured() ) {
        	$product->setFeatured(0);
        }
        else {
        	$product->setFeatured(1);
        }

        $this->em->flush();

        $this->output->set_output($product->getFeatured());
    }

    public function gallery( $product_id ){

        $this->resources['css'][] = 'gallery';
        $this->resources['css'][] = 'fancybox';
        $this->resources['css'][] = 'uploadify';

        $this->resources['js'][] = 'gallery';
        $this->resources['js'][] = 'fancybox';
        $this->resources['js'][] = 'swfobject';
        $this->resources['js'][] = 'uploadify';
        $this->resources['js'][] = 'mouse';
        $this->resources['js'][] = 'sortable';

        $this->session->set_userdata('product_image_uri', $this->uri->uri_string());

        $data['product'] = $this->em->getRepository('models\Entities\Product')->find($product_id);
        $data['images'] = $this->em->getRepository('models\Entities\Product')->getImagesByProduct($product_id);

        $this->_render_view('product/gallery_view', $data);
    }

    public function save_position(){

        $id = $this->input->post('id');
        $this->em->getRepository('models\Entities\Product')->setImagesPosition($id);

    }

    public function delete_image($id){

        $image = $this->em->getRepository('models\Entities\Images\ProductImage')->find($id);

        /* Deleting images from server*/
        unlink( SERVER_PATH . '/assets/img/products/thumb/'.$image->getName() );
        unlink( SERVER_PATH . '/assets/img/products/small/'.$image->getName() );
        unlink( SERVER_PATH . '/assets/img/products/large/'.$image->getName() );
        unlink( SERVER_PATH . '/assets/img/products/medium/'.$image->getName() );

        $this->em->remove($image);
        $this->em->flush();

        $items = $this->em->getRepository('models\Entities\Product')->getImagesAfterDelete($image->getProduct()->getID(), $image->getPosition());

        foreach( $items as $item ) {
            $item->setPosition( $item->getPosition() - 1 );
            $this->em->flush();
        }
        redirect($this->session->userdata('product_image_uri'));
    }

	public function delete() {

		$id_list = explode( ',', $this->input->post('items') );

		$this->em->getRepository('models\Entities\Product')->deleteProducts($id_list);
		$this->output->set_output( TRUE );
	}

    public function get_groups(){

         $category = $this->input->post('category_selection');
         $data['groups'] = $this->em->getRepository('models\Entities\Product\Subcategory')->findBy(array('category' => $category, 'parent' => NULL));

         $html = '<option value="">Sellect</option>';

         foreach($data['groups'] as $group){

             $html .= '<option value="' . $group->getID() . '">' . $group->getName() . '</option>';
         }
         $this->output->set_output($html);
    }

    public function get_subcategories(){

         $group = $this->input->post('group_selection');
         $data['subcategories'] = $this->em->getRepository('models\Entities\Product\Subcategory')->findBy(array('parent' => $group));

         $html = '<option value="">Sellect</option>';

         foreach($data['subcategories'] as $subcategory){

             $html .= '<option value="' . $subcategory->getID() . '">' . $subcategory->getName() . '</option>';
         }
         $this->output->set_output($html);
    }

    public function set_category_highlight( $id ) {

         $product = $this->em->getRepository('models\Entities\Product')->find($id);

         // ACTIVITY MONITORING
         $evtArgs = new \Doctrine\Common\EventArgs();
         $evtArgs->type = ACTIVITY_PRODUCT;
         $evtArgs->operation = ACTIVITY_OPERATION_MENU;
         $evtArgs->record = $product;

         if( $product->getHighlightCategory() ) {
            $product->setHighlightCategory(0);
			$product->getCategoryHighlights()->removeElement($product->getCategory());

			$this->em->flush();

            $status = FALSE;
            $evtArgs->process = ACTIVITY_PROCESS_DELETE;
         }
         else {
            $product->setHighlightCategory(1);
			$product->setCategoryHighlight($product->getCategory());

			$this->em->flush();

            $status = TRUE;
            $evtArgs->process = ACTIVITY_PROCESS_CREATE;
         }

         $this->evm->dispatchEvent(ACTIVITY_EVENT, $evtArgs);
         $this->output->set_output($status);
     }

     public function set_subcategory_highlights( $id ) {

         $product = $this->em->getRepository('models\Entities\Product')->find($id);

         // ACTIVITY MONITORING
         $evtArgs = new \Doctrine\Common\EventArgs();
         $evtArgs->type = ACTIVITY_PRODUCT;
         $evtArgs->operation = ACTIVITY_OPERATION_SLIDESHOW;
         $evtArgs->record = $product;

         if( $product->getHighlightSubcategory() ) {
            $product->setHighlightSubcategory(0);
            $status = FALSE;
            $evtArgs->process = ACTIVITY_PROCESS_DELETE;
         }
         else {
            $product->setHighlightSubcategory(1);
            $status = TRUE;
            $evtArgs->process = ACTIVITY_PROCESS_CREATE;
         }

         $this->em->flush();
         $this->evm->dispatchEvent(ACTIVITY_EVENT, $evtArgs);
         $this->output->set_output($status);
     }

	 public function set_vendor_subcategory_highlights( $id ) {

         $product = $this->em->getRepository('models\Entities\Product')->find($id);
		 $subcategory = $product->getSubcategory();
         $vendor_highlights = $subcategory->getVendorHighlights();

     	 if( in_array($this->em->getReference('models\Entities\Product', $id), $vendor_highlights->toArray()) ) {
            $vendor_highlights->removeElement( $this->em->getReference('models\Entities\Product', $id) );
            $status = FALSE;
         } else {
            $subcategory->setVendorHighlight( $this->em->getReference('models\Entities\Product', $id) );
            $status = TRUE;
         }

         $this->em->flush();
         $this->output->set_output($status);
     }

     public function set_highlighted_subcategory( $id ) {

         $subcategory = $this->em->getRepository('models\Entities\Product\Subcategory')->find($id);

         if( $subcategory->getHighlight() ) {
            $subcategory->setHighlight(0);
            $status = FALSE;
         } else {
            $subcategory->setHighlight(1);
            $status = TRUE;
         }

         $this->em->flush();
         $this->output->set_output($status);
     }

	 public function product_colors_listing( $product_id ) {

        $user = $this->auth_manager->user();

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->subcategoryGridParams['title'] = 'Boje';

        $colModel['position'] = array( 'Position', 50, TRUE, 'center', 1 );
        $colModel['name'] = array( 'Naziv', 200, TRUE, 'center', 1 );
		$colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );

        $buttons[] = array('Obriši boije', 'delete', 'grid_commands', site_url("products/delete_product_colors"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

		if( $this->input->post('page') ) $this->subcategoryGridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("products/product_colors_grid/" . $product_id), $colModel, 'position', 'ASC', $this->subcategoryGridParams, $buttons);

		$data['colors'] = $this->em->getRepository('models\Entities\Product\Color')->getAllColors();
        $data['product'] = $this->em->getRepository('models\Entities\Product')->find($product_id);
        $data['grid_title'] = 'Boje - ' . $data['product']->getName();

		$this->resources['js'][] = 'checkbox';

        $this->_render_view("product/colors_view", $data);
    }

    public function product_colors_grid( $product_id ){

        $valid_fields = array( 'name', 'position', 'status' );
        $this->flexigrid->validate_post($this->subcategoryGridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();

        $records = $this->em->getRepository('models\Entities\Product\ProductColor')->getProductColors($criteria, $product_id);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
    }

    public function change_product_color_status( $id ) {

        $product_color = $this->em->getRepository('models\Entities\Product\ProductColor')->find($id);
        $product_color->getStatus() ? $product_color->setStatus(0) : $product_color->setStatus(1);

        $this->em->flush();

        $this->output->set_output($product_color->getStatus());
    }

	public function delete_product_colors() {

        $id_list = explode(',', $this->input->post('items'));

        foreach ($id_list as $id) {
            $product_color = $this->em->getRepository('models\Entities\Product\ProductColor')->find($id);
            $colors = $this->em->getRepository('models\Entities\Product\ProductColor')->getColorsByProduct($product_color->getProduct()->getID());
            foreach ($colors as $color) {
                $oldPosition = $color->getPosition();
                if ($product_color->getPosition() < $color->getPosition()) {
                    $color->setPosition($oldPosition - 1);
                    $this->em->persist($color);
                    $this->em->flush();
                }
            }

        }
        $this->em->getRepository('models\Entities\Product\ProductColor')->deleteProductColors($id_list);
        $this->output->set_output(TRUE);
    }

	public function add_product_color() {

		if( $this->em->getRepository('models\Entities\Product\ProductColor')->checkColor( $this->input->post('product_id'), $this->input->post('color') ) ) {
			$data['product_color'] = new ProductColor();

	        $position = $this->input->post('position');

	        $maxProductColor = $this->em->getRepository('models\Entities\Product\ProductColor')->getProductColorPosition($this->input->post('product_id'));
	        $colors = $this->em->getRepository('models\Entities\Product\ProductColor')->getColorsByProduct($this->input->post('product_id'));

	        $maxPosition = $maxProductColor[0][1];

	        if ($position) {
	            if ($position >= $maxPosition) {
	                $position = $maxPosition + 1;
	            } else {
	                foreach ($colors as $color) {
	                    $newPosition = $color->getPosition();
	                    if ($position <= $adNowPosition) {
	                        $color->setPosition($newPosition + 1);
	                        $this->em->persist($color);
	                        $this->em->flush();
	                    }
	                }
	            }
	        } else {
	            $position = $maxPosition + 1;
	        }

			$data['product_color']->setProduct($this->em->getReference('models\Entities\Product', $this->input->post('product_id')));
			$data['product_color']->setColor($this->em->getReference('models\Entities\Product\ProductColor', $this->input->post('color')));

			$data['product_color']->setStatus( $this->input->post('status') );
			$data['product_color']->setPosition( $position );

			$this->em->persist($data['product_color']);
			$this->em->flush();
		}

		redirect("products/product_colors_listing/".$this->input->post('product_id'));
	}

	public function product_sizes_listing( $product_id ) {

        $user = $this->auth_manager->user();

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->subcategoryGridParams['title'] = 'Velične';

        $colModel['position'] = array( 'Position', 50, TRUE, 'center', 1 );
        $colModel['name'] = array( 'Naziv', 200, TRUE, 'center', 1 );
		$colModel['status'] = array( 'Status', 50, TRUE, 'center', 0 );

        $buttons[] = array('Obriši velicine', 'delete', 'grid_commands', site_url("products/delete_product_sizes"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
        $buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

		if( $this->input->post('page') ) $this->subcategoryGridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("products/product_sizes_grid/" . $product_id), $colModel, 'position', 'ASC', $this->subcategoryGridParams, $buttons);


        $data['product'] = $this->em->getRepository('models\Entities\Product')->find($product_id);
		$data['sizes'] = $this->em->getRepository('models\Entities\Product\Size')->getAllSizesBySubcategory($data['product']->getSubcategory()->getID());
        $data['grid_title'] = 'Veličine - ' . $data['product']->getName();

		$this->resources['js'][] = 'checkbox';

        $this->_render_view("product/sizes_view", $data);
    }

    public function product_sizes_grid( $product_id ){

        $valid_fields = array( 'name', 'position', 'status' );
        $this->flexigrid->validate_post($this->subcategoryGridParams['id'], 'position', 'ASC', $valid_fields);
        $criteria = $this->flexigrid->get_criteria();

        $records = $this->em->getRepository('models\Entities\Product\ProductSize')->getProductSizes($criteria, $product_id);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'],$records['record_items']));
    }

    public function change_product_size_status( $id ) {

        $product_size = $this->em->getRepository('models\Entities\Product\ProductSize')->find($id);
        $product_size->getStatus() ? $product_size->setStatus(0) : $product_size->setStatus(1);

        $this->em->flush();

        $this->output->set_output($product_size->getStatus());
    }

	public function delete_product_sizes() {

        $id_list = explode(',', $this->input->post('items'));

        foreach ($id_list as $id) {
            $product_size = $this->em->getRepository('models\Entities\Product\ProductSize')->find($id);
            $sizes = $this->em->getRepository('models\Entities\Product\ProductSize')->getSizesByProduct($product_size->getProduct()->getID());
            foreach ($sizes as $size) {
                $oldPosition = $size->getPosition();
                if ($product_size->getPosition() < $size->getPosition()) {
                    $size->setPosition($oldPosition - 1);
                    $this->em->persist($size);
                    $this->em->flush();
                }
            }

        }
        $this->em->getRepository('models\Entities\Product\ProductSize')->deleteProductSizes($id_list);
        $this->output->set_output(TRUE);
    }

	public function add_product_size() {

		if( $this->em->getRepository('models\Entities\Product\ProductSize')->checkSize( $this->input->post('product_id'), $this->input->post('size') ) ) {
			$data['product_size'] = new ProductSize();

	        $position = $this->input->post('position');

	        $maxProductSize = $this->em->getRepository('models\Entities\Product\ProductSize')->getProductSizePosition($this->input->post('product_id'));
	        $sizes = $this->em->getRepository('models\Entities\Product\ProductSize')->getSizesByProduct($this->input->post('product_id'));

	        $maxPosition = $maxProductSize[0][1];

	        if ($position) {
	            if ($position >= $maxPosition) {
	                $position = $maxPosition + 1;
	            } else {
	                foreach ($sizes as $size) {
	                    $newPosition = $size->getPosition();
	                    if ($position <= $adNowPosition) {
	                        $size->setPosition($newPosition + 1);
	                        $this->em->persist($size);
	                        $this->em->flush();
	                    }
	                }
	            }
	        } else {
	            $position = $maxPosition + 1;
	        }

			$data['product_size']->setProduct($this->em->getReference('models\Entities\Product', $this->input->post('product_id')));
			$data['product_size']->setSize($this->em->getReference('models\Entities\Product\ProductSize', $this->input->post('size')));

			$data['product_size']->setStatus( $this->input->post('status') );
			$data['product_size']->setPosition( $position );

			$this->em->persist($data['product_size']);
			$this->em->flush();
		}

		redirect("products/product_sizes_listing/".$this->input->post('product_id'));
	}

	public function create_import() {

		$this->_render_view( 'products_import_view' );
	}

	public function import() {

		if (file_exists(SERVER_IMAGE_PATH.'xlsx/proizvodi.xlsx')) {
		    unlink(SERVER_IMAGE_PATH.'xlsx/proizvodi.xlsx');
		}

        $upload_config['encrypt_name'] 		= FALSE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'xlsx/';
        $upload_config['allowed_types'] 	= '*';
        $upload_config['max_size']			= '2048';
        $upload_config['remove_spaces'] 	= TRUE;

		$this->load->library('upload');

		$this->upload->initialize($upload_config);

		if( $this->upload->do_upload('thumb') ) {

			if( $_FILES['thumb']['name'] == 'proizvodi.xlsx' ) {

				$this->load->library('PHPExcel');

				$inputFileType = PHPExcel_IOFactory::identify(SERVER_IMAGE_PATH.'xlsx/proizvodi.xlsx');

			    $objReader = PHPExcel_IOFactory::createReader($inputFileType);

			    $objReader->setReadDataOnly(true);

			    $objPHPExcel = $objReader->load(SERVER_IMAGE_PATH.'xlsx/proizvodi.xlsx');

			    $total_sheets = $objPHPExcel->getSheetCount();

			    $allSheetName = $objPHPExcel->getSheetNames();
			    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			    $highestRow = $objWorksheet->getHighestRow();
			    $highestColumn = $objWorksheet->getHighestColumn();
			    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

				for( $row = 2; $row <= $highestRow; ++$row ) {
			        for( $col = 0; $col < $highestColumnIndex; ++$col ) {

			            $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			            $arraydata[$row-1][$col] = $value;
			        }

					//echo 'brand: '.$arraydata[$row-1][0].'<br />';

					if( $arraydata[$row-1][0] ) {
						$data['product'] = new Product();

				        $data['product']->setName( $arraydata[$row-1][5] );
						$data['product']->setPrice( $arraydata[$row-1][3] );
						$data['product']->setStatus( 0 );

						$data['product']->setBrand($this->em->getReference('models\Entities\Product\Brand', $arraydata[$row-1][0]));
						$data['product']->setCategory($this->em->getReference('models\Entities\Product\Category', $arraydata[$row-1][1]));
						$data['product']->setSubcategory($this->em->getReference('models\Entities\Product\Subcategory', $arraydata[$row-1][2]));
						$data['product']->setDescription( $arraydata[$row-1][6] );
                        $data['product']->setManufacturerID( $arraydata[$row-1][7] );
                        //$data['product']->setSticker( $arraydata[$row-1][8] );

                        if( $arraydata[$row-1][8] ) {
                            $data['product']->setSticker($this->em->getReference('models\Entities\Product\Sticker', $arraydata[$row-1][8]));
                            $data['product']->setStickers($this->em->getReference('models\Entities\Product\Sticker', $arraydata[$row-1][8]));
                        }

						$filters = explode("-", $arraydata[$row-1][4]);

					    /*$filters = array_count_values( $filters );
					    rsort( $filters );
					    if( $filters[0] > 1 ) {
					    	echo $arraydata[$row-1][5].'<br />';
					    }*/

						foreach( $filters as $filter ) {
							if( $filter ) {
								$data['product']->setProductFilter($this->em->getReference('models\Entities\Product\Filter', $filter));
							}
						}

						$this->em->persist($data['product']);
					}

			    }

				$this->em->flush();

				$data['message'] = '<p class="message_success">Uspešno ste uneli proizvode.</p>';
			} else {
				$data['message'] = '<p class="message_error">Naziv fajla mora biti proizvodi.xlsx!</p>';
				unlink(SERVER_IMAGE_PATH.'xlsx/'.$_FILES['thumb']['name']);
			}
		} else {
			$data['message'] = '<p class="message_error">Došlo je do greške prilikom uploada fajla! Pokušajte ponovo.</p>';
		}

		$this->_render_view( 'products_import_view', $data );
	}

	public function create_price_update() {

		$this->_render_view( 'update_price_view' );
	}

	public function price_update() {

		if (file_exists(SERVER_IMAGE_PATH.'xlsx/cene.xlsx')) {
		    unlink(SERVER_IMAGE_PATH.'xlsx/cene.xlsx');
		}

		$upload_config['encrypt_name'] 		= FALSE;
        $upload_config['upload_path'] 		= SERVER_IMAGE_PATH.'xlsx/';
        $upload_config['allowed_types'] 	= '*';
        $upload_config['max_size']			= '2048';
        $upload_config['remove_spaces'] 	= TRUE;

		$this->load->library('upload');

		$this->upload->initialize($upload_config);

		if( $this->upload->do_upload('thumb') ) {

			if( $_FILES['thumb']['name'] == 'cene.xlsx' ) {

				$this->load->library('PHPExcel');

				$inputFileType = PHPExcel_IOFactory::identify(SERVER_IMAGE_PATH.'xlsx/cene.xlsx');

			    $objReader = PHPExcel_IOFactory::createReader($inputFileType);

			    $objReader->setReadDataOnly(true);

			    $objPHPExcel = $objReader->load(SERVER_IMAGE_PATH.'xlsx/cene.xlsx');

			    $total_sheets = $objPHPExcel->getSheetCount();

			    $allSheetName = $objPHPExcel->getSheetNames();
			    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			    $highestRow = $objWorksheet->getHighestRow();
			    $highestColumn = $objWorksheet->getHighestColumn();
			    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

				for( $row = 2; $row <= $highestRow; ++$row ) {
			        for( $col = 0; $col < $highestColumnIndex; ++$col ) {

			            $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
			            $arraydata[$row-1][$col] = $value;
			        }

					$data['product'] = $this->em->getRepository('models\Entities\Product')->find($arraydata[$row-1][0]);

					if( $data['product'] ) {
						if( $arraydata[$row-1][1] && $arraydata[$row-1][1]  != 0 ) {
							$data['product']->setPrice( $arraydata[$row-1][1] );
						}
                        if( $arraydata[$row-1][3] == 0 || $arraydata[$row-1][3] == 1 ) {
                            $data['product']->setStatus( $arraydata[$row-1][3] );
                        }
                        if( $arraydata[$row-1][4] ) {
                            $data['product']->setVendor( $arraydata[$row-1][4] );
                        } else {
                            $data['product']->setVendor(null);
                        }
                        if( $arraydata[$row-1][5] ) {
                            $data['product']->setManufacturerID( $arraydata[$row-1][5] );
                        } else {
                            $data['product']->setManufacturerID(null);
                        }
                        if( $arraydata[$row-1][6] ) {
                            $data['product']->getStickers()->clear();
                            $data['product']->setSticker($this->em->getReference('models\Entities\Product\Sticker', $arraydata[$row-1][6]));
                            $data['product']->setStickers($this->em->getReference('models\Entities\Product\Sticker', $arraydata[$row-1][6]));
                        } else {
                            $data['product']->getStickers()->clear();
                            $data['product']->setSticker(NULL);
                        }
						if( $arraydata[$row-1][2] && $arraydata[$row-1][2]  != 0 ) {
							$data['product']->setOldPrice( $arraydata[$row-1][2] );
							$data['product']->setPriceRetail( $arraydata[$row-1][2] );

							$this->em->persist($data['product']);
						}
					}

			    }

				$this->em->flush();

				unlink(SERVER_IMAGE_PATH.'xlsx/cene.xlsx');

				$data['message'] = '<p class="message_success">Uspešno ste izmenili cene.</p>';
			} else {
				$data['message'] = '<p class="message_error">Naziv fajla mora biti cene.xlsx!</p>';
				unlink(SERVER_IMAGE_PATH.'xlsx/'.$_FILES['thumb']['name']);
			}
		} else {
			$data['message'] = '<p class="message_error">Došlo je do greške prilikom uploada fajla! Pokušajte ponovo.</p>';
		}

		$this->_render_view( 'update_price_view', $data );
	}

     public function insert_clone_product() {

         $this->_render_view( 'clone_product_view' );
     }

     public function clone_product() {

         if( $data['product'] = $this->em->getRepository('models\Entities\Product')->find($this->input->post('product_id')) ) {

             $cloned = new Product();
             $cloned->setName( $data['product']->getName() );
             $cloned->setPrice( $data['product']->getPrice() );
             $cloned->setStatus( 0 );

             $cloned->setBrand($this->em->getReference('models\Entities\Product\Brand', $data['product']->getBrand()->getID()));
             $cloned->setCategory($this->em->getReference('models\Entities\Product\Category', $data['product']->getCategory()->getID()));
             $cloned->setSubcategory($this->em->getReference('models\Entities\Product\Subcategory', $data['product']->getSubcategory()->getID()));
             $cloned->setDescription( $data['product']->getDescription() );
             $cloned->setManufacturerID( $data['product']->getManufacturerID() );
             if( $data['product']->getSticker() ) {
                 $cloned->setSticker($this->em->getReference('models\Entities\Product\Sticker', $data['product']->getSticker()->getID()));
                 $cloned->setStickers($this->em->getReference('models\Entities\Product\Sticker', $data['product']->getSticker()->getID()));
             }
             $cloned->setVendor( $data['product']->getVendor() );
             $cloned->setVat( $data['product']->getVat() );
             $cloned->setOldPrice( $data['product']->getOldPrice() );
             $cloned->setPriceRetail( $data['product']->getPriceRetail() );
             $cloned->setOther( $data['product']->getOther() );
             $cloned->setInformation( $data['product']->getInformation() );
             $cloned->setFeatured( $data['product']->getFeatured() );
             $cloned->setPromotion( $data['product']->getPromotion() );

             $this->em->persist($cloned);
             $this->em->flush();

             foreach($data['product']->getProductFilters() as $filter) {
                 $cloned->setProductFilter($this->em->getReference('models\Entities\Product\Filter', $filter->getID()));
             }

             $this->em->persist($cloned);
             $this->em->flush();

             foreach ($data['product']->getProductTextFilters() as $text_filter) {
                 $data['textFilter'] = new TextFilter();
                 $data['textFilter']->setProduct($this->em->getReference('models\Entities\Product', $cloned->getID() ));
                 $data['textFilter']->setDescription($text_filter->getDescription());
                 $data['textFilter']->setSpecification($this->em->getReference('models\Entities\Product\Specification', $text_filter->getSpecification()->getID()));
                 $this->em->persist($data['textFilter']);
                 $this->em->flush();
             }

             //$cloned = clone($data['product']);
             //$cloned->setID(NULL);
             //$cloned->setStatus(0);
             //$this->em->persist($cloned);
             //$this->em->flush();

             /*foreach($data['product']->getProductFilters() as $filter) {
                 $cloned->setProductFilter($this->em->getReference('models\Entities\Product\Filter', $filter->getID()));
             }*/

             //$this->em->persist($cloned);
             //$this->em->flush();

             /*foreach ($data['product']->getProductTextFilters() as $text_filter) {
                 $data['textFilter'] = new TextFilter();
                 $data['textFilter']->setProduct($this->em->getReference('models\Entities\Product', $cloned->getID() ));
                 $data['textFilter']->setDescription($text_filter->getDescription());
                 $data['textFilter']->setSpecification($this->em->getReference('models\Entities\Product\Specification', $text_filter->getSpecification()->getID()));
                 $this->em->persist($data['textFilter']);
                 $this->em->flush();
             }*/

             $data['message'] = '<p class="message_success">Uspešno ste klonirali proizvod.</p>';

         } else {
             $data['message'] = '<p class="message_error">Proizvod ne postoji! Pokušajte ponovo.</p>';
         }

         $this->_render_view( 'clone_product_view', $data );
     }
 }

 /* End of file products.php */
 /* Location: ./system/applications/_backend/controllers/products.php */