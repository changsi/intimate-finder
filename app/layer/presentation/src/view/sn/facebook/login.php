<html>
	<head>
	</head>
	<body>
	
		<?php
			if (isset($loginUrl)) {
				echo("<script> top.location.href='" . $loginUrl . "'</script>");
			}
			else {
				echo "welcome to use dorothy!";
			}
		?>
		</body>
</html>


