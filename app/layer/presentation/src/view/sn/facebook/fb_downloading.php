<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>Left or Right</title>
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

.center_text {
	text-align: center;
}

.thumbnails {
	text-align: center;
}

.thumbnails>li {
	display: inline-block;
	*display: inline; /* ie7 fix */
	float: none; /* this is the part that makes it work */
}

.logal {
	padding: 1px 3px 2px;
	font-size: 25.75px;
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

<script>
	var progress_var = 0;
  	$(function() {
  	    var progress = setInterval(function() {
  	    	if (progress_var>=100) {
  	   	        clearInterval(progress);
  	   	        $('.progress').removeClass('active');
  	   	    } else {
  	   	    	check_progression();
  	   	    }
  	    
  	}, 3000);
  	});

	function move_progression_bar(){
		var $bar = $('.bar');
  	   // if (progress_var>=100) {
  	     //   clearInterval(progress);
  	       // $('.progress').removeClass('active');
  	   // } else {
  	    	
  	        $bar.width(progress_var+"%");
  	        
  	     	$bar.text(progress_var+"%");
  	    
	}
  	
  	$(function(){
  		trigger_download();
  	});

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

  	function check_progression(){
		var xmlhttp1=null;
		xmlhttp1 = CreateHTTPRequestObject();
	  
	  	xmlhttp1.onreadystatechange=function()
	    {
	    if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
	      {
		      //alert(xmlhttp1.responseText);
		    if(progress_var != xmlhttp1.responseText){
			    progress_var = xmlhttp1.responseText;
			    move_progression_bar();
	      }
	    }
	    };
		xmlhttp1.open("GET","/intimate-finder/app/sn/facebook/check_progression?data="+progress_var,true);
	  	xmlhttp1.send(null);
	}

	function trigger_download(){
		var xmlhttp=null;
		xmlhttp=CreateHTTPRequestObject();
	  	
	  	xmlhttp.onreadystatechange=function()
	    {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
	      {
		      if(xmlhttp.responseText=='false'){
		    	alert("your data is being downloaded!");
		      }
	      
	      
	      }
	    };
	    
	    
	    
		xmlhttp.open("GET","/intimate-finder/app/sn/facebook/trigger_download",true);
	  	xmlhttp.send(null);
	}

  	
  	</script>
</head>

<body>

	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse"
					data-target=".nav-collapse"> <span class="icon-bar"></span> <span
					class="icon-bar"></span> <span class="icon-bar"></span> <span
					class="icon-bar"></span>
				</a> <a class="brand"
					href="/intimate-finder/app/sn/facebook/welcome"> <span
					class="badge badge-success logal">Left</span> <span
					class="badge badge-warning logal">or</span> <span
					class="badge badge-info logal">Right</span>
				</a>

				<div class="btn-group pull-right">
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i
						class="icon-user"></i> <?php 
						if(isset($my_user_name)){
							echo $my_user_name;
						}
						else{
							echo "guest";
						}
						?> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#">Profile</a></li>
						<li class="divider"></li>
						<li><a href="/intimate-finder/app/sn/facebook/logout">Sign Out</a>
						</li>
					</ul>
				</div>
				<div class="nav-collapse">
					<ul class="nav">
						<li><a href="/intimate-finder/app/sn/facebook/welcome">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#contact">Contact</a></li>
						<li><a href="/intimate-finder/app/admin/admin">Admin</a></li>
					</ul>
				</div>
				<!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<div class="container">

		<div class="row">

			<ul class="thumbnails">
				<li class="span6">

					<div class="thumbnail">

						<img src="/intimate-finder/resource/download.jpg" alt="">
						<div class="center_text">

							<h2>Your data is downloading!</h2>
							<p>Thank you for your patience!</p>
						</div>
					</div>

				</li>

			</ul>

		</div>
		<div class="row">
			<div class="container" id="bar_container">
				<div class="progress progress-striped active">
					<div class="bar" style="width: 0%;"></div>
				</div>
			</div>
		</div>





		<hr>

		<footer>
			<p>© Si Chang 2012</p>
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



