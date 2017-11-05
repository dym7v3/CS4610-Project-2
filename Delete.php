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


$QuestionOrderNum=null;
//Checks if the element exists Question Order Num exists and if it does then
//it will be saved to a variable. 
if (isset($_GET['QuestionOrderNum']))
{
    $QuestionOrderNum = $_GET['QuestionOrderNum'];
} 
else 
{
    die('Error: Element not found in the GET Method');
}

$pid=null;
if (isset($_GET['pidDelete']))
    $pid=$_GET['pidDelete'];
else
    die("Error: Pid was not found in Delete.php");

//Grabs the highest del value and the highest ordering. 
$query = "SELECT MAX(del), MAX(ordering) FROM `problem`;";
$result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) 
    {
       $del=$row['MAX(del)'];
       $maxOrder=$row['MAX(ordering)'];
    }
//the variable del at first has the highest elemented that was deleted.
//then it make the del variable go up by one and then it saves it.
//this allows for it to have like a list of the elements that were deleted first
//in addition, when you do the undo delete it will bring the first question deleted first. 
$del+=1;

//If the first element is being deleted then it will just delete it without changing
//the order of the other elements. 
if($maxOrder==$QuestionOrderNum)
{
    $sql = "UPDATE `problem` SET `del`='$del', `ordering`='-2' WHERE `ordering`='$QuestionOrderNum' ;";
    $result= mysql_query($sql);
}
else //If it wasn't the first element that was deleted then it will adjust the order of tall the other elements.
{
    $sql = "UPDATE `problem` SET `del`='$del', `ordering`='-2' WHERE `ordering`='$QuestionOrderNum' ;";
    $result= mysql_query($sql);
    
    //This will have all the elements be shifted down by one because to maintain the order. 
    $sql = "UPDATE `problem` SET `ordering`= `ordering`-1 WHERE ordering > $QuestionOrderNum";
    $result= mysql_query($sql);
}
    
    $result = mysql_query("SELECT pid FROM `keywords` WHERE `pid`='$pid';");
    if(mysql_num_rows($result) == 0) 
    {}
    else{
        $sql="UPDATE `keywords` SET `del`='-1' WHERE `pid`='$pid';";
        $result= mysql_query($sql);
    }

//Closes the connection and redirects the page to go back to the index page. 
mysql_close($connection);
header('Location: index.php');
?>
