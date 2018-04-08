<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
|  Form validation rules
| -------------------------------------------------------------------
*/

$config = array(
				'login' => array(
											array(
												'field' => 'login_email',
												'rules' => 'trim|required|valid_email|xss_clean'
											),
											array(
												'field' => 'login_password',
												'rules' => 'trim|required'
											)),
				'update_admin' => array(
											array(
												'field' => 'email',
												'rules' => 'trim|required|valid_email'
											),
											array(
												'field' => 'password',
												'rules' => 'trim|matches[personal_passconf]'
											),
											array(
												'field' => 'passconf',
												'rules' => 'trim|matches[personal_password]'
											),
											array(
												'field' => 'first_name',
												'rules' => 'trim|required'
											),
											array(
												'field' => 'last_name',
												'rules' => 'trim|required'
											),
											array(
												'field' => 'phone',
												'rules' => 'trim'
											))
);
			   
/* End of file form_validation.php */
/* Location: ./applications/_frontend/config/form_validation.php */