<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
class Insdn_inquiry extends REST_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Insdn_inquiry_model');
	}
	public function edit_post(){
		$query = $this->Insdn_inquiry_model->editInsdn_inquiry($this->post());
		$this->response($query);
	}

	public function index_get(){
		$query = $this->Insdn_inquiry_model->getInsdn_inquiry($this->get());
		$this->response($query);
	}

	public function id_get($id_Insdn_inquiry){
		$query = $this->Insdn_inquiry_model->getInsdn_inquirybyID($id_Insdn_inquiry);
		$this->response($query);

	}

	public function index_post(){
		$query = $this->Insdn_inquiry_model->addInsdn_inquiry($this->post());
		$result = $query['Result'];
		$this->response($result, $result['status']);
	}

	public function last_get($id){
		$query = $this->Insdn_inquiry_model->getLastInsdn_inquiry($id);
		$this->response($query);
	}

	public function update_get($id){
		$query = $this->Insdn_inquiry_model->getUpdateInsdn_inquiry($id);
		$this->response($query);
	}
}