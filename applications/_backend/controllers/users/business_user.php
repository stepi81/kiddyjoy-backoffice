<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * ...
 * @author Andrej The Savic [ Codeion ]
 */

require_once 'abstract_user.php';

use models\Entities\User\Customer\Business;

class Business_User extends Abstract_User {

    public function __construct() {

        parent::__construct();

        $this->resources['js'] = array('checkbox');

        $this->type = USER_TYPE_BUSINESS;
    }

    public function listing() {

        $this->resources['css'][] = 'flexigrid';
        $this->resources['js'][] = 'flexigrid';

        $this->gridParams['title'] = 'Pregled poslovnih korisnika';

        $colModel['master_id'] = array('Master ID', 100, TRUE, 'center', 1);
        $colModel['company_name'] = array('Naziv kompanije', 204, TRUE, 'center', 1);
        $colModel['contact_person'] = array('Kontakt osoba', 204, TRUE, 'center', 1);
        $colModel['email'] = array('E-mail', 200, TRUE, 'center', 1);
        $colModel['phone'] = array('Telefon', 204, TRUE, 'center', 1);
        $colModel['city'] = array('Grad', 204, TRUE, 'center', 1);
        $colModel['actions'] = array('Detalji', 80, FALSE, 'center', 0);

        $buttons[] = array('Novi korisnik', 'add', 'grid_commands', site_url("users/business_user/create"));
        $buttons[] = array('separator');
        $buttons[] = array('Obriši korisnika', 'delete', 'grid_commands', site_url("users/business_user/delete"));
        $buttons[] = array('separator');
        $buttons[] = array('Izaberi sve', 'select_all', 'grid_commands', '/select');
		$buttons[] = array('separator');
        $buttons[] = array('Poništi izbor', 'deselect_all', 'grid_commands', '/desel');
        $buttons[] = array('separator');

        if( $this->input->post('page') ) $this->gridParams['newp'] = $this->input->post('page');

        $data['grid'] = build_grid_js('grid', site_url("users/business_user/grid"), $colModel, 'id', 'ASC', $this->gridParams, $buttons);
        $data['grid_title'] = "Poslovni korisnici";

        $this->_render_view("master/grid_view", $data);
    }

    public function save() {
        
        $this->resources['css'] = array('theme');

        $user = new Business();
        $user->setEmail($this->input->post('email'));
        $user->setPassword($this->input->post('password'));
        $user->setMasterID($this->input->post('master_id'));
        $user->setCompanyName($this->input->post('company_name'));
        $user->setPhone($this->input->post('phone'));
        $user->setAddress($this->input->post('address'));
        $user->setTaxNumber($this->input->post('tax_number'));
        $user->setCurrentAccount( $this->input->post('current_account') ); 
        $user->setContactPerson($this->input->post('contact_person'));
        $user->setFax($this->input->post('fax'));
        $this->input->post('newsletter') ? $user->setNewsletter( $this->input->post('newsletter') ) : $user->setNewsletter( NULL ); 
    
        if (preg_match('#\((.*?)\)#', $this->input->post('city'), $match)){
        
            if($this->em->getRepository('models\Entities\User\Customer')->getCitiesCheck($match[1])){
                      
                $user->setPostalCode( $this->em->getReference('models\Entities\PostalCode', $match[1]) );
                
                try {
                    //$communicator = new models\Entities\Communicator\TransferUser();
                    //$communicator->setRecord($user);
                    
                    $this->em->persist($user);
                    //$this->em->persist($communicator);
                    $this->em->flush();
                    
                    $data['message'] = '<p class="message_success">Novi privatni korisnik je uspešno kreiran!</p>';
                }
                catch( PDOException $e ) {
                    // TODO try flash data to recover user input
                    $data['message'] = '<p class="message_error">E-mail adresa već postoji u našoj bazi!</p>';
                }
                
            } else {
                $data['message'] = '<p class="message_error">Proverite uneti grad!</p>';
            }
        } else {
            $data['message'] = '<p class="message_error">Proverite uneti grad!</p>';
        }
         
        $this->_render_view( 'user/business/new_business_user', $data );
    }

    public function edit($id) {
        
        $this->resources['css'] = array('theme');

        if ($data['user'] = $this->em->getRepository('models\Entities\User\Customer\Business')->find($id)) {

            // TODO server validation

            if( $this->input->post('password') ) $data['user']->setPassword($this->input->post('password'));
            $data['user']->setPhone($this->input->post('phone'));
            $data['user']->setMasterID($this->input->post('master_id'));
            $data['user']->setCompanyName($this->input->post('company_name'));
            $data['user']->setTaxNumber($this->input->post('tax_number'));
            $data['user']->setCurrentAccount( $this->input->post('current_account') );
            $data['user']->setFax($this->input->post('fax'));
            $data['user']->setContactPerson($this->input->post('contact_person'));
            $data['user']->setAddress($this->input->post('address'));
            $this->input->post('newsletter') ? $data['user']->setNewsletter( $this->input->post('newsletter') ) : $data['user']->setNewsletter( NULL ); 
     
            if ( preg_match('#\((.*?)\)#', $this->input->post('city'), $match)){
                
               if($this->em->getRepository('models\Entities\User\Customer')->getCitiesCheck($match[1])) {
                   
                    $data['user']->setPostalCode( $this->em->getReference('models\Entities\PostalCode', $match[1]) );
                    
                    $this->em->persist($data['user']);
                    $this->em->flush();

                    $data['message'] = '<p class="message_success">Sve izmene su uspešno izvršene!</p>';
               } else {
                    $data['message'] = '<p class="message_error">Proverite uneti grad!</p>';  
               }
            } else {
                $data['message'] = '<p class="message_error">Proverite uneti grad!</p>';  
            }
            $this->_render_view('user/business/edit_business_user', $data);
         } 
         else show_404();
     }

}

/* End of file admin_user.php */
/* Location: ./system/applications/_backend/controllers/users/business_user.php */
