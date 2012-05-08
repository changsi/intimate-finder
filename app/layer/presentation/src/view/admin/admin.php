<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>Computational Geometry Project</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="Si Chang">

<!-- Le styles -->
<link href="/intimate-finder/bootstrap/css/bootstrap.css"
	rel="stylesheet">
<style type="text/css">
body {
	padding-top: 60px;
	padding-bottom: 40px;
}
​
</style>
<link href="/intimate-finder/bootstrap/css/bootstrap-responsive.css"
	rel="stylesheet">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!-- Le fav and touch icons -->
<link rel="shortcut icon"
	href="http://twitter.github.com/bootstrap/assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="114x114"
	href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72"
	href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed"
	href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png">

<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">

     function CreateHTTPRequestObject () {
         // although IE supports the XMLHttpRequest object, but it does not work on local files.
     var forceActiveX = (window.ActiveXObject && location.protocol === "file:");
     if (window.XMLHttpRequest && !forceActiveX) {
         return new XMLHttpRequest();
     }
     else {
         try {
             return new ActiveXObject("Microsoft.XMLHTTP");
         } catch(e) {}
     }
     alert ("Your browser doesn't support XML handling!");
     return null;
 }
    
     function trigger_build_kdtree(){
 		var xmlhttp=null;
 		
 		xmlhttp=CreateHTTPRequestObject();
 	  	//alert("works");
 	  	xmlhttp.onreadystatechange=function()
 	    {
 	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
 	      {
 	 	      
 	 	    	alert(xmlhttp.responseText); 
 	      
 	      }
 	    };
 	    
 	    
 		xmlhttp.open("GET","/intimate-finder/app/admin/trigger_build_kdtree",true);
 	  	xmlhttp.send(null);
 	}
   </script>
</head>

<body>

	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse"
					data-target=".nav-collapse"> <span class="icon-bar"></span> <span
					class="icon-bar"></span> <span class="icon-bar"></span>
				</a> <a class="brand" href="/intimate-finder/app/sn/facebook/welcome">Intimate Finder</a>
				<div class="nav-collapse">
					<ul class="nav">
						<li><a href="/intimate-finder/app/sn/facebook/welcome">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#contact">Contact</a></li>
						<li class="active"><a href="admin">Admin</a></li>
					</ul>
				</div>
				<!--/.nav-collapse -->
			</div>
		</div>
	</div>

	<div class="container">


		<div class="row">

			<div class="span6">


				<p>
					<button class="btn btn-primary btn-large" onclick="trigger_build_kdtree()">Build
						Kd-Tree</button>
				</p>

			</div>

		</div>



		<hr>

		<footer>
			<p>© Si Chang, Enyu Wang, Yue Cao 2012</p>
		</footer>

	</div>
	<!-- /container -->

	<!-- Le javascript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="/intimate-finder/bootstrap/js/jquery.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-transition.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-alert.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-modal.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-dropdown.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-scrollspy.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-tab.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-tooltip.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-popover.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-button.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-collapse.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-carousel.js"></script>
	<script src="/intimate-finder/bootstrap/js/bootstrap-typeahead.js"></script>



</body>
</html>



