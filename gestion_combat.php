<?php session_start();
if(!isset($_SESSION['id'])) header('Location:index.php');
if($_SESSION['id'] == $_GET['id'])
{
	?>
	<!DOCTYPE html>
	<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>Attaquer</title>
    </head>
		<body id="white">
			<h1 class="h1-w">Attaquer un joueur</h1>
			<div class="form">
			<p class="decal">Vous ne pouvez pas attaquer votre propre armée !</br></br><a href="classement.php">Retourner à la liste des joueurs</a></p>
			</div>
			<p class="retour_index"><a href="index.php">Retour à l'index</a></p>
		</body>
	</html>
	<?php
	die();
} 
require_once'cnx.php';
$req1 = $bdd->prepare('SELECT points,coupe FROM membres WHERE id = ?');		
$req1->execute(array($_SESSION['id']));																			
$position = $req1->fetch(); 	

$req2 = $bdd->prepare('SELECT pseudo,points,level,coupe FROM membres WHERE id=?');						
$req2->execute(array($_GET['id']));															
$position_o = $req2->fetch(); 	

if ((($position_o['points']-$position['points'])>100) || (($position_o['points']-$position['points'])<-100))
{
	?>
	<!DOCTYPE html>
	<html>
	<head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>Attaquer</title>
	</head>
		<body id="white">
			<h1 class="h1-w">Attaquer un joueur</h1>
			<div class="form">
			<p class="decal">Vous ne pouvez pas attaquer un joueur qui a plus de <em>100 points</em> d'écart avec vous !</br></br><a href="classement.php">Retourner à la liste des joueurs</a></p>
			</div>
			<p class="retour_index"><a href="index.php">Retour à l'index</a></p>
		</body>
	</html>
	<?php
	die();
} 
include_once'actu.php';
include_once'actu_o.php';

$reqress = $bdd->prepare('SELECT guerrisseur FROM ressources WHERE id=?');						
$reqress->execute(array($_SESSION['id']));															
$ressources = $reqress->fetch();
		
$reqmbr = $bdd->prepare('SELECT level FROM membres WHERE id=?');						
$reqmbr->execute(array($_SESSION['id']));															
$membres = $reqmbr->fetch(); 

//--Requetes pour l'attaquant------
$req31=$bdd->prepare('SELECT nombre,attaque,pillage,prix FROM armee WHERE type=? AND joueur=?');
$req31->execute(array('recrue',$_SESSION['pseudo']));
$recrue = $req31->fetch();
	
$req32=$bdd->prepare('SELECT nombre,attaque,pillage,prix FROM armee WHERE type=? AND joueur=?');
$req32->execute(array('capitaine',$_SESSION['pseudo']));
$capitaine = $req32->fetch();
	
$req33=$bdd->prepare('SELECT nombre,attaque,pillage,prix FROM armee WHERE type=? AND joueur=?');
$req33->execute(array('lieutenant',$_SESSION['pseudo']));
$lieutenant = $req33->fetch();
	
$req34=$bdd->prepare('SELECT nombre,attaque,pillage,prix FROM armee WHERE type=? AND joueur=?');
$req34->execute(array('commandant',$_SESSION['pseudo']));
$commandant = $req34->fetch();

$req4 = $bdd->prepare('SELECT vitesse FROM production WHERE id=?');						
$req4->execute(array($_SESSION['id']));															
$temps_vitesse = $req4->fetch(); 	

$req41 = $bdd->prepare('SELECT temps_attaque FROM ressources WHERE id=?');						
$req41->execute(array($_SESSION['id']));															
$timer = $req41->fetch();

$req42 = $bdd->prepare('SELECT forge FROM niveau WHERE id=?');						
$req42->execute(array($_SESSION['id']));															
$niveau = $req42->fetch(); 		 		
//----------Fin-------------------

//--Requetes pour l'attaqué-------
$req5 = $bdd->prepare('SELECT fer,roche,bois,gold,titan FROM ressources WHERE id=?');						
$req5->execute(array($_GET['id']));															
$ressources_o = $req5->fetch(); 	

$req61=$bdd->prepare('SELECT nombre,defense,prix FROM armee WHERE type=? AND joueur=?');
$req61->execute(array('recrue',$position_o['pseudo']));
$recrue_o = $req61->fetch();
	
$req62=$bdd->prepare('SELECT nombre,defense,prix FROM armee WHERE type=? AND joueur=?');
$req62->execute(array('capitaine',$position_o['pseudo']));
$capitaine_o = $req62->fetch();
	
$req63=$bdd->prepare('SELECT nombre,defense,prix FROM armee WHERE type=? AND joueur=?');
$req63->execute(array('lieutenant',$position_o['pseudo']));
$lieutenant_o = $req63->fetch();
	
$req64=$bdd->prepare('SELECT nombre,defense,prix FROM armee WHERE type=? AND joueur=?');
$req64->execute(array('commandant',$position_o['pseudo']));
$commandant_o = $req64->fetch();	

$req7 = $bdd->prepare('SELECT mur,grenier FROM production WHERE id=?');						
$req7->execute(array($_GET['id']));															
$mur = $req7->fetch(); 								
//----------Fin-------------------

$temps_actu = time();
$difference = $temps_actu - $timer['temps_attaque'];		
include_once'fonction.php';

if($difference >= $temps_vitesse['vitesse'])
{
	$att1 = $recrue['nombre']*$recrue['attaque'];				$def1 = $recrue_o['nombre']*$recrue_o['defense'];
	$att2 = $lieutenant['nombre']*$lieutenant['attaque'];		$def2 = $lieutenant_o['nombre']*$lieutenant_o['defense'];
	$att3 = $capitaine['nombre']*$capitaine['attaque'];			$def3 = $capitaine_o['nombre']*$capitaine_o['defense'];
	$att4 = $commandant['nombre']*$commandant['attaque'];		$def4 = $commandant_o['nombre']*$commandant_o['defense'];
	
	if($niveau['forge']>0){$forge = ($niveau['forge']*3/100)+1;}else{$forge=1;}
	$att_total = ($att1 + $att2 + $att3 + $att4)*$forge; 
	$def_total = $def1 + $def2 + $def3 + $def4 + $mur['mur']; 	
	
	if($att_total >= $def_total)
	{
		
		//----Le combat se déroule---------------------------------------------
		$troupes_total_o = $recrue_o['nombre'] + $capitaine_o['nombre'] + $lieutenant_o['nombre'] + $commandant_o['nombre'];
		$per1_o = $recrue_o['nombre']/$troupes_total_o;
		$per2_o = $capitaine_o['nombre']/$troupes_total_o;
		$per3_o = $lieutenant_o['nombre']/$troupes_total_o;
		$per4_o = $commandant_o['nombre']/$troupes_total_o;
		
		$recrue_p_o = floor((0.25*$recrue_o['nombre'])*$per1_o);										
		$capitaine_p_o = floor((0.25*$capitaine_o['nombre'])*$per2_o);
		$lieutenant_p_o = floor((0.25*$lieutenant_o['nombre'])*$per3_o);
		$commandant_p_o = floor((0.25*$commandant_o['nombre'])*$per4_o);
		
			
		
		$gold_lost_calcul = ($recrue_p_o*$recrue_o['prix'] + $capitaine_p_o*$capitaine_o['prix'] + $lieutenant_p_o*$lieutenant_o['prix'] + $commandant_p_o*$commandant_o['prix'])/1.5;
		
		$troupes_total = $recrue['nombre'] + $capitaine['nombre'] + $lieutenant['nombre'] + $commandant['nombre']; 
		$per1 = $recrue['nombre']/$troupes_total * $gold_lost_calcul;
		$per2 = $capitaine['nombre']/$troupes_total * $gold_lost_calcul;
		$per3 = $lieutenant['nombre']/$troupes_total * $gold_lost_calcul;
		$per4 = $commandant['nombre']/$troupes_total * $gold_lost_calcul;

		$recrue_p = $per1/$recrue['prix'];										
		$capitaine_p = $per2/$capitaine['prix'];	
		$lieutenant_p = $per3/$lieutenant['prix'];		
		$commandant_p = $per4/$commandant['prix'];		
		
		//----Fin du combat----------------------------------------------------------
	

	
		//----------Pillage des ressources-------------
		$capa_pillage = $recrue['pillage']*($recrue['nombre']-$recrue_p) + $lieutenant['pillage']*($lieutenant['nombre']-$lieutenant_p) + $capitaine['pillage']*($capitaine['nombre']-$capitaine_p) + $commandant['pillage']*($commandant['nombre']-$commandant_p);

		$fer_pille = ($capa_pillage - $ressources_o['fer']);
		if($fer_pille<0){$fer_pille = $capa_pillage;}
		else{$fer_pille = $ressources_o['fer'];}
		$bois_pille = $capa_pillage - $ressources_o['bois'];
		if($bois_pille<0){$bois_pille = $capa_pillage;}
		else{$bois_pille = $ressources_o['bois'];}
		$roche_pille = $capa_pillage - $ressources_o['roche'];
		if($roche_pille<0){$roche_pille = $capa_pillage;}
		else{$roche_pille = $ressources_o['roche'];}
		$gold_pille = $capa_pillage - $ressources_o['gold'];
		if($gold_pille<0){$gold_pille = $capa_pillage;}
		else{$gold_pille = $ressources_o['gold'];}
	
		$fer_pille = NegativZero($fer_pille - $mur['grenier']);
		$bois_pille = NegativZero($bois_pille - $mur['grenier']);
		$roche_pille = NegativZero($roche_pille - $mur['grenier']);
		$gold_pille = NegativZero($gold_pille - $mur['grenier']);

		$var1 = floor(($recrue_p_o*$recrue['prix'] + $capitaine_p_o*$capitaine['prix'] + $lieutenant_p_o*$lieutenant['prix'] + $commandant_p_o*$commandant['prix'])/1000);
		$var2 = floor(($recrue_p*$recrue['prix'] + $capitaine_p*$capitaine['prix'] + $lieutenant_p*$lieutenant['prix'] + $commandant_p*$commandant['prix'])/400);
		$paysan = $var1 + $var2;
		$points_o = NegativZero($position_o['points']-2);
		//-------------Fin du pillage------------------------
		
	


		if($membres['level']>=3){
			$rec_guer = floor($recrue_p*(0.005*$ressources['guerrisseur']));
			$lieu_guer = floor($lieutenant_p*(0.005*$ressources['guerrisseur']));
			$cap_guer = floor($capitaine_p*(0.005*$ressources['guerrisseur']));
			$com_guer = floor($commandant_p*(0.005*$ressources['guerrisseur']));}
		if($position_o['level']>=3){
			$rec_guer_o = floor($recrue_p_o*(0.005*$ressources['guerrisseur']));
			$lieu_guer_o = floor($lieutenant_p_o*(0.005*$ressources['guerrisseur']));
			$cap_guer_o = floor($capitaine_p_o*(0.005*$ressources['guerrisseur']));
			$com_guer_o = floor($commandant_p_o*(0.005*$ressources['guerrisseur']));}
		$recrue_p_o = $recrue_p_o + $rec_guer_o;
		$lieutenant_p_o = $lieutenant_p_o + $lieu_guer_o;
		$capitaine_p_o = $capitaine_p_o + $cap_guer_o;
		$commandant_p_o = $commandant_p_o + $com_guer_o;
		
		$recrue_p = $recrue_p + $rec_guer;
		$lieutenant_p = $lieutenant_p + $lieu_guer;
		$capitaine_p = $capitaine_p + $cap_guer;
		$commandant_p = $commandant_p + $com_guer;
			
		//--------------Génération des rapports de combat--------
		$message1 = '<p class="rapport_combat_2">Vous vous êtes fait attaqué par '.$_SESSION['pseudo'].' et vous perdez le combat !</p>
		
		<p class="ses_pertes">Votre armée au moment du combat :<br/>
		'.number_format($recrue_o['nombre'], 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s)  
		'.number_format($lieutenant_o['nombre'], 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s) 
		'.number_format($capitaine_o['nombre'], 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s) 
		'.number_format($commandant_o['nombre'], 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s)</p>
		<p class="ses_pertes">Votre adversaire a perdu :<br/> 
		'.number_format($recrue_p, 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s)  
		'.number_format($lieutenant_p, 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s)
		'.number_format($capitaine_p, 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s) 		
		'.number_format($commandant_p, 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s)</p>
		<p class="bugmodif_pertes">Vous perdez :<br/>
		'.number_format($recrue_p_o, 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s) 
		'.number_format($lieutenant_p_o, 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s)
		'.number_format($capitaine_p_o, 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s)
		'.number_format($commandant_p_o, 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s).</p>
		<p class="ses_pertes">Il vous a volé :<br/>
		'.number_format($fer_pille, 0, '.', ' ').' <img src="images/fer_icon.png" alt="icon du fer" align="top" height="15" width="15"/> fer
		'.number_format($roche_pille, 0, '.', ' ').' <img src="images/roche_icon.png" alt="icon de la roche" align="top" height="15" width="15"/> roche
		'.number_format($bois_pille, 0, '.', ' ').' <img src="images/bois_icon.png" alt="Icon du bois" align="top" height="15" width="15"/> bois
		'.number_format($gold_pille, 0, '.', ' ').' <img src="images/gold_icon.png" alt="Icon de l\'or" align="top" height="15" width="15"/> or.</p>';
		if($position_o['level']>=3){
		$message1 = $message1.'<p class="paysan">Vous entendez une prière depuis le temple ... <strong>'.number_format($rec_guer_o, 0, '.', ' ').'</strong> recrue(s), 
		<strong>'.number_format($lieu_guer_o, 0, '.', ' ').'</strong> lieutenant(s), <strong>'.number_format($cap_guer_o, 0, '.', ' ').'</strong> capitaine(s) et <strong>'.number_format($com_guer_o, 0, '.', ' ').'</strong> 
		commandant(s) ont pu être ramenés à la vie après la bataille !</p>';}
		//---------Message 2
		$message2 = '<p class="rapport_combat_1">Vous remportez le combat !</p>
		<p class="ses_pertes">Votre armée au moment du combat :<br/>
		'.number_format($recrue['nombre'], 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s)  
		'.number_format($lieutenant['nombre'], 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s)
		'.number_format($capitaine['nombre'], 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s) 		
		'.number_format($commandant['nombre'], 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s)</p>
		<p class="ses_pertes">Votre adversaire a perdu :<br/> 
		'.number_format($recrue_p_o, 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s)   
		'.number_format($lieutenant_p_o, 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s)
		'.number_format($capitaine_p_o, 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s)		
		'.number_format($commandant_p_o, 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s)</p>
		<p class="mes_pertes">Vous perdez :<br/>
		'.number_format($recrue_p, 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s) 
		'.number_format($lieutenant_p, 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s)
		'.number_format($capitaine_p, 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s)
		'.number_format($commandant_p, 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s)</p>
		
		<p class="ses_pertes">Vous pillez :
		'.number_format($fer_pille, 0, '.', ' ').' <img src="images/fer_icon.png" alt="icon du fer" align="top" height="15" width="15"/> fer
		'.number_format($roche_pille, 0, '.', ' ').' <img src="images/roche_icon.png" alt="icon de la roche" align="top" height="15" width="15"/> roche
		'.number_format($bois_pille, 0, '.', ' ').' <img src="images/bois_icon.png" alt="Icon du bois" align="top" height="15" width="15"/> bois
		'.number_format($gold_pille, 0, '.', ' ').' <img src="images/gold_icon.png" alt="Icon de l\'or" align="top" height="15" width="15"/> or.</p>';

		if($paysan > 0){$message2 = $message2.'<p class="paysan">En vous regardant combattre <strong>'.number_format($paysan, 0, '.', ' ').'</strong> <img src="/Dematale/images/paysan.png" title="Paysan(s)" alt="Paysan(s)" align="top" height="16"/> décident de rejoindre vos rangs.</p>';}
		else{$message2 = $message2.'<p class="paysan">En regardant le combat, aucun paysan ne semble décidé à vous rejoindre.</p>';}
			
			
		if($membres['level']>=3){
		$message2 = $message2.'<p class="paysan">Vous entendez une prière depuis le temple ... <strong>'.number_format($rec_guer, 0, '.', ' ').'</strong> recrue(s), 
		<strong>'.number_format($lieu_guer, 0, '.', ' ').'</strong> lieutenant(s), <strong>'.number_format($cap_guer, 0, '.', ' ').'</strong> capitaine(s) et <strong>'.number_format($com_guer, 0, '.', ' ').'</strong> 
		commandant(s) ont pu être ramenés à la vie après la bataille !</p>';}
		$titre1 = 'Attaque de '.$_SESSION['pseudo'].'';
		$titre2 = 'Vous avez attaqué '.$position_o['pseudo'].'';
		
		if($position_o['coupe']>0)
		{	
			$stolen_coupe = $position_o['coupe'];
			$given_coupe = $position['coupe'];
			
			$req01 = $bdd->prepare('UPDATE membres SET coupe=? WHERE id=?');
			$req01->execute(array($stolen_coupe,$_SESSION['id']));
			
			$req02 = $bdd->prepare('UPDATE membres SET coupe=? WHERE id=?');
			$req02->execute(array($given_coupe,$_GET['id']));
			
			$message1 = $message1.'<p class="paysan">'.$_SESSION['pseudo'].' en a profité pour voler votre coupe !</p>';
			$message2 = $message2.'<p class="paysan">Pendant l\'attaque, vous avez réussi à subtiliser une coupe à votre adversaire !</p>';
		}
		//-------------------------------------------------------			

		
		
		
		$temps = time();
		//------------Requetes gagnant----------------
		$req8 = $bdd->prepare('UPDATE ressources SET fer=fer+?, roche=roche+?, bois=bois+?, gold=gold+?, paysan=paysan+?, temps_attaque=? WHERE id=?');						
		$req8->execute(array($fer_pille,$roche_pille,$bois_pille,$gold_pille,$paysan,$temps,$_SESSION['id']));		
		
		$req91 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req91->execute(array($recrue_p,$_SESSION['pseudo'],'recrue'));

		$req92 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req92->execute(array($capitaine_p,$_SESSION['pseudo'],'capitaine'));

		$req93 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req93->execute(array($lieutenant_p,$_SESSION['pseudo'],'lieutenant'));

		$req94 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req94->execute(array($commandant_p,$_SESSION['pseudo'],'commandant'));
		
		$req10 = $bdd->prepare('UPDATE membres SET points=points+4 WHERE id=?');						
		$req10->execute(array($_SESSION['id']));	
		//----------------------Fin---------------------
		
		//----------------------Requetes perdant--------
		$req11 = $bdd->prepare('UPDATE ressources SET fer=fer-?, roche=roche-?, bois=bois-?, gold=gold-? WHERE id=?');						
		$req11->execute(array($fer_pille,$roche_pille,$bois_pille,$gold_pille,$_GET['id']));	
		
		$req121 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req121->execute(array($recrue_p_o,$position_o['pseudo'],'recrue'));

		$req122 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req122->execute(array($capitaine_p_o,$position_o['pseudo'],'capitaine'));

		$req123 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req123->execute(array($lieutenant_p_o,$position_o['pseudo'],'lieutenant'));

		$req124 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req124->execute(array($commandant_p_o,$position_o['pseudo'],'commandant'));
		
		$req51 = $bdd->prepare('UPDATE membres SET points=? WHERE id=?');						
		$req51->execute(array($points_o,$_GET['id']));
		
		$req1_mp = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp,id_player) VALUES (?,?,?,?,?,?)');
		$req1_mp->execute(array($position_o['pseudo'],'Chef de guerre',$titre1,$message1,$temps,$_SESSION['id']));

		$req2_mp = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp,statut,id_player) VALUES (?,?,?,?,?,?,?)');
		$req2_mp->execute(array($_SESSION['pseudo'],'Chef de guerre',$titre2,$message2,$temps,1,$_GET['id']));
		
		//--Debut update level spécial car 2 rapports--------------
		$get_points = $bdd->prepare('SELECT points,level FROM membres WHERE id=?');
		$get_points->execute(array($_SESSION['id']));
		$points = $get_points->fetch();
		$new_level = floor($points['points']/100);
		if($new_level > $points['level'])
		{
			$titre = 'Niveau '.$new_level.' débloqué !';
			$message = '<p class="lvlup"><img src="images/lvlup.png"  align="top" alt="Niveau suivant !" title="Niveau suivant !" />
			</br></br>Félicitations ! Vous passez au niveau '.$new_level.' !</br></p>';
			$temps = time();
		
			$add_msg = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp) VALUES (?,?,?,?,?)');
			$add_msg->execute(array($_SESSION['pseudo'],'Chef de guerre',$titre,$message,$temps));
		
			$update_level = $bdd->prepare('UPDATE membres SET level=? WHERE id=?');
			$update_level->execute(array($new_level,$_SESSION['id']));
			
			$req10 = $bdd->query('SELECT MAX(id) FROM mess_priv');
			$id = $req10->fetch();
			$id_rapport_combat = $id[0]-1;
			
			header('Location:mp.php?id='.$id_rapport_combat.'');
			die();
		}
		//--Fin update level-------------------------------------------
		$req11 = $bdd->query('SELECT MAX(id) FROM mess_priv');
		$id = $req11->fetch();
				
		header('Location:mp.php?id='.$id[0].'');
	}
	else
	{		
		
		//----Le combat se déroule---------------------------------------------
		$troupes_total = $recrue['nombre'] + $capitaine['nombre'] + $lieutenant['nombre'] + $commandant['nombre'];
		$per1 = $recrue['nombre']/$troupes_total;
		$per2 = $capitaine['nombre']/$troupes_total;
		$per3 = $lieutenant['nombre']/$troupes_total;
		$per4 = $commandant['nombre']/$troupes_total;
		
		$recrue_p = floor((0.25*$recrue['nombre'])*$per1);										
		$capitaine_p = floor((0.25*$capitaine['nombre'])*$per2);
		$lieutenant_p = floor((0.25*$lieutenant['nombre'])*$per3);
		$commandant_p = floor((0.25*$commandant['nombre'])*$per4);
		
		$gold_lost_calcul = ($recrue_p*$recrue['prix'] + $capitaine_p*$capitaine['prix'] + $lieutenant_p*$lieutenant['prix'] + $commandant_p*$commandant['prix'])/1.5;
		
		$troupes_total_o = $recrue_o['nombre'] + $capitaine_o['nombre'] + $lieutenant_o['nombre'] + $commandant_o['nombre']; //1000
		$per1 = $recrue_o['nombre']/$troupes_total_o * $gold_lost_calcul;
		$per2 = $capitaine_o['nombre']/$troupes_total_o * $gold_lost_calcul;
		$per3 = $lieutenant_o['nombre']/$troupes_total_o * $gold_lost_calcul;
		$per4 = $commandanto['nombre']/$troupes_total_o * $gold_lost_calcul;
		

		
		$recrue_p_o = $per1/$recrue_o['prix'];										
		$capitaine_p_o = $per2/$capitaine_o['prix'];	
		$lieutenant_p_o = $per3/$lieutenant_o['prix'];		
		$commandant_p_o = $per4/$commandant_o['prix'];		
		
		if($recrue_o['nombre']<$recrue_p_o) $recrue_p_o = $recrue_o['nombre'];								
		if($capitaine_o['nombre']<$capitaine_p_o) $capitaine_p_o = $capitaine_o['nombre'];
		if($lieutenant_o['nombre']<$lieutenant_p_o) $lieutenant_p_o = $lieutenant_o['nombre'];
		if($commandant_o['nombre']<$commandant_p_o) $commandant_p_o = $commandant_o['nombre'];
		//----Fin du combat----------------------------------------------------------

		$var1 = floor(($recrue_p_o*$recrue['prix'] + $capitaine_p_o*$capitaine['prix'] + $lieutenant_p_o*$lieutenant['prix'] + $commandant_p_o*$commandant['prix'])/1500);
		$var2 = floor(($recrue_p*$recrue['prix'] + $capitaine_p*$capitaine['prix'] + $lieutenant_p*$lieutenant['prix'] + $commandant_p*$commandant['prix'])/550);
		$paysan = $var1 + $var2;
		
		if($membres['level']>=3){
			$rec_guer = floor($recrue_p*(0.005*$ressources['guerrisseur']));
			$lieu_guer = floor($lieutenant_p*(0.005*$ressources['guerrisseur']));
			$cap_guer = floor($capitaine_p*(0.005*$ressources['guerrisseur']));
			$com_guer = floor($commandant_p*(0.005*$ressources['guerrisseur']));}
		if($position_o['level']>=3){
			$rec_guer_o = floor($recrue_p_o*(0.005*$ressources['guerrisseur']));
			$lieu_guer_o = floor($lieutenant_p_o*(0.005*$ressources['guerrisseur']));
			$cap_guer_o = floor($capitaine_p_o*(0.005*$ressources['guerrisseur']));
			$com_guer_o = floor($commandant_p_o*(0.005*$ressources['guerrisseur']));}
		$recrue_p_o = $recrue_p_o + $rec_guer_o;
		$lieutenant_p_o = $lieutenant_p_o + $lieu_guer_o;
		$capitaine_p_o = $capitaine_p_o + $cap_guer_o;
		$commandant_p_o = $commandant_p_o + $com_guer_o;
		
		$recrue_p = $recrue_p + $rec_guer;
		$lieutenant_p = $lieutenant_p + $lieu_guer;
		$capitaine_p = $capitaine_p + $cap_guer;
		$commandant_p = $commandant_p + $com_guer;
		
		
		//--------------Génération des rapports de combat--------
		$message1 = '<p class="rapport_combat_1">Vous vous êtes fait attaqué par <a class="link_rc_1" href="profil.php?pseudo='.$_SESSION['pseudo'].'">'.$_SESSION['pseudo'].'</a> !</p>
		<p class="son_armee">Votre armée au moment du combat :<br/>
		'.number_format($recrue_o['nombre'], 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s)  
		'.number_format($lieutenant_o['nombre'], 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s) 
		'.number_format($capitaine_o['nombre'], 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s) 	
		'.number_format($commandant_o['nombre'], 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s)</p>
		<p class="ses_pertes">Votre adversaire perd :<br/> 
		'.number_format($recrue_p, 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s)  
		'.number_format($lieutenant_p, 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s) 
		'.number_format($capitaine_p, 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s) 
		'.number_format($commandant_p, 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s)</p>
		<p class="mes_pertes">Vous perdez :<br/>
		'.number_format($recrue_p_o, 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s) 
		'.number_format($lieutenant_p_o, 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s)
		'.number_format($capitaine_p_o, 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s)
		'.number_format($commandant_p_o, 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s).</p>
		<p class="paysan">Votre adversaire n\'a pas réussi à vous voler des ressources ! Vous le regardez partir tout en rigolant.</p>';
		if($paysan>0){ $message1=$message1.'<p class="paysan">En voyant le désastre chez votre adversaire, '.$paysan.' paysans sont venu se joindre à vous !</p>';}
		if($position_o['level']>=3){
		$message1 = $message1.'<p class="paysan">Vous entendez une prière depuis le temple ... <strong>'.number_format($rec_guer_o, 0, '.', ' ').'</strong> recrue(s), 
		<strong>'.number_format($lieu_guer_o, 0, '.', ' ').'</strong> lieutenant(s), <strong>'.number_format($cap_guer_o, 0, '.', ' ').'</strong> capitaine(s) et <strong>'.number_format($com_guer_o, 0, '.', ' ').'</strong> 
		commandant(s) ont pu être ramenés à la vie après la bataille !</p>';}

		//-------------Debut message 2-----------
		$message2 = '<p class="rapport_combat_2">Votre attaque sur <a class="red" href="profil.php?pseudo='.$position_o['pseudo'].'">'.$position_o['pseudo'].'</a> a échoué !</p>
		<p class="ses_pertes">Votre armée au moment du combat :<br/>
		'.number_format($recrue['nombre'], 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s)  
		'.number_format($lieutenant['nombre'], 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s) 
		'.number_format($capitaine['nombre'], 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s) 
		'.number_format($commandant['nombre'], 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s)</p>
		<p class="ses_pertes">Votre adversaire perd :<br/> 
		'.number_format($recrue_p_o, 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s)  
		'.number_format($lieutenant_p_o, 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s)
		'.number_format($capitaine_p_o, 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s) 		
		'.number_format($commandant_p_o, 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s)</p>
		<p class="mes_pertes">Vous avez perdu :<br/>
		'.number_format($recrue_p, 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrues" align="top" height="15" width="15"/> recrue(s) 
		'.number_format($lieutenant_p, 0, '.', ' ').' <img src="images/arm_2.png" alt="Lieutenants" align="top" height="15" width="15"/> lieutenant(s)
		'.number_format($capitaine_p, 0, '.', ' ').' <img src="images/arm_4.png" alt="Capitaines" align="top" height="15" width="15"/> capitaine(s)
		'.number_format($commandant_p, 0, '.', ' ').' <img src="images/arm_1.png" alt="Commandants" align="top" height="15" width="15"/> commandant(s).</p>
		<p class="paysan">Vous ne volez aucune ressources ! En partant, vous apercevez quelques paysans vous regarder tout en rigolant.</p>';
		if($membres['level']>=3){
		$message2 = $message2.'<p class="paysan">Vous entendez une prière depuis le temple ... <strong>'.number_format($rec_guer, 0, '.', ' ').'</strong> recrue(s), 
		<strong>'.number_format($lieu_guer, 0, '.', ' ').'</strong> lieutenant(s), <strong>'.number_format($cap_guer, 0, '.', ' ').'</strong> capitaine(s) et <strong>'.number_format($com_guer, 0, '.', ' ').'</strong> 
		commandant(s) ont pu être ramenés à la vie après la bataille !</p>';}
		$titre1 = 'Attaque de '.$_SESSION['pseudo'].'';
		$titre2 = 'Vous avez attaqué '.$position_o['pseudo'].'';		
		//-------------------------------------------------------





	
		//--------Update défenseur-----------------------
		$req121 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req121->execute(array($recrue_p_o,$position_o['pseudo'],'recrue'));

		$req122 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req122->execute(array($capitaine_p_o,$position_o['pseudo'],'capitaine'));

		$req123 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req123->execute(array($lieutenant_p_o,$position_o['pseudo'],'lieutenant'));

		$req124 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req124->execute(array($commandant_p_o,$position_o['pseudo'],'commandant'));	
		
		$req51 = $bdd->prepare('UPDATE membres SET points=points+1 WHERE id=?');						
		$req51->execute(array($_GET['id']));
		
		$req52 = $bdd->prepare('UPDATE ressources SET paysan=paysan+? WHERE id=?');						
		$req52->execute(array($paysan,$_GET['id']));
		//----------------------Fin update défenseur------

		
		//---------------Update attaquant--------------------			
		$req6 = $bdd->prepare('UPDATE ressources SET temps_attaque=? WHERE id=?');						
		$req6->execute(array($temps_actu,$_SESSION['id']));
		
		$req121 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req121->execute(array($recrue_p,$_SESSION['pseudo'],'recrue'));

		$req122 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req122->execute(array($capitaine_p,$_SESSION['pseudo'],'capitaine'));

		$req123 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req123->execute(array($lieutenant_p,$_SESSION['pseudo'],'lieutenant'));

		$req124 = $bdd->prepare('UPDATE armee SET nombre=nombre-? WHERE joueur=? AND type=?');
		$req124->execute(array($commandant_p,$_SESSION['pseudo'],'commandant'));	
		//---------------------------End update attaquant--------

		$temps = time();
		
		$req1_mp = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp,id_player) VALUES (?,?,?,?,?,?)');
		$req1_mp->execute(array($position_o['pseudo'],'Chef de guerre',$titre1,$message1,$temps,$_SESSION['id']));
		
		$req1_mp = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp,statut,id_player) VALUES (?,?,?,?,?,?,?)');
		$req1_mp->execute(array($_SESSION['pseudo'],'Chef de guerre',$titre2,$message2,$temps,1,$_GET['id']));
		
		//--Debut update level adverse--------------
		$get_points = $bdd->prepare('SELECT points,level FROM membres WHERE id=?');
		$get_points->execute(array($_GET['id']));
		$points = $get_points->fetch();
		$new_level = floor($points['points']/100);
		if($new_level > $points['level'])
		{
			
			$titre = 'Niveau '.$new_level.' débloqué !';
			$message = '<p class="lvlup"><img src="images/lvlup.png"  align="top" alt="Niveau suivant !" title="Niveau suivant !" />
			</br></br>Félicitations ! Vous passez au niveau '.$new_level.' !</br></p>';
			$temps = time();
		
			$add_msg = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp) VALUES (?,?,?,?,?)');
			$add_msg->execute(array($position_o['pseudo'],'Chef de guerre',$titre,$message,$temps));
		
			$update_level = $bdd->prepare('UPDATE membres SET level=? WHERE id=?');
			$update_level->execute(array($new_level,$_GET['id']));
		}
		//--Fin update level adverse--------------
		
		$req10 = $bdd->query('SELECT MAX(id) FROM mess_priv');
		$id = $req10->fetch();
				
		header('Location:mp.php?id='.$id[0].'');
	}
	$req1->closeCursor();
	$req2->closeCursor();
}
else
{
	$attente = floor((($temps_vitesse['vitesse']/60)+0.8)-($difference/60)).' minutes';
	if($attente<1) $attente = 'quelques secondes';
	?>
		<!DOCTYPE html>
		<html>
		<head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>Attaquer</title>
		</head>
			<body id="white">
				<h1 class="h1-w">Attaquer un joueur</h1>
				<div class="form">
				<p class="decal">Vous avez attaqué quelqu'un <strong>il y a moins de <?php echo floor($temps_vitesse['vitesse']/60);?> minutes</strong></br></br>
				Veuillez attendre encore <?php echo $attente;?></p>
				</div>
				<p class="retour_index"><a href="classement.php">Retour au classement</a></p>
			</body>
		</html>
	<?php
	die();	
} ?>
