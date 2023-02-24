<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Auth_model extends CI_Model {

	private $response;

	function get_token(){
		$char = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890._"; //!@#%
        $token = '';
        for ($i = 0; $i < 166; $i++) $token .= $char[(rand() % strlen($char))];

       	return $token;
	}

	function url(){
	  return sprintf(
	    "%s://%s%s",
	    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
	    $_SERVER['SERVER_NAME'],
	    $_SERVER['REQUEST_URI']
	  );
	}
	function base_url(){
	  return sprintf("%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'],""
	  );
	}

	public function getAuth($request){
		$header = $this->input->request_headers();
		if($header['system-key']==SYSTEM_ACCESS){
			if(!empty($request)){
				// $request = json_decode(file_get_contents('php://input'),true);

				$update = 0;
			 	$_where = array(
						 	"username" 			=> $request["username"],
						 	"password" 			=> md5($request["password"])
						 );

				$query = $this->db->get_where("auth", $_where);
				if($query->num_rows() > 0){
					$row = $query->row();

					if(date('Y-m-d H:i:s') > $row->session_time){
						$token = $this->get_token();
						$update = 1;
					}else{
						$token = $row->token;
					}
				}else{
					$this->response['status'] =  401;
					$this->response['message'] = "Bad Request [Invalid Credential]";

					$result['Result'] = $this->response;

					return $result;
				}

				if($update){
				 	$this->db->where("username", $request["username"]);
				 	$this->db->where("password", md5($request["password"]));
					$this->db->update("auth", array(
													"session_time" => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 day')),
													"token" => $token 
												));
				}

				$this->response['status'] 			=  "200";
				$this->response['kode_response'] 	= "00";
				$this->response['message'] 			= "Request Berhasil";
				$this->response['token'] 			= $token;

			}else{
				$this->response['status'] 	=  500;
				$this->response['message'] 	= "Data get is empty";
			}
			return $this->response;
		}else{
			return $this->model_function('not-permitted');
		}
	}

	public function check_token($token){
		// check token dulu
		$this->db->select("*");
		$this->db->from("auth");
		$this->db->where('token', $token);
		$query 			= $this->db->get();
		if($query->num_rows() > 0){
			$row 		= $query->row();

			if(date('Y-m-d H:i:s') > $row->session_time){
				$this->idclient =
				$_response['error']			= true;
				$_response['status'] 		= "401"; // 498 invalid token
				$_response['response_code'] = "58";
				$_response['message'] 		= "TOKEN EXPIRED";
				$_response['timestamp']		= date("Y-m-d H:i:s");
				return array(1,$_response);
			}else{
				return array(0,'');
			}
		}else{
			$_response['error']			= true;
			$_response['status'] 		= "401"; // 498 invalid token
			$_response['response_code'] = "55";
			$_response['message'] 		= "INVALID SIGNATURE/INVALID TOKEN";
			$_response['timestamp']		= date("Y-m-d H:i:s");
			return array(1,$_response);
		}
	}

	public function check_auth($header){
		if($header['system-key']==SYSTEM_ACCESS){
			$authKey = $header["Authorization"];
			$authKey = str_replace("Bearer ", "", $authKey);
			if(!$authKey){
				$_response['error']			= true;
				$_response['status'] 		= "401"; // 499 token required
				$_response['response_code'] = "55";
				$_response['message'] 		= "TOKEN REQUIRED";
				$_response['timestamp']		= date("Y-m-d H:i:s");
				return array(1,$_response);
			}else{
				$auth = $this->check_token($authKey);
				if($auth[0]) return array(1,$auth[1]);
				else return array(0,'');
			}
		}else{
			$_response['error']			= true;
			$_response['status'] 		= "201"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "YOU CAN'T ACCESS THIS SYSTEM";
			$_response['timestamp']		= date("Y-m-d H:i:s");
			return array(1,$_response);
		}
	}

	public function model_function($type, $request = null){
		$data = array('editAuth','getAuthbyID','getAuth','getLastAuth','getUpdateAuth');

		$_response['error']				= true;
		if(in_array($type, $data)){
			$_response['status'] 		= "202"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "DECLINE SYSTEM MALFUNCTION [FUNCTION NOT AVALIABLE]";
		}else if($type=="not-permitted"){
			$_response['status'] 		= "201"; // accepted, but process has not been completed
			$_response['response_code'] = "96";
			$_response['message'] 		= "YOU CAN'T ACCESS THIS SYSTEM";
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