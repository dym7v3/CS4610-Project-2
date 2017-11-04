function empty() {
    var x;
    x = document.getElementById("QuestionContent").value;
    if (x === "") {
        alert("You tried to submit an empty question. Enter a question then click submit.");
        return false;
    };
}

function editting(value,questionNum)
{
    document.getElementById('QuestionOrderNum').value=questionNum;
    document.getElementById('QuestionContent').value=value;
    heading.innerText = "Edit Your Question";
    document.getElementById('QuestionContent').style.height="150px";
    document.getElementById('QuestionContent').style.fontSize="12pt";
    document.getElementById('QuestionSubmitButton').style.display='none';
    document.getElementById('QuestionUpdate').style.display='block';
    document.getElementById('QuestionUpdateCancel').style.display='block';
    document.getElementById('EditOrAddQuestion').value="1";
    
}
//This will be used to give the user suggestions of keywords in the database. 
 function showHint(str) {     
     if (str.length == 0) {
                    $("#txtHint").html("");
                    $("#hidden").show();
                    return;
                } else {
                    $("#hidden").show();  
                    $.get("getHint.php",
                            {
                                q: str
                            },
                    function (data, status) {
                        $("#txtHint").html(data);
                    });
                }
            }
//This will hide the suggest option. When the search is not being used. 
setInterval(function() {
  var search_value=$("#search").val();
  if(search_value==="")
  {
      $("#hidden").hide();
  }
  
}, 5000);