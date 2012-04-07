<?php


if (!isset($register)) {
	echo "sorry, this is a invite-only application. But you can still receive a special invitation through your email!";
	echo "<form id='form' action='".HOST_PREFIX."/home/register_later' method='post'>";
	echo "<input name='submit' type='submit' value='Send Me'>";
	echo "</form> ";
	$register = 2;
	
}
elseif($register == 1) {
	echo '<script type="text/JavaScript">
			setTimeout("location.href = \''.HOST_PREFIX.'/sn/facebook/login'.'\';",1500);
		</script>'; 
}
elseif($register == 2) {
	echo "Thank You For Your Request";
	echo '<script type="text/JavaScript">
			setTimeout("location.href = \''.HOST_PREFIX.'/sn/facebook/login'.'\';",1500);
		</script>'; 
	$register = 1;
}

?>


