<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
class Employee extends REST_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Employee_model');
		$this->load->model('Auth_model');
		$cekAuth = $this->Auth_model->check_auth($this->input->request_headers());
		if($cekAuth[0]) $this->response($cekAuth[1]);
	}
	public function edit_post(){
		$query = $this->Employee_model->model_function('editEmployee', $this->post());
		$this->response($query);
	}

	public function index_get(){
		$query = $this->Employee_model->getEmployee();
		$this->response($query);
	}

	public function id_get($id){
		$query = $this->Employee_model->getEmployeeById($id);
		$this->response($query);
	}

	public function index_post(){
		$query = $this->Employee_model->function_post($this->post());
		$this->response($query, $query['status']);
	}

	public function last_get($id){
		$query = $this->Employee_model->model_function('getLastEmployee',$id);
		$this->response($query);
	}

	public function update_get($id){
		$query = $this->Employee_model->model_function('getUpdateEmployee',$id);
		$this->response($query);
	}
}