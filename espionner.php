<?php session_start();
include_once'actu.php';
include_once'fonction.php';
include_once 'global_function.php';
$id = negativeZero($_GET['id']);


$req1 = $bdd->prepare('SELECT pseudo,points,level FROM membres WHERE id = ?');
$req1->execute(array($id));
$joueur = $req1->fetch();

if(!isset($_SESSION['pseudo']) || (!isset($_GET['id'])) || empty($joueur['pseudo'])) header('Location:index.php');


$req2 = $bdd->prepare('SELECT gold FROM ressources WHERE id=?');
$req2->execute(array($_SESSION['id']));
$argent = $req2->fetch();

$req3 = $bdd->prepare('SELECT decret,points FROM membres WHERE id=?');
$req3->execute(array($_SESSION['id']));
$membres = $req3->fetch();

$difpts = $membres['points'] - $joueur['points'];
if($difpts>200 || $difpts<-200)
{
	header('Location:profil.php?pseudo='.$joueur['pseudo'].'&msg=dif');
	die();
}

if($membres['decret']!=3)
{
	if($joueur['points']>1)
	{
		$or_espio = 5*$joueur['points'];
	}
	else
	{
		$or_espio = 5;
	}
}
else
{
	if($joueur['points']<=3)
	{
		$or_espio = 0;
	}
	else
	{
		$or_espio = floor((5*$joueur['points'])/15);
	}
}
$req1->closeCursor();

if($or_espio > $argent['gold'])
{
	header('Location:profil.php?pseudo='.$joueur['pseudo'].'&msg=money'); //FAIRE LE MSG Vous n'avez pas assez d'or pour espionner ce joueur.
}
else
{
	require_once'actu_o.php';

	$req10 = $bdd->prepare('SELECT espionnage FROM niveau WHERE id=?');
	$req10->execute(array($_SESSION['id']));
	$joueur2 = $req10->fetch();

	//---Infos du joueur espionné-----
	$req2 = $bdd->prepare('SELECT fer,roche,bois,gold,titan FROM ressources WHERE id=?');
	$req2->execute(array($id));
	$ressources = $req2->fetch();

	$req4 = $bdd->prepare('SELECT fer,roche,bois,gold,titan,mur,espionnage,hotel_ventes FROM niveau WHERE id=?');
	$req4->execute(array($id));
	$niveau = $req4->fetch();

    $reqUnits = $bdd->prepare('SELECT pa.id_unit, pa.unit_amount, ui.attack, ui.defense, ui.unit_name, ui.price, ui.looting_capacity FROM player_army pa
        INNER JOIN units_informations ui ON pa.id_unit = ui.id_unit
        WHERE id_player = ?');
    $reqUnits->execute(array($id));
    $opponentUnits = $reqUnits->fetchAll();


    $totalUnits = 0;
    foreach ($opponentUnits as $key => $unit) {
        $totalUnits += $unit['unit_amount'];
    }

	$or_now = $argent['gold'] - $or_espio;
	$req6 = $bdd->prepare('UPDATE ressources SET gold=? WHERE id=?');
	$req6->execute(array($or_now,$_SESSION['id']));
	$req6->closeCursor();




	$spy_dif = $joueur2['espionnage']-$niveau['espionnage'];

	//---------Génération des messages d'espionnage---------------------


	if($spy_dif<=0)
	{
		$message = '<p class="rap_espio1">Voici les informations qui ont pu être récupérés lors de l\'espionnage de <a href="profil.php?pseudo='.$joueur['pseudo'].'">'.$joueur['pseudo'].'</a> :</p><p class="spydifzero">Vous n\'avez pu récuperer aucune information !<br/>Montez le niveau de votre technologie espionnage pour arriver a percer les mystères de votre adversaire.</p>';
	}
	if($spy_dif>0)
	{
		$message = '<p class="rap_espio1">Voici les informations qui ont pu être récupérés lors de l\'espionnage de <a href="profil.php?pseudo='.$joueur['pseudo'].'">'.$joueur['pseudo'].'</a> :</p><table class="espiopio"><tr><td class="espio1">Nombre d\'unité(s)</td><td class="espio2">'.number_format($totalUnits, 0, '.', ' ').'</td></tr>';
		if($spy_dif<=1) $message = $message.'</table>';
	}
	if($spy_dif>1)
	{
		$message = $message.'<tr><td class="espio1">Ressources</td><td class="espio2"><strong class="rap_espio3">'.number_format($ressources['fer'], 0, '.', ' ').'</strong> <img src="images/fer_icon.png" alt="Fer" title="Fer" align="top" />
				<strong class="rap_espio3">'.number_format($ressources['bois'], 0, '.', ' ').'</strong> <img src="images/bois_icon.png" alt="Bois" title="Bois" align="top"/>
				<strong class="rap_espio3">'.number_format($ressources['roche'], 0, '.', ' ').'</strong> <img src="images/roche_icon.png" alt="Roche" title="Roche" align="top"/>
				<strong class="rap_espio3">'.number_format($ressources['gold'], 0, '.', ' ').'</strong> <img src="images/gold_icon.png" alt="Or" title="Or" align="top" /><br/>';
		if($joueur['level']>=5){$message = $message.'<strong class="rap_espio3">'.number_format($ressources['titan'], 0, '.', ' ').'</strong> <img src="images/titan_icon.png" alt="Titan" title="Titan" align="top"/></td></tr>';}else{$message=$message.'</td></tr>';}
		if($spy_dif<=2) $message = $message.'</table>';
	}
	if($spy_dif>2)
	{
		$message = $message.'<tr><td class="espio1">Nombre de recrue(s)</td><td class="espio2">'.number_format($recrue['nombre'], 0, '.', ' ').' <img src="images/arm_3.png" alt="Recrue" width="17" align="top" /></td></tr>';
		if($spy_dif<=3) $message = $message.'</table>';
	}
	if($spy_dif>3)
	{
		$message = $message.'<tr><td class="espio1"> Niveau des bâtiments</td><td class="espio2">Fer : <strong class="rap_espio3">'.$niveau['fer'].'</strong>
				Bois : <strong class="rap_espio3">'.$niveau['bois'].'</strong>
				Roche : <strong class="rap_espio3">'.$niveau['roche'].'</strong>
				Or : <strong class="rap_espio3">'.$niveau['gold'].'</strong><br/>';
		if($joueur['level']>=5){$message=$message.'Titan : <strong class="rap_espio3">'.$niveau['titan'].'</strong></td></tr>';}else{$message=$message.'</td></tr>';}
		if($spy_dif<=4) $message = $message.'</table>';
	}
	if($spy_dif>4)
	{
		$message = $message.'<tr><td class="espio1"> Niveau du mur</td><td class="espio2">'.number_format($niveau['mur'], 0, '.', ' ').'</td></tr>';
		if($spy_dif<=5) $message = $message.'</table>';
	}
	if($spy_dif>8)
	{
		$message = $message.'<tr><td class="espio1"> Niveau de l\'hôtel des ventes</td><td class="espio2">'.number_format($niveau['hotel_ventes'], 0, '.', ' ').'</td></tr>';
		if($spy_dif<=9) $message = $message.'</table>';
	}
	if($spy_dif>10)
	{
		$message = $message.'<tr><td class="espio1"> Nombre de commandants</td><td class="espio2">'.number_format($commandant['nombre'], 0, '.', ' ').' <img src="images/arm_1.png" alt="Recrue" width="17" align="top" /></td></tr></table>';
	}

	$req1->closeCursor();
	$temps=time();
	$expe = 'Chef de guerre';
	$titre = 'Rapport d\'espionnage de '.$joueur['pseudo'].'';
	$req7=$bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp,statut,id_player) VALUES (?,?,?,?,?,?,?)');
	$req7->execute(array($_SESSION['pseudo'],$expe,$titre,$message,$temps,1,$id));
	$req2->closeCursor();

	$req11 = $bdd->query('SELECT MAX(id) FROM mess_priv');
	$id = $req11->fetch();

	header('Location:mp.php?id='.$id[0].'');
} ?>
