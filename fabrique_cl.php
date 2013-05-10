<?php

/**
 *  Fichier généré par la Fabrique de plugin v5
 *   le 2013-05-10 11:52:18
 *
 *  Ce fichier de sauvegarde peut servir à recréer
 *  votre plugin avec le plugin «Fabrique» qui a servi à le créer.
 *
 *  Bien évidemment, les modifications apportées ultérieurement
 *  par vos soins dans le code de ce plugin généré
 *  NE SERONT PAS connues du plugin «Fabrique» et ne pourront pas
 *  être recréées par lui !
 *
 *  La «Fabrique» ne pourra que régénerer le code de base du plugin
 *  avec les informations dont il dispose.
 *
**/

if (!defined("_ECRIRE_INC_VERSION")) return;

$data = array (
  'fabrique' => 
  array (
    'version' => 5,
  ),
  'paquet' => 
  array (
    'nom' => 'Case Law',
    'slogan' => '',
    'description' => '',
    'prefixe' => 'cl',
    'version' => '1.0.0',
    'auteur' => 'Rainer Müller',
    'auteur_lien' => 'http://websimple.be/',
    'licence' => 'GNU/GPL',
    'categorie' => 'divers',
    'etat' => 'stable',
    'compatibilite' => '[3.0.8;3.0.*]',
    'documentation' => '',
    'administrations' => 'on',
    'schema' => '1.0.0',
    'formulaire_config' => '',
    'formulaire_config_titre' => '',
    'fichiers' => 
    array (
      0 => 'fonctions',
      1 => 'pipelines',
    ),
    'inserer' => 
    array (
      'paquet' => '',
      'administrations' => 
      array (
        'maj' => '',
        'desinstallation' => '',
        'fin' => '',
      ),
      'base' => 
      array (
        'tables' => 
        array (
          'fin' => '',
        ),
      ),
    ),
    'scripts' => 
    array (
      'pre_copie' => '',
      'post_creation' => '',
    ),
    'exemples' => 'on',
  ),
  'objets' => 
  array (
    0 => 
    array (
      'nom' => 'Case laws',
      'nom_singulier' => 'Case law',
      'genre' => 'masculin',
      'logo_variantes' => '',
      'table' => 'spip_case_laws',
      'cle_primaire' => 'id_case_law',
      'cle_primaire_sql' => 'bigint(21) NOT NULL',
      'table_type' => 'case_law',
      'champs' => 
      array (
        0 => 
        array (
          'nom' => 'Article',
          'champ' => 'id_article',
          'sql' => 'bigint(21) NOT NULL DEFAULT \'0\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
            2 => 'obligatoire',
          ),
          'recherche' => '2',
          'saisie' => 'selecteur_article',
          'explication' => '',
          'saisie_options' => '',
        ),
        1 => 
        array (
          'nom' => 'Juris',
          'champ' => 'juris',
          'sql' => 'tinytext NOT NULL',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
            2 => 'obligatoire',
          ),
          'recherche' => '',
          'saisie' => 'input',
          'explication' => '4',
          'saisie_options' => '',
        ),
        2 => 
        array (
          'nom' => 'Decdate',
          'champ' => 'decdate',
          'sql' => 'date NOT NULL DEFAULT \'0000-00-00\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '4',
          'saisie' => 'date',
          'explication' => '',
          'saisie_options' => '',
        ),
        3 => 
        array (
          'nom' => 'Signs',
          'champ' => 'signs',
          'sql' => 'tinytext NOT NULL',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
            2 => 'obligatoire',
          ),
          'recherche' => '4',
          'saisie' => 'input',
          'explication' => '',
          'saisie_options' => '',
        ),
        4 => 
        array (
          'nom' => 'Decnumber',
          'champ' => 'decnumber',
          'sql' => 'varchar(20) NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '4',
          'saisie' => 'input',
          'explication' => '',
          'saisie_options' => '',
        ),
        5 => 
        array (
          'nom' => 'Article',
          'champ' => 'article',
          'sql' => 'text NOT NULL',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '4',
          'saisie' => 'textarea',
          'explication' => '',
          'saisie_options' => '',
        ),
        6 => 
        array (
          'nom' => 'Comments',
          'champ' => 'comments',
          'sql' => 'text NOT NULL',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '4',
          'saisie' => 'textarea',
          'explication' => '',
          'saisie_options' => '',
        ),
        7 => 
        array (
          'nom' => 'Dartslink',
          'champ' => 'dartslink',
          'sql' => 'varchar(255) NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'versionne',
          ),
          'recherche' => '4',
          'saisie' => 'input',
          'explication' => '',
          'saisie_options' => '',
        ),
        8 => 
        array (
          'nom' => 'Color',
          'champ' => 'color',
          'sql' => 'varchar(20) NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '8',
          'saisie' => 'input',
          'explication' => '',
          'saisie_options' => '',
        ),
      ),
      'champ_titre' => 'signs',
      'champ_date' => '',
      'statut' => '',
      'chaines' => 
      array (
        'titre_objets' => 'Case laws',
        'titre_objet' => 'Case law',
        'info_aucun_objet' => 'Aucun case law',
        'info_1_objet' => 'Un case law',
        'info_nb_objets' => '@nb@ case laws',
        'icone_creer_objet' => 'Créer un case law',
        'icone_modifier_objet' => 'Modifier ce case law',
        'titre_logo_objet' => 'Logo de ce case law',
        'titre_langue_objet' => 'Langue de ce case law',
        'titre_objets_rubrique' => 'Case laws de la rubrique',
        'info_objets_auteur' => 'Les case laws de cet auteur',
        'retirer_lien_objet' => 'Retirer ce case law',
        'retirer_tous_liens_objets' => 'Retirer tous les case laws',
        'ajouter_lien_objet' => 'Ajouter ce case law',
        'texte_ajouter_objet' => 'Ajouter un case law',
        'texte_creer_associer_objet' => 'Créer et associer un case law',
        'texte_changer_statut_objet' => 'Ce case law est :',
      ),
      'table_liens' => 'on',
      'vue_liens' => 
      array (
        0 => 'spip_articles',
      ),
      'roles' => '',
      'auteurs_liens' => '',
      'vue_auteurs_liens' => '',
      'echafaudages' => 
      array (
        0 => 'prive/squelettes/contenu/objets.html',
        1 => 'prive/squelettes/contenu/objet.html',
      ),
      'autorisations' => 
      array (
        'objet_creer' => '',
        'objet_voir' => '',
        'objet_modifier' => '',
        'objet_supprimer' => '',
        'associerobjet' => '',
      ),
      'boutons' => 
      array (
        0 => 'menu_edition',
        1 => 'outils_rapides',
      ),
    ),
  ),
  'images' => 
  array (
    'paquet' => 
    array (
      'logo' => 
      array (
        0 => 
        array (
          'extension' => 'png',
          'contenu' => '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',
        ),
      ),
    ),
    'objets' => 
    array (
      0 => 
      array (
      ),
    ),
  ),
);

?>