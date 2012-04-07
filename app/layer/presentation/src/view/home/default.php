<head>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "home.css" ;?>"/>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/jquery-1.6.4.js";?>"></script>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/scripts.js";?>"></script>
	<script src="http://connect.facebook.net/en_US/all.js"></script>
	<script type="text/javascript">	
	
		//added by si chang for testing invite button
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
	
		//added by si chang for testing like button
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
		
		function getEntityAndUpdateContent(url) {
			url = "<?php echo HOST_PREFIX; ?>" + url;
			var item = (arguments[2]) ? arguments[2] : false; //subItem is used for populating the second branch of the tree
			var type = (arguments[1]) ? arguments[1] : 0; //type is used to determine on which menu button the user clicked
			
			jQuery.get (
				url,  
	         	{},
		        function(responseText) {
		        		alert(responseText);
					eval(responseText);
					if (data == '') {
						alert('Sorry, empty result');
						return;
					}
					updateContent(data, item, type);
					prepareList(); //to collapse all the list items
					showListcontrol();
		        },  
		        "html"
		     );
		}
		
		function updateContent(data, item, type) {
			obj_array = new Array();
			
			if (item) {
				//if there are already sub items inside, flush the content before reappending 
				if ($('#tag_' + item + ' > ul').length > 0) {
					$('#tag_' + item + ' > ul').remove();
				}
				
				$('#tag_' + item).toggleClass('collapsed');
				
				for(var obj in data) {
					addSubItem(data[obj], type, item);
				}
				
				//Enclose all the li elmts with ul inside the item
				$('#tag_' + item + ' > li').wrapAll(document.createElement("ul"));

			}	
			else {
				clearContent('expList');
				for(var obj in data) {
					addItem(data[obj], type);
				}	
			}
		}
		
		function clearContent(tag) {
			$('#' + tag).html('');
		}
		
		function addItem(data, type) {
			var item = createItemHTML(data, type);
			
			$('#expList').append(item);
		}
		
		function createItemHTML(data, type) {
			switch(type) {
				case 0:
					return '<li id="tag_' + data["dp_id"] + '"><span>DP id :' + data["dp_id"] + ' </span><a href="#" onClick="getEntityAndUpdateContent(\'/dp/get_dp_categories?dpid=' + data["dp_id"] + '\', 0,' + data["dp_id"] + ' )" title="' + data["dp_id"] + '">Show categories</a> - <a href="#" onClick="getEntityAndUpdateContent(\'/dp/get_users_by_dp?dpid=' + data["dp_id"] + '\', 1,' + data["dp_id"] + ' )" title="' + data["dp_id"] + '">Show users</a></li>';
				break;
				case 'mycategories':
					return '<li id="tag_' + data["category_id"] + '"><span>' + data["name"] + ' - ' + data["affinity"] + ' - ' + data["category_id"] + ' - ' + data['description'] + '</span></li>';
				break;
				case 'myfriends':
					return '<li id="tag_' + data["friend_id"] + '"><span>' + data["name"] + ' - id: ' + data["friend_id"] +'</span><a href="#" onClick="getEntityAndUpdateContent(\'/user/get_user_categories?uid=' + data["friend_id"] + '\', 0,' + data["friend_id"] + ' )" title="' + data["friend_id"] + '">More...</a></li>';
				break;
				case 'myfriendsaffinity':
					return '<li id="tag_' + data["friend_id"] + '"><span>' + data["name"] + ' - id: ' + data["friend_id"] +' - affinity: ' + data["affinity"] +'</span></li>';
				break;
				case 'myrecommendations':
					return '<li id="tag_' + data["object_id"] + '"><span>' + data["name"] + ' - Object-id: ' + data["object_id"] +' - Object-type-id: ' + data["object_type_id"] +'</span></li>';
				break;
				case 'mytribes':
					return '<li id="tag_' + data["id"] + '"><span>' + data["name"] + ' - description: ' + data["description"] +'</span></li>';
				break;
				case 'myfriendstribes':
					return '<li id="tag_' + data["network_user_id"] + '"><span>' + data["friend_name"] + ' - id: ' + data["network_user_id"] +' - tribe: ' + data["tribe_name"] +'</span></li>';
				break;
				default:
					return '<li>Error</li>';
			}
		}
		
		function addSubItem(data, type, item) {
			var subItem = createSubItemHTML(data, type);
			
			$('#tag_' + item).append(subItem);
			
		}
		
		function createSubItemHTML(data, type) {
			switch (type) {
				case 0://categories for the dp
					return '<li>' + data["name"] + ' - ' + data["affinity"] + ' - ' + data["category_id"] + ' - ' + data['description'] + '</li>';
				break;
				case 1://users for the dp
					return '<li>' + data["user_id"] + ' - ' + data["name"] + '</li>';				
				break;
			}
		}
		
		function addOpenUlTag(item) {
			$('#tag_' + item).append("<ul>");
		}
		
		function addClosingUlTag(item) {
			$('#tag_' + item).append("</ul>");
		}
		
		
		function showListcontrol() {
			var root = document.getElementById("listControl");
			
			root.style.display = "block";
		}
		
		
		
	</script>
</head>


<body>
	<div id="left">
		<ul class="svertical">
			<li><a href="#" onClick="getEntityAndUpdateContent('<?php echo "/user/get_user_friends?uid=".$login_user_id."&type=info" ;?>', 'myfriends')">My Friends -> categories</a></li>
			<li><a href="#" onClick="getEntityAndUpdateContent('<?php echo "/user/get_user_friends?uid=".$login_user_id."&type=affinity" ;?>', 'myfriendsaffinity')">My Friends affinity</a></li>	
			<li><a href="#" onClick="getEntityAndUpdateContent('<?php echo "/user/get_user_categories?uid=".$login_user_id ;?>', 'mycategories')">My Categories</a></li>	
			<li><a href="#" onClick="getEntityAndUpdateContent('<?php echo "/dp/get_user_dps?uid=".$login_user_id ;?>')"><del>My dynamic DPS</del></a></li>
			<li><a href="#" onClick="getEntityAndUpdateContent('<?php echo "/dp/get_friends_dps?uid=".$login_user_id ;?>')"><del>My Friends dynamic DPS</del></a></li>
			<li><a href="#" onClick="getEntityAndUpdateContent('<?php echo "/content/trend_user_content?uid=".$login_user_id ;?>', 'myrecommendations')">My Recommendations</a></li>
			<li><a href="#" onClick="getEntityAndUpdateContent('<?php echo "/user/get_user_tribe?uid=".$login_user_id ;?>', 'mytribes')">My Tribe</a></li>
			<li><a href="#" onClick="getEntityAndUpdateContent('<?php echo "/user/get_friends_tribes?uid=".$login_user_id ;?>', 'myfriendstribes')">My friends Tribes</a></li>
			<li><a href="<?php echo HOST_PREFIX."/content/item_of_day" ;?>">Items of the day</a></li>
			<li><a href="<?php echo HOST_PREFIX."/admin/home" ;?>">Admin</a></li>
			<li><a href="#" onClick="getEntityAndUpdateContent('<?php echo "/tribe/get_user_tribes?uid=".$login_user_id ;?>', 'mytribes')"><del>My Tribes</del></a></li>
			<li><button id="123" onClick="updateLikeInfor('/content/like?network_user_id=111&object_type_id=2&object_id=333')" type="button"><?php  
		$network_user_id = '111';
		$object_type_id = '2';
		$object_id = '333';
		$data = array("network_user_id"=>$network_user_id, "object_type_id"=>$object_type_id, "object_id"=>$object_id,'action_type_id'=>'1','network_id'=>'2');
		if($ContentService->checkLikeStatus($data)){
			echo "unlike";
		}else{
			echo "like";
		}
	?></button>
	 <input id="aaa" type="text" value="<?php
		$object_type_id = '2';
		$object_id = '333';
		$data = array( "object_type_id"=>$object_type_id, "object_id"=>$object_id, 'action_type_id'=>'1','network_id'=>'2');
		echo $ContentService->getObjectLikeCounts($data);?>" /></li>
		<li><div id="fb-root"></div>
   			<button type="button" onclick="invite()">invite friends</button></li>
   		<li>
   			<form id='form' action='".HOST_PREFIX."/home/come_back_later' method='post'>
			<input name='submit' type='submit' value='Submit'>
			</form> </li>
		</ul>
	</div>
	<div id="right">
		<div id="listContainer">
	          <div id="listControl">
	               <a id="expandList">Expand All</a>
	               <a id="collapseList">Collapse All</a>
	          </div>
	          <ul id="expList">
	             <!--<li>
	               <span>Item A</span>
	               <ul>
	                    <li>
	                         Item A.1
	                         <ul>
	                              <li>
	                                   <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sagittis ultricies arcu, quis porttitor risus placerat et. Proin quis metus diam, quis bibendum dolor. Nulla nec dapibus nunc. Quisque ac erat sit amet nisl venenatis consequat nec in nibh. Aliquam viverra vestibulum elit faucibus sollicitudin.</span>
	                              </li>
	                         </ul>
	                    </li>
	                    <li>
	                         Item A.2
	                    </li>
	                    <li>
	                         Item A.3
	                         <ul>
	                              <li>
	                                   <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sagittis ultricies arcu, quis porttitor risus placerat et. Proin quis metus diam, quis bibendum dolor. Nulla nec dapibus nunc. Quisque ac erat sit amet nisl venenatis consequat nec in nibh. Aliquam viverra vestibulum elit faucibus sollicitudin.</span>
	                              </li>
	                         </ul>
	                    </li>
	               </ul>
	               </li>
	               <li>
	               Item B
	               </li>
	               <li>
	               Item C
	               <ul>
	                    <li>
	                         Item C.1
	                    </li>
	                    <li>
	                         Item C.2
	                         <ul>
	                              <li>
	                                   <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sagittis ultricies arcu, quis porttitor risus placerat et. Proin quis metus diam, quis bibendum dolor. Nulla nec dapibus nunc. Quisque ac erat sit amet nisl venenatis consequat nec in nibh. Aliquam viverra vestibulum elit faucibus sollicitudin.</span>
	                              </li>
	                         </ul>
	                    </li>
	               </ul>
	               </li>-->
	          </ul>
		</div>
     </div>
</body>
