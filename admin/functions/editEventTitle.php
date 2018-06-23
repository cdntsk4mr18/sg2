<?php
require_once('../../config.php');
if (isset($_POST['delete']) && isset($_POST['id'])){
	
	
	$id = $_POST['id'];
	
	$sql = "DELETE FROM events WHERE id = $id";
	$query = $db->prepare( $sql );
	if ($query == false) {
	 print_r($db->errorInfo());
	 die ('Erreur prepare');
	}
	$res = $query->execute();
	if ($res == false) {
	 print_r($query->errorInfo());
	 die ('Erreur execute');
	}
	
}elseif (isset($_POST['title']) && isset($_POST['color']) && isset($_POST['id'])){
	
	$id = $_POST['id'];
	$title = $_POST['title'];
	$time = $_POST['time'];
	$color = $_POST['color'];
	
	$sql = "UPDATE events SET  title = '$title', time = '$time', color = '$color' WHERE id = $id ";

	
	$query = $db->prepare( $sql );
	if ($query == false) {
	 print_r($db->errorInfo());
	 die ('Erreur prepare');
	}
	$sth = $query->execute();
	if ($sth == false) {
	 print_r($query->errorInfo());
	 die ('Erreur execute');
	}

}

if(isset($_POST['send_sms']) && isset($_POST['id'])){ 

	$id = $_POST['id'];

	//get event

	$sql = "SELECT id, title, start, end, time, color FROM events WHERE id = '$id'";

	$req = $db->prepare($sql);
	$req->execute();

	$events = $req->fetchAll();

	foreach($events as $event){
		$title = $event['title'];
		$time = $event['time'];
		$start = $event['start'];
		$end = $event['end'];
	}	

		
	//get user numbers
	$sql_2 = "SELECT mobile_number FROM users";
	$req = $db->prepare($sql_2);
	$req->execute();
	$user_number = $req->fetchAll();


	//new formats
	$s_date=date('Y-n-j', strtotime($start));
	$e_date = date('Y-n-j', strtotime($end));
	$new_time= date('h:i a', strtotime($time));

//loop all user numbers
		foreach($user_number as $user_numbers): 
					
						
		$number=$user_numbers['mobile_number'];
		$api="TR-JOHNJ479456_2C3FN";
		$text = "Event Reminder"."\n".
				"From: CSAB"."\n".
				"What: ".$title."\n".
				"When: ".$s_date." to ".$e_date."\n".
				"Time: ".$new_time."\n".
				"SEE YOU!";

			        $result_text = itexmo($number,$text,$api);

		        	if ($result_text == ""){
						print_r ("iTexMo: No response from server!!!
						Please check the METHOD used (CURL or CURL-LESS). If you are using CURL then try CURL-LESS and vice versa.	
						Please CONTACT US for help. ");	
						}else if ($result_text == 0){
						print_r ("Message Sent!");
						}
						else{	
						print_r ("Error Num ". $result_text . " was encountered!");
						}
		endforeach;

}


//##########################################################################
// ITEXMO SEND SMS API - PHP - CURL-LESS METHOD
// Visit www.itexmo.com/developers.php for more info about this API
//##########################################################################

//##########################################################################

/*function itexmo($number,$message,$apicode){
$url = 'https://www.itexmo.com/php_api/api.php';
$itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
$param = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($itexmo),
    ),
);
$context  = stream_context_create($param);
return file_get_contents($url, false, $context);}*/


function itexmo($number,$message,$apicode){
			$ch = curl_init();
			$itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
			curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			 curl_setopt($ch, CURLOPT_POSTFIELDS, 
			          http_build_query($itexmo));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			return curl_exec ($ch);
			curl_close ($ch);
}

header('Location: ../events.php');

	
?>
