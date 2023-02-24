<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Employee_model extends CI_Model {

	private $response;

	public function __construct()
    {
        parent::__construct();

        $this->maxTimeOut = 60;

        /*$this->refcode = array(
			"00" => "APPROVED / SUCCESS INQUIRY / SUCCESS UPLOAD / SUCCES UPDATE / SUCCESS DELETE",
			"13" => "INVALID AMOUNT",
			"30" => "FORMAT ERROR",
			"55" => "INVALID SIGNATURE/INVALID TOKEN",
			"58" => "TOKEN EXPIRED",
			"76" => "INVALID BILLING / BILLING CODE NOT FOUND / DATA NOT FOUND",
			"88" => "BILL ALREADY PAID / DATA EXISTS",
			"96" => "DECLINE SYSTEM MALFUNCTION / SYSTEM ERROR",
			"97" => "DECLINE CRYPTOGRAPHIC FAILURE"
		);*/
    }

	public function getEmployee(){
		$data = array();
		$get = $this->db->get_where("employee", array('del' => 0));
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
				$_response['message'] 		= "SUCCESS INQUIRY [EMPLOYEE]";
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

	public function getEmployeeById($param){
		$data = array();

		$get = $this->db->get_where("employee",array('id' => $param, 'del' => 0));
		if(!$get){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "SYSTEM ERRROR - DATABASE [EMPLOYEE BY ID]";
			$_response['error_data'] 	= $this->db->error();
		}else{
			if($get->num_rows()>0){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "SUCCESS INQUIRY [EMPLOYEE BY ID]";
				$_response['data']			= $get->row();
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "76";
				$_response['message'] 		= "DATA NOT FOUND [EMPLOYEE BY ID]";
				$_response['data']			=  array();
			}
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}

	// POST
	public function function_post($request){
		$request = json_decode(file_get_contents('php://input'),true);
		if($request['request_name'] == "getEmployeeByParam"){
			$_response = $this->getEmployeeByParam($request['data']);
		}else if($request['request_name'] == "editEmployeeByParam"){
			$_response = $this->editEmployeeByParam($request['where'],$request['data']);
		}else if($request['request_name'] == "delEmployeeByParam"){
			$_response = $this->delEmployeeByParam($request['data']);
			// $_response['request'] = $request;
		}
		return $_response;
	}
	function getEmployeeByParam($param){
		if(array_key_exists('password',$param)) $param['password'] = md5($param['password']);
		$param['del'] = 0;
		$get = $this->db->get_where("employee",$param);
		if(!$get){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "SYSTEM ERRROR - DATABASE [EMPLOYEE BY PARAM]";
			$_response['error_data'] 	= $this->db->error();
			$_response['query']			=  $this->db->last_query();
		}else{
			if($get->num_rows()>0){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "SUCCESS INQUIRY [EMPLOYEE BY PARAM]";
				$_response['data']			= $get->result();
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "76";
				$_response['message'] 		= "DATA NOT FOUND [EMPLOYEE BY PARAM]";
			}
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}

	function editEmployeeByParam($param,$data){
		$note = "";
		if(array_key_exists('password',$param)) $param['password'] = md5($param['password']);
		if(array_key_exists('password',$data)) $data['password'] = md5($data['password']);
		if(array_key_exists('jabatan',$data) && !in_array($data['jabatan'], array('kasir','dapur'))){ 
			$note = "[JABATAN WITH DATA '".$data['jabatan']."' NOT FOUND]";unset($data['jabatan']);
		}
		$param['del'] = 0;
		$cek = $this->db->get_where("employee", $param);
		if($cek->num_rows()==0){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "DATA NOT FOUND [UPDATE EMPLOYEE BY PARAM]";
		}else{
			$this->db->set($data);
			$this->db->where($param);
			$get = $this->db->update("employee");
			if($get){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "APPROVED [UPDATE EMPLOYEE BY PARAM] ".$note;
				$_response['data']			= $this->db->get_where('employee',$param)->row();
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "96";
				$_response['message'] 		= "SYSTEM ERRROR - DATABASE [UPDATE EMPLOYEE BY PARAM]";
				$_response['error_data'] 	= $this->db->error();
				$_response['query'] 		= $this->db->last_query();
			}
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}

	function delEmployeeByParam($param){
		if(array_key_exists('password',$param)) $param['password'] = md5($param['password']);
		$param['del'] 	= 0;
		$this->db->set('del',1);
		$this->db->where($param);
		$cek = $this->db->get("employee");
		if($cek->num_rows()==0){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "DATA NOT FOUND [DELETE EMPLOYEE BY PARAM]";
		}else{
			$this->db->set('del',1);
			$this->db->where($param);
			$get = $this->db->update("employee");
			if($get){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "SUCCESS INQUIRY [DELETE EMPLOYEE BY PARAM]";
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "96";
				$_response['message'] 		= "SYSTEM ERRROR - DATABASE [DELETE EMPLOYEE BY PARAM]";
				$_response['error_data'] 	= $this->db->error();
				$_response['query']			=  $this->db->last_query();
			}
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}

	function cekTimeOut($time_start,$time_end){
		if(($time_end - $time_start) >= $this->maxTimeOut) return true; else return false;
	}
	public function model_function($type, $request = null){
		$data = array('editEmployee','getEmployee','getLastEmployee','getUpdateEmployee');

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