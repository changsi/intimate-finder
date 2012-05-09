<?php //ob_clean();?>
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
.resize{
	max-width:100%;
	max-height:150px;
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
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse"
					data-target=".nav-collapse"> <span class="icon-bar"></span> <span
					class="icon-bar"></span> <span class="icon-bar"></span>
				</a> <a class="brand"
					href="/intimate-finder/app/sn/facebook/welcome">Intimate Finder</a>
				<div class="nav-collapse">
					<ul class="nav">
						<li><a href="#">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#contact">Contact</a></li>
						<li><a href="../../admin/admin">Admin</a></li>
					</ul>
				</div>
				<!--/.nav-collapse -->
			</div>
		</div>
	</div>

	<div class="container">

		<?php 
			print_my_information($my_profile,$my_locations);
			
			foreach($user_rank as  $rank){
				$user_id = $rank['user_id'];
				$score = $rank['frequency'];
				$user_profile = $user_profiles[$user_id];
				$user_profile['score'] = $score;
				$close_location = $users_locations[$user_id];
				$close_location = location_sort_by_frequency($close_location);
				$new_location = $users_new_locations[$user_id];
				$new_location = location_sort_by_frequency($new_location);
				print_user_information($user_profile,$close_location,$new_location);
			}
		?>
		


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
	<script src="/intimate-finder/bootstrap/js/bootstrap-modal.jsm"></script>
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

<?php 

function print_my_information($profile, $locations){
	echo '<div class="row">
	
	<div class="span2">';
	//$user_profile = array('user_id'=>100001504551254, 'birth_date'=>'1987-06-21', 'gender'=>'0', 'name'=>'Si Chang');
	
	echo print_user_profile($profile);
	
	echo '</div>
	
	<div class="span10">
		
	<div class="row">
	<span class="badge badge-inverse">Places that you went before</span>
	</div>
	<hr>
	<div class="row">';
	
		
	echo print_row_locations($locations,0);
	
	echo '</div>
		
	</div>
	
	
	
	</div>';
}

function print_user_information($profile, $close_location, $new_location){
	echo '<hr>
	<div class="row">
	
	<div class="span2">';
	//$user_profile = array('user_id'=>100001504551254, 'birth_date'=>'1987-06-21', 'gender'=>'0', 'name'=>'Si Chang');
	
	echo print_user_profile($profile);
	
	echo '</div>
	
				<div class="span10">';
				if(!empty($close_location)){
					echo	'<div class="row">
					<span class="badge badge-inverse">close palces you both went</span>
					</div>
					<hr>
					<div class="row" >';
					
						
					echo print_row_locations($close_location,0);
					
					echo '</div>';
				}
				
				if(!empty($new_location)){
					echo	'<div class="row">
					<span class="badge badge-inverse">palces you may be interested in</span>
					</div>
					<hr>
					<div class="row">';
						
					echo print_row_locations($new_location,0);
					
					echo	'</div>';
				}		
				
					
			echo 	'</div>
			</div>';
}
 
function birthday ($birthday){
	list($year,$month,$day) = explode("-",$birthday);
	$year_diff  = date("Y") - $year;
	$month_diff = date("m") - $month;
	$day_diff   = date("d") - $day;
	if ($day_diff < 0 || $month_diff < 0)
		$year_diff--;
	return $year_diff;
	}
 


function print_user_profile($profile){
	$user_id = $profile['user_id'];
	$picture_url = 'http://graph.facebook.com/'.$user_id.'/picture?type=large';
	
	$age = birthday($profile['birth_date']);
	$gender = $profile['gender']==0?"male":($profile['gender']==1?"female":"unknown");
	$name = $profile['name'];
	$result = '<div class="row">
	<div class="thumbnail span1">
	<a href="http://www.facebook.com/'.$user_id.'">
	<img class="resize"
	src="'.$picture_url.'"
	alt="">
	</a>
	</div>
	</div>
	
	<div class="caption">
	<p>
		
		
	<p>
	<span class="label label-inverse">Name:  '.$name.'</span>
	</p>
	<p>
		
		
	<p>
	<span class="label label-inverse">Gender: '.ucwords($gender).'</span>
	</p>
	<p>
		
		
	<p>
	<span class="label label-inverse">Age: '.$age.'</span>
	</p>
	<p>'.
	(isset($profile['score'])? '<p>
	<span class="label label-inverse">Score: '.$profile['score'].'</span>
	</p>
	<p>':'')	
		
	.'<p>
	<a href="http://www.facebook.com/'.$user_id.'" class="btn btn-primary">facebook profile</a>
	</p>
	</div>';
	return $result;
}
// 5 locations is a row
function print_row_locations($locations, $start){
	$result = '<ul class="thumbnails">';
	
	for($i=0; $i<5; $i++){
		if($start>=sizeof($locations)){
			break;
		}else{
			$result = $result.print_location($locations[$start]);
		}
		$start++;
	}
	
	$result = $result. '</ul>';
	return $result;
}

function print_location($location){
	$location_id = $location['location_id'];
	$picture_url = 'http://graph.facebook.com/'.$location_id.'/picture?type=large';
	$name = $location['name'];
	$frequency = $location['frequency'];
	$address = (isset($location['street'])?$location['street'].' ':'').
				(isset($location['city'])?$location['city'].' ':'').
				(isset($location['state'])?$location['state'].' ':'').
				(isset($location['country'])?$location['country'].' ':'').
				(isset($location['zip'])?$location['zip'].' ':'');
	$result = '<li class="span2">'.
	
	'<div class="thumbnail">
	
	
	<a href="http://www.facebook.com/'.$location_id.'">
	
	<img class="resize" 
	src="'.$picture_url.'"
	alt="" > 
	
	</a>
	
	<div class="caption">
	<p>
		<span class="label label-important">have been here '.$frequency.' times</span>
	</p>
	<h5>'.$name.'</h5>
	<h6>'.$address.'</h6>
	<p>
	<a href="http://www.facebook.com/'.$location_id.'" class="btn btn-primary">facebook link</a>
	</p>
	</div>'.
	'</div>'.
	
	'</li>';
	return $result;
}
?>


