
<?php


// Connect to our database
function connect(){
$db = mysql_connect('localhost', 'csc325generic', 'password');
$ok = mysql_select_db(CSC325, $db);
if ($db && $ok)
return $db;
else return 0;
}

// Disconnect from our database
function disconnect($db, $result){
if ($result){
  $freed = mysql_free_result($result);
}
$disconn = mysql_close($db);
if ($freed && $disconn)
return 0;
else return 1;
}


// Input an array of Tables
// Print a form for entering the data into the tables.
//
// Note: I could not remove the foreign keys from the input,
//    Because they are not stored as foreign keys in the database.

function generateForm($tables){
  $db = connect();
  $num_tables = count($tables);
  if ($db){
    echo "<html> \n<body>\n";
    echo "<br /> <b> INSERT DATA </b> <br />\n";
    echo "<br />Warning: Table does not correctly insert into multiple tables simultaneously\n";
    echo "<form action=\"Data_insert.php\" method=\"post\">\n";
    $result = mysql_query("SELECT * FROM ".$tables[$i], $db);
    for($i=0; $i<$num_tables; $i++){
      $result = mysql_query("SELECT * FROM ".$tables[$i], $db);
      $keys = array_keys(mysql_fetch_assoc($result));
      for ($j=0; $j<count($keys); $j++){
	$types[$j] = mysql_field_type($result, $j); 
      }
      $iterations = count($keys);
      echo "<br /> <b> " . $tables[$i] . "</b> <br /> <br />\n";
      for ($k = 1; $k < $iterations; $k++ ){
	  printf("%20s : <input type= \"%s\" name=\"%s\" /><br />\n",
	         $keys[$k], $types[$k], $keys[$k]);
      }
    }
    echo "<input type=\"submit\" />\n </form>\n </html>\n</body> <br />";
    $db = disconnect($db, $result);		
  }
}

 /* Procedure:
  * makeSelect()  
  *
  * Parameters
  * $tables --  a list of tables.
  * $desired -- an associative array whose keys are the fields desired and the values that follow the WHERE part of the query
  *
  * Purpose:
  * Creates a mysql SELECT statement using the parameters provided 
  *
  * Produced:
  * Returns the string, or NULL on failure
  *
  * Preconditions:
  * The system must be able to connect to the database.  The function must also have access to the connect() function found in this file.
  * The database used is connected inside of the function, so there is no need to provide a database.
  *
  * Postconditions:
  * The query is returned and the database used is disconnected and cleaned up.
  */
function makeSelect($tables, $desired){
$db = connect();
$fields = array_keys($desired);
$sql = "SELECT ";
for ($i=0; $i<count($desired); $i++){
  $sql .= $fields[$i];
  if ($i<(count($desired)-1))
    $sql .=", ";
}
$sql .= "\nFROM ";
for ($i=0; $i<count($tables); $i++){
  $sql .= $tables[$i];
  if ($i<(count($tables)-1))
    $sql .=", ";
}
$sql .= "\nWHERE ";
for ($i=0; $i<count($desired); $i++){
  $sql .= $desired[$fields[$i]];
  if ($i<(count($desired)-1) && $desired[$fields[$i]])
    $sql .=", ";
}
$db = disconnect($db, NULL);
return $sql;
}




 /* Procedure:
  * insertData 
  *
  * Parameters
  * Gives the names, types, and expected roles of the parameters to the procedure.
  *
  * Purpose:
  * $table -- a table to insert info into 
  * $info -- an associative array of columns and their respected values and stores them in the database.
  *
  * Produced:
  * Returns 1 on success, 0 on failure 
  *
  * Preconditions:
  * The $info parameter must have a value for each column
  * The database used is connected inside of the function, so there is no need to provide a database
  *
  * Postconditions:
  * The database is updated with a new row containing $info in $table.  The database used is disconnected and cleaned up.
  */
function insertData($table, $info){
$db = connect();
$fields = array_keys($info);
$sql = "INSERT INTO ".$table." (" ;
for($i=0; $i<count($fields); $i++){
  $sql .= $fields[$i];
  if ($i<(count($fields))- 1)
    $sql .= ", ";
}
$sql .= ")\nvalues ('"; 
for($i=0; $i<count($fields); $i++){
  $sql .= $info[$fields[$i]];
  if ($i<(count($fields))- 1)
    $sql .= "', '";
}
$sql .= "')";
$result = mysql_query($sql, $db);
$db = disconnect($db, NULL);
return $result;
}


?>
