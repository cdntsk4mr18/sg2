<?php
/**
 * This example shows settings to use when sending via Google's Gmail servers.
 * This uses traditional id & password authentication - look at the gmail_xoauth.phps
 * example to see how to use XOAUTH2.
 * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
 */

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
    );

//Tell PHPMailer to use SMTP
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;

//Set the hostname of the mail server
$mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6

//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 465;

//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'ssl';

//Whether to use SMTP authentication
$mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "suggestions.csab.sg@gmail.com";

//Password to use for SMTP authentication
$mail->Password = "csab12345";

//Set who the message is to be sent from
$mail->setFrom('suggestions.csab.sg@gmail.com', 'csab-sg');

//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');

//Set who the message is to be sent to
$mail->addAddress('suggestions.csab.sg@gmail.com', 'csab-sg.ph');

//Set the subject line
$mail->Subject = '[CSAB-SG SUGGESTIONS]New suggestion from '.$_POST['fullName'];

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
    $name=$_POST['fullName'];
    $number=$_POST['phone'];
    $email=$_POST['email'];
    $comment=$_POST['comment'];


        $content = str_replace(
        array('%name%','%number%','%email%','%comment%'),
        array($name,$number,$email,$comment),
        file_get_contents('../etemp/email-template.html')
    );


$mail->msgHTML($content, dirname(__FILE__));



//$mail->msgHTML(file_get_contents('contents.html'), __DIR__);




//$mail->Body = 'This is the HTML message body <b>in bold!</b>';

//Replace the plain text body with one created manually
$mail->AltBody =    'Name:'.$name.
                    'Number:'.$number.
                    'Email'.$email.
                    'Comment:'.$comment;

//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    //echo "Mailer Error: " . $mail->ErrorInfo;
    $response['status']="error";
    $response['message']='Message Failed! Error Status:'.$mail->ErrorInfo;
    echo json_encode($response);
    
} else {
    //echo "Message sent!";
    //Section 2: IMAP
    //Uncomment these to save your message in the 'Sent Mail' folder.
    #if (save_mail($mail)) {
    #    echo "Message saved!";
    #}
    sent_to_client($_POST['email'],$_POST['fullName']);

    $response['status']="success";
    $response['message']="Message Sent";
    echo json_encode($response);
	header("Location: ../secret.php?doaction=messagesent#suggestions");
}
//$response['status']="error";

//Section 2: IMAP
//IMAP commands requires the PHP IMAP Extension, found at: https://php.net/manual/en/imap.setup.php
//Function to call which uses the PHP imap_*() functions to save messages: https://php.net/manual/en/book.imap.php
//You can use imap_getmailboxes($imapStream, '/imap/ssl') to get a list of available folders or labels, this can
//be useful if you are trying to get this working on a non-Gmail IMAP server.
function save_mail($mail)
{
    //You can change 'Sent Mail' to any other folder or tag
    $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";

    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
    $imapStream = imap_open($path, $mail->Username, $mail->Password);

    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    imap_close($imapStream);

    return $result;
}

function sent_to_client($client_mail,$client_name){


        $mail_client = new PHPMailer;
        $mail_client->isSMTP();
        $mail_client->SMTPDebug = 0;
        $mail_client->Host = 'smtp.gmail.com';
        $mail_client->Port = 465;
        $mail_client->SMTPSecure = 'ssl';
        $mail_client->SMTPAuth = true;
        $mail_client->Username = "suggestions.csab.sg@gmail.com";
        $mail_client->Password = "csab12345";
        $mail_client->setFrom('suggestions.csab.sg@gmail.com', 'csab-sg');
        $mail_client->addAddress($client_mail, $client_mail);
        $mail_client->Subject = 'csab-sg';

    /*    $name_client=$client_name;

            $content = str_replace(
            array('%name%','%number%','%email%','%comment%'),
            array($name,$number,$email,$comment),
            file_get_contents('../pages/email-template.html')
        );


            $mail->msgHTML($content, dirname(__FILE__));*/

            $mail_client->Body = 'Thank you '.$client_name.' for your suggestion.';


            /*$mail->AltBody =    'Name:'.$name.
                                'Number:'.$number.
                                'Email'.$email.
                                'Comment:'.$comment;*/


            if (!$mail_client->send()) {

                return false;
                
            } else {


                return true;
            }
    

}