<?php
/**
 * Déclarations relatives à la base de données
 *
 * @plugin     Case Law
 * @copyright  2013
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Cl\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;


/**
 * Déclaration des alias de tables et filtres automatiques de champs
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interfaces
 *     Déclarations d'interface pour le compilateur
 * @return array
 *     Déclarations d'interface pour le compilateur
 */
function cl_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['case_laws'] = 'case_laws';

	return $interfaces;
}


/**
 * Déclaration des objets éditoriaux
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function cl_declarer_tables_objets_sql($tables) {

	$tables['spip_case_laws'] = array(
		'type' => 'case_law',
		'principale' => "oui", 
		'table_objet_surnoms' => array('caselaw'), // table_objet('case_law') => 'case_laws' 
		'field'=> array(
			"id_case_law"        => "bigint(21) NOT NULL",
			"juris"              => "tinytext NOT NULL",
			"decdate"            => "date NOT NULL DEFAULT '0000-00-00'",
			"signs"              => "tinytext NOT NULL",
			"decnumber"          => "varchar(20) NOT NULL DEFAULT ''",
			"article"            => "text NOT NULL",
			"comments"           => "text NOT NULL",
			"dartslink"          => "varchar(255) NOT NULL DEFAULT ''",
			"color"              => "varchar(20) NOT NULL DEFAULT ''",
			"maj"                => "TIMESTAMP"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_case_law",
		),
		'titre' => "juris AS titre, '' AS lang",
		 #'date' => "",
		'champs_editables'  => array('juris', 'decdate', 'signs', 'decnumber', 'article', 'comments', 'color'),
		'champs_versionnes' => array('juris', 'decdate', 'signs', 'decnumber', 'article', 'comments', 'dartslink', 'color'),
		'rechercher_champs' => array("decdate" => 4, "signs" => 4, "decnumber" => 4, "article" => 4, "comments" => 4, "dartslink" => 4, "color" => 8,'juris'=>2),
		'tables_jointures'  => array('spip_case_laws_liens'),
		

	);

	return $tables;
}


/**
 * Déclaration des tables secondaires (liaisons)
 *
 * @pipeline declarer_tables_auxiliaires
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function cl_declarer_tables_auxiliaires($tables) {

	$tables['spip_case_laws_liens'] = array(
		'field' => array(
			"id_case_law"        => "bigint(21) DEFAULT '0' NOT NULL",
			"id_objet"           => "bigint(21) DEFAULT '0' NOT NULL",
			"objet"              => "VARCHAR(25) DEFAULT '' NOT NULL",
			"vu"                 => "VARCHAR(6) DEFAULT 'non' NOT NULL"
		),
		'key' => array(
			"PRIMARY KEY"        => "id_case_law,id_objet,objet",
			"KEY id_case_law"    => "id_case_law"
		)
	);

	return $tables;
}


?>