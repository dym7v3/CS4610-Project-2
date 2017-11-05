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

$query = "SELECT keyword FROM keywords";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $keywordArr[] = $row['keyword'];
    }
}

if(empty($keywordArr))
{
    die("Error: You didn't have any keywords saved in the database. Add some first before doing searching.");
}

$keywords=null;
if(isset($_GET['search']))
    $keywords=$_GET['search'];
else
    die("Error: No keywords were entered. ");

//This will spilt the array over comma's and then it will insert them into the keyword table. 
$keywordWithWhiteSpaces = explode(",", $keywords);
//This trims white spaces in the array.
$keyword=array_map('trim', $keywordWithWhiteSpaces); 


// lookup all hints from array if $q is different from "" 
if ($q !== "") {
    $q = strtolower($q);
    $len=strlen($q);
    
    for ($i = 0; $i < count($keywordArr); $i++) {
        if (stristr($q, substr($keywordArr[$i], 0, $len))) {
            if ($hint === "") {
                $hint = $keywordArr[$i];
            } else {
                $hint .= ", $keywordArr[$i]";
            }
        }
    }
}

// Output "no suggestion" if no hint was found or output correct values 
echo $hint === "" ? "no suggestion" : $hint;

mysqli_close($conn);
?>

SELECT pid, MATCH(keyword)AGAINST('add*+num') as relevance FROM `keywords` WHERE MATCH(keyword)AGAINST('add'+'num' IN BOOLEAN MODE) ORDER BY relevance DESC  