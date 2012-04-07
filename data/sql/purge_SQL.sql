truncate user;
truncate object;
truncate history;
truncate user_agent;
truncate object_keyword;
truncate object_category;
truncate dp;
truncate friend;
truncate dp_category;


truncate user_category;
truncate user_user;
truncate user_group;
truncate dp_user;
truncate dp_dp;


delete from dp where dp_type_id=1;
delete dp_category from dp_category left join dp on dp_category.dp_id=dp.id where dp.id is NULL;





truncate object_url;
truncate object;
truncate user_object;
truncate user_network_data;
truncate network_friend;
truncate network_post_url;
truncate recommended_user_object;
truncate object_count;
truncate user_tribe;
