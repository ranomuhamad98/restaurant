<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conf extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Curl');
        $this->load->model("SessLogin");
    }

    public function token(){
    	echo json_encode($this->Curl->get_token());
    }

	public function index()
	{
		if (get_cookie("id") != "") redirect(base_url('dashboard'));
		$this->load->view('login');
	}

	public function dashboard(){
		
		if (get_cookie("id") == "") redirect(base_url('login'));

		$this->load->view("kasir/dashboard");
	}

	// function of action login
	public function action_login()
	{
		$result = $this->SessLogin->getlogin($this->input->post('username'),$this->input->post('password'));
		if($result){
			redirect('dashboard');
		}else{
			$this->session->set_flashdata('failed',"LOGIN GAGAL");
			redirect(base_url('login'));
		}

	}

	public function logout(){
		$this->SessLogin->getLogout();
	}
}
