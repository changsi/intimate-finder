<?php //ob_clean();?>
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
.resize_location_pic{
	max-width:100%;
	max-height:70px;
}

.resize_profile_pic{
	max-width:100%;
	max-height:100%;
}

.logal {
    padding: 1px 3px 2px;
    font-size:25.75px; 
}

span.interest
{
margin-top:10px;
margin-bottom:10px;
margin-right:5px;
margin-left:10px;
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
              <li><a href="/intimate-finder/app/sn/facebook/welcome">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
              <li><a href="/intimate-finder/app/admin/admin">Admin</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

	<div class="container">

		<?php 
			print_my_information($my_profile,$my_locations);
			
			foreach($user_rank as  $rank){
				$user_id = $rank['user_id'];
				$score = $rank['score'];
				$user_profile = $user_profiles[$user_id];
				$user_profile['score'] = $score;
				$close_location = $users_locations[$user_id];
				$close_location = location_sort_by_frequency($close_location);
				//$new_location = $users_new_locations[$user_id];
				//$new_location = location_sort_by_frequency($new_location);
				$user_interests = isset($user_common_interests[$user_id])? $user_common_interests[$user_id]:null;
				print_user_information($user_profile,$close_location, $user_interests);
			}
		?>
		


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
	<div class="row-fluid">';
	
		
	echo print_row_locations($locations,0);
	
	echo '</div>
		
	</div>
	
	
	
	</div>';
}

function print_user_information($profile, $close_location, $interests){
	
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
					<div class="row-fluid" >';
					
						
					echo print_row_locations($close_location,0);
					
					
					
					echo '</div>';
					
				}
				/*
				if(!empty($new_location)){
					echo	'<div class="row">
					<span class="badge badge-inverse">palces you may be interested in</span>
					</div>
					<hr>
					<div class="row">';
						
					echo print_row_locations($new_location,0);
					
					echo	'</div>';
				}		
				*/
				
				echo	'<hr>
				<div class="row">';
				echo print_user_interests($interests);
				echo	'</div>';
					
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
 
function print_user_interests($interests){
	$result = "";
	if(isset($interests) && !empty($interests)){
		
		foreach($interests as $interest){
			$result = $result. '  <span class="label label-info interest">'.$interest.'   </span>  ';
		}
		
	}
	
	return $result;
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
	<img class="resize_profile_pic"
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
	//$result = '<ul class="thumbnails">';
	$result = '<div class="row">';
	for($i=0; $i<6; $i++){
		if($start>=sizeof($locations)){
			break;
		}else{
			$result = $result.print_location_2($locations[$start]);
		}
		$start++;
	}
	$result = $result.'</div>';
	
	$result = $result.'<div class="row">';
	for($i=6; $i<12; $i++){
		if($start>=sizeof($locations)){
			break;
		}else{
			$result = $result.print_location_2($locations[$start]);
		}
		$start++;
	}
	$result = $result.'</div>';
	
	//$result = $result. '</ul>';
	return $result;
}

function print_location($location){
	$location_id = $location['location_id'];
	$picture_url = 'http://graph.facebook.com/'.$location_id.'/picture?type=square';
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
	
	<img class="resize_location_pic" 
	src="'.$picture_url.'"
	alt="" > 
	
	</a>
	
	<div class="caption">
	<p>
		<span class="label label-warning">'.$frequency.' times</span>
	</p>
	<h5>'.$name.'</h5>
	<h6>'.$address.'</h6>
	</div>'.
	'</div>'.
	
	'</li>';
	return $result;
}

function print_location_2($location){
	$location_id = $location['location_id'];
	$picture_url = 'http://graph.facebook.com/'.$location_id.'/picture?type=square';
	$name = $location['name'];
	$frequency = $location['frequency'];
	$address = (isset($location['street'])?$location['street'].' ':'').
	(isset($location['city'])?$location['city'].' ':'').
	(isset($location['state'])?$location['state'].' ':'').
	(isset($location['country'])?$location['country'].' ':'').
	(isset($location['zip'])?$location['zip'].' ':'');
	$result = '<div class="span2">'.

			'
			<div class="row-fluid">
			<div class="span6">
			<a href="http://www.facebook.com/'.$location_id.'">

			<img class="resize_location_pic"
			src="'.$picture_url.'"
			alt="" >

			</a>
			</div>

			<div class="caption">
			<p>
			<span class="label label-important">'.$frequency.' times</span>
			</p>
			
			</div>
			</div>'.
			'<h5>'.$name.'</h5>'.
			'</div>';
	return $result;
}
?>


