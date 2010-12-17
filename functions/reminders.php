<?php

   include('connection.php');

function sendMail($description, $email){
  // Message
  $message = '
      <html>
      <body>
      <p>'.$decription.'</p> 
      </body>
      </html>';

  // Headers
  $header = 'MIME-Version: 1.0' . "\r\n" .
    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
    'From: webmaster@grinnellopencalender.com' . "\r\n" .
    'Reply-To: webmaster@grinnellopencalender.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

  // Send Message
  mail( $email, 'GOC Password Reset', $message, $header);
}

function sendText($description, $email){
  // Message
  $message = '
      <html>
      <body>
      <p>'.$decription.'</p> 
      </body>
      </html>';

  // Headers
  $header = 'MIME-Version: 1.0' . "\r\n" .
    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
    'From: webmaster@grinnellopencalender.com' . "\r\n" .
    'Reply-To: webmaster@grinnellopencalender.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

  // Send Message
  mail( $email, 'GOC Password Reset', $message, $header);
}

?> 

