<?php
if (isset($tribe)) {//For the ajax request checking if the tribe name already exists..
	if (!empty($tribe)) {//if there is another tribe with the same name dont allow it
		echo "false";
	}
	else {
		echo "true";
	}
}
else {
?>
<script src="<?php print(JS_PATH); ?>jquery-1.7.1.js"></script>
<script type="text/javascript" src="<?php print(JS_PATH); ?>jquery.validate.js"></script>
<style type="text/css">
* { font-family: Verdana; font-size: 96%; }
label { width: 10em; float: left; }
label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
p { clear: both; }
.submit { margin-left: 12em; }
em { font-weight: bold; padding-right: 1em; vertical-align: top; }
</style>
<script>
	function validateCategories() {
		var gender = $("#gender").val() || [];
		var age = $("#age").val() || [];
		var relation = $("#relation").val() || [];
		var education = $("#education").val() || [];
		var alchemyCat = $("#c52909").val() != 0  || $("#c53818").val() != 0 || $("#c28813").val() != 0 || $("#c48374").val() != 0   || $("#c30399").val() != 0 || $("#c58954").val() != 0 || $("#c47792").val() != 0 || $("#c48378").val() != 0 || $("#c52868").val() != 0 || $("#c49049").val() != 0 || $("#c5045").val() != 0 || $("#c44675").val() != 0;
		return gender.length || age.length || relation.length || education.length || alchemyCat;
	}
	$(document).ready(function(){
		$("#tribeForm").validate({
			rules: {
				name: {
					required: true,
					<?php isset($data['tid']) ?  '' : print('remote: "'.HOST_PREFIX.'/admin/tribe/update"'); ?>
				}
			},
			messages: {
				name: {
					required: "Please enter a Tribe Name",
					remote: jQuery.format("{0} already exists")
				}
			},
			submitHandler: function(form) {
				if (validateCategories()) {
					alert("form submitted, redirecting to the list");
					form.submit();
				}
				else {
					alert("No category has been set, please enter at least one category (profile/alchemy) for this tribe");
				}
			}
		})
	});
	
	function toogleDisableSelect(id) {
		if ($('#'+id).attr("disabled") == "disabled") {
			$('#'+id).removeAttr("disabled");
		}
		else {
			$('#'+id).attr("disabled","disabled");
		}

	}
</script>
<form id="tribeForm" method="post" action="<?php isset($data['tid']) ? print(HOST_PREFIX.'/admin/tribe/update?tid='.$data['tid']) : print(HOST_PREFIX.'/admin/tribe/update') ;?>" style="width:50%;">
	<?php isset($data['tid']) ?  print('<input type="hidden" name="tid" value="'.$data['tid'].'"/>') : '' ;?>
	<fieldset style="float:left;width:100%;">
		<legend>Tribe information:</legend>
			Name:  <em>*</em><input type="text" id="name" name="name" size="30" minlength="2" value="<?php isset($data['name']) ?  print($data['name']) : '' ;?>"/><br />
			Description: <input type="text" name="description" size="30" value="<?php isset($data['description']) ?  print($data['description']) : '' ;?>" /><br />
			Slogan: <input type="text" name="slogan" size="30" value="<?php isset($data['slogan']) ?  print($data['slogan']) : '' ;?>"  /><br />
			Badge: <input type="text" name="badge" size="30"  value="<?php isset($data['badge']) ?  print($data['badge']) : '' ;?>" />
	</fieldset>
	<fieldset style="float:left;width:100%;">
		<legend>Tribe profile categories:</legend>
			Gender: <input type="checkbox" onclick="toogleDisableSelect('gender')" />Not Specified
			<br />
			<input type="hidden" name="gender" value="0"/>
			<select id="gender" name="gender[]" multiple="multiple">
				<option value="9995999" <?php isset($data['cat']['c9995999']) ?  print('selected="selected"') : '' ;?> >Male</option>
				<option value="9996999" <?php isset($data['cat']['c9996999']) ?  print('selected="selected"') : '' ;?> >Female</option>
			</select>
			<br />
			Age:	<input type="checkbox" onclick="toogleDisableSelect('age')" />Not Specified
			<br />
			<input type="hidden" name="age" value="0"/>
			<select id="age" name="age[]" multiple="multiple">
				<option value="9997999" <?php isset($data['cat']['c9997999']) ?  print('selected="selected"') : '' ;?> ><14</option>
				<option value="9998999" <?php isset($data['cat']['c9998999']) ?  print('selected="selected"') : '' ;?> >>=14 <18</option>
				<option value="9999999" <?php isset($data['cat']['c9999999']) ?  print('selected="selected"') : '' ;?> >>=18 <30</option>
				<option value="99910999" <?php isset($data['cat']['c99910999']) ?  print('selected="selected"') : '' ;?> >>=30 <37</option>
				<option value="99911999" <?php isset($data['cat']['c99911999']) ?  print('selected="selected"') : '' ;?> >>37</option>
			</select>
			<br />
			Relationship: <input type="checkbox" onclick="toogleDisableSelect('relation')" />Not Specified
			<br />
			<input type="hidden" name="relation" value="0"/>
			<select id="relation" name="relation[]" multiple="multiple">
				<option value="99915999" <?php isset($data['cat']['c99915999']) ?  print('selected="selected"') : '' ;?> >single</option>
				<option value="99916999" <?php isset($data['cat']['c99916999']) ?  print('selected="selected"') : '' ;?> >in_a_relationship</option>
				<option value="99917999" <?php isset($data['cat']['c99917999']) ?  print('selected="selected"') : '' ;?> >engaged</option>
				<option value="99918999" <?php isset($data['cat']['c99918999']) ?  print('selected="selected"') : '' ;?> >married</option>
				<option value="99919999" <?php isset($data['cat']['c99919999']) ?  print('selected="selected"') : '' ;?> >its_complicated</option>
				<option value="99920999" <?php isset($data['cat']['c99920999']) ?  print('selected="selected"') : '' ;?> >open</option>
				<option value="99921999" <?php isset($data['cat']['c99921999']) ?  print('selected="selected"') : '' ;?> >widowed</option>
				<option value="99922999" <?php isset($data['cat']['c99922999']) ?  print('selected="selected"') : '' ;?> >seperated</option>
				<option value="99923999" <?php isset($data['cat']['c99923999']) ?  print('selected="selected"') : '' ;?> >divorced</option>
				<option value="99925999" <?php isset($data['cat']['c99924999']) ?  print('selected="selected"') : '' ;?> >civil_union</option>
				<option value="99925999" <?php isset($data['cat']['c99925999']) ?  print('selected="selected"') : '' ;?> >domestic_partnership</option>
			</select>
			<br />
			Education: <input type="checkbox" onclick="toogleDisableSelect('education')" />Not Specified
			<br />
			<input type="hidden" name="education" value="0"/>
			<select id="education" name="education[]" multiple="multiple">
				<option value="99912999" <?php isset($data['cat']['c99912999']) ?  print('selected="selected"') : '' ;?> >high school</option>
				<option value="99913999" <?php isset($data['cat']['c99913999']) ?  print('selected="selected"') : '' ;?> >college</option>
				<option value="99914999" <?php isset($data['cat']['c99914999']) ?  print('selected="selected"') : '' ;?> >grad</option>
			</select>
	</fieldset>
	<fieldset id="alchemy" style="float:left;width:100%;">
		<legend>Tribe Alchemy categories:</legend>
			Categories: 
			<br />
			Arts & Entertainment : <input id="c52909" class="required number" type="text" size="5" name="c52909" value="<?php isset($data['cat']['c52909']) ?  print $data['cat']['c52909']['affinity'] : print 0 ;?>"/><br />
			Business : <input id="c53818" class="required number" type="text" size="5" name="c53818" value="<?php isset($data['cat']['c53818']) ?  print $data['cat']['c53818']['affinity'] : print 0 ;?>"/><br />
			Computers & Internet : <input id="c28813" class="required number" type="text" size="5" name="c28813" value="<?php isset($data['cat']['c28813']) ?  print $data['cat']['c28813']['affinity'] : print 0 ;?>"/><br />
			Culture & Politics : <input id="c48374" class="required number" type="text" size="5" name="c48374" value="<?php isset($data['cat']['c48374']) ?  print $data['cat']['c48374']['affinity'] : print 0 ;?>"/><br />
			Gaming : <input id="c30399" class="required number" type="text" size="5" name="c30399" value="<?php isset($data['cat']['c30399']) ?  print $data['cat']['c30399']['affinity'] : print 0 ;?>"/><br />
			Health : <input id="c58954" class="required number" type="text" size="5" name="c58954" value="<?php isset($data['cat']['c58954']) ?  print $data['cat']['c58954']['affinity'] : print 0 ;?>"/><br />
			Law & Crime : <input id="c47792" class="required number" type="text" size="5" name="c47792" value="<?php isset($data['cat']['c47792']) ?  print $data['cat']['c47792']['affinity'] : print 0 ;?>"/><br />
			Religion : <input  id="c48378" class="required number" type="text" size="5" name="c48378" value="<?php isset($data['cat']['c48378']) ?  print $data['cat']['c48378']['affinity'] : print 0 ;?>"/><br />
			Recreation : <input id="c52868" class="required number" type="text" size="5" name="c52868" value="<?php isset($data['cat']['c52868']) ?  print $data['cat']['c52868']['affinity'] : print 0 ;?>"/><br />
			Science & Technology : <input id="c49049" class="required number" type="text" size="5" name="c49049" value="<?php isset($data['cat']['c49049']) ?  print $data['cat']['c49049']['affinity'] : print 0 ;?>"/><br />
			Sports : <input id="c5045" class="required number" type="text" size="5" name="c5045" value="<?php isset($data['cat']['c5045']) ?  print $data['cat']['c5045']['affinity'] : print 0 ;?>"/><br />
			Weather : <input id="c44675" class="required number" type="text" size="5" name="c44675" value="<?php isset($data['cat']['c44675']) ?  print $data['cat']['c44675']['affinity'] : print 0 ;?>"/><br />
	</fieldset>
	<input  class="submit" type="submit" name="submit" value="SUBMIT" style="float:left;" />
</form>
<?php
}
?>
