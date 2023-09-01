<?php
	require dirname(__FILE__).'/../main.php';
	$for_tests = $argv[1] == 'test';
	$SPHINX_ROOT = SPHINX_ROOT.($for_tests ? '/test' : '');
	if (!defined('IS_DEV')) {
		define('IS_DEV', false);
	}

	$phpcrwl_db_ip = IS_DEV ? '127.0.0.1' : OTRS_SERVER_LOCAL_IP;
	$tracker_db_ip = IS_DEV ? '127.0.0.1' : OTRS_SERVER_LOCAL_IP;
	$jobs_db_ip = IS_DEV ? '127.0.0.1' : WEB2_LOCAL_IP4;
	$geonames_db_ip = IS_DEV ? '127.0.0.1' : OTRS_SERVER_LOCAL_IP;

	[$phpcrwl_user, $geonames_user, $jobs_user, $tracker_user] = IS_DEV ?
		['learn4go_jobs2', 'learn4go_jobs2', 'learn4go_jobs2', 'learn4go_jobs2'] :
		['learn4gd_phpcrwl', 'learn4gd_geo', 'learn4gd_jobs2', 'learn4gd_phpbt'];

	[$phpcrwl_db, $geonames_db, $jobs_db, $tracker_db] = IS_DEV ?
		['unsupported', 'unsupported', $for_tests ? 'learn4go_jobs2_test' : 'ssjobs', 'unsupported'] :
		['learn4gd_phpcrwl', 'learn4gd_geonames', 'learn4gd_jobs2', 'learn4gd_phpbt'];

	[$phpcrwl_pass, $geonames_pass, $jobs_pass, $tracker_pass] = IS_DEV ?
		['j6', 'j6', 'j6', 'j6'] :
		['j5', 'iG', 'Da', 'Pr'];

	$max_job_id = $for_tests ?
        'if (max(id) > '.(SPHINX_MAX_OLD_JOB_ID + 1).', max(id), '.(SPHINX_MAX_OLD_JOB_ID + 2).') as \'max(id)\'' :
        'MAX(jobs.id)';

	//for some reason without newlines in below output next line gets merged...
?>
#############################################################################
## data source definition
#############################################################################
source phpcrwl_db {
	type					= mysql

	sql_host				= <?= $phpcrwl_db_ip."\n" ?>
	sql_user				= <?= $phpcrwl_user."\n" ?>
	sql_pass				= <?= $phpcrwl_pass."\n" ?>
	sql_db					= <?= $phpcrwl_db."\n" ?>

	sql_query				= SELECT id FROM pages

	sql_ranged_throttle		= 250
}

source geonames_db {
	type					= mysql

	sql_host				= <?= $geonames_db_ip."\n" ?>
	sql_user				= <?= $geonames_user."\n" ?>
	sql_pass				= <?= $geonames_pass."\n" ?>
	sql_db					= <?= $geonames_db."\n" ?>
	sql_port				= 3306	# optional, default is 3306

	sql_query				= SELECT id FROM geocities

	sql_ranged_throttle		= 200
}

source jobs2_db {
	type					= mysql

	sql_host				= <?= $jobs_db_ip."\n" ?>
	sql_user				= <?= $jobs_user."\n" ?>
	sql_pass				= <?= $jobs_pass."\n" ?>
	sql_db					= <?= $jobs_db."\n" ?>
	sql_port				= 3306	# optional, default is 3306

	sql_query				= SELECT id FROM cvs

	sql_ranged_throttle		= 500
}

source tracker_db {
	type					= mysql

	sql_host				= <?= $tracker_db_ip."\n" ?>
	sql_user				= <?= $tracker_user."\n" ?>
	sql_pass				= <?= $tracker_pass."\n" ?>
	sql_db					= <?= $tracker_db."\n" ?>

	sql_query				= SELECT id FROM requirements

	sql_ranged_throttle		= 200
}

source pages : phpcrwl_db
{
	sql_query				= \
	SELECT SQL_NO_CACHE id, url, title, description, content, section  \
	FROM pages

	sql_attr_uint = section
}

source geocities : geonames_db
{
	sql_query_range = SELECT MIN(id), MAX(id) FROM geocities
	sql_range_step = 20000

	sql_query_pre = SET NAMES utf8

	sql_query				= \
		SELECT SQL_NO_CACHE id, name, alt_names, admin1_code, country_code, population \
		FROM geocities \
		WHERE timezone <> '' AND id >= $start AND id <= $end

	sql_attr_uint = population
}

source requirements : tracker_db
{
	sql_query_pre = SET SESSION group_concat_max_len=10000
	sql_query				= \
		SELECT SQL_NO_CACHE requirements.id, title, summary, project_id, assignee_id, type_id, status_id, UNIX_TIMESTAMP(requirements.creation_date) AS creation_date_timestamp, GROUP_CONCAT(content) AS comments \
		FROM requirements \
		LEFT JOIN requirement_comments ON requirements.id = requirement_id \
		GROUP BY requirements.id


	sql_attr_uint = project_id
	sql_attr_uint = assignee_id
	sql_attr_uint = type_id
	sql_attr_uint = status_id

    sql_attr_uint = creation_date_timestamp
}
#You can specify bit count for integer attributes by appending ':BITCOUNT' to attribute name.
#Attributes with less than default 32-bit size, or bitfields, perform slower.
#But they require less RAM when using extern storage: such bitfields are packed together in 32-bit chunks in .spa attribute data file.
#Bit size settings are ignored if using inline storage.
#Default storage is extern

source cvs : jobs2_db
{
	sql_query_range = SELECT MIN(cvs.id), MAX(cvs.id) FROM cvs
	sql_range_step = 20000

	sql_query_pre 			= REPLACE INTO sphinx_counters (id, max_doc_id) SELECT 1, MAX(id) FROM cvs
	sql_query				= \
		SELECT SQL_NO_CACHE cvs.id, cv_texts.title, cv_texts.summary, cv_texts.skills, cv_texts.education, cv_texts.experience, cvs.domain_id, cvs.is_suspended, cvs.hide, \
			jobseekers.is_male, jobseekers.citizenship_country_id, cvs.country_id, cvs.state_id, cvs.city_id, cvs.unified_salary_amount, \
			jobseekers.country_id AS jobseeker_country_id, jobseekers.state_id AS jobseeker_state_id, jobseekers.city_id AS jobseeker_city_id, \
			UNIX_TIMESTAMP(cvs.creation_date) AS creation_date_timestamp, education_levels.order_id AS education_level_order_id, \
			experience_levels.order_id AS experience_level_order_id, jobseekers.last_login_country_id AS jobseeker_last_login_country_id \
		FROM cvs \
		JOIN cv_texts ON cv_texts.cv_id = cvs.id \
		JOIN jobseekers ON cvs.jobseeker_id = jobseekers.id \
		JOIN education_levels ON cvs.education_level_id = education_levels.id \
		JOIN experience_levels ON cvs.experience_level_id = experience_levels.id \
		WHERE cvs.id >= $start AND cvs.id <= $end

	sql_attr_uint = domain_id
	sql_attr_uint = hide:1
	sql_attr_uint = is_suspended:1
	sql_attr_uint = is_male:1
	sql_attr_uint = citizenship_country_id
	sql_attr_uint = jobseeker_country_id
	sql_attr_uint = jobseeker_state_id
	sql_attr_uint = jobseeker_city_id
	sql_attr_uint = country_id
	sql_attr_uint = state_id
	sql_attr_uint = city_id
	sql_attr_uint = unified_salary_amount
	sql_attr_uint = education_level_order_id
	sql_attr_uint = experience_level_order_id
	sql_attr_uint = jobseeker_last_login_country_id

    sql_attr_uint = creation_date_timestamp

	sql_attr_multi = uint categories from query; \
		SELECT SQL_NO_CACHE cv_id, job_category_id FROM cv_job_categories; \
		SELECT MIN(id), MAX(id) FROM job_categories
	sql_attr_multi = uint employment_types from query; \
		SELECT SQL_NO_CACHE cv_id, employment_type_id FROM cv_employment_types; \
		SELECT MIN(id), MAX(id) FROM employment_types
	sql_attr_multi = uint languages from query; \
		SELECT SQL_NO_CACHE cv_id, language_id FROM cv_language_skills; \
		SELECT MIN(id), MAX(id) FROM languages
}
#Currently it seems that nothing marks deleted CV IDs and there is no notification to Delta index about deleted CVs.  Deleted CVs should get an attribute deleted=1
source cvs_delta : cvs
{
	sql_query_range =
    <?php /* this additional counter is used for updating the main counter when merging main with delta */ ?>
	sql_query_pre 			= REPLACE INTO sphinx_counters (id, max_doc_id) SELECT 2, MAX(id) FROM cvs
    sql_query				= \
		SELECT SQL_NO_CACHE cvs.id, cv_texts.title, cv_texts.summary, cv_texts.skills, cv_texts.education, cv_texts.experience, cvs.domain_id, cvs.is_suspended, cvs.hide, \
			jobseekers.is_male, jobseekers.citizenship_country_id, cvs.country_id, cvs.state_id, cvs.city_id, cvs.unified_salary_amount, \
			jobseekers.country_id AS jobseeker_country_id, jobseekers.state_id AS jobseeker_state_id, jobseekers.city_id AS jobseeker_city_id, \
			UNIX_TIMESTAMP(cvs.creation_date) AS creation_date_timestamp, education_levels.order_id AS education_level_order_id, \
			experience_levels.order_id AS experience_level_order_id, jobseekers.last_login_country_id AS jobseeker_last_login_country_id \
		FROM cvs \
		JOIN cv_texts ON cv_texts.cv_id = cvs.id \
		JOIN jobseekers ON cvs.jobseeker_id = jobseekers.id \
		JOIN education_levels ON cvs.education_level_id = education_levels.id \
		JOIN experience_levels ON cvs.experience_level_id = experience_levels.id \
		WHERE cvs.id > ( SELECT max_doc_id FROM sphinx_counters WHERE id = 1 )

	sql_attr_multi = uint categories from query; \
		SELECT SQL_NO_CACHE cv_id, job_category_id FROM cv_job_categories WHERE cv_id >= (SELECT max_doc_id FROM sphinx_counters WHERE id = 1); \
		SELECT MIN(id), MAX(id) FROM job_categories
	sql_attr_multi = uint employment_types from query; \
		SELECT SQL_NO_CACHE cv_id, employment_type_id FROM cv_employment_types WHERE cv_id >= (SELECT max_doc_id FROM sphinx_counters WHERE id = 1); \
		SELECT MIN(id), MAX(id) FROM employment_types
	sql_attr_multi = uint languages from query; \
		SELECT SQL_NO_CACHE cv_id, language_id FROM cv_language_skills WHERE cv_id >= (SELECT max_doc_id FROM sphinx_counters WHERE id = 1); \
		SELECT MIN(id), MAX(id) FROM languages
}
#jobs_flexible index is taking jobs from ID 1 to ID where jobs are most crowded(around 140mln). There are large ID gaps until 140 mln ID and crawling  until that number needs to be done with big stepping
#after that ID there are more jobs and stepping must be smaller to avoid server overload.
#Jobs flexible indexing must be only every few hours but frequent enough to keep delta indexes fast as delta always starts from where flexible finished and if flexible is happening seldon - delta will start going slower
source jobs_flexible : jobs2_db
{
    sql_query_range = SELECT MIN(jobs.id), <?= SPHINX_MAX_OLD_JOB_ID ?> FROM jobs
    sql_range_step = 400000
    sql_query_pre = REPLACE INTO sphinx_counters (id, last_full_index) SELECT 3, NOW()

    sql_query				= \
        SELECT j.id, j.id AS job_id, IF(j.is_online, 'online', '') AS online, j.is_online, CONCAT(tcountry.translation, ' ', st.name, ' ', ci.name) AS location, j.title, j.description, j.skills, j.benefits, j.reference_number, \
        j.domain_id, j.is_suspended, j.additional_locations, UNIX_TIMESTAMP(j.creation_date) AS creation_date_timestamp, UNIX_TIMESTAMP(j.expiry_date) AS expiry_date_timestamp, IF(xml_id = '', j.employer_name, employers.name) AS employer, \
        j.education_level_id, j.experience_level_id, UNIX_TIMESTAMP(original_creation_date) AS original_creation_date_timestamp, UNIX_TIMESTAMP(top_until) AS top_until_timestamp, ct.region_id, j.country_id, j.state_id, j.county_id, j.city_id, j.posting_app_language_id, j.employer_id, j.display_status, \
        GROUP_CONCAT(ac.country_id) AS accepted_countries, english_only, concat('id_', group_concat(distinct jsc.job_category_id SEPARATOR ' id_')) AS job_category_ids, \
        concat('id_', group_concat(distinct jss.job_subcategory_id SEPARATOR ' id_')) AS job_subcategory_ids, \
        GROUP_CONCAT(tcategory.translation) AS category, \
        GROUP_CONCAT(tsubcategory.translation) AS subcategory, \
        GROUP_CONCAT(temployment.translation) AS employment_type \
        FROM  \
        jobs j JOIN \
        employers ON j.employer_id = employers.id JOIN \
        cities ci ON ci.id = j.city_id LEFT JOIN \
        states st ON st.id = j.state_id LEFT JOIN \
        countries ct ON ct.id = j.country_id LEFT JOIN \
        applicant_filter_accepted_countries ac ON ac.applicant_filter_id = j.applicant_filter_id LEFT JOIN \
        translations tcountry ON \
        tcountry.translation_id = 0 AND tcountry.app_language_id = 1 AND tcountry.class_name = 'Country' AND tcountry.object_id = j.country_id \
        LEFT JOIN job_selected_categories jsc ON j.id = jsc.job_id \
        LEFT JOIN translations tcategory ON tcategory.class_name = 'Job_category' AND tcategory.translation_id = 0 AND tcategory.app_language_id = 1 AND tcategory.object_id = jsc.job_category_id \
        LEFT JOIN job_selected_subcategories jss ON j.id = jss.job_id \
        LEFT JOIN translations tsubcategory ON tsubcategory.class_name = 'Job_subcategory' AND tsubcategory.translation_id = 0 AND tsubcategory.app_language_id = 1 AND tsubcategory.object_id = jss.job_subcategory_id \
        LEFT JOIN job_employment_types jet ON j.id = jet.job_id \
        LEFT JOIN translations temployment ON temployment.class_name = 'Employment_type' AND temployment.translation_id = 0 AND temployment.app_language_id = 1 AND temployment.object_id = employment_type_id \
        WHERE j.id >= $start AND j.id <= $end AND expiry_date > NOW() \
        GROUP BY j.id

    sql_attr_uint = job_id
    sql_attr_uint = domain_id
    sql_attr_uint = posting_app_language_id
    sql_attr_bool = is_suspended
    sql_attr_uint = display_status
    sql_attr_uint = experience_level_id
    sql_attr_uint = education_level_id
    sql_attr_uint = employer_id
    sql_attr_uint = region_id
    sql_attr_uint = country_id
    sql_attr_uint = state_id
    sql_attr_uint = county_id
    sql_attr_uint = city_id
    sql_attr_bool = is_online
    sql_attr_bool = english_only

    sql_attr_uint = original_creation_date_timestamp
    sql_attr_uint = creation_date_timestamp
    sql_attr_uint = expiry_date_timestamp
    sql_attr_uint = top_until_timestamp

    sql_attr_multi = uint accepted_countries from field accepted_countries

    sql_attr_multi = uint job_categories from query; \
        SELECT SQL_NO_CACHE job_id, job_category_id FROM job_selected_categories WHERE job_id <= <?= SPHINX_MAX_OLD_JOB_ID ?>

    sql_attr_multi = uint job_subcategories from query; \
        SELECT SQL_NO_CACHE job_id, job_subcategory_id FROM job_selected_subcategories WHERE job_id <= <?= SPHINX_MAX_OLD_JOB_ID ?>

    sql_attr_multi = uint employment_types from query; \
        SELECT SQL_NO_CACHE job_id, employment_type_id FROM job_employment_types WHERE job_id <= <?= SPHINX_MAX_OLD_JOB_ID ?>

}
#Flexible_newest index is taking jobs only from the ID where jobs are most crowded. There are large ID gaps until 140 mln ID and crawling  until that number needs to be done with big stepping
#after that ID there are more jobs and stepping must be smaller to avoid server overload
source jobs_flexible_newest : jobs_flexible
{
    sql_range_step = 20000
    sql_query_range = SELECT <?= SPHINX_MAX_OLD_JOB_ID + 1 ?>, <?= $max_job_id ?> FROM jobs

    sql_attr_uint = job_id
    sql_attr_uint = domain_id
    sql_attr_uint = posting_app_language_id
    sql_attr_bool = is_suspended
    sql_attr_uint = display_status
    sql_attr_uint = experience_level_id
    sql_attr_uint = education_level_id
    sql_attr_uint = employer_id
    sql_attr_uint = region_id
    sql_attr_uint = country_id
    sql_attr_uint = state_id
    sql_attr_uint = county_id
    sql_attr_uint = city_id
    sql_attr_bool = is_online
    sql_attr_bool = english_only

    sql_attr_uint = original_creation_date_timestamp
    sql_attr_uint = creation_date_timestamp
    sql_attr_uint = expiry_date_timestamp
    sql_attr_uint = top_until_timestamp

    sql_attr_multi = uint accepted_countries from field accepted_countries

    sql_attr_multi = uint job_categories from query; \
        SELECT SQL_NO_CACHE job_id, job_category_id FROM job_selected_categories WHERE job_id > <?= SPHINX_MAX_OLD_JOB_ID ?>

    sql_attr_multi = uint job_subcategories from query; \
        SELECT SQL_NO_CACHE job_id, job_subcategory_id FROM job_selected_subcategories WHERE job_id > <?= SPHINX_MAX_OLD_JOB_ID ?>

    sql_attr_multi = uint employment_types from query; \
        SELECT SQL_NO_CACHE job_id, employment_type_id FROM job_employment_types WHERE job_id > <?= SPHINX_MAX_OLD_JOB_ID ?>

}
#Delta is indexing newest jobs from the point where the last index was made by the main index - flexible and flexible_newest
#Deleted attribute is needed only for merging with main index in order to drop expired and deleted jobs from results after merge otherwise they will be added to main index and cannot be dropped
source jobs_flexible_delta : jobs2_db
{
	sql_query_range =
    <?php /* this additional counter is used for updating the main counter when merging main with delta */ ?>
	sql_query_pre 			= REPLACE INTO sphinx_counters (id, last_full_index) SELECT 4, NOW()

    sql_query_kbatch = SELECT job_id FROM jobs_kill_list

	sql_query				= \
        SELECT j.id, j.id AS job_id, IF(j.is_online, 'online', '') AS online, j.is_online, CONCAT(tcountry.translation, ' ', st.name, ' ', ci.name) AS location, j.title, j.description, j.skills, j.benefits, j.reference_number, \
        j.domain_id, j.is_suspended, j.additional_locations, UNIX_TIMESTAMP(j.creation_date) AS creation_date_timestamp, UNIX_TIMESTAMP(j.expiry_date) AS expiry_date_timestamp, IF(xml_id = '', j.employer_name, employers.name) AS employer, \
        j.education_level_id, j.experience_level_id, UNIX_TIMESTAMP(original_creation_date) AS original_creation_date_timestamp, UNIX_TIMESTAMP(top_until) AS top_until_timestamp, ct.region_id, j.country_id, j.state_id, j.county_id, j.city_id, j.posting_app_language_id, j.employer_id, j.display_status, \
        GROUP_CONCAT(ac.country_id) AS accepted_countries, english_only, concat('id_', group_concat(distinct jsc.job_category_id SEPARATOR ' id_')) AS job_category_ids, \
        concat('id_', group_concat(distinct jss.job_subcategory_id SEPARATOR ' id_')) AS job_subcategory_ids, \
        GROUP_CONCAT(tcategory.translation) AS category, \
        GROUP_CONCAT(tsubcategory.translation) AS subcategory, \
        GROUP_CONCAT(temployment.translation) AS employment_type \
		FROM  \
			jobs j JOIN \
			employers ON j.employer_id = employers.id JOIN \
			cities ci ON ci.id = j.city_id LEFT JOIN \
			states st ON st.id = j.state_id LEFT JOIN \
			countries ct ON ct.id = j.country_id LEFT JOIN \
			applicant_filter_accepted_countries ac ON ac.applicant_filter_id = j.applicant_filter_id LEFT JOIN \
			translations tcountry ON \
				tcountry.translation_id = 0 AND tcountry.app_language_id = 1 AND tcountry.class_name = 'Country' AND tcountry.object_id = j.country_id \
            LEFT JOIN job_selected_categories jsc ON j.id = jsc.job_id \
            LEFT JOIN translations tcategory ON tcategory.class_name = 'Job_category' AND tcategory.translation_id = 0 AND tcategory.app_language_id = 1 AND tcategory.object_id = jsc.job_category_id \
            LEFT JOIN job_selected_subcategories jss ON j.id = jss.job_id \
            LEFT JOIN translations tsubcategory ON tsubcategory.class_name = 'Job_subcategory' AND tsubcategory.translation_id = 0 AND tsubcategory.app_language_id = 1 AND tsubcategory.object_id = jss.job_subcategory_id \
            LEFT JOIN job_employment_types jet ON j.id = jet.job_id \
            LEFT JOIN translations temployment ON temployment.class_name = 'Employment_type' AND temployment.translation_id = 0 AND temployment.app_language_id = 1 AND temployment.object_id = employment_type_id \
		WHERE j.modification_time > (SELECT last_full_index FROM sphinx_counters WHERE id = 3) AND expiry_date > NOW() \
		GROUP BY j.id

	sql_attr_uint = job_id
	sql_attr_uint = domain_id
	sql_attr_uint = posting_app_language_id
	sql_attr_bool = is_suspended
	sql_attr_uint = display_status
	sql_attr_uint = experience_level_id
	sql_attr_uint = education_level_id
	sql_attr_uint = employer_id
	sql_attr_uint = region_id
	sql_attr_uint = country_id
	sql_attr_uint = state_id
	sql_attr_uint = county_id
	sql_attr_uint = city_id
	sql_attr_bool = is_online
	sql_attr_bool = english_only

	sql_attr_uint = original_creation_date_timestamp
    sql_attr_uint = creation_date_timestamp
    sql_attr_uint = expiry_date_timestamp
    sql_attr_uint = top_until_timestamp

	sql_attr_multi = uint accepted_countries from field accepted_countries

	sql_attr_multi = uint job_categories from query; \
		SELECT SQL_NO_CACHE job_id, job_category_id \
		FROM job_selected_categories \
			JOIN jobs ON jobs.id = job_id \
		WHERE jobs.modification_time > (SELECT last_full_index FROM sphinx_counters WHERE id = 3 ); \
		SELECT MIN(id), MAX(id) FROM job_categories

	sql_attr_multi = uint job_subcategories from query; \
		SELECT SQL_NO_CACHE job_id, job_subcategory_id \
		FROM job_selected_subcategories \
			JOIN jobs ON jobs.id = job_id \
		WHERE jobs.modification_time > (SELECT last_full_index FROM sphinx_counters WHERE id = 3 ); \
		SELECT MIN(id), MAX(id) FROM job_subcategories

	sql_attr_multi = uint employment_types from query; \
		SELECT SQL_NO_CACHE job_id, employment_type_id \
		FROM job_employment_types \
			JOIN jobs ON jobs.id = job_id \
		WHERE jobs.modification_time > (SELECT last_full_index FROM sphinx_counters WHERE id = 3 ); \
		SELECT MIN(id), MAX(id) FROM employment_types
}
#jobs_completions offers suggestions when making entries in the job search form on jobsite and for sorting suggestions for sorting relevance by popularity of the result
source jobs_completions : jobs2_db
{
	sql_query				= \
		SELECT id, translation, translation AS text, 0 AS city_id, 0 AS country_id, 0 AS state_id, 0 AS subcategory_id, 100 AS num_jobs FROM translations WHERE \
			class_name IN ('Education_level', 'Employment_type', 'Language') AND app_language_id = 1 AND translation_id = 0 \
		UNION \
\
		SELECT id, translation, translation AS text, 1 AS city_id, ct.object_id AS country_id, 0 AS state_id, 0 AS subcategory_id, SUM(count) AS num_jobs FROM translations ct JOIN job_counts_new cojc ON ct.object_id = cojc.object_id AND cojc.object_type = 1 AND fresh_only = 0 WHERE \
			class_name IN ('Country') AND app_language_id = 1 AND translation_id = 0 \
			GROUP BY ct.object_id \
		UNION \
\
		SELECT id, translation, translation AS text, 0 AS city_id, 0 AS country_id, 0 AS state_id, 0 AS subcategory_id, SUM(count) AS num_jobs FROM translations ca_t JOIN job_counts_new cajc ON ca_t.object_id = cajc.job_category_id AND fresh_only = 0 WHERE \
			class_name IN ('Job_category') AND app_language_id = 1 AND translation_id = 0 \
			GROUP BY ca_t.object_id \
		UNION \
\
		SELECT id, translation, translation AS text, 0 AS city_id, 0 AS country_id, 0 AS state_id, su_t.object_id AS subcategory_id, SUM(count) AS num_jobs FROM translations su_t JOIN job_counts_subcategories sujc ON su_t.object_id = sujc.job_subcategory_id AND fresh_only = 0 WHERE \
			class_name IN ('Job_subcategory') AND app_language_id = 1 AND translation_id = 0 \
			GROUP BY su_t.object_id \
		UNION \
\
		SELECT states.id+100000 AS id, name, CONCAT(translation, ' ', IF(name REGEXP '^[a-z][a-z][^a-z]', SUBSTRING(name, 4), name)) AS text, 0 AS city_id, country_id AS country_id, states.id AS state_id, 0 AS subcategory_id, SUM(count) AS num_jobs FROM states \
			JOIN job_counts_new sjc ON states.id = sjc.object_id AND sjc.object_type = 2 AND fresh_only = 0 JOIN translations ON class_name = 'Country' AND app_language_id = 1 AND translation_id = 0 AND states.country_id = translations.object_id \
			WHERE states.id != 0 GROUP BY states.id \
		UNION \
\
		SELECT cities.id+200000 AS id, cities.name, CONCAT(translation, ' ', IF(states.name REGEXP '^[a-z][a-z][^a-z]', SUBSTRING(states.name, 4), states.name), ' ', cities.name) AS text, cities.id AS city_id, cities.country_id AS country_id, state_id, 0 AS subcategory_id, SUM(count) AS num_jobs FROM cities \
			JOIN job_counts_new cjc ON cities.id = cjc.object_id AND cjc.object_type = 4 AND fresh_only = 0 JOIN states ON cities.state_id = states.id JOIN translations ON class_name = 'Country' AND app_language_id = 1 AND translation_id = 0 AND cities.country_id = translations.object_id \
			WHERE cities.id != 0 GROUP BY cities.id \
		ORDER BY id


	sql_attr_string = text
	sql_attr_uint = country_id
	sql_attr_uint = state_id
	sql_attr_uint = city_id
	sql_attr_uint = subcategory_id
	sql_attr_uint = num_jobs

	sql_attr_multi = uint category_ids from query; \
		SELECT id, object_id FROM translations WHERE class_name IN ('Job_category') AND  app_language_id = 1 AND translation_id = 0 \
		UNION \
		SELECT id, job_category_id FROM translations t JOIN job_category_subcategories cs ON t.object_id = cs.job_subcategory_id WHERE \
			class_name IN ('Job_subcategory') AND app_language_id = 1 AND translation_id = 0 \
		ORDER BY id; \
		SELECT MIN(id), MAX(id) FROM job_categories;
}

#############################################################################
## index definition
#############################################################################
index pages
{
	source			= pages
	path			= <?= $SPHINX_ROOT ?>/var/data/pages
	mlock			= 0
	morphology		= stem_en
	min_word_len	= 3
	html_strip		= 0
	exceptions		= <?= $SPHINX_ROOT ?>/etc/page_exceptions.txt
}

index geocities1
{
	source			= geocities
	path			= <?= $SPHINX_ROOT ?>/var/data/geocities1
	mlock			= 0
	morphology		= none
	min_word_len	= 2
	html_strip		= 0
}

index cvs1
{
	source			= cvs
	path			= <?= $SPHINX_ROOT ?>/var/data/cvs1
	mlock			= 0
	morphology		= none
	min_word_len	= 2
	html_strip		= 0
	exceptions		= <?= $SPHINX_ROOT ?>/etc/jobs_exceptions.txt
}

index cvs1_delta : cvs1
{
	source			= cvs_delta
	path			= <?= $SPHINX_ROOT ?>/var/data/cvs1_delta
}

index job_titles
{
	type			= rt
	path			= <?= $SPHINX_ROOT ?>/var/data/job_titles
	# id is implicit "field" it will store job id and can be used to REPLACE/DELETE entries based on job id
	rt_field		= title 		# original, unfiltered title cleaned up (no non alphanum chars) for indexing
	rt_attr_string	= full_title	# original, unfiltered title for storage and possibly more extensive comparision.
	rt_attr_string	= hash 			# ssdeep hash
	rt_mem_limit	= 2046M
}

index jobs_flexible
{
	source			= jobs_flexible
	path			= <?= $SPHINX_ROOT ?>/var/data/jobs_flexible
	mlock			= 0
	morphology		= none
	min_word_len	= 2
	html_strip		= 0
	exceptions		= <?= $SPHINX_ROOT ?>/etc/jobs_exceptions.txt
}

index jobs_flexible_newest
{
    source			= jobs_flexible_newest
    path			= <?= $SPHINX_ROOT ?>/var/data/jobs_flexible_newest
    mlock			= 0
    morphology		= none
    min_word_len	= 2
    html_strip		= 0
    exceptions		= <?= $SPHINX_ROOT ?>/etc/jobs_exceptions.txt
}

index jobs_flexible_delta
{
	source			= jobs_flexible_delta
	path			= <?= $SPHINX_ROOT ?>/var/data/jobs_flexible_delta
	mlock			= 0
	morphology		= none
	min_word_len	= 2
	html_strip		= 0
	exceptions		= <?= $SPHINX_ROOT ?>/etc/jobs_exceptions.txt
    kbatch = jobs_flexible, jobs_flexible_newest
    kbatch_source = kl, id
}

index jobs_completions
{
	source			= jobs_completions
	path			= <?= $SPHINX_ROOT ?>/var/data/jobs_completions
	mlock			= 0
	morphology		= none
	min_word_len	= 2
	html_strip		= 0
	min_infix_len	= 2
	exceptions		= <?= $SPHINX_ROOT ?>/etc/jobs_exceptions.txt
}

index requirements
{
	source			= requirements
	path			= <?= $SPHINX_ROOT ?>/var/data/requirements
	mlock			= 0
	morphology		= stem_en
	min_word_len	= 3
	html_strip		= 1
}

#############################################################################
## indexer settings
## Max memory for indexer is 2047Mb internal limit
#############################################################################

indexer
{
	mem_limit		= 2046M
}

#############################################################################
## searchd settings
#############################################################################
#max_children setting depends on how Apache workers are set and is a dangerous setting if set incorrectly.
#Currently 30 seems just a random number because default is 0 (unlimited) in workers=threads, or 1.5 times the CPU cores count in workers=thread_pool mode.
#there are only 12 cores on the current server
#Upon reading more in manual - more damage can be done by limiting number of children so setting this value to default 0 as we seem to be in workers=threads mode
searchd
{
	read_timeout			= 5
	client_timeout			= 300
	max_children			= 0
	workers 				= threads
	pid_file				= <?= $SPHINX_ROOT ?>/var/searchd.pid
	seamless_rotate			= 1
	preopen_indexes			= 1
	unlink_old				= 1
	max_packet_size			= 8M
	max_filters				= 256
	max_filter_values		= 4096
	log 					= <?= $SPHINX_ROOT ?>/var/log/searchd.log
	binlog_path 			= <?= $SPHINX_ROOT ?>/var/log/
	<?= !IS_DEV ? '' : 'listen = '.($for_tests ? "localhost:".SPHINX_SEARCH_TEST_PORT : 'localhost')."\n" ?>
	<?= !IS_DEV || !$for_tests ? '' : "listen = localhost:".SPHINXQL_SEARCH_TEST_PORT.":mysql41"."\n" ?>
	<?= !IS_DEV ? '' : 'query_log = '.$SPHINX_ROOT."/var/log/queries.log\n" ?>
	<?= !IS_DEV ? '' : "query_log_format = sphinxql\n" ?>

}

# --eof--
