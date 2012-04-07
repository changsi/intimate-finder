Since the semantic will be running in a cronjob and will be saving the html output files to the html tags parsed folder, we can simply create a script which does the following:
- create a simple form where manish can tweek the weights of the HTML TAGS and the weight of the PARSERS.
- then when manish clicks submit:
- gets all the files inside of the semantic html tags parsed folder.
- loop each file and for each file, change remove the settings in the begginning.
- save the new file to a new file and put it in the to_parse folder from the semantic module.
- backup the original file to a backup_parsed folder in the semantic module, just in case you need the original one, in the future.
- then just wait until the semantic module runs or simply execute directly the semantic module, passing it each file. If you decide to run it sequentially, then you must execute the following modules, too:
	+ user_category
	+ user_user
	+ user_group
	+ dp
	
	(Please check the RunAllModulesWithAlchemyServiceViaJava.java file to get more confortable with this and check the file: /home/envio/projects/targeted_advertising/trunk/client/spiral/app/layer/presentation/src/entity/test/test_url_categories.php)

Additionally you must create the config files dynamically and the correspondent template files.
<?php

?>
