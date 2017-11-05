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
$first=true;
$myString="";
$seprateKeywords=array();

for($z=0; $z<count($keywordArr); $z++)
{
    if($first){
        $myString.="$keywordArr[$z]";
        $first=false;
    }
 else {
        $myString.=" , $keywordArr[$z]";
    }
}
//This will separate over commas and remove duplicates and remove any space string.
$seprateKeywords= explode(",", $myString);
$seprateKeywords=array_map('trim', $seprateKeywords);
$seprateKeywords= array_values(array_unique($seprateKeywords));

// get the q parameter from URL
$q = filter_input(INPUT_GET, "q");

$hint = "";

// lookup all hints from array if $q is different from "" 
if ($q !== "") {
    $q = strtolower($q);
   $len=strlen($q);
    
    for ($i = 0; $i < count($seprateKeywords); $i++) {
        if (stristr($q, substr($seprateKeywords[$i], 0, $len))) {
           if ($hint === "") {
               $hint = $seprateKeywords[$i];
            } else {
                $hint .= ", $seprateKeywords[$i]";
            }
        }
    }
}

// Output "no suggestion" if no hint was found or output correct values 
echo $hint === "" ? "no suggestion" : $hint;

mysqli_close($conn);
?>