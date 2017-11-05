<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "";
$databaseName = "mathprobdb";

$conn = mysqli_connect($dbhost, $dbuser, $dbpassword, $databaseName);

if (!$conn) {
  die('Could not connect: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8');

$keywordArr = array();

$query = "SELECT keyword FROM keywords WHERE `del`='0';";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $keywordArr[] = $row['keyword'];
    }
}
//This will check if don't have any keywords saved in the keywords table. 
if(empty($keywordArr))
{
    echo " <a href='http://localhost/Project2/index.php'>Go Back Home</a><br><br>";
    die("Error: You didn't have any keywords saved in the database. Add some first before doing searching.");
}

$keywords=null;
if(isset($_GET['search']))
    $keywords=$_GET['search'];
else
    die("Error: No keywords were entered. ");

$sql="SELECT pid, keyword, MATCH(keyword)AGAINST('$keywords') as relevance FROM `keywords` WHERE MATCH(keyword)AGAINST('$keywords' IN BOOLEAN MODE) ORDER BY relevance DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        //$keyword[] = $row['keyword'];
        //$pid[]=$row['pid'];
        print $row['keyword'] . "\n";
        print $row['pid'];
        
    }
}



mysqli_close($conn);
?>

  