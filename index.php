<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "mathprobdb";

$con = mysql_connect($dbhost, $dbuser, $dbpassword);

if (!$con) {
    die('Could not connect: ' . mysql_error());
}

mysql_select_db($dbname, $con);

$problemId = array();
$problemContent = array();
$problemOrder= array();

$paging="1";

if(isset($_GET['pageNum']))
    $paging=$_GET['pageNum'];

if($paging=="" || $paging=="1")
{
    $pageNumber="0";
}
else
{
    $pageNumber=($paging*20)-20;
}


$query = "SELECT `pid`, `content`, ordering FROM problem WHERE `del`='0' ORDER BY ordering DESC LIMIT $pageNumber,20";
$result = mysql_query($query);

while ($row = mysql_fetch_assoc($result)) {
    $problemId[] = $row['pid'];
    $problemContent[] = $row['content'];
    $problemOrder[]=$row['ordering'];
}
if (!empty($problemOrder))
{
    $max=max($problemOrder);
}

//This will be used to get the right number of rows so it will then know to print
//out 20 per page. 
$sql = "SELECT `pid`, `content`, `ordering` FROM problem WHERE `del`='0' ORDER BY ordering DESC ";
$results = mysql_query($sql);

//This gets the amount of rows in the page. 
$numberOfRows= mysql_num_rows($results);

//Then we want to get the correct amount of pages.
//So we want 20 questions per page some we divide by 20 and take the ceiling.
$amountOfpages=$numberOfRows/20;
$amountOfpages=ceil($amountOfpages);

//This will be used to check if their is a deleted element. 
$query = "SELECT `pid` FROM problem WHERE `del`='1'";
$result = mysql_query($query);

$deleted=null;
while ($row = mysql_fetch_assoc($result))
{
    $deleted = $row['pid'];
   }

if($deleted==null)
{
    $showUndoButton=false;
}
else
    $showUndoButton=true;

//This will go and grab the associated keywords with each pid. combine then into my string 
//And will be later on saved in a hidden input tag.
function getKeywords($problemPid)
{ 
    $query="SELECT keyword FROM keywords WHERE pid='$problemPid' AND del='0';";
    $result=mysql_query($query);
    $keywordString="";
    $first=true;
    while ($row = mysql_fetch_assoc($result)){
        $keyword = $row['keyword'];
        if($first){
            $keywordString.="$keyword";
            $first=false;
        }else
            $keywordString.=" , $keyword";
    }
    return $keywordString; //send the list of keywords back to the function call.
}


?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Math Question Bank</title>
        <script src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
        <script type="text/javascript">
	window.MathJax = {
		tex2jax : {
			inlineMath : [ [ '$', '$' ], [ "\\(", "\\)" ] ],
			processEscapes : true
		}
	};
        </script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">  
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="DynamicFunctions.js"></script>
        <link rel="stylesheet" href="index.css"/>
     </head>
<body>
<br>
    <form action="./search.php" method="get">
            <div class=" search input-group col-xs-2 pull-right " >
                <input type="text" class="form-control" onkeyup="showHint(this.value);"placeholder="Search" id="search" name="search">
                <div class="input-group-btn">
                    <button class="btn btn-info" onclick="return checkKeywords(search.value)" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                </div>    
            </div>
        <br><br>
    </form>
<div class="pull-right suggestions">
    <p hidden id="hidden">Suggestions: <span id="txtHint"></span></p>
</div>
<br>
    <div class="container">    
        <form id="addNewQuestion" action="./AddOrEdittingAQuestion.php" method="get">
            <div class="form-group">
                <h2 id="heading">Insert A Question</h2>
                <input id="EditOrAddQuestion" name="EditOrAddQuestion" type="hidden" value="0" /> 
                <textarea id="QuestionContent" class="form-control" name="QuestionContent" placeholder="Type Question Content..." cols="50" rows="3"></textarea>
                <br>
                <textarea id="tags" class="form-control" name="tags" placeholder="Enter keywords (e.g. division, add, subtract) separated by commas..." cols="50" rows="1"></textarea>
                <br>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-right">
                            <input id="QuestionSubmitButton" class="btn btn-primary" type="submit"  value="Submit" onClick="return empty()" />
                            <div class="btn-group">
                                <input id="QuestionUpdate"  class="btn btn btn-info" type="submit" style="display: none" value="UPDATE" onClick="return empty()" />
                                <input id="QuestionUpdateCancel"  class="btn btn btn-info" type="reset" onclick='window.location.reload();' style="display: none" value="Cancel" />
                                <input id="QuestionOrderNum" name="QuestionOrderNum" type="hidden" value="-1"/>
                            </div>
                        </div>
                    </div>
                </div>    
        </form>
            <h2>Math Problem Bank</h2>      
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <div class="text-center">
                                <?php for ($j = 1; $j <= $amountOfpages; $j++) { ?>
                                    <ul class="pagination pagination-lg">
                                       <li class="<?php if($paging==$j)
                                                        {
                                                            print "active";
                                                        }
                                                    ?>"
                                        >
                                        <a href="index.php?pageNum=<?php echo $j; ?>" ><?php echo $j; ?></a></li>

                                    </ul>
                                <?php } ?>
                            </div>
                        </tr>
                        <tr>
                            <th>ID</th>
                            <th>Question</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>   
                                <form class="UndoDelete" action='./UndoDelete.php' method="get">
                                    <?php if($showUndoButton)
                                            {
                                                print "<input id='QuestionSubmitButton' "
                                                 . "class='btn btn-warning ' type='submit' "
                                                 . "value='Undo' "
                                                 . "onClick='' />";
                                            }?>
                                </form>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < count($problemOrder); $i++) { ?>
                            <tr>                        
                                <td><?php print "<strong>$problemOrder[$i]</strong>";?></td>
                                <td><?php print $problemContent[$i]; ?></td>
                                <td></td>
                                <td>
                                    <form class='ChangeOrderForm' action="./ChangeOrder.php" method="get">  
                                        <input name="QuestionPid" type="hidden" value="<?php print $problemOrder[$i]; ?>"/>  
                                        <input name="UpOrDown" type="hidden" value="1" />    
                                        <?php if ( $numberOfRows != $problemOrder[$i])
                                                {
                                                    print "<button type='submit' class='btn btn-info '>
                                                           <span class='glyphicon glyphicon-arrow-up'></span>
                                                           </button>";
                                                } 
                                        ?>            
                                    </form>

                                </td>
                                <td> 
                                    <form class='ChangeOrderForm' action="./ChangeOrder.php" method="get">  
                                        <input name="QuestionPid" type="hidden" value="<?php print $problemOrder[$i] ?>"/>    
                                        <input name="UpOrDown" type="hidden" value="0" />
                                        <?php if ($max != $i+1)
                                                {
                                                    print "<button type='submit' class='btn btn-info'>
                                                           <span class='glyphicon glyphicon-arrow-down'></span>
                                                            </button>";
                                                } 
                                        ?>           
                                    </form>
                                </td>
                                <td>
                                    <form class='EditForm' action="./AddOrEdittingAQuestion.php" method="get">    
                                        <input id="problemContent" name="problemContent" type="hidden" value='<?php print $problemContent[$i]?>'/>
                                        <input name="QuestionNum" type="hidden" value="<?php print $problemId[$i] ?>"/> 
                                        <input name="problemsKeywords" type="hidden" value="<?php print getKeywords($problemId[$i]); ?>"/>
                                        <input id="EditOrAddQuestion" name="EditOrAddQuestion" type="hidden" value="1" />
                                        <button type="button" class="btn btn-success " onclick="editting(problemContent.value, QuestionNum.value, problemsKeywords.value)">Edit</button>
                                    </form>
                                </td>
                                <td>
                                    <form class='DeleteForm' action="./Delete.php" method="get">
                                        <input name="QuestionOrderNum" type="hidden" value="<?php print $problemOrder[$i] ?>"/> 
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>

                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <table class="table table-striped">
                    <tr>
                        <div class="text-center">
                            <?php for ($j = 1; $j <= $amountOfpages; $j++) { ?>
                                <ul class="pagination pagination-lg">
                                    <li class="<?php if($paging==$j)
                                                        {
                                                            print "active";
                                                        }
                                                ?>"
                                    >
                                        <a href="index.php?pageNum=<?php echo $j; ?>" ><?php echo $j; ?></a>
                                    </li>
                                </ul>
                            <?php } ?>
                        </div>
                    </tr>
                </table>
            </div>    
        </div>
    </body>
</html>

    
    
    <?php
mysql_close($con);
?>