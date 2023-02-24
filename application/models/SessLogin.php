<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SessLogin extends CI_Model {

	public function __construct() {
        parent::__construct();
        $this->load->model('Curl');
    }

	// cek login user
	public function getlogin($username, $password)
	{	
		$body['url']			= $this->Curl->get_url().'/index.php/employee';
		$body['token']			= $this->Curl->get_token();
		$param['email'] 		= $username;
		$param['password'] 		= $password;
		$param['status']		= 1;
		$data['request_name'] 	= "getEmployeeByParam";
		$data['data'] 			= $param;
		

		$request = $this->Curl->http_request($body,$data,'POST');
		$result = json_decode($request, TRUE);
		if($result['error']){
			return false;
		}else{
			$data = $result['data'][0];
			set_cookie('email', $data['email'], 24 * 3600);
			set_cookie('id',$data['id'], 24 * 3600);
			return true;
		}
	}

	public function getLogout(){
        delete_cookie('id');
        delete_cookie('email');
		$this->session->sess_destroy();
		redirect(base_url('login'));
    }
}