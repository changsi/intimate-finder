<?php

if (isset($tribes)) {
?>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "home.css" ;?>"/>
<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/jquery-1.6.4.js";?>"></script>
<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/scripts.js";?>"></script>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
 $(document).ready(function() {
 	$("#more").click(function () {
      	$(".hiddenContainer").slideToggle("slow");
 	});
 });
</script>
<style>
	body {
		width: 100%;
		height: 100%;

	}
	#container {
		float: left;
		width: 100%;
		text-align: center;
	}
	.tribeContainer {
		width: 100%;
		height: 60%;
	}
	.tribeContainer div.left {
		width:30%;
		height:50%;
		margin-right: 6px;
	}
	.hiddenContainer {
		width: 100%;
		height: 60%;
	}
	.hiddenContainer div.left {
		width:22%;
		height:50%;
		margin-right: 6px;
	}
	.left {
		float:left;
	}
	.tribeContainer div.badge  {
		height: 50%;
		padding: 10px 20px 6px;
	}
	.tribeContainer div.badge a {
		width: 100%;
		height: 100%;
		display: block;
		text-decoration: none;
		border: 1px solid #ccc;
		
		color: #999;
		text-decoration: none;
		font-size: 20px;
		line-height: 30px;
		background: #ddd;
		box-shadow: 1px 3px 3px rgba(0,0,0,.5);
		-webkit-box-shadow: 1px 3px 3px rgba(0,0,0,.5);
		-moz-box-shadow: 1px 3px 3px rgba(0,0,0,.5);
		text-shadow: #fff 0px 1px 1px;
		background: -webkit-gradient(linear, left top, left bottom, from(#eeeeee), to(#cccccc));
		background: -moz-linear-gradient(top,  #eeeeee,  #cccccc);
		filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#eeeeee', endColorstr='#cccccc');
	}
	.tribeContainer div.badge a:active {
		box-shadow: 0px 0px 0px rgba(0,0,0,.5);
		-webkit-box-shadow: 0px 0px 0px rgba(0,0,0,.5);
		-moz-box-shadow: 0px 0px 0px rgba(0,0,0,.5);
		position: relative;
		top: 1px;
		left: 1px;
	}
	.hiddenContainer {
		display:	none;
		float: left;
	}
</style>
</head>
<body>
		<div id="container">
			<h4>Pick your tribe</h4>
			<div class="tribeContainer">
				<?php for ($i = 0; $i < 3; $i++) { ?>
				<div class="left">
					<div class="badge">
						<a href=""><?php print($tribes[$i]['info']['name']);?></a>
					</div>
					<?php if (isset($tribes[$i]['affinity'])) {
					?>
					<h5>Affinity:</h5>
					<?php
						print($tribes[$i]['affinity']);
					}
					?>
					<?php if (isset($tribes[$i]['friends_list'])) {
					?>
					<h5>Friends in the tribe:</h5>
					<?php
						print($tribes[$i]['friends_list']);
					}
					?>
					<form method="post" action="<?php print(HOST_PREFIX.'/home/tribe_selection') ;?>">
						<input type="hidden" name="tribe_id" value="<?php print($tribes[0]['tribe_id']); ?>"/>
						<input type="submit" name="submit" value="join"/>
					</form>
				</div>
				<?php 
				} ?>
			</div>
		</div>
		<div class="hiddenContainer">
			<div class="tribeContainer">
				<?php for ($i = 3; $i < count($tribes); $i++) { ?>
				<div class="left">
					<div class="badge">
						<a href=""><?php print($tribes[$i]['info']['name']);?></a>
					</div>
					<?php if (isset($tribes[$i]['affinity'])) {
					?>
					<h5>Affinity:</h5>
					<?php
						print($tribes[$i]['affinity']);
					}
					?>
					<?php if (isset($tribes[$i]['friends_list'])) {
					?>
					<h5>Friends in the tribe:</h5>
					<?php
						print($tribes[$i]['friends_list']);
					}
					?>
				</div>
				<?php 
				} ?>
			</div>
		</div>
		<br/>
		<a id="more" href="#">Show more</a>	   
	</form>
</body>

<?php
}

?>
