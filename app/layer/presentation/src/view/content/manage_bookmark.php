<head>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "home.css" ;?>"/>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/jquery-1.6.4.js";?>"></script>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/scripts.js";?>"></script>
	<script type="text/javascript">	
	function triggerEvent(eventCode) {
		
		var network_id =  document.getElementById("networkid").value;
		var network_user_id = document.getElementById("networkuserid").value;
		var object_type_id =  document.getElementById("objecttypeid").value;
		var object_id =  document.getElementById("objectid").value;
		

		url = "<?php echo HOST_PREFIX ;?>/content/manage_bookmark?event_id=";
		url = url + eventCode+"&network_id="+network_id+"&network_user_id="+network_user_id+"&object_type_id="+object_type_id;
		url = url + "&object_id="+ object_id;
		
		jQuery.get (
			url,  
	       	{},
		    function(responseText) {
//				alert(responseText);
				eval(responseText);
				showContent(data);
			},  
		    "html"
		);	

		if(eventCode!=1) triggerEvent(1);
		
	}


	function showContent(data) {
		$('tr#content').html('');
		var cnt = 1;
		//alert(data);
		for(var obj in data) {
			
			$('#pageResult').append("<tr id=content>"+
									"<td width = 10%>"+cnt+"</td>"+
									"<td><a href=#"+data[obj]['url']+"id="+ data[obj]['object_type_id']+ ">"+ data[obj]['name']+"</a> </td>"+
									"<td><a href='#' id="+data[obj]['object_type_id']+"#"+data[obj]['object_id']+" onClick = deleteItem(this);> delete </a> </td>"+ 
					"</tr>");
			++cnt;
		}
	}

	function deleteItem(field) {
		
		var tribeCell = field.id;
		var temp = tribeCell.split("#")
		
		document.getElementById("objecttypeid").value = temp[0];
		document.getElementById("objectid").value = temp[1];

		triggerEvent(3);
	}
	</script>
	
</head>


<body>
<form id="myForm" method="GET">
	
	<div id="left">
		<ul class="svertical">
			
		</ul>
	</div>
	<div id="right">
		<div id="listContainer">
	          <div id="listControl">
	               <a id="expandList">Expand All</a>
	               <a id="collapseList">Collapse All</a>
	          </div>
	          <ul id="expList">
	            	<p><h4>BookMark</h4></p>
	          </ul>
	           <table border="1">
	           		
	           		<tr>
	           			<td>NetWorkUserId</td>
	           			<td>
	           				<input type="text" id="networkuserid" name="networkuserid" value = "550022193">
	           			</td>
	           		</tr>
	           		
	           		<tr>
	           			<td>NetWorkId</td>
	           			<td>
	           				<input type="text" id="networkid" name="networkid" value = 2>
	           			</td>
	           		</tr>
	           		
	           		<tr>
	           			<td>ObjectTypeId</td>
	           			<td>
	           				<input type="text" id="objecttypeid" name="objecttypeid">
	           			</td>
	           		</tr>
	           		
	           		<tr>
	           			<td>ObjectId</td>
	           			<td>
	           				<input type="text" id="objectid" name="objectid">
	           			</td>
	           		</tr>
	           		
	           		<tr>
	           			<td colspan="2">
	           				<input  type="button" id="btn1" value="Insert" onClick = "triggerEvent(2)"/>
	           				<input  type="button" id="btn1" value="Select" onClick = "triggerEvent(1)"/>
	           			</td>
	           			
	           		</tr>
	           		
	           </table>
	           <table border="1" id="pageResult">
					<tr>
						<th>Sr.No.</th>
						<th>BookMark</th> 
					</tr>
				</table>
		</div>
     </div>
     </form>
</body>
