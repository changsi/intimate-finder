<head>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "home.css" ;?>"/>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/jquery-1.6.4.js";?>"></script>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/scripts.js";?>"></script>
	<script type="text/javascript">	

		function updateLikeInfor(url) {
			url = "<?php echo HOST_PREFIX; ?>" + url;
			var item = (arguments[2]) ? arguments[2] : false; //subItem is used for populating the second branch of the tree
			var type = (arguments[1]) ? arguments[1] : 0; //type is used to determine on which menu button the user clicked
			if($.trim($("#123").html()) == "like"){
				url = url+"&type=0";
			}
			else{
				url = url+"&type=1";
			}
			jQuery.get (
				url,  
	         		{},
		     	function(responseText) {
		        		alert(responseText);
					eval(responseText);
					if(status){
						$("#123").html("unlike");
					}else{
						$("#123").html("like");
					}
					$("#aaa").val(data);
		        	},  
		        	"html"
		     );
		}
		
	</script>
</head>


<body>
	<button id="123" onClick="updateLikeInfor('/content/like?network_user_id=111&object_type_id=2&object_id=333')" type="button"><?php  
		$network_user_id = '111';
		$object_type_id = '2';
		$object_id = '333';
		$data = array("network_user_id"=>$network_user_id, "object_type_id"=>$object_type_id, "object_id"=>$object_id);
		if($ContentService->checkLikeStatus($data)){
			echo "unlike";
		}else{
			echo "like";
		}
	?></button>
	 <input id="aaa" type="text" value="<?php
		$object_type_id = '2';
		$object_id = '333';
		$data = array( "object_type_id"=>$object_type_id, "object_id"=>$object_id);
		echo $ContentService->getObjectCounts($data);?>" />
</body>
