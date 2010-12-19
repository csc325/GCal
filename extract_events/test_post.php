<?php
$url = 'http://www.cs.grinnell.edu/~leepjeff/CSC_325/final_work/test_curl_mysql.php';
$fields = array (
         'num' => urlencode('1315')
	  );
foreach( $fields as $key=>$value ) { $fields_string .= $key . '=' . $value . '&'; }
rtrim( $fields_string, '&' );

do_post_request( $url, $fields_string );

function do_post_request($url, $data, $optional_headers = null)
{
  $params = array('http' => array(
              'method' => 'POST',
              'content' => $data
            ));
  if ($optional_headers !== null) {
    $params['http']['header'] = $optional_headers;
  }
  $ctx = stream_context_create($params);
  $fp = @fopen($url, 'rb', false, $ctx);
  if (!$fp) {
    throw new Exception("Problem with $url, $php_errormsg");
  }
  $response = @stream_get_contents($fp);
  if ($response === false) {
    throw new Exception("Problem reading data from $url, $php_errormsg");
  }
  return $response;
}
?>
