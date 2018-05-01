<!DOCTYPE html>

<html>

<head>

<link rel="Shortcut Icon" href="/favicon.ico">

<title>kinchat</title>

<style>

body, html

{
margin: 0px;
padding: 2.5%;
padding-top: 1%;
height: 95%;
width: 95%;
background: -moz-linear-gradient(100% 100% 90deg, white, #99CCFF);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#99CCFF), to(white));

}

div

{
float: left;
}

#htmlDocument

{
float: left;
border: 1px solid lightgray;
height: 84%;
width: 70%;
background-color: #DEEFF5;
padding: 2%;
box-shadow: inset 0px 2px 10px rgba(0, 0, 0, 0.2);
-webkit-box-shadow: inset 0px 2px 10px rgba(0, 0, 0, 0.2);
-moz-box-shadow: inset 0px 2px 10px rgba(0, 0, 0, 0.2);
cursor: text;
font-family: arial;
}

.buttons

{
float: left;
padding: 2.5%;
margin: 2.5% 2.5%;
border-radius: 3px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
background-color: lightblue;
text-align: center;
font-size: 20px;
box-shadow: 0px 3px 4px gray;
-moz-box-shadow: 0px 3px 4px gray;
-webkit-box-shadow: 0px 3px 4px gray;
cursor: pointer;
font-family: Monospace, "Courier New";
}

.buttons:hover

{
background-color: skyblue;
color: white;
box-shadow: 0px 0px 0px white;
-webkit-box-shadow: 0px 0px 0px white;
-moz-box-shadow: 0px 0px 0px white;
}

#viewChat

{
float: left;
width: 35%;
height: 75%;
margin: -3% 0px 0px 10%;
padding: 2.5%;
border: 2px solid lightgray;
border-radius: 10px;
-webkit-border-radius: 10px;
-moz-border-radius: 10px;
background-color: white;
}


</style>



<body onload="load()">

<img src="/kinchat.PNG" />

<br />

<div style="width: 40%; height: 35%; float: left; padding: 2.5%; border: 2px solid lightgray; border-radius: 10px; -moz-border-radius: 10px; -webkit-border-radius: 10px; background-color: white;">

<div style="width: 98%; height: 25%; padding: 1%;">

<div style="width: 60%; height: 100%; background-color: #E3F4F6; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; border: 1px solid lightgray; margin: 0px 20% 0px 15%; padding: 0px 5% 0px 10%;">

<div class="buttons" onclick="formatText('bold')" style="font-weight: bold;" title="Bold">B</div>

<div class="buttons" onclick="formatText('italic')" style="font-style: italic;" title="Italic">I</div>

<div class="buttons" onclick="formatText('underline')" style="text-decoration: underline;" title="Underline">U</div>

<div class="buttons" onclick="formatText('strikethrough')" style="text-decoration: line-through;" title="Strikethrough">abc</div>

<div class="buttons" onclick="formatText('subscript')" title="Subscript">x<span style="vertical-align: text-bottom; font-size: 10px;">2</span></div>

<div class="buttons" onclick="formatText('superscript')" title="Superscript">x<span style="vertical-align: text-top; font-size: 10px;">2</span></div>

</div>

</div>

<div style="width: 98%; height: 60%; float: left; padding: 1%; margin-top: 5%;">

<iframe id="htmlDocument" onload="formatText('FontName','Verdana')"></iframe>

<div style="float: left; width: 25%; height: 98%;">

<img id="arrow" src="/Arrow.png" onMouseOver="arrowHover()" onMouseOut="arrowOut()" onClick="enterChat()" style="float: left; width: 40%; height: 40%; margin: 30%; cursor: pointer;" title="Enter Chat"></img>

</div>

</div>

</div>

<br /><br />

<iframe id="viewChat" src="chat.html"></iframe>

<script>

var htmlDocument = document.getElementById('htmlDocument');

function load()

{
editContent();
reload();
}



// Make iFrame Editable

function editContent()

{
getIFrameDocument("htmlDocument").designMode = "On";
}

function getIFrameDocument(aID)

{
return document.getElementById(aID).contentDocument;
}



// Create Text Formatting Commands

function formatText(aName, aArg)

{
getIFrameDocument('htmlDocument').execCommand(aName, false, aArg);
document.getElementById('htmlDocument').contentWindow.focus();
}



// Keyboard Shortcuts

shortcut("Ctrl+B", formatText('bold')); 

shortcut("Ctrl+I", formatText('italic'));

shortcut("Ctrl+U", formatText('underline'));



// Arrow Hover Effect

function arrowHover()

{
document.getElementById("arrow").setAttribute("src","Arrow2.png");
}

function arrowOut()

{
document.getElementById("arrow").setAttribute("src","Arrow.png");
}



// Enter Chat To Server

function enterChat()

{
var html;
var formatting;

html = document.getElementById('htmlDocument').contentWindow.document.body.innerHTML;

formatting = '<hr />';

window.location = "http://testingsite.hostei.com/index.php?html=" + html + formatting;
}



<?php

$html = $_GET['html'];

$myFile = "chat.html";
$fileHandle = fopen($myFile,'a') or die("We're sorry. We were unable to open the chat.");
fwrite($fileHandle, $html);
fclose($fileHandle);

?>



// Reload Chat Panel

function reload()

{
var viewChat = document.getElementById('viewChat');
viewChat.src = viewChat.src;
setTimeout('reload()',1000);
}

</script>

</body>

</html>