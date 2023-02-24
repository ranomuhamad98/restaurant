<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
class Transaction extends REST_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Transaction_model');
		$this->load->model('Auth_model');
		$cekAuth = $this->Auth_model->check_auth($this->input->request_headers());
		if($cekAuth[0]) $this->response($cekAuth[1]);
	}
	public function edit_post(){
		$query = $this->Transaction_model->model_function('editTransaction', $this->post());
		$this->response($query);
	}

	public function index_get(){
		$query = $this->Transaction_model->getTransaction();
		$this->response($query);
	}

	public function id_get($id){
		$query = $this->Transaction_model->getTransactionById($id);
		$this->response($query);
	}

	public function index_post(){
		$query = $this->Transaction_model->function_post($this->post());
		$this->response($query, $query['status']);
	}

	public function last_get($id){
		$query = $this->Transaction_model->model_function('getLastTransaction',$id);
		$this->response($query);
	}

	public function update_get($id){
		$query = $this->Transaction_model->model_function('getUpdateTransaction',$id);
		$this->response($query);
	}
}