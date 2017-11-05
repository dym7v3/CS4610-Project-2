<?php

$databaseHost = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "mathprobdb";

//Try to make the connnection with the database. 
$connection = mysql_connect($databaseHost, $databaseUsername, $databasePassword);

//If the connection is not made then it will exit with an error. 
if (!$connection) {
    die('Error: Could not connect for reason "' . mysql_error() . '"!');
}

//After the connection to the DB is made then we need to select a table. 
mysql_select_db($databaseName, $connection);

//Grabs the highest del value and the highest ordering. 
$query = "SELECT MAX(del), MAX(ordering) FROM `problem`; ";
$result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) 
    {
       $del=$row['MAX(del)'];
       $maxOrder=$row['MAX(ordering)'];
    }
$maxOrder+=1;
//Grabs the highest del value and the highest ordering. 
$query = "SELECT pid FROM `problem` WHERE `del`='$del';";
$result = mysql_query($query);
while ($row = mysql_fetch_assoc($result)) 
{
       $pid=$row['pid'];
       
}
   $result = mysql_query("SELECT pid FROM `keywords` WHERE `pid`='$pid';");
    if(mysql_num_rows($result) == 0) 
    {}
    else{
        $sql="UPDATE `keywords` SET `del`='0' WHERE `pid`='$pid';";
        $result= mysql_query($sql);
    }

//Updates the table so that where you had the highest del number it is set back to zero
//and the it becomes the first problem in the question bank. 
$sql = "UPDATE `problem` SET `del`='0', `ordering`=$maxOrder WHERE `del`=$del;";
$result= mysql_query($sql);




//Closes the connection and redirects the page to go back to the index page. 
mysql_close($connection);
header('Location: index.php');
?>