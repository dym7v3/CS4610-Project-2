function empty() {
    var TagContent=document.getElementById("tags").value;
    var QuestionContent = document.getElementById("QuestionContent").value;
    
    if (QuestionContent === "") {
        alert("You tried to submit an empty question. Enter a question then click submit.");
        return false;
    };
    if(TagContent=== ""){
        alert("You have no keywords assocated with this question.\nAtleast one keyword is required. ");
        return false;
    };
    
}

function editting(value,questionNum,keywords)
{
    document.getElementById('tags').value=keywords;
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

function checkKeywords(str)
{
    if(str.length===0){
        alert("You want to search with nothing in the search bar. ");
        return false;
    }
}


//This will be used to give the user suggestions of keywords in the database. 
 function showHint(str) {     
     if (str.length === 0) {
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