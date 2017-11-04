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

