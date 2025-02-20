<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>test</title>
  </head>
  <body>
    <h1 id="h1" onclick="showAlert()">Hello, world!</h1>



    <script>
     
     function mOver(){
	 document.getElementById("h1").innerHTML = "Hello, Rumba!";
     }

     function mOut(){
	 var x = document.getElementById("h1");
	 x.style="color: red";
	 x.innerHTML = "<p> Hello, Vladi </p>";
     }

     function showAlert(){
	 var request = new XMLHttpRequest();
	 request.open("get", "js_test_server.php");

	 request.onreadystatechange = function(e){
	     if(this.readyState == 4 && this.status == 200){
		 var response = this.responseText;

		 document.getElementById("h1").innerHTML = response;
	     }
	 }
	 
	 request.send();
     }
    </script>
  </body>
</html>
