<?php
/*
* extract_info.php 
* PHP version 5
*
* LICENSE: This source file is subject to version 3.01 of the PHP license
* that is available through the world-wide-web at the following URI:
* http://www.php.net/license/3_01.txt. If you did not receive a copy of
* the PHP License and are unable to obtain it through the web, please
* send a note to license@php.net so we can mail you a copy immediately.
*
* @extracts events from the Grinnell Campus Calendar
* @author CSC-325 Database and Web Application Fall 2010 Class
* @license http://www.php.net/license/3_01.txt PHP License 3.01
* @version 3.0
*/

// Include Simple HTML DOM
include('simplehtmldom/simple_html_dom.php');

// code stolen from connections.php. We could use an include statement
// to include connections.php, but currently this script is standalone.
//
// Connect to our project's mysql database.
$link = mysql_connect('localhost', 'csc325generic', 'password');
if (!$link) {
      die('Could not connect ' . mysql_error());
}

$db = mysql_select_db('CSC325');

if (!$db) {
      mysql_close($db);
          die('DB Could not connect ' . mysql_error());
}

// make an array of the already submitted campus event ids.
// this array is used in steal_events to prevent repeat event
// submissions.
$submitted_event_ids = array();

$submitted_query = 'SELECT * FROM school_event_ids';

$submitted_results = mysql_query( $submitted_query );

while( $row = mysql_fetch_assoc( $submitted_results ) ) 
  array_push( $submitted_event_ids, $row['id'] );

// grab current date and format it. This will be used to create the
// url that we are spidering.
$today = date( 'Ymd' );
// get the date for a week after the current date.
$next_week = date( 'Ymd', strtotime( '+1 week' ) );

// steal events for the next two weeks.

steal_events( $today, $submitted_event_ids );
steal_events( $next_week, $submitted_event_ids );

function steal_events( $date, $submitted_event_ids)
{
  /**
   * This is the URL for the grinnell calendar for the week in which
   * the date given resides.
   */

  $calendar_url = 'http://schedule25wb.grinnell.edu/wv3/wv3_servlet/'
    . 'urd/run/wv_event.WeekList?evdt=' . $date . ',evfilter=138,ebdv'
    . 'iewmode=list'; 

  // Create a DOM object from the URL
  $calendar_html = file_get_html( $calendar_url );

  // get all anchors from the source code
  $anchors = $calendar_html->find('a');

  // pattern that finds the event IDs for each event
  $pattern = '^javascript:rsrvInfo\(\'(\d*)\'\)$';

  // loops through each anchor, examines the 'href' property of that
  // anchor, then uses a regular expression to search for and
  // extract event IDs for each event.
  foreach( $anchors as $anchor )
  {
    $link = $anchor->href;
    if( preg_match( "/$pattern/", $link, $matches ) )
    {
      $event_id = $matches[1];
      //make sure that this event_id has not already been submitted
      if ( ! in_array( $event_id, $submitted_event_ids ) )
      {
        // if this event has not been submitted, enter its id into the
	// database, then call submit_event.
	$insert_used_id_query = 'INSERT INTO school_event_ids'
	  . ' (id) VALUES(' . $event_id . ')';
	mysql_query( $insert_used_id_query );
	$event_info = extract_event_info( $event_id );
	submit_event( $event_info );
      }
    }
  }
}


// function that is used to extract all of the vital information
// from a given event (all that is needed is the event's 
// event ID.)
function extract_event_info( $event_id )
{
  //initialize the array in which we will store all event information.
  $event_info = array();
  //creates the correct URL for a given event with given event_id
  $event_url = 'http://schedule25wb.grinnell.edu/wv3/wv3_servlet/urd'
    . '/run/wv_event.Detail?id=' . $event_id;

  // create new DOM object using event_url
  $event_html = file_get_html( $event_url );

  // find all table entries (this is where the rest of the information
  // that we need is stored)
  $table_entries = $event_html->find('td');

  // find unformatted times.
  // these come in the form Fri Dec 17 2010 12:00 P.M.  <br /> to
  //    <br /> [Fri Dec 18 2010] (this date is optional and only
  //    occurs if the end date is different than start date.)
  //    1:00 P.M.
  // This date then needs to be parsed.

  // find times, then strip the html tags from them.
  $raw_times = strip_tags( $table_entries[5]->innertext );
  // split the times into two sections - the start time and the
  // end time.
  $temp_times = explode( 'to', $raw_times );

  // explode the start date section ($temp_times[0]) by spaces
  // so that I can extract the correct information.
  $start_date_times = explode( ' ', trim ( $temp_times[0] ) );
  // extract the Dec 17 2010 section by imploding the 2nd through
  // 4th elements of the array.
  $start_date = implode( ' ', array_slice( $start_date_times, 1, 3) );
  // extract the 12:00 P.M. section and push it together so that it is
  // 12:00PM (this is so that later the end_date string will be
  // compatible with the strtotime PHP function.
  $start_time = implode( '', array_slice( $start_date_times, 4, 2) );
  // get rid of &nbsp whitespace characters that mess things up  
  $start_time = ereg_replace("[^A-Za-z0-9:]", "", $start_time );

  // separate the elements of the second (end date) section
  // of $temp_times[]
  $end_date_times = explode( ' ', trim ( $temp_times[1] ) );
  // if the $end_date_times only has 2 elements, then it does not
  // have a date section and so the end date is the same as the
  // start date, and $end_date = $start_date.
  //
  // the $end_time still needs to be extracted.
  if ( count($end_date_times) == 2 )
  {
    $end_date = $start_date;
    $end_time = implode( '', $end_date_times );
  }
  // otherwise, run through the same steps that were implemented
  // with the $start_date and $start_time.
  else
  {
    $end_date = implode( ' ', array_slice( $end_date_times, 1, 3) );
    $end_time = implode( '', array_slice( $end_date_times, 4, 2) );
  }

  $end_time = ereg_replace("[^A-Za-z0-9:]", "", $end_time );

  // extract the location and description strings.
  $entries_length = count( $table_entries );
  for( $table_pos = 0; $table_pos < $entries_length; $table_pos++ )
  {
    if ( $table_entries[$table_pos]->innertext == 'Location:' )
      $location_pos = $table_pos + 3;
    else if ( $table_entries[$table_pos]->innertext == 'Description:' )
      $description_pos = $table_pos + 1;
    // use organization as category because the official Grinnell
    // calendar has nothing better to use as category.
    else if ( $table_entries[$table_pos]->innertext == 'Organization:' )
      $category_pos = $table_pos + 1;
  }

  if ( ! empty( $location_pos ) )
  {
     $location = trim( strip_tags( 
      $table_entries[$location_pos]->innertext ) );
  }

  if ( ! empty( $description_pos ) )
  {
    $description = trim( strip_tags( 
      $table_entries[$description_pos]->innertext ) );
  }

  if ( ! empty( $category_pos ) )
  {
    $category = trim(  strip_tags(
      $table_entries[$category_pos]->innertext ) ); 
  }

  //extract the event's title 
  $event_title = $event_html->find('div.DetailName');
  $event_title = $event_title[0];
  // get rid of annoying '(12/14)' label thing that goes after title
  $event_title->first_child()->innertext = '';
  $event_title = trim( strip_tags( $event_title->innertext ) );

  // make sure that location and category are not empty.
  if( empty( $location ) || strlen( $location ) == 0)
    $location = 'unkown';
  if( empty( $category ) )
    $category = 'miscellaneous';

  //insert the info into final array
  $event_info['event_title'] = $event_title; 
  $event_info['start_date'] = $start_date;
  $event_info['start_time'] = $start_time;
  $event_info['end_date'] = $end_date;
  $event_info['end_time'] = $end_time;
  $event_info['event_location'] = $location;
  $event_info['event_description'] = $description;
  $event_info['event_category'] = $category;

  return $event_info;

}



// function that takes the event_info array from the 
// extract_event_info function, then passes this to submit.php
// via post using do_post_request. submit_event effectively 
// puts the campus calendar event into our open calendar. 
function submit_event ( $event_info )
{
  // this URL should refer to the final calendar's submit.php script.
  // I just chose charles arbitrarily.
  $url = 'http://www.cs.grinnell.edu/~frantzch/CSC325/GCal/submit.php';
  
  // urlifying code used from david walsh's blog at:
  // http://davidwalsh.name/execute-http-post-php-curl
  // set POST variables
  $fields = array(
              'event_name' => urlencode( $event_info['event_title'] ),
	      'start_date' => urlencode( $event_info['start_date'] ),
	      'start_time' => urlencode( $event_info['start_time'] ),
	      'end_date' => urlencode( $event_info['end_date'] ),
	      'end_time' => urlencode( $event_info['end_time'] ),
	      'location' => urlencode( $event_info['event_location'] ),
	      'category' => urlencode( $event_info['event_category'] ),
	      'description' => urlencode( $event_info['event_description'] )
	    );
  //url-ify the data for the POST
  foreach( $fields as $key => $value ) {
    $fields_string .= $key . '=' . $value . '&';
  }
  rtrim($fields_string, '&');
  do_post_request( $url, $fields_string );
}

// function that takes an absolute url and a post-request
// string and then sends a the string to the given url.
// I use this to submit my information to submit.php. 
//
// This code was taken from Wez Furlong's blog at:
// http://wezfurlong.org/blog/2006/nov/http-post-from-php-without-curl
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
