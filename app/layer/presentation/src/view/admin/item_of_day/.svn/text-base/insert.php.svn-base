<?php

?>
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "home.css" ;?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "tcal.css" ;?>"/>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/jquery-1.6.4.js";?>"></script>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/scripts.js";?>"></script>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/tcal_insertItem.js";?>"></script>
	<script type="text/javascript">	
	function showContent(data) {
		$('select#item').html('');
		
		for(var obj in data) {
			$('#item').append("<option value="+data[obj]['object_id']+'#'+data[obj]['object_type_id']+">"+data[obj]['name']+"</option>");
		}
	}
	
	function getItemOfDay1(date){
				url = "<?php echo HOST_PREFIX ;?>/content/get_item_of_day?date=";
				url  = url + date;
				jQuery.get (
					url,  
		         	{},
			        function(responseText) {
		         		//alert("responseText"+responseText);
						eval(responseText);
						showContent(data);
			        },  
			        "html"
			     );
	}	

	function fecthItemsForTheTribe() {
		
		if(validateForm()) {
			
			var date = document.getElementById("date").value;
			var tribe = document.getElementById("tribe").value;
			url = "<?php echo HOST_PREFIX ;?>/admin/item_of_day/get_items_for_tribe?date=";
			url  = url +date+"&tribe="+tribe;

			jQuery.get (
				url,  
	         	{},
		        function(responseText) {
					//alert("responseText"+responseText);
					eval(responseText);
					showContent(data);
		       },  
		       "html"
		   );
		}
	}

	function validateForm(){
		var tribe = document.getElementById("tribe").value;
		var date = document.getElementById("date").value;
		if(tribe!="" && date!=""){
			return true;
		}
	}

	function validateEntireForm(){
		var tribe = document.getElementById("tribe").value;
		var date = document.getElementById("date").value;
		var item = document.getElementById("item").value;
		var msg  = "";
		if(tribe == ""){
			msg+= "Please select Tribe\n";
		}
		if(date == ""){
			msg+= "Please select Date\n";
		}
		if(item == ""){
			msg+= "Please select Item\n";
		}
		if(msg!=""){
			alert(msg);
			return false;
		}
		return true;
	}

	function insertNewItem(){
		if(validateNewItemForm()){
			 document.adminInsertItemOfDAyForm.submit();

		}
	}

	function validateNewItemForm(){
		var url = document.getElementById("url").value;
		var name = document.getElementById("name").value;
		
		var msg  = "";
		
		if(url == ""){
			msg+= "Please enter url\n";
		}
		if(name == ""){
			msg+= "Please enter name\n";
		}
		
		if(msg!=""){
			alert(msg);
			return false;
		}
		return true;
	}

	function disableButton(){
		if(document.getElementById("rdb2").checked){
			document.getElementById("btn1").disabled = true;
			document.getElementById("btn2").disabled = false;
		}
		if(document.getElementById("rdb1").checked){
			document.getElementById("btn2").disabled = true;
			document.getElementById("btn1").disabled = false;
		}
			
	}
	
	</script>
</head>
<body onload="disableButton()">
<form id="adminInsertItemOfDAyForm" name="adminInsertItemOfDAyForm" action="./insert" method="POST">
	<div id="left" >
		<ul class="svertical">
			<div>
				<h3>Insert</h3>
			</div>
		</ul>
	</div>
	<div id="right" style="background:white;border:1">
		<div id="listContainer" style="background:white">
	          <div id="listControl">
	               <a id="expandList">Expand All</a>
	               <a id="collapseList">Collapse All</a>
	          </div>
	          <ul id="expList">
	            	<p><h4>Item of the day</h4></p>
	          </ul>
	           <table border="1" id="pageResult" style="border-width: 1px; border-color:#000000;border-style: solid;">
	           		<tr>
	           			<td>Day</td>
	           			<td><input type="text" id="date" name="date" class="tcal" value="" onchange=""/></td>
	           		</tr>
	           		<tr>
	           			<td>Tribe</td>
	           			<td>
	           				<select id="tribe" name="tribe" onchange="fecthItemsForTheTribe()">
	           					<option value="">Select Tribe</option>
	           					<?php
	           					foreach ($data as $value) {?>
	           						<option value="<?php echo $value["id"];?>">
	           							<?php echo $value["name"]; ?>
	           						</option>
	           					<?php }?>
	           				</select>
	           			</td>
	           		</tr>
	           		<tr>
	           			<td>Item</td>
	           			<td>
	           				<select id="item" name="item">
	           					<option value="">Select Item</option>
	           				</select>
	           			</td>
	           		</tr>
	           		<tr>
	           			<td colspan="2">
	           				<input  type="button" id="btn1" value="Submit" onClick = "if(validateEntireForm())submit();"/>
	           			</td>
	           		</tr>
	           		<tr>
	           			<td colspan="2">&nbsp;</td>
	           		</tr>
	           		<tr>
	           			<td colspan="2">
	           				<p>
	           					<font size="3" face="verdana" color="green">
	           						<?php 
										if(isset($submitFlag)){
											if ($submitFlag) echo "Sucess";
											else echo "Error";
										}
									?>
	           				</font>
	           				</p>
	           			</td>
	           		</tr>
	           </table>
	           	
				<input type="radio" name="group1" id="rdb1" value="0" onclick="disableButton()" checked> Item of the day<br>
				<input type="radio" name="group1" id="rdb2" value="1" onclick="disableButton()" > New Item<br>
	           
	           <ul id="expList">
	            	<p><h4>New Item</h4></p>
	          	</ul>
	           <table border="1" id="pageResult" style="border-width: 1px; border-color:#000000;border-style: solid;">
	           		<tr>
	           			<td>url</td>
	           			<td><input type="text" id="url" name="url"  value=""/></td>
	           		</tr>
	           		<tr>
	           			<td>name</td>
	           			<td>
	           				<input type="text" id="name" name="name"/>
	           			</td>
	           		</tr>
	           		<tr>
	           			<td>author</td>
	           			<td><input type="text" id="author" name="author"/></td>
	           		</tr>
	           		<tr>
	           			<td>genre</td>
	           			<td><input type="text" id="genre" name="genre"/></td>
	           		</tr>
	           		
	           		<tr>
	           			<td>genre2</td>
	           			<td><input type="text" id="genre2" name="genre2"/></td>
	           		</tr>
	           		
	           		<tr>
	           			<td colspan="2">
	           				<input type="button" id="btn2" value="Submit" onclick="insertNewItem()"/>
	           			</td>
	           		</tr>
	           		<tr>
	           			<td colspan="2">&nbsp;</td>
	           		</tr>
	           		<tr>
	           			<td colspan="2">
	           				<p>
	           					<font size="3" face="verdana" color="green">
	           						<?php 
										if(isset($submitFlag1)){
											if ($submitFlag1) echo "Sucess";
											else echo "Error";
										}
									?>
	           				</font>
	           				</p>
	           			</td>
	           		</tr>
	           		
	           </table>
		</div>
     </div>
     </form>
</body>
