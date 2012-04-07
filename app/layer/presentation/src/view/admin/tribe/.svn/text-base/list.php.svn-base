<?php 
if (isset($data)) {
	echo "var data = " . json_encode($data) . ";";
}
else {
?>
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "home.css" ;?>"/>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/jquery-1.6.4.js";?>"></script>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/scripts.js";?>"></script>
	<script type="text/javascript">	

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
				case 'tribeInfo':
					return '<li id="tag_' + data["id"] + '"><span>Tribe id :' + data["id"] + ' - Name: ' + data["name"] + ' - Description: ' + data["description"] + ' </span></li>';
				break;
				case 'tribeCategories':
					return '<li>' + data["name"] + ' - ' + data["affinity"] + ' - ' + data["category_id"] + '</li>';
				break;
				case 'tribeDps':
					return '<li id="tag_' + data["dp_id"] + '">DP id : ' + data["dp_id"] + ' <a href="#" onClick="getEntityAndUpdateContent(\'/dp/get_users_by_dp?dpid=' + data["dp_id"] + '\', 1,' + data["dp_id"] + ' )" title="' + data["dp_id"] + '">Show users</a> - <a href="#" onClick="getEntityAndUpdateContent(\'/dp/get_dp_categories?dpid=' + data["dp_id"] + '\', 0,' + data["dp_id"] + ' )" title="' + data["dp_id"] + '">Show categories</a></li>';
				break;
				case 'tribeUsers':
					return '<li>Name : ' + data["name"] + ' -  screen_name : ' +  data["screen_name"] + ' - Member since :' + data["created_date"] + '</li>';
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
		
		function toggleOptions(id) {
			$('#'+id).toggle();		
		}
		
	</script>
</head>

<body>
	<div id="left">
		<ul class="svertical">
			<?php 
				foreach ($tribes as $tribe) {
				 	echo '<li><a href="#" onClick="toggleOptions(\''.$tribe['id'].'\')">'.$tribe['name'].'</a></li>';
				 	echo '<ul id="'.$tribe['id'].'" style="display:none;">
				 			<li><a href="#" onClick="getEntityAndUpdateContent(\'/admin/tribe/get_related_dps?tid='.$tribe['id'].'\', \'tribeDps\')"><del>Get related Dps</del></a></li>
				 			<li><a href="#" onClick="getEntityAndUpdateContent(\'/admin/tribe/get_related_users?tid='.$tribe['id'].'\', \'tribeUsers\')"><del>Get all related users</del></a></li>
				 			<li><a href="#" onClick="getEntityAndUpdateContent(\'/admin/tribe/get_tribe_users?tid='.$tribe['id'].'\', \'tribeUsers\')">Get users</a></li>
				 			<li><a href="#" onClick="getEntityAndUpdateContent(\'/admin/tribe/get_tribe_categories?tid='.$tribe['id'].'\', \'tribeCategories\')">Get tribe categories</a></li>
				 			<li><a href="#" onClick="getEntityAndUpdateContent(\'/admin/tribe/list?tid='.$tribe['id'].'\', \'tribeInfo\')">Get tribe info</a></li>
				 			<li><input type="button" value="Edit '.$tribe['name'].'" onClick="window.location.href=\''.HOST_PREFIX.'/admin/tribe/update?tid='.$tribe['id'].'\'"></li><br />
				 		</ul>';
				}
				echo '<br /><li><input type="button" value="Create new tribe" onClick="window.location.href=\''.HOST_PREFIX.'/admin/tribe/update\'"></li>';
			?>
		</ul>
	</div>
	<div id="right">
		<div id="listContainer">
	          <div id="listControl">
	               <a id="expandList">Expand All</a>
	               <a id="collapseList">Collapse All</a>
	          </div>
	          <ul id="expList">
	        	</ul>
		</div>
     </div>
</body>
<?php
}
?>
