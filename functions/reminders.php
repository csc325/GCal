<?php
/*
* reminders.php sends users reminders via email or phone
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @category functions
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/
include('connection.php');

/*
* sends user email
* @param string $description message
* @param string $email address of receiver
*/ 
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

/*
* sends user text (NOT IMPLEMENTED (SAME AS EMAIL))
* @param string $description message
* @param string $email address of receiver
*/ 
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

