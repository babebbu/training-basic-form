<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

// Retrieve Data From HTTP POST
[
  'firstname' => $firstname, 
  'lastname' => $lastname,
  'phone' => $phone,
  'email' => $email,
  'position' => $position,
] = $_POST;

// Test Displaying Data
/*
if($_POST != null) {
  echo $firstname . "<br>";
  echo $lastname . "<br>";
  echo $email . "<br>";
  echo $phone . "<br>";
}
*/
//var_dump($_FILES);

$imageUrl = saveFile($_FILES['cv']);

echo "<br><br>ImageURL = ";
var_dump($imageUrl);
echo "<br><br>";
if ($imageUrl != null) {

  // Sending e-mail to webmaster
  //sendToWebmaster($firstname, $lastname, $phone, $email);
  //sendToApplicant($firstname, $lastname, $phone, $email);

  echo "Inserting";
  insertRow($firstname, $lastname, $phone, $email, $imageUrl);
}

// Validate
$lengthOfPhoneNumberInput = strlen($phone);
if ($lengthOfPhoneNumberInput > 10) {
  echo "กรอกเกิน ไปกรอกใหม่";
}

function insertRow($firstname, $lastname, $phone, $email, $imageUrl) {

  $DB_HOST = 'localhost';
  $DB_USER = 'root';
  $DB_PASSWORD = '';
  $DB_NAME = 'applicant';
  $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

  // Sanitize Request
  $firstname = $mysqli->real_escape_string($firstname);
  $lastname = $mysqli->real_escape_string($lastname);
  $phone = $mysqli->real_escape_string($phone);
  $email = $mysqli->real_escape_string($email);

  var_dump($mysqli);

  // Query
  $result = $mysqli->query("
    INSERT INTO `applicants` (
      `id`, `firstname`, `lastname`, `phone`, `email`, `attachment`
    ) VALUES (
      NULL, '$firstname', '$lastname', '$phone', '$email', '$imageUrl'
    )
  ");

  var_dump($result);
}

function saveFile($file) {
  echo 'saving file...<br>';
  $targetDirectory = "uploads/";

  // Set Flag
  $uploadOk = 1;

  $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

  //$targetFile = $targetDirectory . basename($file["name"]);
  $targetFile = $targetDirectory . time() . '.' . $imageFileType;

  var_dump($targetFile); echo "<br>";

  
  $image = toImage($file);
  if($image !== false) {
    echo "File is an image - " . $image["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }

  // Check if file already exists
  if (file_exists($targetFile)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }

  // Check file size
  if ($file["size"] > 50000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }

var_dump($imageFileType);

  $allowFileTypes = [
    "jpg", "png", "jpeg", "gif", "pdf"
  ];

  // Allow certain file formats
  if(!in_array($imageFileType, $allowFileTypes)) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk) {
    // if everything is ok, try to upload file
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
      echo "The file ". htmlspecialchars( basename( $file["name"])). " has been uploaded.";
      return $targetFile;
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  } else {
    echo "Sorry, your file was not uploaded.";
  }

}

function toImage($file) {
  return getimagesize($file["tmp_name"]);
}


function sendToWebmaster($firstname, $lastname, $phone, $email) {
  try {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'example.user@gmail.com';                     //SMTP username
    $mail->Password   = 'secret';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');
  
    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
  
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'New Resume/CV from the website';
    $mail->Body    = "Hi Webmaster,<br> I'm <b>$firstname</b> <b>$lastname</b>. Please contact me at <u>$phone</u> or <u>$email</u>.";
    $mail->AltBody = "Hi Webmaster,\n I'm, $firstname $lastname. plase contact me at $phone or $email";
  
    $mail->send();
    echo 'Message has been sent';
  } catch(Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}

function sendToApplicant($firstname, $lastname, $phone, $email) {
  try {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'example.user@gmail.com';                     //SMTP username
    $mail->Password   = 'secret';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');
  
    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
  
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'New Resume/CV from the website';
    $mail->Body    = "Hi Webmaster,<br> I'm <b>$firstname</b> <b>$lastname</b>. Please contact me at <u>$phone</u> or <u>$email</u>.";
    $mail->AltBody = "Hi Webmaster,\n I'm, $firstname $lastname. plase contact me at $phone or $email";
  
    $mail->send();
    echo 'Message has been sent';
  } catch(Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}