<?php
/**
 * Fichier gérant l'installation et désinstallation du plugin Case Law
 *
 * @plugin     Case Law
 * @copyright  2013
 * @author     Rainer Müller
 * @licence    GNU/GPL
 * @package    SPIP\Cl\Installation
 */

if (!defined('_ECRIRE_INC_VERSION')) return;

function update_db(){
   $sql=sql_select('id_case_law,id_article','spip_case_laws');
   while($data=sql_fetch( $sql)){
        sql_insertq('spip_case_laws_liens',array('objet'=>'article','id_objet'=>$data['id_article'],'id_case_law'=>$data['id_case_law']));
            }
       return;
    
}

/**
 * Fonction d'installation et de mise à jour du plugin Case Law.
 *
 * Vous pouvez :
 *
 * - créer la structure SQL,
 * - insérer du pre-contenu,
 * - installer des valeurs de configuration,
 * - mettre à jour la structure SQL 
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 * @return void
**/
function cl_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();
	# quelques exemples
	# (que vous pouvez supprimer !)
	# 
	# $maj['create'] = array(array('creer_base'));
	#
	# include_spip('inc/config')
	# $maj['create'] = array(
	#	array('maj_tables', array('spip_xx', 'spip_xx_liens')),
	#	array('ecrire_config', array('cl', array('exemple' => "Texte de l'exemple")))
	#);
	#
	# $maj['1.1.0']  = array(array('sql_alter','TABLE spip_xx RENAME TO spip_yy'));
	# $maj['1.2.0']  = array(array('sql_alter','TABLE spip_xx DROP COLUMN id_auteur'));
	# $maj['1.3.0']  = array(
	#	array('sql_alter','TABLE spip_xx CHANGE numero numero int(11) default 0 NOT NULL'),
	#	array('sql_alter','TABLE spip_xx CHANGE texte petit_texte mediumtext NOT NULL default \'\''),
	# );
	# ...

	$maj['create'] = array(array('maj_tables', array('spip_case_laws', 'spip_case_laws_liens')));
    $maj['1.0.3'] = array(
        array('maj_tables', array('spip_case_laws', 'spip_case_laws_liens')),
        array('update_db','')
        );

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}


/**
 * Fonction de désinstallation du plugin Case Law.
 * 
 * Vous devez :
 *
 * - nettoyer toutes les données ajoutées par le plugin et son utilisation
 * - supprimer les tables et les champs créés par le plugin. 
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @return void
**/
function cl_vider_tables($nom_meta_base_version) {
	# quelques exemples
	# (que vous pouvez supprimer !)
	# sql_drop_table("spip_xx");
	# sql_drop_table("spip_xx_liens");

	sql_drop_table("spip_case_laws");
	sql_drop_table("spip_case_laws_liens");

	# Nettoyer les versionnages et forums
	sql_delete("spip_versions",              sql_in("objet", array('case_law')));
	sql_delete("spip_versions_fragments",    sql_in("objet", array('case_law')));
	sql_delete("spip_forum",                 sql_in("objet", array('case_law')));

	effacer_meta($nom_meta_base_version);
}

?>