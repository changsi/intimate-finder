<html>
   <head>
   <title>Skyweaver</title>
   <script src="http://connect.facebook.net/en_US/all.js">
   </script>
   <script>
   <?php
   	
   		echo 'function invite(){
   		FB.init({ 
       		appId:\''.APPID.'\' , cookie:true, 
       		status:true, xfbml:true 
     	});

     	FB.ui({ method: \'apprequests\', 
       		message: \'Here is a new Requests dialog...\'});
   	}';
   	
   ?>
   </script>
   </head>
   <body>
   <div id="fb-root"></div>
   <button type="button" onclick="invite()">invite friends</button>
   </body>
 </html>
