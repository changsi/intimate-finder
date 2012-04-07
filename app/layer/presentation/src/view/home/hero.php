<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Bootstrap, from Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="root" >

    <!-- Le styles -->
    <link href="/dorothy_app/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>
    <link href="/dorothy_app/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="http://twitter.github.com/bootstrap/assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Dorothy</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <h1>Hello, world!</h1>
        <p>This is a template for a simple marketing or informational 
website. It includes a large callout called the hero unit and three 
supporting pieces of content. Use it as a starting point to create 
something more unique.</p>
        <p><a class="btn btn-primary btn-large">Learn more »</a></p>
      </div>

      <!-- Example row of columns -->
      <div class="row">
            <div class="tabbable">
    				<ul class="nav nav-tabs">
    				<?php
    					$active = true;
    					$i = 0;
						foreach($categories_objects as $category=>$objects){
							if($active){
								echo "<li class='active'><a href='#".$i."' data-toggle='tab'>".ucfirst(strtolower($category))."</a></li>";
								$active=false;
							}else{
								echo "<li><a href='#".$i."' data-toggle='tab'>".ucfirst(strtolower($category))."</a></li>";
							}
							$i++;
						}
    				?>
    				</ul>
    				<div class="tab-content">
    						    	<?php 
    						    	$active = true;
    						    	$i = 0;
    						    	foreach ($categories_objects as $category=>$objects){
    						    		if($active){
    						    			echo "<div class='tab-pane active' id='".$i."'>";
    						    			print_thumbnails($objects);
    						    			echo "</div>";
    						    			$active = false;
    						    		}
    						    		else{
    						    			echo "<div class='tab-pane' id='".$i."'>";
    						    			print_thumbnails($objects);
    						    			echo "</div>";
    						    		}
    						    		$i++;
    						    	}
    						    	
    						    	?>
    			</div>
      </div>

      <hr>

      <footer>
        <p>© Company 2012</p>
      </footer>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/dorothy_app/bootstrap/js/jquery.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-transition.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-alert.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-modal.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-dropdown.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-scrollspy.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-tab.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-tooltip.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-popover.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-button.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-collapse.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-carousel.js"></script>
    <script src="/dorothy_app/bootstrap/js/bootstrap-typeahead.js"></script>

  

</body></html>

<?php 

function print_row($row){
	foreach($row as $element){
		echo "<li class=\"span4\">";
		echo "<div class=\"thumbnail\">";
		echo "<a href=\"".$element['link']."\">";
		echo "<img src=\"http://graph.facebook.com/".$element['object_id']."/picture?type=large\" alt=\"\">";
		echo "</a>";
		
		echo "<h5>".$element['name']."</h5>";
		echo substr($element['description'],0,200)." ......";
		echo "</div>";
		echo "</li>";
	}
	
}

function print_thumbnails($objects){
	echo "<ul class=\"thumbnails\">";
	$row=ceil(count($objects));
	for($i=0; $i<$row; $i++){
		echo "<div class=\"row\">";
		print_row(array_slice($objects, $i*3, 3));
		echo "</div>";
	}
	echo "</ul>";
}

?>