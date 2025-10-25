<?php
$my_email = "admin@simplisend.in";
$continue = "admin@simplisend.in";
$errors = array();
// Remove $_COOKIE elements from $_REQUEST.
if(count($_COOKIE)){foreach(array_keys($_COOKIE) as $value){unset($_REQUEST[$value]);}}
// Check all fields for an email header.
function recursive_array_check_header($element_value)
{
global $set;
if(!is_array($element_value)){if(preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i",$element_value)){$set = 1;}}
else
{
foreach($element_value as $value){if($set){break;} recursive_array_check_header($value);}
}
}
recursive_array_check_header($_REQUEST);
if($set){$errors[] = "You cannot send an email header";}
unset($set);
// Validate email field.
if(isset($_REQUEST['email']) && !empty($_REQUEST['email']))
{
if(preg_match("/(%0A|%0D|\n+|\r+|:)/i",$_REQUEST['email'])){$errors[] = "Email address may not contain a new line or a colon";}
$_REQUEST['email'] = trim($_REQUEST['email']);
if(substr_count($_REQUEST['email'],"@") != 1 || stristr($_REQUEST['email']," ")){$errors[] = "Email address is invalid";}else{$exploded_email = explode("@",$_REQUEST['email']);if(empty($exploded_email[0]) || strlen($exploded_email[0]) > 64 || empty($exploded_email[1])){$errors[] = "Email address is invalid";}else{if(substr_count($exploded_email[1],".") == 0){$errors[] = "Email address is invalid";}else{$exploded_domain = explode(".",$exploded_email[1]);if(in_array("",$exploded_domain)){$errors[] = "Email address is invalid";}else{foreach($exploded_domain as $value){if(strlen($value) > 63 || !preg_match('/^[a-z0-9-]+$/i',$value)){$errors[] = "Email address is invalid"; break;}}}}}}
}
// Check referrer is from same site.
if(!(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))){$errors[] = "You must enable referrer logging to use the form";}
// Check for a blank form.
function recursive_array_check_blank($element_value)
{
global $set;
if(!is_array($element_value)){if(!empty($element_value)){$set = 1;}}
else
{
foreach($element_value as $value){if($set){break;} recursive_array_check_blank($value);}
}
}
recursive_array_check_blank($_REQUEST);
if(!$set){$errors[] = "You cannot send a blank form";}
unset($set);
// Display any errors and exit if errors exist.
if(count($errors)){foreach($errors as $value){print "$value<br>";} exit;}
if(!defined("PHP_EOL")){define("PHP_EOL", strtoupper(substr(PHP_OS,0,3) == "WIN") ? "\r\n" : "\n");}
// Build message.
function build_message($request_input){if(!isset($message_output)){$message_output ="";}if(!is_array($request_input)){$message_output = $request_input;}else{foreach($request_input as $key => $value){if(!empty($value)){if(!is_numeric($key)){$message_output .= str_replace("_"," ",ucfirst($key)).": ".build_message($value).PHP_EOL.PHP_EOL;}else{$message_output .= build_message($value).", ";}}}}return rtrim($message_output,", ");}
$message = build_message($_REQUEST);
$message = $message . PHP_EOL.PHP_EOL."-- ".PHP_EOL."";
$message = stripslashes($message);
$subject = "Simplisend Logistics";
$headers = "From: " . $_REQUEST['Name'];
mail($my_email,$subject,$message,$headers);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Simplisend Logistics</title>
<link rel=" icon" href="images/faveicon.png" type="image/x-icon">
<link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
    rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

<style>
  body{background-color: #232222;}
.form-container {width: 324px; text-align: center; color:##232222;; padding: 50px 0px; margin: auto;display:block; }


svg {
  width: 110px;
  display: block;
  margin: 40px auto 0;
  margin-bottom: 9px;
 color:  #ffff; 
}

.path {
  stroke-dasharray: 1000;
  stroke-dashoffset: 0;
  &.circle {
    -webkit-animation: dash .9s ease-in-out;
    animation: dash .9s ease-in-out;
  }
  &.line {
    stroke-dashoffset: 1000;
    -webkit-animation: dash .9s .35s ease-in-out forwards;
    animation: dash .9s .35s ease-in-out forwards;
  }
  &.check {
    stroke-dashoffset: -100;
    -webkit-animation: dash-check .9s .35s ease-in-out forwards;
    animation: dash-check .9s .35s ease-in-out forwards;
  }
}

p {
  text-align: center;
  margin: 20px 0 60px;
  font-size: 1.25em;
  &.success {
    color: #73AF55;
  }
  &.error {
    color: #D06079;
  }
}


@-webkit-keyframes dash {
  0% {
    stroke-dashoffset: 1000;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

@keyframes dash {
  0% {
    stroke-dashoffset: 1000;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

@-webkit-keyframes dash-check {
  0% {
    stroke-dashoffset: -100;
  }
  100% {
    stroke-dashoffset: 900;
  }
}

@keyframes dash-check {
  0% {
    stroke-dashoffset: -100;
  }
  100% {
    stroke-dashoffset: 900;
  }
}
.appointment-button {
  position: relative;
  text-align: center;
  margin-top: 0px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #2A2D31;
  background-color: #D9DDE0;
  letter-spacing: 1px;
  margin: auto;
}

.appointment-button:hover {
  background-color: #000;border: 1px solid #fff;transition: 0.4s; 
}

.appointment-button:hover::before {
  background-color: #000 !important;transition: 0.8s !important;
}
.button-branding{margin: auto;display: flex;align-items: center;margin-top: 20px;}
.button-branding a{text-decoration: none;border: 1px solid lightgray;padding: 12px 20px;color: #fff;background-color: #2D2A2A;transition: 0.4s;
border: 1px solid #fff;margin: auto;font-family: "Poppins", sans-serif;}
.button-branding a:hover, .button-branding a:focus{background-color: #000;border: 1px solid #fff;}
</style>
</head>
<body>
<div class="form-container">
  <div class="wrapper"> 
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
  <circle class="path circle" fill="none" stroke=" #fff;" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
  <polyline class="path check" fill="none" stroke=" #fff" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
</svg>
  <h3 style="font-weight:600;text-transform: capitalize;color: #fff;font-family: Raleway, sans-serif;font-size: 32px; margin: 10px;">Thank you</h3>
  <h4 style="font-weight:400; margin-bottom:20px;color: #fff;margin-top:10px;font-size:18px;font-family:Poppins, sans-serif;">Your mail has been sent</h4>
</a>
<div class="button-branding">
        <a href="index.html" target="_self">
        Back to Home
        </a>
      </div>
      
</body>
</html>