<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

class Abstract_User extends MY_Controller {

    public $type;

    public $gridParams = array('id'    => 'productsGrid',
                               'width' => 'auto', 
                               'height' => 400, 
                               'rp' => 15, 
                               'rpOptions' => '[10,15,20,25,40]', 
                               'pagestat' => 'Prikaz: {from} do {to} / Ukupno: {total} korisnika.', 
                               'pagetext' => 'Stranica', 
                               'outof' => 'od', 
                               'findtext' => 'Pronađi', 
                               'procmsg' => 'Obrada u toku, molimo sačekajte...', 
                               'blockOpacity' => 0.5, 
                               'showTableToggleBtn' => true);

    public function __construct() {

        parent::__construct();

        $this->load->helper('flexigrid');
        $this->load->library('flexigrid');
        $this->load->library('form_validation');
    }

    public function create() {
    	
        switch( $this->type ) {

            case USER_TYPE_ADMIN :
                $data['groups'] = $this->em->getRepository('models\Entities\User\Admin_Group')->findAll();
                $this->_render_view('user/new_admin_user', $data);
                break;

            case USER_TYPE_PERSONAL :
                $this->resources['css'] = array('theme','datepicker');
                //$data['groups'] = $this->em->getRepository('models\Entities\User\Customer\Personal')->findAll();
                $this->_render_view('user/personal/new_personal_user'/*, $data*/
                );
                break;

            case USER_TYPE_BUSINESS :
                $this->resources['css'] = array('theme');
                //$data['groups'] = $this->em->getRepository('models\Entities\User\Customer\Personal')->findAll();
                $this->_render_view('user/business/new_business_user'/*, $data*/
                );
                break;

            case USER_TYPE_NEWSLETTER :
                //$data['groups'] = $this->em->getRepository('models\Entities\User\NewsletterUser')->findAll();
                $this->_render_view('user/newsletter_user/new_newsletter_user'/*, $data*/
                );
                break;
        }
    }

    public function details( $id ) {
    	
        switch( $this->type ) {

            case USER_TYPE_ADMIN :
                $entity = 'models\Entities\User\Admin';
                $view = 'user/edit_admin_user';
                $data['groups'] = $this->em->getRepository('models\Entities\User\Admin_Group')->findAll();
                break;

            case USER_TYPE_PERSONAL :
                $this->resources['css'] = array('theme','datepicker','flexigrid');
        		$this->resources['js'][] = 'flexigrid';
                $entity = 'models\Entities\User\Customer\Personal';
                $view = 'user/personal/edit_personal_user';
                $data['grid'] = $this->shopping_listing( $id );
               // $data['groups'] = $this->em->getRepository('models\Entities\User\Admin_Group')->findAll();
                break;

            case USER_TYPE_BUSINESS :
                $this->resources['css'] = array('theme'); 
                $entity = 'models\Entities\User\Customer\Business';
                $view = 'user/business/edit_business_user';
               // $data['groups'] = $this->em->getRepository('models\Entities\User\Admin_Group')->findAll();
                break;

            case USER_TYPE_NEWSLETTER :
                $entity = 'models\Entities\User\NewsletterUser';
                $view = 'user/newsletter_user/edit_newsletter_user';
               // $data['groups'] = $this->em->getRepository('models\Entities\User\Admin_Group')->findAll();
                break;
        }

        if ($data['user'] = $this->em->getRepository($entity)->find($id)) {
            $this->_render_view($view, $data);
        } else
            show_404();
    }

    public function delete() {

        switch( $this->type ) {

            case USER_TYPE_ADMIN :
                $entitie = 'models\Entities\User\Admin';
                break;

            case USER_TYPE_PERSONAL :
                $entitie = 'models\Entities\User\Customer\Personal';
				
				$id_list = explode(',', $this->input->post('items'));
				foreach ($id_list as $user_id) {
					$avatar = $this->em->getRepository($entitie)->find($user_id)->getAvatar();
					if ($avatar){
    					unlink( SERVER_IMAGE_PATH.'users/large/'.$avatar );
    					unlink( SERVER_IMAGE_PATH.'users/small/'.$avatar );
    				}
                }
                break;

            case USER_TYPE_BUSINESS :
                $entitie = 'models\Entities\User\Customer\Business';
                break;

            case USER_TYPE_NEWSLETTER :
                $entitie = 'models\Entities\User\NewsletterUser';
        }

        $query = $this->em->createQuery('DELETE FROM ' . $entitie . ' u WHERE u.id IN (' . $this->input->post('items') . ')');
        $numDeleted = $query->execute();
        
		if ($numDeleted) $this->output->set_output(TRUE);
	
    }

    public function grid() {

        switch( $this->type ) {

            case USER_TYPE_ADMIN :
                $valid_fields = array('group', 'first_name', 'last_name', 'email');
                $this->flexigrid->validate_post($this->gridParams['id'],'first_name', 'asc', $valid_fields);
                break;

            case USER_TYPE_PERSONAL :
                $valid_fields = array('first_name', 'last_name', 'nickname', 'email', 'phone', 'city', 'points', 'registration_date');
                $this->flexigrid->validate_post($this->gridParams['id'],'registration_date', 'desc', $valid_fields);
                break;

            case USER_TYPE_BUSINESS :
                $valid_fields = array('master_id', 'company_name', 'contact_person', 'email', 'phone', 'city');
                $this->flexigrid->validate_post($this->gridParams['id'],'company_name', 'asc', $valid_fields);
                break;

            case USER_TYPE_NEWSLETTER :
                $valid_fields = array('email');
                $this->flexigrid->validate_post($this->gridParams['id'], 'email', 'asc', $valid_fields);
                break;
        }

        $criteria = $this->flexigrid->get_criteria();
        $records = $this->user_model->getUsersByType($criteria, $this->type);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }
    
    public function shopping_listing( $user_id ) {
    	
    	$this->gridParams['title'] = 'Pregled kupovina korisnika';

        $colModel['date'] = array('Datum kupovine', 120, TRUE, 'center', 1);
        $colModel['type'] = array('Tip kupovine', 120, FALSE, 'center', 0);
        $colModel['total_price'] = array('Ukupnca cena', 120, TRUE, 'center', 1);
        $colModel['discount'] = array('Popust', 120, TRUE, 'center', 1);
        $colModel['used_points'] = array('Iskorišćeni bodovi', 90, FALSE, 'center', 0);
        $colModel['status'] = array('Status', 260, FALSE, 'center', 0);
        $colModel['points'] = array('Osvojeni bodovi', 90, FALSE, 'center', 0);
        $colModel['activation'] = array('Datum aktivacije', 120, FALSE, 'center', 0);
        $colModel['action'] = array('Akcija', 60, FALSE, 'center', 0);

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');
        
        return build_grid_js('grid', site_url("users/personal_user/shopping_grid/".$user_id), $colModel, 'id', 'ASC', $this->gridParams);
    }
    
    public function shopping_grid( $user_id ) {
    	
    	$valid_fields = array('date', 'total_price');
        $this->flexigrid->validate_post($this->gridParams['id'],'registration_date', 'desc', $valid_fields);
        
        $criteria = $this->flexigrid->get_criteria();
        $records = $this->user_model->getShoppingHistory($user_id, $criteria);

        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }

    public function gridFriends($id) {



		$valid_fields = array('first_name', 'last_name', 'nickname', 'email', 'phone', 'city', 'registration_date');
        $this->flexigrid->validate_post($this->gridParams['id'],'registration_date', 'desc', $valid_fields);

        

        $criteria = $this->flexigrid->get_criteria();
		
		//$this->user_model2 = $this->em->getRepository('models\Entities\User\Customer\Personal')->find($id);
		
        //$friends = $this->user_model2->getFriends();
		
		$records['record_items'] = array();
		
		$records = $this->user_model->getUserFriends($criteria, $id);
		/*
		if($friends)
	        foreach ($friends as $user) {
	
	            $records['record_items'][] = array(
	                $user->getID(), 
	                $user->getFirstName(), 
	                $user->getLastName(),
	                $user->getNickname(), 
	                $user->getEmail(), 
	                $user->getPhone(), 
	                $user->getPostalCode() -> getCity(), 
	                $user->getFormatedRegistrationDate(), 
	                '<a href="' . site_url('users/personal_user/details/' . $user->getID()) . '"><img border="0" src="' . layout_url('flexigrid/details.png') . '"></a>',
	                '<a href="' . site_url('users/personal_user/friends/' . $user->getID()) . '"><img border="0" src="' . layout_url('flexigrid/friends.png') . '"></a>'
	            );
	        }
		*/
		$records['record_count'] = count($records['record_items']);
		
        $this->session->unset_userdata('edit_visited');
        $this->output->set_header($this->config->item('json_header'));
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $records['record_items']));
    }
}

/* End of file abstract_user.php */
/* Location: ./system/applications/_backend/controllers/users/abstract_user.php */
