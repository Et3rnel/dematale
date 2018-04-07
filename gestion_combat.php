<?php
session_start();
require_once 'global_function.php';
require_once 'class/FightReport.php';

$idDefenser = (int) $_GET['id'];

if (!isset($_SESSION['id'])) {
    redirectTo('index');
}
if ($_SESSION['id'] === $idDefenser) {
    redirectTo('classement', array('page' => 1, 'error' => 'own'));
    // TODO : write a message in red if the player try to attack himself
}

$nbPointsAppart = 100;
$lostPercent = 0.25;

// TODO : why not putting this in a conf .ini file or inside DB
require_once'cnx.php';

$req1 = $bdd->prepare('SELECT points,coupe FROM membres WHERE id = ?');
$req1->execute(array($_SESSION['id']));
$position = $req1->fetch();

$req2 = $bdd->prepare('SELECT pseudo,points,level,coupe FROM membres WHERE id=?');
$req2->execute(array($idDefenser));
$position_o = $req2->fetch();

if ((($position_o['points']-$position['points'])>$nbPointsAppart) || (($position_o['points']-$position['points'])<-$nbPointsAppart)) {
    // TODO : write message in classement : Vous ne pouvez pas attaquer un joueur qui a plus de <em>100 points</em> d'écart avec vous !
    redirectTo('classement', array('page' => 1, 'error' => 'points'));
}

include_once'actu.php';
include_once'actu_o.php';

$reqmbr = $bdd->prepare('SELECT level FROM membres WHERE id=?');
$reqmbr->execute(array($_SESSION['id']));
$membres = $reqmbr->fetch();

$reqUnits = $bdd->prepare('SELECT pa.id_unit, pa.unit_amount, ui.attack, ui.defense, ui.unit_name, ui.price, ui.looting_capacity FROM player_army pa
    INNER JOIN units_informations ui ON pa.id_unit = ui.id_unit
    WHERE id_player = ?');
$reqUnits->execute(array($_SESSION['id']));
$attackerUnits = $reqUnits->fetchAll();

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
$req5->execute(array($idDefenser));
$ressources_o = $req5->fetch();

$reqUnits = $bdd->prepare('SELECT pa.id_unit, pa.unit_amount, ui.attack, ui.defense, ui.unit_name, ui.price, ui.looting_capacity FROM player_army pa
    INNER JOIN units_informations ui ON pa.id_unit = ui.id_unit
    WHERE id_player = ?');
$reqUnits->execute(array($idDefenser));
$defenserUnits = $reqUnits->fetchAll();

$req7 = $bdd->prepare('SELECT mur,grenier FROM production WHERE id=?');
$req7->execute(array($idDefenser));
$mur = $req7->fetch();
//----------Fin-------------------

$temps_actu = time();
$difference = $temps_actu - $timer['temps_attaque'];
include_once'fonction.php'; // TODO : supprimé à terme
// TODO remettre comme avant
// if ($difference >= $temps_vitesse['vitesse']) {
if (true) {

    $totalAttack = $totalDefense = $totalUnitsAttacker = $totalUnitsDefender = 0;

    foreach ($attackerUnits as $key => $unit) {
        $totalAttack += $unit['attack'] * $unit['unit_amount'];
        $totalUnitsAttacker += $unit['unit_amount'];
    }

    foreach ($defenserUnits as $key => $unit) {
        $totalDefense += $unit['defense'] * $unit['unit_amount'];
        $totalUnitsDefender += $unit['unit_amount'];
    }

	if ($niveau['forge'] > 0) {
        $forge = ($niveau['forge']*3/100)+1;
    } else {
        $forge=1;
    }

    $totalAttack *= $forge;
    $totalDefense += $mur['mur'];

	if($totalAttack >= $totalDefense) {

        $opponentGoldLost = $attackerGoldLost = $totalLootingCapacity = 0;

        // lost units calcul
        foreach ($defenserUnits as $key => $unit) {
            $unitPercent = $unit['unit_amount'] / $totalUnitsDefender;
            $nbLostUnits = $unit['unit_amount'] * $lostPercent * $unitPercent;

            $defenserUnits[$key]['lost_units'] = floor($nbLostUnits);
            $opponentGoldLost += $defenserUnits[$key]['lost_units'] * $unit['price'];
        }

		$opponentGoldLost /= 1.5;
        $opponentPeasants = floor($opponentGoldLost / 1000);

        // lost units calcul
        foreach ($attackerUnits as $key => $unit) {
            $attackerUnits[$key]['lost_units'] = (($unit['unit_amount'] / $totalUnitsAttacker) * $opponentGoldLost) / $unit['price'];
            $totalLootingCapacity += $unit['looting_capacity'] * ($unit['unit_amount'] - $defenserUnits[$key]['lost_units']);

            $attackerGoldLost += $attackerUnits[$key]['lost_units'] * $unit['price'];
        }

        $attackerPeasants = floor($attackerGoldLost / 400);

		// Ressources looting
		$fer_pille = ($totalLootingCapacity - $ressources_o['fer']);
		if($fer_pille<0){$fer_pille = $totalLootingCapacity;}
		else{$fer_pille = $ressources_o['fer'];}
		$bois_pille = $totalLootingCapacity - $ressources_o['bois'];
		if($bois_pille<0){$bois_pille = $totalLootingCapacity;}
		else{$bois_pille = $ressources_o['bois'];}
		$roche_pille = $totalLootingCapacity - $ressources_o['roche'];
		if($roche_pille<0){$roche_pille = $totalLootingCapacity;}
		else{$roche_pille = $ressources_o['roche'];}
		$gold_pille = $totalLootingCapacity - $ressources_o['gold'];
		if($gold_pille<0){$gold_pille = $totalLootingCapacity;}
		else{$gold_pille = $ressources_o['gold'];}

		$fer_pille = negativeZero($fer_pille - $mur['grenier']);
		$bois_pille = negativeZero($bois_pille - $mur['grenier']);
		$roche_pille = negativeZero($roche_pille - $mur['grenier']);
		$gold_pille = negativeZero($gold_pille - $mur['grenier']);

        $stolenRessources = array(
            array('name' => 'fer', 'quantity' => $fer_pille),
            array('name' => 'bois', 'quantity' => $bois_pille),
            array('name' => 'roche', 'quantity' => $roche_pille),
            array('name' => 'gold', 'quantity' => $gold_pille)
        );

		$paysan = $opponentPeasants + $attackerPeasants;
		$points_o = negativeZero($position_o['points']-2);
		//-------------Fin du pillage------------------------

		//--------------Génération des rapports de combat--------
        $fightReport = new FightReport($attackerUnits, $defenserUnits, $fightWon = true, $position_o['pseudo'], $stolenRessources, $paysan);
        $message2 = $fightReport->generateAttackerReport();
        $message1 = $fightReport->generateDefenderReport();

		$titre1 = 'Attaque de '.$_SESSION['pseudo'].'';
		$titre2 = 'Vous avez attaqué '.$position_o['pseudo'].'';

		if($position_o['coupe']>0)
		{
			$stolen_coupe = $position_o['coupe'];
			$given_coupe = $position['coupe'];

			$req01 = $bdd->prepare('UPDATE membres SET coupe=? WHERE id=?');
			$req01->execute(array($stolen_coupe,$_SESSION['id']));

			$req02 = $bdd->prepare('UPDATE membres SET coupe=? WHERE id=?');
			$req02->execute(array($given_coupe,$idDefenser));

			$message1 = $message1.'<p class="paysan">'.$_SESSION['pseudo'].' en a profité pour voler votre coupe !</p>';
			$message2 = $message2.'<p class="paysan">Pendant l\'attaque, vous avez réussi à subtiliser une coupe à votre adversaire !</p>';
		}
		//-------------------------------------------------------




		$temps = time();
		//------------Requetes gagnant----------------
		$req8 = $bdd->prepare('UPDATE ressources SET fer=fer+?, roche=roche+?, bois=bois+?, gold=gold+?, paysan=paysan+?, temps_attaque=? WHERE id=?');
		$req8->execute(array($fer_pille,$roche_pille,$bois_pille,$gold_pille,$paysan,$temps,$_SESSION['id']));

        // Adding lost units for the attacker
        foreach ($attackerUnits as $unit) {
            $removeArmyReq = $bdd->prepare('UPDATE player_army SET unit_amount = unit_amount - ? WHERE id_player =?  AND id_unit = ?');
            $removeArmyReq->execute(array($unit['lost_units'], $_SESSION['id'], $unit['id_unit']));
        }

		$req10 = $bdd->prepare('UPDATE membres SET points=points+4 WHERE id=?');
		$req10->execute(array($_SESSION['id']));
		//----------------------Fin---------------------

		//----------------------Requetes perdant--------
		$req11 = $bdd->prepare('UPDATE ressources SET fer=fer-?, roche=roche-?, bois=bois-?, gold=gold-? WHERE id=?');
		$req11->execute(array($fer_pille,$roche_pille,$bois_pille,$gold_pille, $idDefenser));

        // Adding lost units for the attacker
        foreach ($defenserUnits as $unit) {
            $removeArmyReq = $bdd->prepare('UPDATE player_army SET unit_amount = unit_amount - ? WHERE id_player =?  AND id_unit = ?');
            $removeArmyReq->execute(array($unit['lost_units'], $idDefenser, $unit['id_unit']));
        }

		$req51 = $bdd->prepare('UPDATE membres SET points=? WHERE id=?');
		$req51->execute(array($points_o,$idDefenser));

		$req1_mp = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp,id_player) VALUES (?,?,?,?,?,?)');
		$req1_mp->execute(array($position_o['pseudo'],'Chef de guerre',$titre1,$message1,$temps,$_SESSION['id']));

		$req2_mp = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp,statut,id_player) VALUES (?,?,?,?,?,?,?)');
		$req2_mp->execute(array($_SESSION['pseudo'],'Chef de guerre',$titre2,$message2,$temps,1,$idDefenser));

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
        $attackerGoldLost = $opponentGoldLost = 0;

		// Lost units calcul
        foreach ($attackerUnits as $key => $unit) {
            $unitPercent = $unit['unit_amount'] / $totalUnitsAttacker;
            $nbLostUnits = $unit['unit_amount'] * $lostPercent * $unitPercent;
            $attackerUnits[$key]['lost_units'] = floor($nbLostUnits);

            $attackerGoldLost += $attackerUnits[$key]['lost_units'] * $unit['price'];
        }

        $attackerGoldLost /= 1.5;


        // todo : on est ici
        foreach ($defenserUnits as $key => $unit) {
            $defenserUnits[$key]['lost_units'] = (($unit['unit_amount'] / $totalUnitsDefender) * $attackerGoldLost) / $unit['price'];
            if ($unit['unit_amount'] < $defenserUnits[$key]['lost_units']) {
                $defenserUnits[$key]['lost_units'] = $unit['unit_amount'];
            }

            $opponentGoldLost += $defenserUnits[$key]['lost_units'] * $unit['price'];
        }
		//----Fin du combat----------------------------------------------------------


        $opponentPeasants = floor($opponentGoldLost / 1500);
        $attackerPeasants = floor($attackerGoldLost / 550);
		$paysan = $opponentPeasants + $attackerPeasants;


		//--------------Génération des rapports de combat--------
        $stolenRessources = array();

        $fightReport = new FightReport($attackerUnits, $defenserUnits, $fightWon = false, $position_o['pseudo'], $stolenRessources, $paysan);
        $message2 = $fightReport->generateAttackerReport();
        $message1 = $fightReport->generateDefenderReport();

		$titre1 = 'Attaque de '.$_SESSION['pseudo'].'';
		$titre2 = 'Vous avez attaqué '.$position_o['pseudo'].'';
		//-------------------------------------------------------

        foreach ($defenserUnits as $unit) {
            $removeArmyReq = $bdd->prepare('UPDATE player_army SET unit_amount = unit_amount - ? WHERE id_player = ?  AND id_unit = ?');
            $removeArmyReq->execute(array($unit['lost_units'], $idDefenser, $unit['id_unit']));
        }

		$req51 = $bdd->prepare('UPDATE membres SET points=points+1 WHERE id=?');
		$req51->execute(array($idDefenser));

		$req52 = $bdd->prepare('UPDATE ressources SET paysan=paysan+? WHERE id=?');
		$req52->execute(array($paysan,$idDefenser));
		//----------------------Fin update défenseur------


		//---------------Update attaquant--------------------
		$req6 = $bdd->prepare('UPDATE ressources SET temps_attaque=? WHERE id=?');
		$req6->execute(array($temps_actu,$_SESSION['id']));


        foreach ($attackerUnits as $unit) {
            $removeArmyReq = $bdd->prepare('UPDATE player_army SET unit_amount = unit_amount - ? WHERE id_player =?  AND id_unit = ?');
            $removeArmyReq->execute(array($unit['lost_units'], $_SESSION['id'], $unit['id_unit']));
        }
		//---------------------------End update attaquant--------

		$temps = time();

		$req1_mp = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp,id_player) VALUES (?,?,?,?,?,?)');
		$req1_mp->execute(array($position_o['pseudo'],'Chef de guerre',$titre1,$message1,$temps,$_SESSION['id']));

		$req1_mp = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp,statut,id_player) VALUES (?,?,?,?,?,?,?)');
		$req1_mp->execute(array($_SESSION['pseudo'],'Chef de guerre',$titre2,$message2,$temps,1,$idDefenser));

		//--Debut update level adverse--------------
		$get_points = $bdd->prepare('SELECT points,level FROM membres WHERE id=?');
		$get_points->execute(array($idDefenser));
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
			$update_level->execute(array($new_level,$idDefenser));
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
