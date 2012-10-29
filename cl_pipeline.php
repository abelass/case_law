<?php

function cl_affiche_milieu($flux) {
   $exec = $flux["args"]["exec"];

	if ($exec=='articles'){
		$id_article = $flux["args"]["id_article"];
      $contexte = array('id_article'=>$id_article,);
      $ret = "<div id='pave_selection'>";
      $ret .= recuperer_fond("form_cl", $contexte);
      $ret .= "</div>";
      $flux["data"] .= $ret;
	};
    return $flux;
}

function cl_header_prive($flux){
    $flux .= recuperer_fond('inc/inc_header');
	return $flux;	

}


?>
