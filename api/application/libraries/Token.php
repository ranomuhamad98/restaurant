<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Token {

        public function token_get()
        {
                // $tokenData = array();
                // $tokenData['id'] = 1;        
                // $output['token'] = AUTHORIZATION::generateToken($tokenData);
                // $this->set_response($output, REST_Controller::HTTP_OK); // <--

        	$char = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890._"; //!@#%
                $token = '';
                for ($i = 0; $i < 166; $i++) $token .= $char[(rand() % strlen($char))];

	       return $token;

               // return 'hjVFsCeMzMbd5hAo2zMGB8WDRZnLEDaVW4znHO7ezisEA01CzNh9WcML98vNMGHGKe2p1aEAs4d14eEE0VNW5yGeH90rQI6yX8YYiAwLEJMJYpmYi7TnGxClHCMwjS2e._aizG2coPWkcghuo8R2Grom28Sl.Vz.UKhrp';
        }
}