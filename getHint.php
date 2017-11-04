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

$query = "SELECT DISTINCT keyword FROM keywords WHERE del='0';";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $keywordArr[] = $row['keyword'];
    }
}

// get the q parameter from URL
$q = filter_input(INPUT_GET, "q");

$hint = "";

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