<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Menu_model extends CI_Model {

	private $response;

	public function __construct()
    {
        parent::__construct();

        $this->maxTimeOut = 60;

        /*$this->refcode = array(
			"00" => "APPROVED / SUCCESS INQUIRY / SUCCESS UPLOAD",
			"13" => "INVALID AMOUNT",
			"30" => "FORMAT ERROR",
			"55" => "INVALID SIGNATURE/INVALID TOKEN",
			"58" => "TOKEN EXPIRED",
			"76" => "INVALID BILLING / BILLING CODE NOT FOUND / DATA NOT FOUND",
			"88" => "BILL ALREADY PAID / DATA EXISTS",
			"96" => "DECLINE SYSTEM MALFUNCTION / SYSTEM ERROR",
			"97" => "DECLINE CRYPTOGRAPHIC FAILURE"
		);*/

		// $_response['query']			= $this->db->last_query();
    }

	public function getMenu(){
		$data = array();
		$get = $this->db->get_where("menu", array('del' => 0));
		if(!$get){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "SYSTEM ERRROR - DATABASE";
			$_response['error_data'] 	= $this->db->error();
			$_response['timestamp']		= date("Y-m-d H:i:s");
		}else{
			if($get->num_rows()>0){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "SUCCESS INQUIRY [MENU]";
				$_response['data']			= $get->result();
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "76";
				$_response['message'] 		= "DATA NOT FOUND";
				$_response['data']			=  array();
			}
			$_response['timestamp']		= date("Y-m-d H:i:s");
		}
		return $_response;
	}

	public function getMenuById($param){
		$data = array();

		$get = $this->db->get_where("menu",array('id' => $param, 'del' => 0));
		if(!$get){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "SYSTEM ERRROR - DATABASE";
			$_response['error_data'] 	= $this->db->error();
		}else{
			if($get->num_rows()>0){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "SUCCESS INQUIRY [MENU BY ID]";
				$_response['data']			= $get->row();
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "76";
				$_response['message'] 		= "DATA NOT FOUND";
				$_response['data']			=  array();
			}
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}

	// POST
	public function function_post($request){
		$request = json_decode(file_get_contents('php://input'),true);
		if($request['request_name'] == "editMenuByParam"){
			$_response = $this->editMenuByParam($request['where'],$request['data']);
		}
		return $_response;
	}

	function editMenuByParam($param,$data){
		if(array_key_exists('del',$param)) unset($param['del']);
		if(array_key_exists('id',$data)) unset($data['id']);

		$param['del'] = 0;
		$cek = $this->db->get_where("menu", $param);
		if($cek->num_rows()==0){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "DATA NOT FOUND [UPDATE MENU BY PARAM]";
		}else{
			$this->db->set($data);
			$this->db->where($param);
			$get = $this->db->update("menu");
			if($get){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "APPROVED [UPDATE MENU BY PARAM] ".$note;
				$_response['data']			= $this->db->get_where('menu',$param)->row();
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "96";
				$_response['message'] 		= "SYSTEM ERRROR - DATABASE [UPDATE MENU BY PARAM]";
				$_response['error_data'] 	= $this->db->error();
				$_response['query'] 		= $this->db->last_query();
			}
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}

	public function model_function($type, $request = null){
		$data = array('editMenu','getMenu','getLastMenu','getUpdateMenu');

		$_response['error']				= true;
		if(in_array($type, $data)){
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "DECLINE SYSTEM MALFUNCTION [FUNCTION NOT AVALIABLE]";
		}else{
			$_response['status'] 		= "404"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "URL NOT FOUND";
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}
}
?>