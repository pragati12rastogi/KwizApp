<?php 

namespace App\Custom;
use App\Model\Employee;

class RestApi{
	
	public static function getApiAccessKey(){
		
		$api_access_key = '';
		return $api_access_key;
	}
	
	public static function sendData($notification,$data,$token){

		$api_access_key = RestApi::getApiAccessKey();
		$registrationIds = $token; 
		
		$headers = array
		(
			'Authorization: key=' .$api_access_key,
			'Content-Type: application/json'
		);

		$fields = array
		(
			'to'  => $registrationIds,
			'notification'      => $notification,
			'data'              => $data
		);
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, '' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );

		curl_close( $ch );

		return json_decode($result,true);
	}
}

?>
