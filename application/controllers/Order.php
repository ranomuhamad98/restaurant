<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('SessLogin');   
        $this->load->model('Curl');      
    }

	public function index()
	{	
		// delete_cookie('cart');
		$data = $this->SessLogin->getlogin($this->input->post('username'),$this->input->post('password'));

		$body['url']			= $this->Curl->get_url().'/menu';
		$body['token']			= $this->Curl->get_token();
		
		$get = $this->Curl->http_request($body,'','GET');
		$data['list_menu'] = json_decode($get, TRUE);
		$this->load->view('order/menu', $data);
	}
	public function list_order(){
		// view
		// delete_cookie('cart');
		if(get_cookie('cart')){
			// echo get_cookie('cart');
			//$this->input->post('quantity') && 
			$quantity = $this->input->post('quantity');
			$data_cart = json_decode(get_cookie('cart'), TRUE);
			$temp_data_cart = array();
			$arr_cart = array();
			$i=0;
			foreach ($data_cart as $row) {
				$get = $this->Curl->get_menu($row['id_menu']);
				$get = $get['data'];
				$get['quantity'] = $quantity[$i];
				array_push($arr_cart,$get);
				array_push($temp_data_cart, array("id_menu"=>$row["id_menu"], "quantity"=>$quantity[$i]));
				$i++;
			}
			set_cookie('cart', json_encode($temp_data_cart), 24 * 3600);
			
			$data['data_cart'] = json_encode($arr_cart);
			
			$this->load->view('order/pesanan',$data);
		}else{
			redirect('cart');
		}
	}

	public function do_order(){
		// proses
		if($this->input->post('table_no')){
			$body['token']			= $this->Curl->get_token();

			$data['nomor_meja'] 	= $this->input->post('table_no');
			$data['metode_bayar'] 	= $this->input->post('payment_method');
			$data['detail']			= json_decode(get_cookie('cart'), TRUE);
			$do['request_name'] = "addTransaction";
			$do['data'] = $data;

			$body['url']			= $this->Curl->get_url().'/transaction';
			
			$get = json_decode($this->Curl->http_request($body,$do,'POST'), TRUE);
			if($get['error']){
				$this->session->set_flashdata('info',$get['response_code'].' '.$get['message']);
			}else{
				// echo json_encode($get['data']);
				set_cookie('finish_order', json_encode($get['data']), 24 * 3600);
			}
			// echo json_encode(json_decode(get_cookie('finish_order'), TRUE));
			redirect('finish_order');
		}else{
			redirect(base_url());
		}
		
	}
	public function finish_order(){
		// delete_cookie('finish_order');
		if(!empty(get_cookie('finish_order'))){
			delete_cookie('cart');
			$order = json_decode(get_cookie('finish_order'), TRUE);
			$data['detail'] = $order['detail_transaction'];
			$data['data']  	= $order['transaction'];
			$this->load->view('order/finish', $data);
		}
		else{
			redirect(base_url());
		}
	}
	public function cart(){
		// view
		$arr_cart = array();
		if(get_cookie('cart')){
			$body['token']			= $this->Curl->get_token();
			$cart = json_decode(get_cookie('cart'), TRUE);
			foreach($cart as $row){
				$body['url']			= $this->Curl->get_url().'/menu/id/'.$row["id_menu"];
				$get = json_decode($this->Curl->http_request($body,'','GET'), TRUE);
				$get = $get['data'];
				$get['quantity'] = $row["quantity"];
				array_push($arr_cart,$get);
			}
		}
		$data['data_cart'] = json_encode($arr_cart);
		$this->load->view('order/cart', $data);
	}

	public function del_cart(){
		if($this->uri->segment(2)){
			$id = $this->uri->segment(2);
			if(get_cookie('cart')){
				$data_cart = json_decode(get_cookie('cart'), TRUE);
				$temp_data_cart = array();
				foreach($data_cart as $row){
					if($row['id_menu'] != $id) array_push($temp_data_cart,$row);
				}
				// echo json_encode($temp_data_cart);
				if(count($temp_data_cart)==0){
					delete_cookie('cart');
				}else{
					set_cookie('cart', json_encode($temp_data_cart), 24 * 3600);
				}
			}
		}
		redirect('cart');
	}

	public function do_cart(){
		if ($this->uri->segment(2)) {
			$id = $this->uri->segment(2);
			$data_cart = array();
			if(get_cookie('cart')){
				$data_cart = json_decode(get_cookie('cart'), TRUE);
				$check = 0;
				foreach ($data_cart as $row) {
					if($row["id_menu"]==$id) $check=1;
				}
				if(!$check){
					array_push($data_cart, array("id_menu"=>$id,"quantity"=>1));
					$this->session->set_flashdata('info',"SUCCESS ADD DATA TO CART");
				}else{
					$this->session->set_flashdata('info',"DATA ALREADY EXISTS ON CART");
				}
			}else{
				array_push($data_cart, array("id_menu"=>$id,"quantity"=>1));
			}
			set_cookie('cart', json_encode($data_cart), 24 * 3600);
			redirect(base_url());
		}else{
			redirect('cart');
		}
	}
	public function status(){
		// after do status (view)
		if($this->input->post()){
			$_data['info']	= "";
			$body['token']			= $this->Curl->get_token();

			$data['input_time'] 		= date('Y-m-d');
			$data['nomor_pesanan']		= $this->input->post('transaction_number');
			$do['request_name'] 		= "getTransactionByParam";
			$do['data'] = $data;
			// echo json_encode($do);
			$body['url']			= $this->Curl->get_url().'/transaction';
			$get = json_decode($this->Curl->http_request($body,$do,'POST'), TRUE);	
			if($get['error']){
				$_data['info'] = "[".$get['response_code'].' '.$get['message']."]";
			}else{
				$temp_data = $get['data'][0];
				$_data['data'] = json_encode($temp_data);

				$body['url']			= $this->Curl->get_url().'/transaction_detail/id/'.$temp_data['id'];
				$get = json_decode($this->Curl->http_request($body,'','GET'), TRUE);
				if($get['error']){
					$_data['info'] .= "[".$get['response_code'].' '.$get['message']."]";
				}else{
					$_data['detail'] = json_encode($get['data']);
				}
			}
			// echo json_encode(json_decode(get_cookie('finish_order'), TRUE));
		}else{
			$_data['info'] = "";
			$_data['data'] = "";
			$_data['detail'] = "";
		}
		$this->load->view('order/status',$_data);
	}
	// public function do_status(){
	// 	// proses
	// }
}
