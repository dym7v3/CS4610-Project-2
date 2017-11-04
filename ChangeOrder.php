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

//If didn't get element from the GET method it will crash. 
if (isset($_GET['QuestionPid']))
{
    $QuestionOrderNum = $_GET['QuestionPid'];
} 
else 
{
    die('Error: Element not found in the GET Method');
}
//If didn't get element from the GET method it will crash. 
if (isset($_GET['UpOrDown']))
{
    $MovingUpOrDown = $_GET['UpOrDown'];
} 
else 
{
    die('Error: Element not found in the GET Method');
}

//If the movingUpOrDown variable is 1 means it will move the selected question up.
//If the movingUpOrDown variable is something else then it will move it down. 
if($MovingUpOrDown == "1")
{ //This will move the order of the element up. Unless its the highest element
    $query = "SELECT `ordering` FROM `problem` WHERE `ordering`='$QuestionOrderNum' AND `del`='0';";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) 
    {
       $order=$row['ordering'];
    }
    //It will change the question order num to 1 one.
    $QuestionOrderNum+=1;
    
    //It will change the order number of the question number above the question
    //which was selected. It will change the order number to -1.
    $sql = "UPDATE `problem` SET `ordering`='-1' WHERE `ordering`='$QuestionOrderNum' AND `del`='0';";
    $result= mysql_query($sql);
   
    //Then getting the order variable from the select query it will increase the
    //order to plus one. And lower the question order num down one.
    //It will take the orginal question selected and change the order of it to plus one.
    $order+=1;
    $QuestionOrderNum-=1;
    $sql = "UPDATE `problem` SET `ordering`='$order' WHERE `ordering`='$QuestionOrderNum' AND `del`='0';";
    $result= mysql_query($sql);
    
    
    //Finally, it will find the question which has the order num as -1 and it will 
    //change its order to the correct order which would be one less now then it was orginally. 
    $sql = "UPDATE `problem` SET `ordering`='$QuestionOrderNum' WHERE `ordering`='-1' AND `del`='0';";
    $result= mysql_query($sql);
    
}
else
{ //This will move the order of the element down. Unless its the lowest element. 
    $query = "SELECT `ordering` FROM `problem` WHERE `ordering`='$QuestionOrderNum' AND `del`='0';";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) 
    {
       $order=$row['ordering'];
    }
    
    //Changes the QuestionOrder Num to the previous question.
    $QuestionOrderNum-=1;
    
    
    //Grabs the previous question and sets it ordering to be negative one. 
    $sql = "UPDATE `problem` SET `ordering`='-1' WHERE `ordering`='$QuestionOrderNum' AND `del`='0';";
    $result= mysql_query($sql);
   
    //Then it will take the order of select query and it will put it down by one.
    //Then it will take the Question order num and add one to it.
    $order-=1;
    $QuestionOrderNum+=1;
    
    //Run the query which will take the orginal selected question and change the order 
    //to be one less then it was previously before.
    $sql = "UPDATE `problem` SET `ordering`='$order' WHERE `ordering`='$QuestionOrderNum' AND `del`='0';";
    $result= mysql_query($sql);
    
    //Then it will finally find the question that has the order number be negative -1
    //and change its order to be higher by one. In this way it will switch the two elements in
    //the database.
    $sql = "UPDATE `problem` SET `ordering`='$QuestionOrderNum' WHERE `ordering`='-1' AND `del`='0';";
    $result= mysql_query($sql);
    
    
}

//Closes the connection and redirects the page to go back to the index page. 
mysql_close($connection);
header('Location: index.php');
?>
