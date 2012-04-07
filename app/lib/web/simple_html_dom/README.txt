Client Tasks: The /tmp/ should have the 777 permissions
	1- DONE: create a new project and add it to the svn
	2- DONE: create a new file system structure
	3- DONE: copy some basic files that you might use from other projects.
	4- DONE: create the basic DB schema from the Spiral project.
	5- create the rules, contexts, entities and views for:
		5.1- DONE (ANT) show my dynamic dp (dp/get_user_dps.php)
		5.2- DONE (ANT) show my dynamic dp's categories and affinities (dp/get_dp_categories.php)
		5.3- DONE (ANT) show my friends dynamic dps (dp/get_friends_dps.php)
		5.4- DONE (ANT) show my friends dynamic dp's categories (1st: dp/get_friends_dps.php; 2nd: dp/get_dp_categories.php for each returned dp  => via ajax)
		5.5- DONE (ANT) show my categories (user/get_user_categories.php)
		5.5- DONE (ANT) show my friend's categories (1st: user/get_user_friends.php; 2nd: user/get_user_categories.php for each friend => via ajax)
		5.6- DONE (AD) login to facebook (sn/facebook/login.php) (redirect the domain skyweaver.com to 127.0.0.1 in /etc/hosts, simply add the line '127.0.0.1 skyweaver.com')
		5.7- DONE (ANT) get logged in user's feeds page URLs => get the most recent X feeds from the user page and then get the correspondent urls (script/start_fetching_facebook_user_data.php)
		5.8- DONE (ANT) get user's friends (user/get_user_friends.php)
		5.9- DONE (ANT) get the friend's feeds and then the correspondent urls (script/start_fetching_facebook_user_data.php)
		5.10- TO FINISH KEEP TESTING CONSTANTLY (ANT) get the user's profile and the friend's profile and save it to the user_network_data table (FB DB), which will be used later. (script/start_fetching_facebook_user_data.php)
			The user_network_data table should have the following structure:
		CREATED	user_network_data:
					PK (BIGINT): network_id
					PK (BIGINT): network_user_id
					(VARCHAR): age
					(VARCHAR): gender
					(VARCHAR): education
					(VARCHAR): location
					(VARCHAR): and_other_important_fields_from_the_fb_profile
					(TINYINT unsigned default 0): control_flag
					
		5.11- (ANT) PROCESS - TO TEST do the trending part => basically recommend some books, musics, movies, web-pages to the logged user. (This should be a pre-calculated thing): (content/trend_user_content.php and script/start_preparing_trended_user_content.php and script/start_fetching_facebook_user_data.php)
			5.11.1- DONE - EXCEPT WEB PAGES see how we can get it (AD) - create a script which reads the logged in user and his friend's profile and save to a table all the books, musics, movies and web-pages that is in the profiles. (dont forget to passe names in normalize string functions to remove accents,space...) Then save it to the following tables: (script/start_fetching_facebook_user_data.php)
		CREATED	object_count table:
					PK (BIGINT): object_type_id (id for books, musics, movies, web-pages)
					PK (BIGINT): object_id (hash for the name of the correspondent books, musics, movies, web-pages)
					(INT): count (how many users have this - This is in all world - not only for the friends, but for all the users in our platform)
				
		CREATED	user_object table:
					PK (BIGINT): network_user_id (facebook user id)
					PK (BIGINT): object_type_id
					PK (BIGINT): object_id
					
		CREATED	object table:
					PK (BIGINT): object_type_id
					PK (BIGINT): object_id
					(VARCHAR): value
					(VARCHAR): url
					etc...
				
		CREATED	Additionally you should have already the table "object_type" which contains the following attriutes:
					PK (BIGINT): id
					(VARCHAR): name
					
					Here is an example:
						id name
						1 music
						2 movie
						3 book
						4 webpage
						5 ...
			
			5.11.2-  DONE then create a function which query the CP DB and gets the top 5 similar friends for the logged in user (user_user table).
			5.11.3-  DONE then based in that friend's IDS, do another query to get the top 2 musics for this friends with more counts. You should use the tables: object_count and user_object.
			5.11.4-  DONE change the previous query (from the 5.11.3 task) to don't return musics that the logged in user already has.
			5.11.5-  DONE do the 5.11.3 and 5.11.4 for the movies, books and web-pages too.
			5.11.6-  DONE save the new recommendation objects to the following table:
				recommended_user_object:
					PK (BIGINT): network_user_id (facebook user id)
					PK (BIGINT): object_type_id
					PK (BIGINT): object_id
			5.11.7-  DONE create a simple rule, context-service, entity and view to return the recommended objects grouped by type. Something like this:
					
					Recommended content:
						Movies:
							xxx
							yyy
						Musics:
							rrrr
							tt
						Books:
							oooo
							pppppp
						Web-Pages:
							http://goo.com/ss
							http://yah.it/qqq244
		
		5.12- DONE (SC) create the code so the user can invite other users (user/invite.php)
		5.13- DONE (SC) create the code so the user can like a specific content - we should do this through Facebook but additionally we should track this action for future purposes. (content/like.php)
		We should have a table to save all this kind of actions.
		Something like this:
			user_action:
				PK (BIGINT - autoincremented): id
				(BIGINT): action_type_id
				(BIGINT): user_network_id
				(BIGINT): object_type_id
				(BIGINT): object_id
				(BIGINT): other_user_network_id
				(timestamp - DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP): created_date
		
		Additionally we will have a table which will already contain all the action types (Like, invite), this is:
			user_action_type:
				PK (BIGINT - autoincremented): id
				(VARCHAR): name
			
		5.14- IN PROCESS (AD) create the code for the "item of the day". This will be a manual process for now. Basically we need to do the following: (content/item_of_day.php)
			5.14.1- create an admin panel so we can insert manually an item for each day of the week. But this item should be different for each tribe.
			5.14.2- create a DB table to store the item of the day by tribe. (and based in weekday), this is:
				item_of_day:
					PK (BIGINT - auto-incremented key): id
					(BIGINT): object_type_id
					(BIGINT): object_id
					(BIGINT): tribe_id
					(timestamp - DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP): date
		
		5.15- (ANT) PROCESS - TO TEST create an admin panel where we can create tribes and add the correspondent meta-data (like description, etc...). In the backend this admin panel will insert an MANUAL dp to the DB. This panel should have a sub-panel to add the correspondent categories and affinities. (admin/tribe/insert.php, admin/tribe/update.php, admin/tribe/list.php, admin/tribe/get_related_dps.php, admin/tribe/get_all_related_users.php, dp/get_users_by_dp.php and user/get_user_categories.php, dp/get_dp_categories.php)

		5.16- (ANT) DONE get users for DP. (dp/get_users_by_dp.php) 	
		5.17- Live db : create user_user table, user to user affintiy based in 2 things affinitiy from CP (only based on browser categories interests) + profile filter (personal categories) Manish has to give us the formula to calculate the new affinity.
		5.18- Live db : create user_tribe table, (all the users and the correspondant tribes being inserted after the user answers the questions.  Create service and entity receives a tribe_id and user_id and insert 
			1. We dont have any data: Questions part =>(assign based on answers) manual tribe
			2. We already have some data in CP : Pick the top 3 tribes 
				the CP will only process on browser categories
				tribe_category containing browser cat and profile cat
	
	
	
	
Core Platform Tasks:
	1- (AMIT) DONE - REFINE - TEST create new parser for the PROFILE variables => PROFILE PARSER
	2- (ANT + SC + AD) test CP and see if is generating the right DPs.
	3- (ANT + JP) create an admin panel to tweeking the weight of the parsers (url categories and profile categories) => Additionally create a new service in the CP to execute this or simply do this in php. (admin/test/tweeking_parsers_weight.php)
	4- (ANT) PROCESS - TEST create an admin panel to create manual dps and see what are the dynamic dps related with this manual dps. (admin/tribe/insert.php, admin/tribe/update.php, admin/tribe/list.php, admin/tribe/get_related_dps.php, admin/tribe/get_all_related_users.php, dp/get_users_by_dp.php and user/get_user_categories.php, dp/get_dp_categories.php)
	5- (ANT) PROCESS - TEST create an admin panel where we can see all the manual dps, dynamic dps, correspondent users and categories. (admin/tribe/insert.php, admin/tribe/update.php, admin/tribe/list.php, admin/tribe/get_related_dps.php, admin/tribe/get_all_related_users.php, dp/get_users_by_dp.php and user/get_user_categories.php, dp/get_dp_categories.php)

Common:
	1- (SC + AD) Create test plan
	2- (JP) install core-platform in the server and with a good architecture
	3- (ALL) execute some QA testing

UI DESIGN: 
	1- (JESSE) Create the layouts
	2- (ANT or SC + AD) integrate the layouts with the code.

INSTALLATION STEPS:
1- create a facebok_app DB
2- load the db_schema.sql to the new db
3- edit the /home/envio/projects/targeted_advertising/trunk/client/facebook_app/app/config/db_config.php file with the DB Credentials.
4- create a symbolic link to /var/www/html/facebook_app which point to the facebook_app project:
	/var/www/html/facebook_app -> /home/envio/projects/targeted_advertising/trunk/client/facebook_app/
5- Change your /etc/hosts file as root user with the following entry: (sudo vi /etc/hosts)
	127.0.0.1 skyweaver.com
6- open browser and type: http://localhost/facebook_app/app/home
7- follow the interface
8- create the cronjobs or run manually the following scripts:
	client:
		/home/envio/projects/targeted_advertising/trunk/client/facebook_app/app/script/start_core_platform_process_for_friends.php
		/home/envio/projects/targeted_advertising/trunk/client/facebook_app/app/script/start_core_platform_process_for_urls.php
		/home/envio/projects/targeted_advertising/trunk/client/facebook_app/app/script/start_core_platform_process_for_user_profile.php
		/home/envio/projects/targeted_advertising/trunk/client/facebook_app/app/script/start_preparing_trended_user_content.php
	cp:
		check the README.txt and CRONJOBS.txt files from the CP.



FUTURES TASKS :
1-Implement FB like function that likes a FB object and external objects
2-Create a bunch of scripts which loads the all data to memory. Change the entities to get the correspondent data from memory and not from sql.
3-In user object table, we are not filling the genre,genre2, author and release date columns, should we fill them given that FB doesnt allow us to store anything?
4-Profile Categories array in config has to be loaded in memory and read from the CP config file.
5- ANT DONE start fetching facebook data script, lets use FB batch API. It allows us to gorup request and even do dependency
 
