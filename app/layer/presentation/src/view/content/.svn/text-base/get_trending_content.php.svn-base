<head>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "home.css" ;?>"/>
	<title>Trends</title>
</head>
<body>
<form id="myForm" name ="myForm" method="POST">
<input type="hidden" id="network_user_id" name="network_user_id"  value="<?php echo $userId;?>"/>
<input type="hidden" id="network_id" name="network_id"  value="<?php echo $network_id;?>"/>

	<input type="hidden" id="delFlag" name="delFlag"  value="0"/>
	<div id="left">
		<ul class="svertical">
			
		</ul>
	</div>
	<div id="right" style="background:white;border:1">
		<div id="listContainer">
	          <div id="listControl">
	               <a id="expandList">Expand All</a>
	               <a id="collapseList">Collapse All</a>
	          </div>
	          <ul id="expList">
	            	<p><h4>Trend</h4></p>
	          </ul>
	           <table border="1" id="updateControls" style="border-width: 1px; border-color:#000000;border-style: solid;">
	           		<tr>
	           			<td>
	           				<select id="object_type_id" name="object_type_id" onchange="submit()">
	           					<option value="0">Select Type</option>
	           					<?php 
	           						if(isset($object_types)){
	           						if(isset($object_type_id)) $temp = $object_type_id;
	           						foreach($object_types as $object_type) {
	           						if( $object_type["id"] ==  $temp){
	           							$temp1 = $object_type["name"];
	           						}
	           						
	           					?>
	           						<option value="<?php echo $object_type["id"]?>">
	           							<?php echo $object_type["name"]?>
	           						</option>
	           					<?php 	
	           						}
	           					} 
	           					?>
	           				</select>
	           			</td>
	           		
	           		</tr>
	          		 <tr>
	           				<td><font size="3" face="verdana" color="GREEN"> <?php if(isset($temp1))echo "$temp1";?></font></td>
	           		</tr>
	           		<?php 
	           		if(isset($trendingObjects) && count($trendingObjects) > 0){
	           			$cnt =0;
	           			
	           			foreach($trendingObjects as $trendingObject) {
	           		?>
	           			
	           			<tr>
	           				<td><?php echo ++$cnt.": ";?></td>
	           				<td><a href = "<?php echo $trendingObject["url"]?>" target="_blank"><?php echo $trendingObject["name"]?></a></td>
	           			</tr>
	           		<?php 	
	           			}
	           		} else {
	           		?>
	           			<tr>
	           				<td colspan = 2><font size="3" face="verdana" color="red"> Data currently unavailable</font></td>
	           			</tr>
	           		<?php 		
	           		}
	           		?>
	           	</table>
	           	
	           	 
	    </div>
	</div>
</form>
</body>
	           