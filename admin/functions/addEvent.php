<?php

// Connexion à la base de données
require_once('../../config.php');
//echo $_POST['title'];
if (isset($_POST['title']) && isset($_POST['start']) && isset($_POST['end']) && isset($_POST['color'])){
	
	$title = $_POST['title'];
	$start = $_POST['start'];
	$end = $_POST['end'];
	$time = $_POST['time'];	
	$color = $_POST['color'];


	$sql = "INSERT INTO events(title, start, end, time, color) values ('$title', '$start', '$end', '$time', '$color')";
	//$req = $bdd->prepare($sql);
	//$req->execute();
	
	echo $sql;
	
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




$sql_2 = "SELECT mobile_number FROM users";
//$result_2 = $db->prepare( $sql_2 );

$req = $db->prepare($sql_2);
$req->execute();

$user_number = $req->fetchAll();



foreach($user_number as $user_numbers): 
			
				
		$number=$user_numbers['mobile_number'];
		$api="TR-JRVAL728059_QKJYA";
		$text = "What:".$title."\n".
			    "When:".$start."-".$end."\n".
			    "Time:".$time;

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

function itexmo($number,$message,$apicode){
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
return file_get_contents($url, false, $context);}

header('Location: '.$_SERVER['HTTP_REFERER']);

	
?>
