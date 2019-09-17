<?php 

//Settings 
$max_allowed_file_size = 10000000; // size in KB 
$allowed_extensions = array("jpg", "jpeg", "gif", "bmp");
$upload_folder = 'uploads/'; //<-- this folder must be writeable by the script
$your_email = 'dreamdev9288@gmail.com';//<<--  update this to your email address

$errors ='';

// echo "starting";

require_once('PHPMailer/PHPMailer.php');
require_once('PHPMailer/Exception.php');
require_once('PHPMailer/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
	$mail = new PHPMailer();

	$mail->SMTPDebug = 4; 
	$mail->IsSMTP();
	$mail->Host = "smtp.sendgrid.net";

	$mail->SMTPAuth = false;
	$mail->SMTPAutoTLS = false; 
	$mail->Port = 25; 

	// optional
	// used only when SMTP requires authentication  
	$mail->SMTPAuth = true;
	$mail->Username = 'apikey';
	$mail->Password = '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';

	$mail->SetFrom($your_email, 'Star');
	$mail->Subject   = 'Message Subject';
	$mail->Body      = 'aaaaa';
	$mail->AddAddress( 'shilianxin1995@163.com' );

	for($i = 0; $i < count($_FILES['files']['name']); $i++) {
	    $filetmp = $_FILES["files"]["tmp_name"][$i];
	    $filename = $_FILES["files"]["name"][$i];
	    $filetype = $_FILES["files"]["type"][$i];
	    $path_of_uploaded_file = $upload_folder . $filename;

	    // echo $path_of_uploaded_file;

	    move_uploaded_file($filetmp,$path_of_uploaded_file);		

	    $mail->AddAttachment( $path_of_uploaded_file , $filename );		    
	}

	$res = $mail->Send();

	if ($res) {
		header('Location: thank-you.html');
	}


	echo "send mail failed:".$mail->ErrorInfo;
	return;

}

///////////////////////////Functions/////////////////
// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
	<title>File upload form</title>
<!-- define some style elements-->
<style>
label,a, body 
{
	font-family : Arial, Helvetica, sans-serif;
	font-size : 12px; 
}

</style>	
<!-- a helper script for vaidating the form-->
<script language="JavaScript" src="scripts/gen_validatorv31.js" type="text/javascript"></script>	
</head>

<body>
<?php
if(!empty($errors))
{
	echo nl2br($errors);
}
?>
<form method="POST" name="email_form_with_php" 
action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data"> 
<p>
<label for='message'>Message:</label> <br>
<input type="text" name="name" value="Super">
<input type="text" name="email" value="dreamdev9288@gmail.com">
<textarea name="message"></textarea>
</p>
<p>
<label for='uploaded_file'>Select A File To Upload:</label> <br>
<input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
</p>
<input type="submit" value="Submit" name='submit'>
</form>
<script language="JavaScript">
// Code for validating the form
// Visit http://www.javascript-coder.com/html-form/javascript-form-validation.phtml
// for details
var frmvalidator  = new Validator("email_form_with_php");
</script>
<noscript>
<small><a href='http://www.html-form-guide.com/email-form/php-email-form-attachment.html'
>How to attach file to email in PHP</a> article page.</small>
</noscript>

</body>
</html>