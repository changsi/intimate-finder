<head>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "home.css" ;?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH. "tcal.css" ;?>"/>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/jquery-1.6.4.js";?>"></script>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/scripts.js";?>"></script>
	<script type="text/javascript" src="<?php echo HOST_PREFIX . "/layer/presentation/webroot/js/tcal.js";?>"></script>
	<script type="text/javascript">	

		function showContent(data){
			$('tr#content').html('');
			
			for(var obj in data) {
				$('#pageResult').append("<tr id='content'><td>"+data[obj]['tribe_name']+"</td><td>"+data[obj]['name']+"</td></tr>");
			}
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

	</script>
	
</head>


<body>
<form id="myForm" method="POST">
	
	<div id="left">
		<ul class="svertical">
			<div>
				<input type="text" id="date" name="date" class="tcal" value="" onchange=""/>
			</div>
		</ul>
	</div>
	<div id="right">
		<div id="listContainer">
	          <div id="listControl">
	               <a id="expandList">Expand All</a>
	               <a id="collapseList">Collapse All</a>
	          </div>
	          <ul id="expList">
	            	<p><h4>Item of the day</h4></p>
	          </ul>
	           <table border="1" id="pageResult" style="border-width: 1px; border-color:#000000;border-style: solid;">
	           		<tr>
	           			<th>Tribe Name</th>
	           			<th>Item of the day</th>
	           		</tr>
	           </table>
		</div>
     </div>
     </form>
</body>
<script type="text/javascript">
	<?php 
		if (isset($data)) {
			echo "eval('var data = " . json_encode($data) . ";');";
			echo "showContent(data);";
		}
	?>
</script>