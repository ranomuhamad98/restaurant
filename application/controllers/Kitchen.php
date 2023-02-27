<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kitchen extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('SessLogin');   
        $this->load->model('Curl');  
        $this->token = $this->Curl->get_token();    

        if (get_cookie("id") == "") redirect(base_url('login'));
    }

    public function dashboard(){
        $body['token']          = $this->token;
        $body['url']            = $this->Curl->get_url().'/transaction';
        
        $get = json_decode($this->Curl->http_request($body,'','GET'), TRUE);
        if(!$get['error']){
            $data['data'] = json_encode($get['data']);
            $data['info']           = "";
        }else{
            $data['data'] = json_encode(array());
            $data['info']           = $get['response_code'].' '.$get['message'];
        }
        
        $this->load->view('kitchen/dashboard', $data);
    }

    public function list_menu(){
        $body['token']          = $this->token;
        $body['url']            = $this->Curl->get_url().'/menu';
        
        $get = json_decode($this->Curl->http_request($body,'','GET'), TRUE);
        if(!$get['error']){
            $data['data'] = json_encode($get['data']);
            $data['info']           = "";
        }else{
            $data['data'] = json_encode(array());
            $data['info']           = $get['response_code'].' '.$get['message'];
        }
        
        $this->load->view('kitchen/menu', $data);
    }
    public function list_menu_off(){
        $body['token']          = $this->token;

        $where['id']            = $this->uri->segment(2);
        $data['status']         = 0;
        $do['request_name']     = "editMenuByParam";
        $do['where']             = $where;
        $do['data']             = $data;

        $body['url']            = $this->Curl->get_url().'/menu';
        
        $get = json_decode($this->Curl->http_request($body,$do,'POST'), TRUE);
        
        $this->session->set_flashdata('info',$get['response_code'].' '.$get['message']);
        redirect('list_menu');
    }
    public function list_menu_on(){
        $body['token']          = $this->token;

        $where['id']            = (int) $this->uri->segment(2);
        $data['status']         = 1;
        $do['request_name']     = "editMenuByParam";
        $do['where']            = $where;
        $do['data']             = $data;

        $body['url']            = $this->Curl->get_url().'/menu';
        
        $get = json_decode($this->Curl->http_request($body,$do,'POST'), TRUE);
        
        $this->session->set_flashdata('info',$get['response_code'].' '.$get['message']);
        redirect('list_menu');
    }

    public function payment_unpaid(){
        $this->payment_change($this->uri->segment(2),0);
    }
    public function payment_paid(){
        $this->payment_change($this->uri->segment(2),1);
    }
    private function payment_change($id,$status){
        echo "[".$id." ".$status."]";
        $body['token']          = $this->token;

        $where['id']            = $id;
        $data['status_bayar']   = $status;
        $do['request_name']     = "editTransactionByParam";
        $do['where']            = $where;
        $do['data']             = $data;

        $body['url']            = $this->Curl->get_url().'/transaction';
        
        $get = json_decode($this->Curl->http_request($body,$do,'POST'), TRUE);
        $get = $get['message']['message_transaction'];
        $this->session->set_flashdata('info',"[".$get['response_code']."] ".$get['message']);
        redirect('dashboard');
        // echo json_encode($data);/
    }

    public function update_order(){
        /*
        2 = id transaction
        3 = id menu
        4 = status
        */
        $_id_transaction = (int) $this->uri->segment(2);
        $_id_menu = (int) $this->uri->segment(3);
        $_status = $this->uri->segment(4);
        $body['token']          = $this->token;

        $where['id_transaction']= $_id_transaction;
        $where['id_menu']       = $_id_menu;
        $data['status']         = $_status;
        $do['request_name']     = "editTransactionDtlByParam";
        $do['where']            = $where;
        $do['data']             = $data;

        $body['url']            = $this->Curl->get_url().'/transaction_detail';
        $get = json_decode($this->Curl->http_request($body,$do,'POST'), TRUE);

        

        $id = $this->uri->segment(2);
        $body['token']          = $this->token;
        $body['url']            = $this->Curl->get_url().'/transaction_detail/id/'.$_id_transaction;
        $get_data2 = json_decode($this->Curl->http_request($body,'','GET'), TRUE);
        $total_get = count($get_data2['data']);$data_ready=0;
        foreach($get_data2['data'] as $row){
            if($row['status']==2) $data_ready++;
        }
        
        if($total_get==$data_ready){
            $data_get['status_pesanan'] = "ready";
        }else if($data_ready>0||$data_ready<$total_get){
            $data_get['status_pesanan'] = "process";
        }else if($data_ready==0){
            $data_get['status_pesanan'] = "pending";
        }
        $where_get['id']        = $_id_transaction;
        $do['request_name']     = "editTransactionByParam";
        $do['where']            = $where_get;
        $do['data']             = $data_get;

        $body['url']            = $this->Curl->get_url().'/transaction';
        $get_data = json_decode($this->Curl->http_request($body,$do,'POST'), TRUE);
        $_message_get_data = $get_data['message'];

        $_info = "[".$get['response_code'].' '.$get['message']."] ";
        $_info .= "[".$get_data['response_code'].' '.$_message_get_data['message_transaction']['message']."]";
        
        $this->session->set_flashdata('info',$_info);
        redirect('list_order/'.$this->uri->segment(2));
    }

    public function list_order_detail(){
        $id = $this->uri->segment(2);
        $body['token']          = $this->token;
        $body['url']            = $this->Curl->get_url().'/transaction_detail/id/'.$id;
        
        $get = json_decode($this->Curl->http_request($body,'','GET'), TRUE);
        if(!$get['error']){
            $data['data']           = json_encode($get['data']);
            $data['info']           = "";
        }else{
            $data['data']           = json_encode(array());
            $data['info']           = $get['response_code'].' '.$get['message'];
        }

        $this->load->view('kitchen/detail_pesanan', $data);
    }
    public function list_order_update(){

    }
}
?>