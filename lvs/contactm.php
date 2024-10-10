
<?php
$cname = htmlspecialchars($_POST['cname']);
$cemail = htmlspecialchars($_POST['cemail']);
$message = htmlspecialchars($_POST['message']);

if(!empty($cemail) && !empty($message)){
  if(filter_var($cemail, FILTER_VALIDATE_EMAIL)){
    $receiver = "admin@pnblack.com"; //enter that email address where you want to receive all messages
    $subject = "From: $cname <$cemail>";
    $body = "Name: $cname\nEmail: $cemail\n\nMessage:\n$message\n\nRegards,\n$cname";
    $sender = "From: $cemail";
    if(mail($receiver, $subject, $body, $sender)){
       echo "Your message has been sent";
    }else{
       echo "Sorry, failed to send your message!";
    }
  }else{
    echo "Enter a valid email address!";
  }
}else{
  echo "Email and message field is required!";
}
?>