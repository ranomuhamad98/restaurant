<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
class Auth extends REST_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Auth_model');
	}
	public function edit_post(){
		$query = $this->Auth_model->model_function('editAuth', $this->post());
		$this->response($query);
	}

	public function index_get(){
		$query = $this->Auth_model->getAuth($this->get());
		$this->response($query);
	}

	public function id_get($id_Auth){
		$query = $this->Auth_model->model_function('getAuthbyID',$id_Auth);
		$this->response($query);
	}

	public function index_post(){
		$query = $this->Auth_model->model_function('getAuth',$this->post());
		$result = $query['Result'];
		$this->response($query, $result['status']);
	}

	public function last_get($id){
		$query = $this->Auth_model->model_function('getLastAuth',$id);
		$this->response($query);
	}

	public function update_get($id){
		$query = $this->Auth_model->model_function('getUpdateAuth',$id);
		$this->response($query);
	}
}