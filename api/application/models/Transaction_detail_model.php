<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaction_detail_model extends CI_Model {

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

	public function getTransaction_detailById($param){
		$data = array();
		$this->db->select("transaction_detail.*,menu.nama, menu.jenis");
		$this->db->from("transaction_detail");
		$this->db->where('transaction_detail.id_transaction', $param);
		$this->db->join('menu', 'menu.id = transaction_detail.id_menu', 'inner');
		$get = $this->db->get();
		if(!$get){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "SYSTEM ERRROR - DATABASE [TRANSACTION DETAIL BY ID]";
			$_response['error_data'] 	= $this->db->error();
		}else{
			if($get->num_rows()>0){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "SUCCESS INQUIRY [TRANSACTION DETAIL BY ID]";
				$_response['data']			= $get->result();
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "76";
				$_response['message'] 		= "DATA NOT FOUND [TRANSACTION DETAIL BY ID]";
				$_response['data']			=  array();
			}
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}

	// POST
	public function function_post($request){
		$request = json_decode(file_get_contents('php://input'),true);
		if($request['request_name'] == "editTransactionDtlByParam"){
			$_response = $this->editTransaction_detailByParam($request['where'],$request['data']);
		}else if($request['request_name'] == "delTransactionDtlByParam"){
			$_response = $this->delTransaction_detailByParam($request['data']);
			// $_response['request'] = $request;
		}
		return $_response;
	}
	function getTransaction_detailByParam($param){
		if(array_key_exists('password',$param)) $param['password'] = md5($param['password']);
		$param['del'] = 0;
		$get = $this->db->get_where("Transaction_detail",$param);
		if(!$get){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "SYSTEM ERRROR - DATABASE [Transaction_detail BY PARAM]";
			$_response['error_data'] 	= $this->db->error();
			$_response['query']			=  $this->db->last_query();
		}else{
			if($get->num_rows()>0){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "SUCCESS INQUIRY [Transaction_detail BY PARAM]";
				$_response['data']			= $get->result();
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "76";
				$_response['message'] 		= "DATA NOT FOUND [Transaction_detail BY PARAM]";
			}
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}

	function editTransaction_detailByParam($param,$data){		
		if(array_key_exists('quantity',$param)) unset($param['quantity']);
		if(array_key_exists('total',$param)) unset($param['total']);
		if(array_key_exists('status',$param)) unset($param['status']);
		if(array_key_exists('id_transaction',$param)) { 
			$param['transaction_detail.id_transaction'] = $param['id_transaction'];
			unset($param['id_transaction']);
		}
		if(array_key_exists('id_menu',$param)) { 
			$param['transaction_detail.id_menu'] = $param['id_menu'];
			unset($param['id_menu']);
		}

		if(array_key_exists('id_transaction',$data)) unset($data['id_transaction']);
		if(array_key_exists('id_menu',$data)) unset($data['id_menu']);
		if(array_key_exists('total',$data)) unset($data['total']);
		if(array_key_exists('quantity',$data)) { 
			$data['transaction_detail.quantity'] = $data['quantity'];
			unset($data['quantity']);
		}
		if(array_key_exists('status',$data)) { 
			$data['transaction_detail.status'] = $data['status'];
			unset($data['status']);
		}

		// $cek = $this->db->get_where("transaction_detail", $param);
		$this->db->select("transaction_detail.*,menu.nama, menu.jenis, menu.harga");
		$this->db->from("transaction_detail");
		$this->db->where($param);
		$this->db->join('menu', 'menu.id = transaction_detail.id_menu', 'inner');
		$cek = $this->db->get();
		if($cek->num_rows()==0){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "DATA NOT FOUND [UPDATE TRANSACTION DETAIL BY PARAM]";
		}else{
			$row_data = $cek->row();
			if(array_key_exists('transaction_detail.quantity',$data)) $data['total']  = $data['transaction_detail.quantity'] * $row_data->harga;
			$this->db->set($data);
			$this->db->where($param);
			$get = $this->db->update("transaction_detail");
			if($get){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "APPROVED [UPDATE TRANSACTION DETAIL BY PARAM]";
				$_response['data']			= $this->db->get_where('transaction_detail',$param)->row();
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "96";
				$_response['message'] 		= "SYSTEM ERRROR - DATABASE [UPDATE TRANSACTION DETAIL BY PARAM]";
				$_response['error_data'] 	= $this->db->error();
				$_response['query'] 		= $this->db->last_query();
				$_response['total'] 		= $data['transaction_detail.quantity'];
			}
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}

	function delTransaction_detailByParam($param){
		if(array_key_exists('quantity',$param)) unset($param['quantity']);
		if(array_key_exists('total',$param)) unset($param['total']);
		if(array_key_exists('status',$param)) unset($param['status']);
		
		$this->db->where($param);
		$cek = $this->db->get("transaction_detail");
		if($cek->num_rows()==0){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "DATA NOT FOUND [DELETE TRANSACTION DETAIL BY PARAM]";
		}else{
			$get = $this->db->delete("transaction_detail", $param);
			if($get){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "SUCCESS INQUIRY [DELETE TRANSACTION DETAIL BY PARAM]";
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "96";
				$_response['message'] 		= "SYSTEM ERRROR - DATABASE [DELETE TRANSACTION DETAIL BY PARAM]";
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
		$data = array('editTransaction_detail','getTransaction_detail','getLastTransaction_detail','getUpdateTransaction_detail');

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