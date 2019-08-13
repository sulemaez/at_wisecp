<?php
    class AFRICASTALKINGAPI{
        public $api_token = NULL;
        public $short_code = "";
        public $username = "";
        public $error     = NULL;
       
       
        public function __construct(){

        }

        public function set_credentials($api_token='',$username='',$shortcode = ""){
            $this->api_token  = $api_token;
            $this->username = $username;
            $this->short_code = $shortcode;
        }


        public function Submit($title = NULL,$message = NULL,$number = 0){
          $url = "https://api.africastalking.com/version1/messaging";
           
           $numbers = "";
           if(is_array($number)){
              foreach($number as $n){
                 $numbers .= $n.",";
               }
               $numbers = rtrim($numbers,',');
           }else{
              $numbers = $number;
           } 
           
           $data = [
                'to'      => $numbers,
                'message'     => $message,
                'username' => $this->username,
            ];

           if($this->short_code != ""){
              $data['from'] = $this->short_code;
           }

         
            $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\napiKey: ".$this->api_token." \r\nAccept: application/json",
                        'method'  => 'POST',
                        'content' => http_build_query($data),
                    )
                );
                
            
            $context  = stream_context_create($options); 
            $result = file_get_contents($url, false, $context);
            
            if($result === FALSE){
                $this->error = $result;
                return false;
            }else{
                return true;
            }
        }

        public function Balance(){
               $url = "https://api.africastalking.com/version1/user?username=".$this->username;
 
                $options = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\napiKey: ".$this->api_token." \r\nAccept: application/json",
                        'method'  => 'GET',
                    )
                );
                
                $context  = stream_context_create($options);
                
                $result = file_get_contents($url, false, $context);

                if ($result === FALSE) { 
                    $this->error = "Could not fetch balance ! ";
                    return false;
                }

                $res = json_decode($result,false);
                return $res->UserData->balance; 
               
        }

        function ReportLook($rid){
            return false;
        }
        
         function get_prices(){
            return false;
        }
}