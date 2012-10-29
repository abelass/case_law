<?php

if (!defined("_ECRIRE_INC_VERSION")) return;
	
function cl_declarer_tables_principales($tables_principales){

$cl_case_laws = array(
	"id_case_law"	=> "bigint(21) NOT NULL",
	"id_article"	=> "bigint(21) NOT NULL",
	"juris"	=> "tinytext NOT NULL",
 	"decdate" => "date DEFAULT '0000-00-00' NOT NULL",
	"decnumber" => "varchar(20) NOT NULL",
 	"signs" => "text(21) NOT NULL",
 	"article"	=> "text NOT NULL",
 	"comments"	=> "text NOT NULL",
	"dartslink" => "varchar(255) NOT NULL",
 	"color" => "varchar(20) NOT NULL",
	);
	
$cl_case_laws_key = array(
	"PRIMARY KEY"		=> "id_case_law",
	"KEY id_article"	=> "id_article",	
	);

$cl_case_laws_join = array(
	"id_article"		=> "id_article",
	"id_case_law"		=> "id_case_law",	
	);

$tables_principales['spip_cl_case_laws'] = array(
	'field' => &$cl_case_laws,
	'key' => &$cl_case_laws_key,
	'join' => &$cl_case_laws_join
	);

return $tables_principales;
};
?>
