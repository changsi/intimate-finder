/********** DEPRECATED - SEE /UPDATE VIEW **********/
/*
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>
<style type="text/css">
* { font-family: Verdana; font-size: 96%; }
label { width: 10em; float: left; }
label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
p { clear: both; }
.submit { margin-left: 12em; }
em { font-weight: bold; padding-right: 1em; vertical-align: top; }
</style>
<script>	
	$(document).ready(function(){
		$("#tribeForm").validate({
			submitHandler: function(form) {
				form.submit();
			}
		})
	});
</script>
<form id="tribeForm" method="post" action="<?php echo HOST_PREFIX."/admin/tribe/insert" ;?>" style="width:50%;">
	<fieldset style="float:left;width:100%;">
		<legend>Tribe information:</legend>
			Name:  <em>*</em><input type="text" id="name" name="name" size="30" class="required" minlength="2" /><br />
			Description: <input type="text" name="description" size="30" /><br />
			Slogan: <input type="text" name="slogan" size="30" /><br />
			Badge: <input type="text" name="badge" size="30" />
	</fieldset>
	<fieldset style="float:left;width:100%;">
		<legend>Tribe profile categories:</legend>
			Gender:
			<br />
			<select name="gender[]" multiple="multiple">
				<option value="0" selected="selected">not specified</option>
				<option value="1">Male</option>
				<option value="2">Female</option>
			</select>
			<br />
			Age:	
			<br />
			<select name="age[]" multiple="multiple">
				<option value="0" selected="selected">not specified</option>
				<option value="9997999"><14</option>
				<option value="9998999">>=14 <21</option>
				<option value="9999999">>=21 <29</option>
				<option value="99910999">>=29 <36</option>
				<option value="99911999">>35</option>
			</select>
			<br />
			Relationship: 
			<br />
			<select name="relation[]" multiple="multiple">
				<option value="0" selected="selected">not specified</option>
				<option value="99915999">single</option>
				<option value="99916999">in_a_relationship</option>
				<option value="99917999">engaged</option>
				<option value="99918999">married</option>
				<option value="9991999">its_complicated</option>
				<option value="99920999">open</option>
				<option value="99921999">widowed</option>
				<option value="99922999">seperated</option>
				<option value="99923999">divorced</option>
				<option value="99925999">civil_union</option>
				<option value="99925999">domestic_partnership</option>
			</select>
			<br />
			Education: 
			<br />
			<select name="education[]" multiple="multiple">
				<option value="0" selected="selected">not specified</option>
				<option value="99912999">high school</option>
				<option value="99913999">college</option>
				<option value="99914999">grad</option>
			</select>
	</fieldset>
	<fieldset id="alchemy" style="float:left;width:100%;">
		<legend>Tribe Alchemy categories:</legend>
			Categories: 
			<br />
			Arts & Entertainment : <input id="c52909" class="required number" type="text" size="5" name="c52909" value='0'/><br />
			Business : <input id="c53818" class="required number" type="text" size="5" name="c53818" value='0'/><br />
			Computers & Internet : <input id="c28813" class="required number" type="text" size="5" name="c28813" value='0'/><br />
			Culture & Politics : <input id="c48374" class="required number" type="text" size="5" name="c48374" value='0'/><br />
			Gaming : <input id="c30399" class="required number" type="text" size="5" name="c30399" value='0'/><br />
			Health : <input id="c58954" class="required number" type="text" size="5" name="c58954" value='0'/><br />
			Law & Crime : <input id="c47792" class="required number" type="text" size="5" name="c47792" value='0'/><br />
			Religion : <input  id="c48378" class="required number" type="text" size="5" name="c48378" value='0'/><br />
			Recreation : <input id="c52868" class="required number" type="text" size="5" name="c52868" value='0'/><br />
			Science & Technology : <input id="c49049" class="required number" type="text" size="5" name="c49049" value='0'/><br />
			Sports : <input id="c5045" class="required number" type="text" size="5" name="c5045" value='0'/><br />
			Weather : <input id="c44675" class="required number" type="text" size="5" name="c44675" value='0'/><br />
	</fieldset>
	<input  class="submit" type="submit" name="submit" value="SUBMIT" style="float:left;" />
</form>
*/
