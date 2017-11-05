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

//Tries to get the EditOrAddQuestion variable. 
if(isset($_GET['EditOrAddQuestion']))
    $EditOrAddQuestion=$_GET['EditOrAddQuestion'];
else
    die("Error couldn't figure out if you wanted to add a question or to edit a question.");
   
$insertContent=null;
 if(isset($_GET['QuestionContent']))
    $insertContent=$_GET['QuestionContent'];
else
    die("Error: Did't get question content ");

$insertKeywords=null;
if(isset($_GET['tags']))
    $insertKeywords=$_GET['tags'];
else
    die("Error: Didn't get the keyword content");


function addKeywords($insertKeyword, $questionNum){
        if($insertKeyword!="" && $insertKeyword!=" ")
        {
            $InsertNewTags ="INSERT INTO `keywords`(`pid`,`keyword`) VALUES ('$questionNum', '$insertKeyword')";
            $result = mysql_query($InsertNewTags);
         }

}

//Checks if the user is either editting or adding a new question.
//When the user is adding a new question then the value is zero otherwise it will 
//insert the changed to the database. 
if($EditOrAddQuestion=="0")
    {    
    //This checks if the content from the question box was recieved and if it was
    //then it will set a string variable containing that content.   
    $getOrder= "SELECT MAX(ordering) FROM `problem` WHERE del='0';";
    $result = mysql_query($getOrder);
    while ($row = mysql_fetch_assoc($result)) 
        {
            $order=$row['MAX(ordering)'];
        }

    //Makes a new order by adding plus one.
    $order+=1;
    //This adds the content of the question to the question bank and makes it have the correct order number. 
    $InsertNewQuestion = "INSERT INTO `problem`(`content`, `ordering`) VALUES ('$insertContent','$order')";
    $result = mysql_query($InsertNewQuestion);
    
    $getTheNewPid="SELECT pid FROM `problem` WHERE del='0' AND ordering='$order'";
    $result = mysql_query($getTheNewPid);
    while ($row = mysql_fetch_assoc($result)) 
        {
            $pid=$row['pid'];
        }
          
        addKeywords($insertKeywords, $pid);
}
else
{
//Gets the problem number so then it will know where to insert the content.    
$problemNum=null;
if(isset($_GET['QuestionOrderNum']))
    $problemNum=$_GET['QuestionOrderNum'];
else
    die("Error: Did't get question number! "); 
    //This adds the content of the question to the question bank into the correct problemNum.  
    $sql = "UPDATE `problem` SET `content` ='$insertContent' WHERE `ordering` = '$problemNum' ;";
    $result = mysql_query($sql);    
    
    $result = mysql_query("SELECT pid FROM `keywords` WHERE `pid`='$problemNum' AND `del`='0';");
    if(mysql_num_rows($result) == 0) {
      //This will add the new keywords into the database. 
        addKeywords($insertKeywords, $problemNum);
    } else {
        $updateSearchTable="UPDATE `keywords` SET `keyword`='$insertKeywords' WHERE `pid`='$problemNum';";
        $result=mysql_query($updateSearchTable);
    }  
}
//Closes the connection and redirects the page to go back to the index page. 
mysql_close($connection);
header('Location: index.php');