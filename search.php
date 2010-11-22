<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Script</title>
</head>

<body>

<h3>Search Form</h3>
<form method = "POST" action = "results.php">
Event name: <input type = "text" name = "event"><br>
Creator: <input type = "text" name = "user"><br>
<?php
   include('functions/connection.php');

   $resource = mysql_query("SELECT categoryName FROM categories;");
   echo "Category: <select name = 'category'><option></option>";
   while($row = mysql_fetch_row($resource))
       echo "<option value = $row[0]>$row[0]</option>";
   echo "</select><br>";

   $resource = mysql_query("SELECT locationName FROM locations;");
   echo "Location: <select name = 'location'><option></option>";
   while($row = mysql_fetch_row($resource))
       echo "<option value = $row[0]>$row[0]</option>";
   echo "</select><br>";

   mysql_close();
?>

<input type = "submit" name = "submit">
     </form>

</head>
</html>