<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Common{

	/**
	 * Constructor
	 *
	 * Get instance for Database Lib
	 *
	 * @access	public
	 */
	function __construct()
	{
		$this->CI =& get_instance();

		log_message('debug', "User Class Initialized");
	}

	// --------------------------------------------------------------------

	/**
	 * checkRecord
	 *
	 * @access	public
	 * @param	string	the username
	 * @param	string	what row to grab
	 * @return	string	the data
	 */
	function checkrecord($tname, $field, $val){
		$query = $this->CI->db->get_where($tname,array($field => $val),1,0);
		if($query->num_rows() >= 1){
			return true;
		}
		else{
			return false;
		}
	}
	
	function checkRecordId($tname, $field1, $val1, $field2, $val2){
		$query = $this->CI->db->get_where($tname,array($field1 => $val1, $field2 => $val2),1,0);
		if($query->num_rows() >= 1){
			return true;
		}
		else{
			return false;
		}
	}
	
	function generateRandomString($length = 6) {
    $characters = '0123456789iWantUabcdefghijklmnopqrstuvwxyzTodayABCDEFGHIJKLMNOPQRSTUVWXYZTechugo';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
	}
	
	function do_upload($locationDir,$key,$user_id){
		$resultArr = array();
		$resultArr['name'] = '';
		if (!empty($_FILES[$key])){
			$myFile = $_FILES[$key];
			$type = $myFile["type"];
			$types = explode('/',$type);
			$filetype =  $types[1]; 
			$target_dir = dirname($_SERVER["SCRIPT_FILENAME"])."/".$locationDir.'/';
				
			$target_file = $target_dir . basename($myFile["name"]);
			
			// ensure a safe filename
			//$name = $user_id.uniqid().'.'.$filetype;
			$name = basename($myFile["name"]);
			//$name = uniqid();
			// preserve file from temporary directory
			$success = move_uploaded_file($myFile["tmp_name"],"$target_dir .$name");
			
			if (!$success) {
					$resultArr['message'] = "An error occured!,please try again.";
			}
			else{
					$resultArr['message'] = "1";
					$resultArr['name'] = $name;
			}
		}
		else{
			$resultArr['message'] = "File not exist.";
		}
				return $resultArr;
	}

	/************************** pUSH nOTIFICATION **********************/
	
	public function notification1($device_type = null, $token_id = null, $notiData = null)

		{
			// /print_r($device_type);die();
			$url = "https://fcm.googleapis.com/fcm/send";
			$server_key = "AAAAnx89b8U:APA91bFpFRYNKbjmLsW_SXBfjmlyEbPQwsdFCo7w-4NJ1cbQ6VSEpdJpQ-VVLC2L140fVI5Bzy31Po4k9V6YVomHOHqWs7rw7u1WdJxIdQMwDlD7H2mzXJzBE750oXde5SOz6NqTZTIw";


			if ($device_type == 1 || $device_type == 'android') { //android

				$resgistrationIDs = $token_id;


			//~ $fields = array(
			//~ 'to' => $resgistrationIDs,
			//~ 'notification' => array(
			//~ 'title' => $notiData['title'],
			//~ 'body' => $notiData['message'],
			//~ 'type' => $notiData['type'],
			//~ 'sound' => 'default',
			//~ 'data' => $notiData['data']
			//~ ),
			//~ 'priority' => 'high'
			//~ );
			
			$fields = array (
			'to' => $resgistrationIDs,
			'data' => array(
			"body" => $notiData['message'],
			"title" => $notiData['title'],
			"icon" => "myicon",
			"key_data"=>$notiData['data'],
			"notificationType"=>$notiData['type']
			),
			'priority' => 'high'
			);


			//print_r(json_encode($fields));die;
			//CURL request to route notification to FCM connection server (provided by Google)	
			$headers = array(
			'Content-Type:application/json',
			'Authorization:key=' . $server_key
			);
			//CURL request to route notification to FCM connection server (provided by Google)	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch); 
			//print_r($result);
			if ($result == FALSE) {
			return 0;
			} else {
			return 1;
			}
			curl_close($ch);
			} else { //IOS

			$resgistrationIDs = $token_id;

			$fields = array(
			'to' => $resgistrationIDs,
			'notification' => array(
			"body" => $notiData['message'],
			"title" => $notiData['title'],
			"icon" => "myicon",
			"key_data"=>$notiData['data'],
			"notificationType"=>$notiData['type'],
			"sound" => 'NotificationTone.wav'
			),
			'sound' => 'NotificationTone.wav',
			'priority' => 'high'
			);

			//CURL request to route notification to FCM connection server (provided by Google)	
			$headers = array(
			'Content-Type:application/json',
			'Authorization:key=' . $server_key
			);
			//CURL request to route notification to FCM connection server (provided by Google)	
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch); 
			if ($result == FALSE) {
			return 0;
			} else {
			return 1;
			}
			curl_close($ch);
			}
		}

}
// END Common Class
