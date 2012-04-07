<?php 
/*
INFO: This entity receives a network_user_id and answers to questions from the view, it then determines the tribe the user belongs to.
Save it to user tribe table and redirect the user to home page
*/

$network_user_id = isset($_SESSION['network_user_id']) && $_SESSION['network_user_id'] ? $_SESSION['network_user_id'] : false;
$access_token = isset($_SESSION['access_token']) && $_SESSION['access_token'] ? $_SESSION['access_token'] : false;

if ($access_token && $network_user_id) {
	if (isset($_SESSION['tribe'])) {//check if the user has already a tribe meaning he doesnt belong here..
		header("Location: ".HOST_PREFIX."/home/home");
	}
	else {
		if (isset($_POST['submit']) && $_POST['submit'] == 'SUBMIT') {
			//form fully submitted
			if (isset($_POST['answers'])) {
				$answers = $_POST['answers'];
				$questionsToTribes = unserialize(QUESTIONS_TO_TRIBES);
				if (array_key_exists($answers, $questionsToTribes)) {
					$tribesIds = $questionsToTribes[$answers];
					//select 3 tribes
					$_SESSION['tribes'] = $tribesIds;
					//redirect to pick a tribe
					$url = HOST_PREFIX.'/home/tribe_selection';
				}
				else {
					echo "No tribe in the QUESTIONS_TO_TRIBES array, please add corresponding tribes for this path of answers: $answers";
				}
			}
			else {
				echo "Error answers in POST doesnt exist, check JS code";
			}
		}
		else if (isset($_POST) && !empty($_POST) ) {
			//got the answer for some specific question(s)
			//check which question(s) are next
			//send it/them
			
			//Dat to be sent back to the client
			$data = array();
			$answersStr = '';
			//end
			
			$nextQuestions = array();
			$dependenciesQuestions = unserialize(DEPENDENCIES_QUESTIONS);
			
			foreach ($_POST as $key => $post) {
				$answersStr .= $key.".".$post.",";
			}
			$answersStr = substr($answersStr, 0, -1);
			
			if (array_key_exists($answersStr, $dependenciesQuestions)) {
				if ($dependenciesQuestions[$answersStr] == 'end') {//there are no questions left, the form can be submitted
					$nextQuestions = 'end';
				}
				else {
					$nextQuestionsIds = $dependenciesQuestions[$answersStr];
					$questions = unserialize(QUESTIONS);
					foreach ($nextQuestionsIds as $id) {
						$nextQuestions[$id] = $questions[$id];
					}
				}
			}
			$data = json_encode($nextQuestions);
		}
		else {
			//first time, show the first question(s)
			$questions = unserialize(QUESTIONS);
			$firstQuestions = unserialize(FIRST_QUESTIONS);		
		}
	}
}
else {
	//redirect to login
	header("Location: ".HOST_PREFIX."/sn/facebook/login");
	return;
}


?>
