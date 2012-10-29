<?php

if (!defined("_ECRIRE_INC_VERSION")) return;
/* @annotation: Charge les valeurs par défaut pour le fomulaire editer objet */
function formulaires_edit_case_law_charger_dist($id_article) {
    $valeurs = array(
	"id_case_law"	=> _request('id_case_law'),
	"id_article"	=> "$id_article",
	"juris"	=> "",
 	"decdate" => "",
	"decnumber" => "",
 	"signs" => "",
 	"article"	=> "",
 	"comments"	=> "",
	"dartslink" => "",
 	"color" => "",
 	"edit" => _request('edit'), 	
    );
    $id_case_law = $valeurs['id_case_law'];
    $id_article = $valeurs['id_article']; 

   $sql = spip_query( "SELECT * FROM spip_articles WHERE id_article='$id_article' ");

			while($data = spip_fetch_array($sql)) {
			$date = affdate($data['date'],'d-m-Y');
					}
    $valeurs['decdate'] =  $date;					
	   $sql = spip_query( "SELECT * FROM spip_cl_case_laws WHERE id_case_law='$id_case_law' ");

			while($data = spip_fetch_array($sql)) {
			$valeurs['juris'] = $data['juris'];
			$valeurs['decdate'] = $data['decdate'];
			$valeurs['decnumber'] = $data['decnumber'];
			$valeurs['signs'] = $data['signs'];
			$valeurs['article'] = $data['article'];
			$valeurs['comments'] = $data['comments'];
			$valeurs['dartslink'] = $data['dartslink'];
			$valeurs['color'] = $data['color'];
			
					}
  
    $valeurs['_hidden'] .= "<input type='hidden' name='id_case_law' value='$id_case_law' />";
    $valeurs['_hidden'] .= "<input type='hidden' name='id_article' value='$id_article' />";    
    
    return $valeurs;
}


  function formulaires_edit_case_law_verifier_dist() {
      $erreurs = array();
      foreach(array('juris') as $champ) {
         if (!_request($champ)) {
              $erreurs[$champ] = "This field is required !";
          }
    }
     if (count($erreurs)) {
          $erreurs['message_erreur'] = "You have an error";
      }
     return $erreurs;
  }

function formulaires_edit_case_law_traiter_dist(){
		$id_case_law= _request('id_case_law');
		$id_article= _request('id_article');
		$juris= _request('juris');
		$decdate= _request('decdate');
		$decnumber = _request('decnumber');
 		$signs = _request('signs');
 		$article	=_request('article');
 		$comments	=_request('comments');
		$dartslink =_request('dartslink');
 		$color =_request('color');
 		$delete =_request('delete'); 		
 		
		$decdate = date('Y-m-d',strtotime($decdate));

	if(!$id_case_law){
 		$arg_inser_cl = array(
			'id_case_law' => '',
			'id_article' => $id_article,
			'juris' => $juris,
			'decdate' => $decdate,
			'decnumber' => $decnumber,
			'signs' => $signs,
			'article' => $article,
			'comments'=> $comments,
			'dartslink' => $dartslink,
			'color' => $color,
			);
			$id_case_law = sql_insertq('spip_cl_case_laws',$arg_inser_cl);
		}
	elseif(!$delete){
		spip_query("UPDATE spip_cl_case_laws SET juris='$juris',decdate='$decdate',decnumber='$decnumber',signs='$signs',article='$article',comments='$comments',dartslink='$dartslink',color='$color'   WHERE id_case_law='$id_case_law'");
		}
	elseif($delete){
			spip_query ("DELETE FROM spip_cl_case_laws  WHERE id_case_law='$id_case_law'");
			}
	}

?>