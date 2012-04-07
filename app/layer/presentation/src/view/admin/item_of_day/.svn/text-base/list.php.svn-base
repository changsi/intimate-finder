
<?php
		
?>

<head>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "home.css" ;?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "tcal.css" ;?>"/>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/jquery-1.6.4.js";?>"></script>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/scripts.js";?>"></script>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/tcal.js";?>"></script>
	<script type="text/javascript">	

		function showContent(data){
			$('tr#content').html('');
			var cnt = 1;
			
			for(var obj in data) {
				$('#pageResult').append("<tr id=content><td id=td_"+cnt+">"+data[obj]['tribe_name']+"</td><td id=item_"+cnt+">"+data[obj]['name']+"</td>"+
						"<td><a href='#' id="+cnt+" onClick = fillTheForm(this);> edit </a> </td>"+ 
						"<td><a href='#' id="+cnt+" onClick = deleteItem(this);> delete </a> </td>"+
						"</tr>");
				++cnt;
			}
		}

		function deleteItem(Field) {
			document.getElementById("delFlag").value = 1;
			fillTheForm(Field);
			
			document.myForm.submit();

		}
		function getItemOfDay(date){
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

		function fillTheForm(field) {
				
				var tribeCell = "td_"+field.id;
				var tribeID = $("td#"+tribeCell).html();
				var tribe_dropdownlistbox = document.getElementById("tribe");
				
				 
				for(var x = 0; x < tribe_dropdownlistbox.length  ; x++) {
					
				   if(tribeID == tribe_dropdownlistbox.options[x].text)
				   	tribe_dropdownlistbox.selectedIndex = x;
				}				
				
				fecthItemsForTheTribe();
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

		function fecthItemsForTheTribe() {

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
					fillItemsForTheTribe(data);
				},  
			    "html"
			);	
		}

		function fillItemsForTheTribe(data) {
			$('select#item').html('');
			
			for(var obj in data) {
				$('#item').append("<option value="+data[obj]['object_id']+'#'+data[obj]['object_type_id']+">"+data[obj]['name']+"</option>");
			}
		}
		
		function fillTheList(){
			var date = document.getElementById("date").value;
			getItemOfDay(date);
		}
	
	</script>
	
</head>


<body>
<form id="myForm" name ="myForm" method="POST">
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
	            	<p><h4>Item of the day</h4></p>
	          </ul>
	        
	           
	            <table border="1" id="updateControls" style="border-width: 1px; border-color:#000000;border-style: solid;">
	           		<tr>
	           			<td>Day</td>
	           			<td><input type="text" id="date" name="date" class="tcal" value=<?php echo $date;?>/></td>
	           		</tr>
	           		<tr>
	           			<td>Tribe </td>
	           			<td>
	           				<select id="tribe" name="tribe" onchange="fecthItemsForTheTribe()">
	           					<option value="">Select Tribe</option>
	           					
	           					<?php
	           					foreach ($tribes as $value) {?>
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
	           
				<table border="1" id="pageResult" style="border-width: 1px; border-color:#000000;border-style: solid;">
					<tr>
						<th>Tribe Name</th>
						<th>Item of the day</th>
					</tr>
				</table>
		</div>
     </div>
     </form>

     <script type="text/javascript">
	<?php 
		echo "fillTheList();";
	?>
</script>
</body>
