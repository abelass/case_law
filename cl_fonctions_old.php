<?php


/* Fonction qui divise sans virgule */
function divise($texte,$divise){
    $s="";
    $s=ceil($texte / $divise);
    return $s;
    }




function cloturer_enchere($id_objet,$statut) {
			$sql2 = spip_query( "SELECT * FROM spip_encheres_mises WHERE id_objet='$id_objet' ORDER BY montant_mise DESC LIMIT 1");
				while($data = spip_fetch_array($sql2)) {
					$id_encherisseur=$data['id_encherisseur'];
					};

			// Détermine l'action suivant si l'enchère est resté sans mises (statut: mise_en_vente) ou avec mises (statut : mise_en_vente_active)	
			$actualiser = charger_fonction('actions_enchere_gagne','inc');
			$actualiser('cloture_cron_'.$statut,$id_objet,$id_encherisseur);
			
}

function image_reduire_recadre($img, $largeur, $hauteur, $position='center') {
       include_spip('inc/filtres_images');
       if ($img!='IMG/'){
            list ($ret["hauteur"],$ret["largeur"]) = taille_image($img);
            $ratio_x = $ret["largeur"]/$largeur;
            $ratio_y = $ret["hauteur"]/$hauteur;
            $ratio   = ($ratio_x <= $ratio_y) ? $ratio_x : $ratio_y;
            return image_recadre(image_reduire_par($img, $ratio), $largeur, $hauteur, $position);
            }
}
/* Fonction permet de cacluler le temps entre la date actuelle; et une date donnée */

function difference_jour($date_fin,$mode = NULL){
	if(!$mode){
	$mode='complete';
	};


	$date_fin_jours = date('Y-m-d',strtotime($date_fin));
	$date_fin_heures = date('G:i:s',strtotime($date_fin));

  	$split_jours = explode("-", $date_fin_jours);
  	$split_heures = explode(":", $date_fin_heures);
  	
 

  	$date_fin = mktime($split_heures[0]-1, $split_heures[1], $split_heures[2], $split_jours[1], $split_jours[2], $split_jours[0]) ;



  	$date_actuel = time();



  	$date_difference = $date_fin-$date_actuel;
  	

	$mois=date('m',$date_difference)-1;
 	$jours=date('d',$date_difference)-1;
 	$jours_nom=date('d',$date_difference);
	$heures=date('G',$date_difference);
	$minutes=date('i',$date_difference);
	$secondes=date('s',$date_difference);


	if ($mode=='simple'){
		if ($mois !=0 ) $mois_affichage=$mois.' '._T('m').' ';
 		if ($jours !=0) $jours_affichage=$jours.' '._T('j').' ';
		if ($heures !=0) $heures_affichage=$heures.' '._T('h').' ';
		if ($minutes !=0) $minutes_affichage=$minutes.' '._T('min').' ';
		if ($secondes !=0) $secondes_affichage=$secondes.' '._T('s');
		}
	if ($mode=='complete'){
		if ($mois !=0) $mois_affichage=$mois.' '._T('mois').' ';
 		if ($jours !=0){
			if($jours==1) $jours_affichage=$jours.' '._T('jour').' ';
			else $jours_affichage=$jours.' '._T('jours').' ';
			}
		if ($heures !=0) $heures_affichage=$heures.' '._T('h').' ';
		if ($minutes !=0) $minutes_affichage=$minutes.' '._T('min').' ';
		if ($secondes !=0) $secondes_affichage=$secondes.' '._T('s');
		};

	$affichage =$mois_affichage.$jours_affichage.$heures_affichage.$minutes_affichage.$secondes_affichage;

 	return $affichage;

}
// Filtre qui cherche et remplace le nom par le nom de la marque si existe

function nom_marque($nom, $id_auteur) {
 	$sql = spip_query( "SELECT * FROM spip_auteurs_elargis WHERE id_auteur='$id_auteur'");
 				while($data = spip_fetch_array($sql)) {
				$nom_marque=extraire_multi($data['nom_marque']);
							}
if($nom_marque) return $nom_marque;
else return $nom;
}

// Filtre qui convertie une url en url de renvoi

function url_retour($url_retour, $parametre_url1 = NULL, $parametre_url2 = NULL, $parametre_url3 = NULL) {
		if ($parametre_url1) $par1= '&'.$parametre_url1;
		if ($parametre_url2) $par2 = '&'.$parametre_url2;
		if ($parametre_url3) $par3 = '&'.$parametre_url3;
		$url_retour= $url_retour.$par1.$par2.$par3;
		header('Location:'.$url_retour);
}

// http://doc.spip.org/@afficher_case_document
function afficher_case_document2($id_document, $id, $script, $type, $deplier=false,$tag=false) {
		
	global $spip_lang_right;
	$document = sql_fetsel("docs.id_document, docs.id_vignette,docs.extension,docs.titre,docs.descriptif,docs.fichier,docs.largeur,docs.hauteur,docs.taille,docs.mode,docs.distant, docs.date, L.vu", "spip_documents AS docs INNER JOIN spip_documents_liens AS L ON L.id_document=docs.id_document", "L.id_objet=".intval($id)." AND objet=".sql_quote($type)." AND L.id_document=".sql_quote($id_document));
	if ($tag==$id_document){
	$tag=' class="logo"';}
	else{$tag='';};

	if (!$document) return "";

	$id_vignette = $document['id_vignette'];
	$extension = $document['extension'];
	$titre = $document['titre'];
	$descriptif = $document['descriptif'];
	$url = generer_url_entite($id_document, 'document');
	$fichier = $document['fichier'];
	$largeur = $document['largeur'];
	$hauteur = $document['hauteur'];
	$taille = $document['taille'];
	$mode = $document['mode'];
	$distant = $document['distant'];

	// le doc est-il appele dans le texte ?
	$doublon = est_inclus($id_document);

	$cadre = strlen($titre) ? $titre : basename($fichier);

	$letype = sql_fetsel("titre,inclus", "spip_types_documents", "extension=".sql_quote($extension));
	if ($letype) {
		$type_inclus = $letype['inclus'];
		$type_titre = $letype['titre'];
	}
	//
	// Afficher un document
	//
	$ret = "";
	if ($mode == 'document') {

		$ret .= debut_cadre_enfonce("doc-24.gif", true, "", lignes_longues(typo($cadre),20), "document$id_document");
		$ret .= "<a name='document$id_document'></a>\n";

		if ($distant == 'oui') {
			$dist = "\n<div class='verdana1' style='float: $spip_lang_right; text-align: $spip_lang_right;'>";

			// Signaler les documents distants par une icone de trombone
			$dist .= "\n<img src='" . chemin_image('attachment.gif') . "'\n\talt=\"$fichier\"\n\ttitle=\"$fichier\" />\n";
			// Bouton permettant de copier en local le fichier
			include_spip('inc/tourner');
			$dist .= bouton_copier_local($document, $type, $id, $id_document, $script);
			
			$dist .="</div>\n";
		} else {
			$dist = '';
		}

		//
		// Affichage de la vignette
		//
		$ret .= "\n<div style='text-align: center'>"
		. $dist
		. document_et_vignette($document, $url, true)
		. '</div>'
		. "\n<div class='verdana1' style='text-align: center; color: black;'>\n"
		. ($type_titre ? $type_titre : 
		      ( _T('info_document').' '.majuscules($extension)))
		. "</div>";

		// Affichage du raccourci <doc...> correspondant
		$raccourci = '';
		if ($doublon)
			$raccourci .= affiche_raccourci_doc('doc', $id_document, '');
		else {
			if (($type_inclus == "embed" OR $type_inclus == "image") AND $largeur > 0 AND $hauteur > 0) {
				$raccourci .= "<b>"._T('info_inclusion_vignette')."</b><br />";
			}
			$raccourci .= "<div style='color: 333333'>"
			. affiche_raccourci_doc('doc', $id_document, 'left')
			. affiche_raccourci_doc('doc', $id_document, 'center')
			. affiche_raccourci_doc('doc', $id_document, 'right')
			. "</div>\n";
	
			if (($type_inclus == "embed" OR $type_inclus == "image") AND $largeur > 0 AND $hauteur > 0) {
				$raccourci .= "<div style='padding:2px; ' class='arial1 spip_xx-small'>";
				$raccourci .= "<b>"._T('info_inclusion_directe')."</b><br />";
				$raccourci .= "<div style='color: 333333'>"
				. affiche_raccourci_doc('emb', $id_document, 'left')
				. affiche_raccourci_doc('emb', $id_document, 'center')
				. affiche_raccourci_doc('emb', $id_document, 'right')
				. "</div>\n";
				$raccourci .= "</div>";
			}
		}

		$ret .= "\n<div style='padding:2px; ' class='arial1 spip_xx-small'>"
			. $raccourci."</div>\n";

		$legender = charger_fonction('legender2', 'inc');
		$ret .= $legender($id_document, $document, $script, $type, $id, "document$id_document", $deplier);

		$ret .= fin_cadre_enfonce(true);

	} else if ($mode == 'image') {

	//
	// Afficher une image inserable dans l'article
	//
	
	  $ret .= debut_cadre_relief("image-24.gif", true, "", lignes_longues(typo($cadre),20), "document$id_document");

		//
		// Afficher un apercu (pour les images)
		//
		if ($type_inclus == 'image') {
			$ret .= "<div style='text-align: center; padding: 2px;'". $tag.">\n";
			if($tag) $ret.= "<h2>"._T('logo')."</h2>";
			$ret .= document_et_vignette($document, $url, true);
			$ret .= "</div>\n";
			if($tag) $ret .= "<br class='nettoyeur'>&nbsp;</br>";			
		}

		//
		// Preparer le raccourci a afficher sous la vignette ou sous l'apercu
		//
		$raccourci = "";
		if (strlen($descriptif) > 0 OR strlen($titre) > 0)
			$doc = 'doc';
		else
			$doc = 'img';

		if ($doublon)
			$raccourci .= affiche_raccourci_doc($doc, $id_document, '');
		else {
			$raccourci .=
				affiche_raccourci_doc($doc, $id_document, 'left')
				. affiche_raccourci_doc($doc, $id_document, 'center')
				. affiche_raccourci_doc($doc, $id_document, 'right');
		}

		$ret .= "\n<div style='padding:2px; ' class='arial1 spip_xx-small'>"
			. $raccourci."</div>\n";


		$legender = charger_fonction('legender2', 'inc');
		$ret .= $legender($id_document, $document, $script, $type, $id, "document$id_document", $deplier);
		
		$ret .= fin_cadre_relief(true);
	}
	return "<div>$ret</div>"; // on encapsule chaque document dans un container pour permettre son remplacement en ajax
}

// http://doc.spip.org/@afficher_documents_colonne
function afficher_documents_colonne2($id, $type="article",$script=NULL) {
	include_spip('inc/autoriser');
	
	// il faut avoir les droits de modif sur l'article pour pouvoir uploader !
	if (!autoriser('joindredocument',$type,$id))
		return "";

	include_spip('inc/presentation'); // pour l'aide quand on appelle afficher_documents_colonne depuis un squelette
	// seuls cas connus : article, breve ou rubrique
	if ($script==NULL){
		$script = $type.'s_edit';
		if (!test_espace_prive())
			$script = parametre_url(self(),"show_docs",'');
	}
	$id_document_actif = _request('show_docs');

	$joindre = charger_fonction('joindre2', 'inc');

	define('_INTERFACE_DOCUMENTS', true);
	if (!_INTERFACE_DOCUMENTS
	OR $GLOBALS['meta']["documents_$type"]=='non') {

	// Ajouter nouvelle image
	$ret = "<div id='images'>\n" 
		. $joindre(array(
			'cadre' => 'relief',
			'icone' => 'image-24.gif',
			'fonction' => 'creer.gif',
			'titre' => majuscules(_T('bouton_ajouter_image')).aide("ins_img"),
			'script' => $script,
			'args' => "id_$type=$id",
			'id' => $id,
			'intitule' => _T('info_telecharger'),
			'mode' => 'image',
			'type' => $type,
			'ancre' => '',
			'id_document' => 0,
			'iframe_script' => generer_url_ecrire("documents_colonne","id=$id&type=$type",true)
		))
		. '</div><br />';

	if (!_INTERFACE_DOCUMENTS) {
		//// Images sans documents
		$res = sql_select("D.id_document", "spip_documents AS D LEFT JOIN spip_documents_liens AS T ON T.id_document=D.id_document", "T.id_objet=" . intval($id) . " AND T.objet=" . sql_quote($type) . " AND D.mode='image'", "", "D.id_document");

		$ret .= "\n<div id='liste_images'>";

		while ($doc = sql_fetch($res)) {
			$id_document = $doc['id_document'];
			$deplier = ($id_document_actif==$id_document);
			$ret .= afficher_case_document2($id_document, $id, $script, $type, $deplier);
		}

		$ret .= "</div><br /><br />\n";
		}
	}
	$url_art=intval($id);
			$sql = spip_query( "SELECT * FROM spip_documents_liens WHERE id_objet='$url_art' ORDER BY id_document ASC" );
			$compteur=0; 
			while($data = spip_fetch_array($sql)) {
			$id_document=$data['id_document'];
			$sql_2 = spip_query( "SELECT * FROM spip_documents WHERE id_document='$id_document' AND mode='image' ");

				while($data = spip_fetch_array($sql_2)) {
				$compteur = $compteur+1;
				if ($compteur == '1'){$tag=$id_document;}
				
			}
		}							

	// Ajouter nouveau document limité a 5
	if ($compteur<5){

	$bouton = !_INTERFACE_DOCUMENTS
		? majuscules(_T('bouton_ajouter_document')).aide("ins_doc")
		: (_T('bouton_ajouter_image_document')).aide("ins_doc");

	$ret .= "<div id='documents'></div>\n<div id='portfolio'></div>\n";
	if ($GLOBALS['meta']["documents_$type"]!='non') {
		$ret .= $joindre(array(
			'cadre' => _INTERFACE_DOCUMENTS ? 'relief' : 'enfonce',
			'icone' => 'doc-24.gif',
			'fonction' => 'creer.gif',
			'titre' => $bouton,
			'script' => $script,
			'args' => "id_$type=$id",
			'id' => $id,
			'intitule' => _T('info_telecharger'),
			'mode' => _INTERFACE_DOCUMENTS ? 'choix' : 'document',
			'type' => $type,
			'ancre' => '',
			'tag' => $tag,			
			'id_document' => 0,
			'compteur' => $compteur,			
			'iframe_script' => generer_url_ecrire("documents_colonne","id=$id&type=$type",true)
		));
	}
}
	// Afficher les documents lies
	$ret .= "<br /><div id='liste_documents'>\n";

	//// Documents associes
	$res = sql_select("D.id_document", "spip_documents AS D LEFT JOIN spip_documents_liens AS T ON T.id_document=D.id_document", "T.id_objet=" . intval($id) . " AND T.objet=" . sql_quote($type)
	. ((!_INTERFACE_DOCUMENTS)
		? " AND D.mode='document'"	
    	: " AND D.mode IN ('image','document')"
	), "", "D.mode, D.id_document");

	while($row = sql_fetch($res))
		$ret .= afficher_case_document2($row['id_document'], $id, $script, $type, ($id_document_actif==$row['id_document']),$tag);

	$ret .= "</div>";
	if (test_espace_prive()){
		$ret .= http_script('', "async_upload.js")
		  . http_script('$("form.form_upload").async_upload(async_upload_article_edit)');
	}
    
	return $ret;

}

?>