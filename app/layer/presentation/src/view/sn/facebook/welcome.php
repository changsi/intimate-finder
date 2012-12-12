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

.logal {
    padding: 1px 3px 2px;
    font-size:25.75px;
    
    
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


</head>

<body>

	<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          
          <a class="brand" href="/intimate-finder/app/sn/facebook/welcome">
				<span class="badge badge-success logal">Left</span>
				<span class="badge badge-warning logal">or</span>
				<span class="badge badge-info logal">Right</span>
		  </a>
				
          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> 
               <?php 
              if(isset($my_user_name)){
              	echo $my_user_name;
              }
              else{
              	echo "guest";
              }
              
              ?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#">Profile</a></li>
              <li class="divider"></li>
              <li><a href="/intimate-finder/app/sn/facebook/logout">Sign Out</a></li>
            </ul>
          </div>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="/intimate-finder/app/sn/facebook/welcome">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
              <li><a href="/intimate-finder/app/admin/admin">Admin</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

	<div class="container">
		
		
		<div class="row">
			<div class="span4">
				<ul class="thumbnails">
					<li class="span4">

						<div class="thumbnail">

							<img
								src="http://renrutkram.files.wordpress.com/2011/03/istock_000005622581medium.jpg"
								alt="">
						</div>

					</li>

				</ul>
			</div>
			
			<div class="span6">
				
					<h1>Want to Make More Friends Around You</h1>
					<br>
					<p></p>
					<h3>In this city, there are thousands of people you just pass by
						every day and you never get the chance to know them. Our
						application wants to make it easy to discover the hidden
						connections around you, and to meet interesting people.</h3>
					<p> </p>
					<br>
					<p>
						<a class="btn btn-primary btn-large" href="
						<?php 
							if($login){
								echo "/intimate-finder/app/sn/facebook/fb_downloading";
							}else{
								echo "/intimate-finder/app/sn/facebook/login";
							}
						?>"> 
						<?php 
						
						if($login) echo "Download Data";
						else{
							echo "Login Facebook";
						}
						?> 
						
						</a>
						<a class="btn btn-primary btn-large" href="/intimate-finder/app/home/recommendation2"> Show My Recommendation </a>
					</p>
				
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



