<?php
function genie_encheres_dist($time){

$date_actuel= time();

// Cherche d'abord les objet en vente 
	$sql = spip_query( "SELECT * FROM spip_encheres_objets WHERE statut='mise_en_vente_active' OR statut='mise_en_vente'" );
		while($data = spip_fetch_array($sql)) {
 		$id_objet = $data['id_objet'];
 		$statut = $data['statut'];
		$id_encherisseur=$data['id_auteur'];
 		$date_fin = $data['date_fin'];
 		$date_fin_jours = date('d-m-Y',strtotime($date_fin));
 		$date_fin_heures = date('G:i:s',strtotime($date_fin));
 
   	$split_jours = explode("-", $date_fin_jours);
   	$split_heures = explode(":", $date_fin_heures);
 
   	$date_fin = mktime($split_heures[0], $split_heures[1], $split_heures[2], $split_jours[1], $split_jours[0], $split_jours[2]) ;
 		$date_difference = ($date_fin-$date_actuel)/3600;

		// Cloture de la vente 
		if ($date_difference <= 0 ){

			$sql2 = spip_query( "SELECT * FROM spip_encheres_mises WHERE id_objet='$id_objet' ORDER BY montant_mise DESC LIMIT 1");
				while($data = spip_fetch_array($sql2)) {
					$id_encherisseur=$data['id_encherisseur'];
					};

			// Détermine l'action suivant si l'enchère est resté sans mises (statut: mise_en_vente) ou avec mises (statut : mise_en_vente_active)	
			echo $statut;
			$actualiser = charger_fonction('actions_enchere_gagne','inc');
			$actualiser('cloture_cron_'.$statut,$id_objet,$id_encherisseur);
			}

		// Selectionne celle qui se termine dans les 24 heures
		elseif ($date_difference <= 24){

			// Cherche l'encherisseur gagnant actuel afin d'éviter de  lui envoyer un mail d'avis
			$sql2 = spip_query( "SELECT * FROM spip_encheres_mises WHERE id_objet='$id_objet' ORDER BY montant_mise DESC LIMIT 1");

				while($data = spip_fetch_array($sql2)) {
					$id_encherisseur_top=$data['id_encherisseur'];
					}

			// Pour ceux qui restent selectionne ceux qui suivent l'objet et qui n'on pas déjà re&ccedil;u un mail d'avertissement
					$sql3 = spip_query( "SELECT * FROM spip_encheres_encherisseurs WHERE id_objet='$id_objet' AND suivre='1' AND id_encherisseur != '$id_encherisseur_top' AND envoi_avis !='24'" );
						while($data = spip_fetch_array($sql3)) {

						$id_auteur = $data['id_encherisseur'];

						// Envoi des mails et mention de l'envoi dans la base de donnée et dans le log

						$sql4 = spip_query( "SELECT * FROM spip_auteurs WHERE id_auteur='$id_auteur'" );
							while($data = spip_fetch_array($sql4)) {
							$email = $data['email'];

								spip_log("CRON : action envoi mail 24 heures avant fin d'enchère: $id_objet email: $email",'encheres_cron');
								$envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
								$envoyer_message_visiteur($email,'avis_24_heures',$id_objet);

							spip_query("UPDATE spip_encheres_encherisseurs SET envoi_avis='24' WHERE id_encherisseur='$id_auteur' AND id_objet='$id_objet'");
							spip_log("CRON : update bdd: $id_objet email: $email",'encheres_cron');
							}
						}

				};

		}
// Puis les objets vendu

	 $vendu = spip_query( "SELECT * FROM spip_encheres_objets WHERE statut='vendu' AND statut_payement_objet='' ORDER BY date_vente" );
		 while($v = spip_fetch_array($vendu)) {
 		 $id_objet = $v['id_objet'];
		 $id_auteur=$v['id_auteur'];
		 $id_article=$v['id_article'];
		 $id_acheteur=$v['id_acheteur']; 
 		 $date_vente = $v['date_vente'];
 		 $envoi_rappels = $v['envoi_rappels'];

 		 $date_vente_jours = date('d-m-Y',strtotime($date_vente));
 		 $date_vente_heures = date('G:i:s',strtotime($date_vente));
   	 	$split_vente_jours = explode("-", $date_vente_jours);
   	 	$split_vente_heures = explode(":", $date_vente_heures);
   		 $date_vente = mktime($split_vente_heures[0], $split_vente_heures[1], $split_vente_heures[2], $split_vente_jours[1], $split_vente_jours[0], $split_vente_jours[2]) ;
 		 $date_difference_vente = ($date_actuel-$date_vente)/86400;

		 // 28 jours sans paiement enregistré 
		 if ($date_difference_vente >= 28 AND $envoi_rappels==21){

			 // on remet le statut on stand_by
			 spip_query("UPDATE spip_encheres_objets SET statut='stand_by' WHERE id_objet='$id_objet'");
			 spip_log("CRON : update bdd rappel 28: id_objet: $id_objet ",'encheres_cron');

 			// on envoie les mails 
 
 			$acheteur = spip_query( "SELECT * FROM spip_auteurs WHERE id_auteur='$id_acheteur'" );
 				while($data = spip_fetch_array($acheteur)) {
 				$email_acheteur = $data['email'];
 
 				$envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
 				$envoyer_message_visiteur($email_acheteur,'rappel_acheteur',$id_objet);
 
 				$envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
 				$envoyer_message_visiteur($email_acheteur,'rappel_vendeur',$id_objet);
						
 				$envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
 				$envoyer_message_visiteur($email_acheteur,'rappel_asso',$id_objet);

 				//Mail d'info au webmaster
 				$envoyer_inscription_webmaster = charger_fonction('envoyer_message_webmaster','inc');
				$envoyer_inscription_webmaster('rappel_acheteur',$id_objet);
 
 				spip_log("CRON : envoi rappel 21: id_objet: $id_objet ",'encheres_cron');
				
			 }
		}
		 // 21 jours sans paiement enregistré

		 if ($date_difference_vente >= 21 AND $envoi_rappels==15){

			 // on note le rappel dans la bdd 
			 spip_query("UPDATE spip_encheres_objets SET envoi_rappels='21' WHERE id_objet='$id_objet'");
			 spip_log("CRON : update bdd rappel 21: id_objet: $id_objet ",'encheres_cron');

			 // on envoie les mails 

			 $acheteur = spip_query( "SELECT * FROM spip_auteurs WHERE id_auteur='$id_acheteur'" );
				 while($data = spip_fetch_array($acheteur)) {
				 $email_acheteur = $data['email'];

				 $envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
				 $envoyer_message_visiteur($email_acheteur,'rappel_acheteur',$id_objet);

				 $envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
				 $envoyer_message_visiteur($email_acheteur,'rappel_vendeur',$id_objet);
						
				 $envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
				 $envoyer_message_visiteur($email_acheteur,'rappel_asso',$id_objet);

				 //Mail d'info au webmaster
				 $envoyer_inscription_webmaster = charger_fonction('envoyer_message_webmaster','inc');
				 $envoyer_inscription_webmaster('rappel_acheteur',$id_objet);

				 spip_log("CRON : envoi rappel 21: id_objet: $id_objet ",'encheres_cron');
				 }
			 }

		 // 15 jours sans paiement enregistré
		 elseif ($date_difference_vente >= 15 AND $envoi_rappels==7){

			 // on note le rappel dans la bdd 
			 spip_query("UPDATE spip_encheres_objets SET envoi_rappels='15' WHERE id_objet='$id_objet'");
			 spip_log("CRON : update bdd rappel 15: id_objet: $id_objet ",'encheres_cron');

			 // on envoie les mails 

			 $acheteur = spip_query( "SELECT * FROM spip_auteurs WHERE id_auteur='$id_acheteur'" );
				 while($data = spip_fetch_array($acheteur)) {
				 $email_acheteur = $data['email'];

				 $envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
				 $envoyer_message_visiteur($email_acheteur,'rappel_acheteur',$id_objet);

				 $envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
				 $envoyer_message_visiteur($email_acheteur,'rappel_vendeur',$id_objet);
						
				 $envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
				 $envoyer_message_visiteur($email_acheteur,'rappel_asso',$id_objet);
					
				 //Mail d'info au webmaster
				 $envoyer_inscription_webmaster = charger_fonction('envoyer_message_webmaster','inc');
				 $envoyer_inscription_webmaster('rappel_acheteur',$id_objet);


				 spip_log("CRON : envoi rappel 15: id_objet: $id_objet ",'encheres_cron');
				 }

			 }

		 // 7 jours sans paiement enregistré
		 elseif ($date_difference_vente >= 7 AND $envoi_rappels==0){

			 // on note le rappel dans la bdd 
			 spip_query("UPDATE spip_encheres_objets SET envoi_rappels='7' WHERE id_objet='$id_objet'");
			 spip_log("CRON : update bdd rappel 7: id_objet: $id_objet ",'encheres_cron');

			 // on envoie les mails 

			 $acheteur = spip_query( "SELECT * FROM spip_auteurs WHERE id_auteur='$id_acheteur'" );
				 while($data = spip_fetch_array($acheteur)) {
				 $email_acheteur = $data['email'];

				 $envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
				 $envoyer_message_visiteur($email_acheteur,'rappel_acheteur',$id_objet);

				 $envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
				 $envoyer_message_visiteur($email_acheteur,'rappel_vendeur',$id_objet);
						
				 $envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
				 $envoyer_message_visiteur($email_acheteur,'rappel_asso',$id_objet);

				 spip_log("CRON : envoi rappel 7: id_objet: $id_objet ",'encheres_cron');
			 }
		 };
	 
	}
	
	$livre = spip_query( "SELECT * FROM spip_encheres_objets WHERE statut='vente_termine' AND statut_livraison!='envoie'  ORDER BY date_vente" );
		while($v = spip_fetch_array($livre)) {
		$id_objet = $v['id_objet'];
		$id_acheteur = $v['id_acheteur'];		
		$date_paiement_livraison = $v['date_paiement_livraison'];
   		$split_jours = explode("-", $date_paiement_livraison);
   		$split_heures = explode(":", $date_paiement_livraison);
 
   		$date_paiement_livraison_explode = mktime($split_heures[0], $split_heures[1], $split_heures[2], $split_jours[1], $split_jours[0], $split_jours[2]) ;
 		$date_difference_paiement_livraison = ($date_paiement_livraison_explode-$date_actuel)/3600;	
 			 
 		if ($date_difference_paiement_livraison >= 5){
 		
 			//Envoi d'un rappel au vendeur
 			
 			$envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
			$envoyer_message_visiteur('','rappel_vendeur_livraison',$id_objet);

			
			//Mail d'info au webmaster
			
			$envoyer_inscription_webmaster = charger_fonction('envoyer_message_webmaster','inc');
			$envoyer_inscription_webmaster('rappel_vendeur',$id_objet);
			
			spip_query("UPDATE spip_encheres_objets SET envoi_rappels='rappel_5_jours_livraison' WHERE id_objet='$id_objet'");
			
			spip_log("CRON : rappel vendeur 5 jours depuis paiement livraison: id_objet: $id_objet ",'encheres_cron');
 		
 			} 
 		}

	$recu = spip_query( "SELECT * FROM spip_encheres_objets WHERE statut='vente_termine' AND statut_livraison='envoie'" );
		while($v = spip_fetch_array($recu)) {
		$id_objet = $v['id_objet'];
		$id_acheteur = $v['id_acheteur'];	
		$date_livraison = $v['date_livraison'];
   		$split_jours = explode("-", $date_livraison);
   		$split_heures = explode(":", $date_livraison);
   		$date_livraison_explode = mktime($split_heures[0], $split_heures[1], $split_heures[2], $split_jours[1], $split_jours[0], $split_jours[2]) ;
 		$date_difference_livraison = ($date_livraison_explode-$date_actuel)/3600;
 		
 		$encherisseur = spip_query( "SELECT * FROM spip_encheres_encherisseurs WHERE id_encherisseurs='$id_acheteur' AND id_objet='$id_objet' AND envoi_avis='rappel_5_jours'" );
 		while($v = spip_fetch_array($encherisseur)) {echo '1';
 		$avis=$v['envoi_avis'];
 			}
 					 
		if ($date_difference_livraison >= 5 AND !$avis){
		 	$acheteur = spip_query( "SELECT * FROM spip_auteurs WHERE id_auteur='$id_acheteur'" );
 			
 			while($v = spip_fetch_array($acheteur)) {
 			$email_acheteur = $v['email'];
 			$envoyer_message_visiteur = charger_fonction('envoyer_message_visiteur','inc');
			$envoyer_message_visiteur($email_acheteur,'rappel_acheteur_recu',$id_objet);
			}
			
			spip_query("UPDATE spip_encheres_encherisseurs SET envoi_avis='rappel_5_jours' WHERE id_encherisseur='$id_acheteur' AND id_objet='$id_objet'");
			
			spip_log("CRON : rappel achteur 5 jours depuis livraison: id_objet: $id_objet ",'encheres_cron');
		 
		 }
	}
}
?>
