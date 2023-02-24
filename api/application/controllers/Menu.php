<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
class Menu extends REST_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Menu_model');
		$this->load->model('Auth_model');
		$cekAuth = $this->Auth_model->check_auth($this->input->request_headers());
		if($cekAuth[0]) $this->response($cekAuth[1]);
	}
	public function edit_post(){
		$query = $this->Menu_model->model_function('editMenu', $this->post());
		$this->response($query);
	}

	public function index_get(){
		$query = $this->Menu_model->getMenu();//$this->get()
		$this->response($query);
	}

	public function id_get($id_Menu){
		$query = $this->Menu_model->getMenubyID($id_Menu);
		$this->response($query);
	}

	public function index_post(){
		$query = $this->Menu_model->function_post($this->post());
		$this->response($query, $query['status']);
	}

	public function last_get($id){
		$query = $this->Menu_model->model_function('getLastMenu',$id);
		$this->response($query);
	}

	public function update_get($id){
		$query = $this->Menu_model->model_function('getUpdateMenu',$id);
		$this->response($query);
	}
}