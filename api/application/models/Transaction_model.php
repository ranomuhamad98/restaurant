<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaction_model extends CI_Model {

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

    public function getTransaction(){
		$data = array();
		$this->db->order_by('id','desc');
		$get = $this->db->get_where("transaction", array('del' => 0));
		if(!$get){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "SYSTEM ERRROR - DATABASE [GET TRANSACTION]";
			$_response['error_data'] 	= $this->db->error();
			$_response['timestamp']		= date("Y-m-d H:i:s");
		}else{
			if($get->num_rows()>0){
				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "SUCCESS INQUIRY [GET TRANSACTION]";
				$_response['data']			= $get->result();
			}else{
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "76";
				$_response['message'] 		= "DATA NOT FOUND [GET TRANSACTION]";
				$_response['data']			=  array();
			}
			$_response['timestamp']		= date("Y-m-d H:i:s");
		}
		return $_response;
	}

	public function getTransactionById($param){
		$data = array();
		$this->db->select("transaction.*,concat(employee.nama_depan,' ',employee.nama_belakang) as nama_employee");
		$this->db->from("transaction");
		$this->db->where('transaction.id', $param);
		$this->db->join('employee', 'employee.id = transaction.id_employee', 'inner');
		$get = $this->db->get();
		if(!$get){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "SYSTEM ERRROR - DATABASE [TRANSACTION DETAIL BY ID]";
			$_response['error_data'] 	= $this->db->error();
		}else{
			if($get->num_rows()>0){
				$this->db->select("transaction_detail.*,menu.nama, menu.jenis");
				$this->db->from("transaction_detail");
				$this->db->where('transaction_detail.id_transaction', $param);
				$this->db->join('menu', 'menu.id = transaction_detail.id_menu', 'inner');
				$detail = $this->db->get();

				$data['transaction']		= $get->row();
				$data['detail']				= $detail->result();

				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= "SUCCESS INQUIRY [TRANSACTION DETAIL BY ID]";
				$_response['data']			= $data;
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
		if($request['request_name'] == "addTransaction"){
		
			$_response = $this->addTransaction($request['data']);
		
		}else if($request['request_name'] == "getTransactionByParam"){
			
			$_response = $this->getTransactionByParam($request['data']);
				
		}else if($request['request_name'] == "editTransactionByParam"){
			
			$_response = $this->editTransactionByParam($request['where'],$request['data'],$request['detail_data']);
		
		}else if($request['request_name'] == "delTransactionByParam"){
		
			$_response = $this->delTransactionByParam($request['data']);

		}
		return $_response;
	}

	function addTransaction($data){
		if(!isset($data['nomor_meja']) && !isset($data['detail'])){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "FORMAT ERROR: 'nomor_meja' AND 'detail' MUST BE SENT [ADD TRANSACTION BY PARAM]";
		}else{
			
			$data_transaction['nomor_meja'] 	= $data['nomor_meja'];
			$data_transaction['metode_bayar'] 			= $data['metode_bayar'];
			$data_transaction['nomor_pesanan'] 	= $this->getNomorPesanan(); 
			$data_transaction['status_pesanan'] = 'pending';
			$data_transaction['input_time']		= date('Y-m-d H:i:s');
			$add_transaction = $this->db->insert('transaction',$data_transaction);
			$insert_id = $this->db->insert_id();
			$proses_add = $this->db->last_query();
			if($add_transaction){
				$data_detail = array();
				$detail = $data['detail'];	
				foreach ($detail as $row) {
					$_detail['id_transaction'] 	= $insert_id;
					$_detail['id_menu'] 		= $row['id_menu'];
					$_detail['quantity'] 		= $row['quantity'];
					$_detail['total']			= $row['quantity'] * $this->getHargaMenu($row['id_menu']);
					array_push($data_detail, $_detail);
				}

				$add_transaction_detail 		= $this->db->insert_batch('transaction_detail', $data_detail);
				if($add_transaction_detail){
					$_response['error']				= false;
					$_response['status'] 			= "200"; // accepted, but process has not been completed
					$_response['response_code'] 	= "00";
					$_response['message'] 			= "SUCCESS INSERT [ADD TRANSACTION]";
					$data_trx['transaction'] 		= $this->db->get_where('transaction', array('id'=>$insert_id))->row();
					$data_trx['detail_transaction']	= $this->db->get_where('transaction_detail', array('id_transaction'=>$insert_id))->result();
					$_response['data']				= $data_trx;
				}else{
					$this->db->delete('transaction', array('id' => $insert_id));
					$this->db->delete('transaction_detail', array('id_transaction' => $insert_id));
					$_response['error']			= true;
					$_response['status'] 		= "202"; // accepted, but process has not been completed
					$_response['response_code'] = "96";
					$_response['message'] 		= "SYSTEM ERROR - FAILED INSERT [ADD TRANSACTION]";
					$_response['proses_add']	= $proses_add;
					$_response['proses_add_detail'] = $this->db->last_query();
					$_response['insert_id'] 	= $insert_id;
					$_response['data_insert']	= $data_detail;
				}
			}
		}
		return $_response;
	}
	function getTransactionByParam($param){
		if(array_key_exists('id', $param)) unset($param['id']);
		if(array_key_exists('id_employee', $param)) unset($param['id_employee']);
		if(array_key_exists('nomor_meja', $param)) unset($param['nomor_meja']);
		if(array_key_exists('status_bayar', $param)) unset($param['status_bayar']);
		if(array_key_exists('metode_bayar', $param)) unset($param['metode_bayar']);
		if(array_key_exists('del', $param)) unset($param['del']);

		if(
			!isset($param['status_pesanan'])
			&&
			!isset($param['input_title'])
			&&
			!isset($param['nomor_pesanan'])
			){
			$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "FORMAT ERROR: 'nomor_pesanan' OR 'input_time' OR 'nomor_pesanan' MUST BE SENT [GET TRANSACTION BY PARAM]";
		}else{
			$param['del'] = 0;
			$input_time="";
   			if(array_key_exists('input_time', $param)){
     			$input_time = $param['input_time'];
     			unset($param['input_time']);
     		}
			if($input_time!=""){
     			$this->db->like('input_time',$input_time);
     		}
			$get = $this->db->get_where("transaction",$param);
			if(!$get){
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "96";
				$_response['message'] 		= "SYSTEM ERRROR - DATABASE [GET TRANSACTION BY PARAM]";
				$_response['error_data'] 	= $this->db->error();
				$_response['query']			=  $this->db->last_query();
			}else{
				if($get->num_rows()>0){
					$_response['error']			= false;
					$_response['status'] 		= "200"; // accepted, but process has not been completed
					$_response['response_code'] = "00";
					$_response['message'] 		= "SUCCESS INQUIRY [GET TRANSACTION BY PARAM]";
					$_response['data']			= $get->result();
				}else{
					$_response['error']			= true;
					$_response['status'] 		= "202"; // accepted, but process has not been completed
					$_response['response_code'] = "76";
					$_response['message'] 		= "DATA NOT FOUND [GET TRANSACTION BY PARAM]";
				}
			}
		}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}
	function editTransactionByParam($param,$data,$detail){		
		if(array_key_exists('id_employee',$param)) unset($param['id_employee']);
      	if(array_key_exists('status_pesanan',$param)) unset($param['status_pesanan']);
    	if(array_key_exists('status_bayar',$param)) unset($param['status_bayar']);
     	if(array_key_exists('metode_bayar',$param)) unset($param['metode_bayar']);
     	if(array_key_exists('del',$param)) unset($param['del']);

     	if(array_key_exists('id',$data)) unset($param['id']);
     	if(array_key_exists('nomor_meja',$data)) unset($param['nomor_meja']);
     	if(array_key_exists('nomor_pesanan',$data)) unset($param['nomor_pesanan']);
     	if(array_key_exists('status_pesanan',$data)) unset($param['status_pesanan']);
     	if(array_key_exists('input_time',$data)) unset($param['input_time']);
     	if(array_key_exists('del',$data)) unset($param['del']);

     	if(
     		(isset($param['nomor_pesanan']) && !isset($param['input_time'])) 
     		||
     		(!isset($param['nomor_pesanan']) && isset($param['input_time']))
     	){
     		$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "FORMAT ERROR: 'nomor_pesanan' AND 'input_time' MUST BE SENT [DELETE TRANSACTION BY PARAM]";
     	}else if(
     		!isset($param['nomor_pesanan']) && !isset($param['input_time']) && !isset($param['id'])
     	){
     		$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "FORMAT ERROR: ('nomor_pesanan' AND 'input_time') OR ('nomor_pesanan' AND 'input_time' AND 'id') OR ('id') MUST BE SENT [DELETE TRANSACTION BY PARAM]";
     	}else{
			$input_time="";
   			if(array_key_exists('input_time', $param)){
     			$input_time = $param['input_time'];
     			unset($param['input_time']);
     		}
			if($input_time!=""){
     			$this->db->like('input_time',$input_time);
     		}
     		$cek = $this->db->get_where('transaction', $param);
     		if($cek->num_rows()>0){
     			if($data){
					$this->db->set($data);
					$this->db->where($param);
					$this->db->like('input_time',$input_time,'after');
					$update_trx = $this->db->update('transaction');
					if($update_trx){
						$_response_trx['error']				= false;
						$_response_trx['status'] 			= "200"; // accepted, but process has not been completed
						$_response_trx['response_code'] 	= "00";
						$_response_trx['message'] 			= "SUCCESS UPDATE [EDIT TRANSACTION]";
					}else{
						$_response_trx['error']				= true;
						$_response_trx['status'] 			= "202"; // accepted, but process has not been completed
						$_response_trx['response_code'] 	= "96";
						$_response_trx['message'] 			= "SYSTEM ERROR - FAILED UPDATE [EDIT TRANSACTION]";
						$_response_trx['error_data']	 	= $this->db->error();
					}
				}
				
				if($detail){
					$id_trx = (isset($param['id'])) ? $param['id'] : $this->getIdTransaksi($param['nomor_pesanan'],$param['input_time']);
		     		$data_detail = array();
					foreach ($detail as $row) {
						$_detail['id_transaction'] 	= $id_trx;
						$_detail['id_menu'] 		= $row['id_menu'];
						$_detail['quantity'] 		= $row['quantity'];
						$_detail['total']			= $row['quantity'] * $this->getHargaMenu($row['id_menu']);
						array_push($data_detail, $_detail);
					}
					$total_data = count($data_detail);
					$iter_update = 0;
					$update_error = array();
					for ($i=0; $i < $total_data; $i++) { 
						$this->db->set($data_detail[$i]);
						$this->db->where(array('id_transaction'=> $id_trx, 'id_menu' => $data_detail[$i]['id_menu']));
						$update_trx_dtl = $this->db->update('transaction_detail');
						if($update_trx_dtl) $iter_update++; else array_push($update_error, $data_detail[$i]);
					}
					if($iter_update<$total_data){
						$_response_trx_dtl['error']				= true;
						$_response_trx_dtl['status'] 			= "202"; // accepted, but process has not been completed
						$_response_trx_dtl['response_code'] 	= "96";
						$_response_trx_dtl['message'] 			= "SYSTEM ERROR - FAILED UPDATE [EDIT TRANSACTION DETAIL]";
						$_response_trx_dtl['update_data_error'] = $update_error;
					}else{
						$_response_trx_dtl['error']				= false;
						$_response_trx_dtl['status'] 			= "200"; // accepted, but process has not been completed
						$_response_trx_dtl['response_code'] 	= "00";
						$_response_trx_dtl['message'] 			= "SUCCESS UPDATE [EDIT TRANSACTION DETAIL]";
					}
				}
				// $_response_trx_dtl = array();

				$_response['error']			= false;
				$_response['status'] 		= "200"; // accepted, but process has not been completed
				$_response['response_code'] = "00";
				$_response['message'] 		= array("message_transaction" => $_response_trx, "message_transaction_detail" => $_response_trx_dtl);
     			
     		}else{
     			$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "76";
				$_response['message'] 		= "DATA NOT FOUND [EDIT TRANSACTION]";
				$_response['query']			= $this->db->last_query();
     		}
     	}
		
     	$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}
	function delTransactionByParam($param){
        if(array_key_exists('id_employee',$param)) unset($param['id_employee']);
        if(array_key_exists('nomor_meja',$param)) unset($param['nomor_meja']);
      	if(array_key_exists('status_pesanan',$param)) unset($param['status_pesanan']);
    	if(array_key_exists('status_bayar',$param)) unset($param['status_bayar']);
     	if(array_key_exists('metode_bayar',$param)) unset($param['metode_bayar']);
     	if(array_key_exists('del',$param)) unset($param['del']);

     	if(
     		(isset($param['nomor_pesanan']) && !isset($param['input_time'])) 
     		||
     		(!isset($param['nomor_pesanan']) && isset($param['input_time']))
     	){
     		$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "FORMAT ERROR: 'nomor_pesanan' AND 'input_time' MUST BE SENT [DELETE TRANSACTION BY PARAM]";
     	}else if(
     		!isset($param['nomor_pesanan']) && !isset($param['input_time']) && !isset($param['id'])
     	){
     		$_response['error']			= true;
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "76";
			$_response['message'] 		= "FORMAT ERROR: ('nomor_pesanan' AND 'input_time') OR ('nomor_pesanan' AND 'input_time' AND 'id') OR ('id') MUST BE SENT [DELETE TRANSACTION BY PARAM]";
     	}else{
     		$input_time="";
   			if(array_key_exists('input_time', $param)){
     			$input_time = $param['input_time'];
     			unset($param['input_time']);
     		}
     		$param['del'] = 0;
     		$this->db->select("*");
     		$this->db->from("transaction");
     		$this->db->where($param);
     		if($input_time!=""){
     			$this->db->like('input_time',$input_time);
     		}
     		$cek = $this->db->get();
			if($cek->num_rows()==0){
				$_response['error']			= true;
				$_response['status'] 		= "202"; // accepted, but process has not been completed
				$_response['response_code'] = "76";
				$_response['message'] 		= "DATA NOT FOUND [DELETE TRANSACTION DETAIL BY PARAM]";
			}else{
				$this->db->set('del', 1);
				$this->db->where($param);
				$get = $this->db->update("transaction");
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
     	}
		$_response['timestamp']		= date("Y-m-d H:i:s");
		return $_response;
	}

	function getNomorPesanan(){
		$this->db->like('input_time', date('Y-m-d'), 'match');
		$get = $this->db->get('transaction');
		$num_rows = $get->num_rows();
		$id = "P".str_pad(($num_rows+1), 4, '0', STR_PAD_LEFT);
		return $id;
	}
	function getHargaMenu($id){
		$data = $this->db->get_where('menu', array('id' => $id, 'del' => 0));
		$row = $data->row();
		return $row->harga;
	}
	function getIdTransaksi($nomor_pesanan,$input_time){
		$this->db->like('input_time', $input_time,'match');
		$get = $this->db->get_where("transaction", array("nomor_pesanan" => $nomor_pesanan));
		$row = $get->row();
		return $row->id;
	}

	function cekTimeOut($time_start,$time_end){
		if(($time_end - $time_start) >= $this->maxTimeOut) return true; else return false;
	}
	public function model_function($type, $request = null){
		$data = array('editTransaction','getTransaction','getLastTransaction','getUpdateTransaction');

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