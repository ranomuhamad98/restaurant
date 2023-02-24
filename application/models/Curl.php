<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Curl extends CI_Model {
	

	public function __construct() {
        parent::__construct();
        
        $this->url = 'http://localhost:8081/restaurant/api';
    }
    function get_url(){
    	return $this->url;
    }

	function get_token(){
		$username = "msorderm01";
		$password = "pass_msorderm01";
		$body['url'] = $this->url."/index.php/auth?username=".$username."&password=".$password;
		$get = $this->http_request($body,'','GET');
		$result = json_decode($get, TRUE);		
		return $result['token'];
	}
	function get_menu($id){
		$body['token']			= $this->get_token();
		$body['url']			= $this->get_url().'/menu/id/'.$id;
		$get = json_decode($this->http_request($body,'','GET'), TRUE);
		return $get;
	}
	function http_request($body,$data=null,$method){
	    // persiapkan curl
	    $ch = curl_init(); 

	    // set url 
	    curl_setopt($ch, CURLOPT_URL, $body['url']);

	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); //timeout in seconds

	    // return the transfer as a string 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	    
		$headers = array(
			"Accept: */*",
			"Content-type: application/json",
			"system-key: ranorano"
		);
		if(array_key_exists('token', $body)) array_push($headers, "Authorization: Bearer ".$body['token']);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if(strtoupper($method)=="GET"){
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
		}else if($method=="POST"){
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}
		
		// curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 1);
		// curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

	    // $output contains the output string 
	    $output = curl_exec($ch); 
	    // echo $output;

	    // tutup curl 
	    curl_close($ch);      

	    // mengembalikan hasil curl
	    return $output;
	}

}