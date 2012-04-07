<?php
//show in html one form with one question, when the user has answered the question and clicked next, send the response in ajax and get the new question
if (isset($data) && isset($answersStr)) {
	echo "var data = ".$data." ;";
	echo "var answersStr = '".$answersStr."';";
}
else if (isset($url)) {
	echo "var url = '".$url."' ;";
}
else if (isset($firstQuestions) && isset($questions) ) {
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

<script type="text/javascript">
function hideContent(tag) {
	$('#' + tag).children().hide();
}
function clearContent(tag) {
	$('#' + tag).html('');
}
function replaceHtmlQuestions(data) {
	var htmlStr = '';
	if (data != '') {
		for (var i in data) {
			htmlStr += '<h4>'+data[i]['question']+'</h4>';
			for (var x in data[i]['answers']) {
				htmlStr += '<input type="radio" name="'+i+'" id="radio-choice-'+x+i+'" tabindex="2" class="form required" value="'+x+'" /><label for="radio-choice-'+x+i+'">'+data[i]['answers'][x]['answer']+'</label>';
				htmlStr += '<br />';
			}
		}
	}
	else {
		htmlStr +=  '<h4>Sorry an error occurred, we couldn\'t find the next question</h4>';
	}
	$('#changeableContent').append(htmlStr);
}

function getNextQuestion(answers) {
     url = "<?php echo HOST_PREFIX.'/home/question'; ?>";	

	jQuery.post (
		url,  
     	answers,
     	function(responseText) {
			eval(responseText);
			
			if (answersStr != '') {
				if (fullAnswersStr != '') {
					fullAnswersStr += ','+answersStr;
				}
				else {
					fullAnswersStr += answersStr;
				}
			}
			if (data == 'end') {
				var dataStr = '<input id="answers" type="hidden" name="answers" value="'+fullAnswersStr+'"/>';
				$('#changeableContent').append(dataStr);
				$('#submit').val('SUBMIT');
				jQuery.post (
					url,  
			     	$("form:first").serialize()+'&submit=SUBMIT',
			     	function(responseText) {
						eval(responseText);
						window.location.href = url;		
			        	},
			        	"html"
			     );
			}
			else {
				//hideContent('changeableContent');
				clearContent('changeableContent');
				replaceHtmlQuestions(data);
			}
        	},
        	"html"
     );
}
var fullAnswersStr = '';
$.validator.messages.required = "Please pick an answer";

$(document).ready(function(){
	$("#introForm").validate({
		
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			if ( element.is(":radio") )
				error.appendTo( element.parent().prev());
		}, 
		submitHandler: function(form) {

			if ($("#submit").val() == 'SUBMIT') {
				alert("form submitted");	
				form.submit();
			}
			else {
				alert("next question");
				var x = $(".form:checked").serialize();
				getNextQuestion(x);
			}
		}
	})
});
</script>

<body>
	<form id="introForm" method="post" action="<?php print(HOST_PREFIX.'/home/question') ;?>">
	    <div id="changeableContent">
	    <?php 
	    	foreach ($firstQuestions as $questionId) {	    
	    ?>
	         <h4><?php echo $questions[$questionId]['question'] ;?></h4>
	         <div>
	         <?php  foreach ($questions[$questionId]['answers'] as $key => $answer) {	    ?>
	         <input type="radio" name="<?php echo $questionId ;?>" id="radio-choice-<?php echo $questionId.$key ;?>" tabindex="2" class="form required" value="<?php echo $key ;?>" /><label for="radio-choice-<?php echo $questionId.$key ;?>"><?php echo $answer['answer'] ;?></label>
	         <br />
	         <?php } ?>
	         </div>
	    <?php } ?>
	    </div>
         <br />
	    <input id="submit" class="next" type="submit" name="submit" value="NEXT"/>
	</form>
<body/>

<?php
}
?>
