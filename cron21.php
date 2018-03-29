<?php include_once'cnx.php'; 

$req001 = $bdd->query('UPDATE membres SET coupe=0 WHERE coupe!=0');

$nbr_joueurs = -1;
$req01 = $bdd->query('SELECT AVG(points) AS moyenne_points FROM membres WHERE points>0');
$donnees = $req01->fetch();
$donnees['moyenne_points'];

$req02 = $bdd->prepare('SELECT id FROM membres WHERE points<?');
$req02->execute(array($donnees['moyenne_points']));
while($joueur = $req02->fetch())
{
	$nbr_joueurs = $nbr_joueurs+1;
	$id_joueur[] = $joueur['id'];
}
$coupe_bronze = $id_joueur[rand(0, $nbr_joueurs)];
$coupe_argent = $id_joueur[rand(0, $nbr_joueurs)];
while($coupe_argent == $coupe_bronze)
{
	$coupe_argent = $id_joueur[rand(0, $nbr_joueurs)];
}
$coupe_or = $id_joueur[rand(0, $nbr_joueurs)];
while($coupe_or == $coupe_bronze || $coupe_or == $coupe_argent)
{
	$coupe_or = $id_joueur[rand(0, $nbr_joueurs)];
}
$random = rand(0,3);
if($random == 3)
{
	$coupe_diamant = $id_joueur[rand(0, $nbr_joueurs)];
	while($coupe_diamant == $coupe_bronze || $coupe_diamant == $coupe_argent || $coupe_diamant == $coupe_or)
	{
		$coupe_diamant = $id_joueur[rand(0, $nbr_joueurs)];
	}
	$req06 = $bdd->prepare('SELECT pseudo FROM membres WHERE id=?');
	$req06->execute(array($coupe_diamant));
	$diamant = $req06->fetch();
}
$req03 = $bdd->prepare('SELECT pseudo FROM membres WHERE id=?');
$req03->execute(array($coupe_bronze));
$bronze = $req03->fetch();

$req04 = $bdd->prepare('SELECT pseudo FROM membres WHERE id=?');
$req04->execute(array($coupe_argent));
$argent = $req04->fetch();

$req05 = $bdd->prepare('SELECT pseudo FROM membres WHERE id=?');
$req05->execute(array($coupe_or));
$or = $req05->fetch();






$temps = time();
$titre = 'Récapitulatif du jour';
$req1 = $bdd->query('SELECT id,nbr_membre,modele FROM alliance');
while($info = $req1->fetch())
{
	$id_alliance[$info['id']] = $info['nbr_membre'];
	$modele_alliance[$info['id']] = $info['modele'];
}
$req2 = $bdd->query('SELECT id,popularite FROM niveau_alliance');
while($info2 = $req2->fetch())
{
	$niveau_popu[$info2['id']] = $info2['popularite'];
}

$req2 = $bdd->query('SELECT id,pseudo,id_alliance,apply,points,level FROM membres');
while($membre = $req2->fetch())
{
	if($membre['id_alliance'] != 0 && $membre['apply']==1)
	{
		if($modele_alliance[$membre['id_alliance']] == 1)
		{
			$nbr_paysans = 2*$id_alliance[$membre['id_alliance']];
			if($niveau_popu[$membre['id_alliance']]>1) $nbr_paysans = $nbr_paysans*$niveau_popu[$membre['id_alliance']];
		}
		else
		{
			$nbr_paysans = $id_alliance[$membre['id_alliance']];
			if($niveau_popu[$membre['id_alliance']]>1) $nbr_paysans = $nbr_paysans*$niveau_popu[$membre['id_alliance']];
		}
	
		$req3 = $bdd->prepare('UPDATE ressources SET paysan=paysan+? WHERE id=?');
		$req3->execute(array($nbr_paysans,$membre['id']));
		
		$message = '<p class="cron">Récapitulatif du jour</p><br/><p class="cron_msg">- Votre alliance vous a apporté <strong>'.$nbr_paysans.'</strong> paysan(s) aujourd\'hui.<br/>';
	}
	else
	{
		$message = '<p class="cron">Récapitulatif du jour</p><p class="cron_msg">- Rien de spécial ... peut-être devriez-vous pensez à creer ou rejoindre une alliance?</br>';
	}
	
	if($membre['level']>0)
	{
		$req_hv = $bdd->prepare('SELECT hotel_ventes FROM production WHERE id=?');
		$req_hv->execute(array($membre['id'])); 
		$hotel_prod = $req_hv->fetch();		
		
		$message = $message.'<br/>- Votre hôtel des ventes vous rapporte '.number_format($hotel_prod['hotel_ventes'], 0, '.', ' ').' <img src="images/gold_icon.png" alt="Icon de l\'or" height="20" width="15" align="top"/><br/>';	
	
		$upd = $bdd->prepare('UPDATE ressources SET gold=gold+? WHERE id=?');
		$upd->execute(array($hotel_prod['hotel_ventes'],$membre['id']));
	}
	
	
		
	$message = $message.'<br/> - <strong>'.$bronze['pseudo'].'</strong> <img src="images/coupes/1.png" alt="Coupe" height="20" width="15" align="top"/> a eu la coupe Bronze, <strong>'.$argent['pseudo'].'</strong> <img src="images/coupes/2.png" alt="Coupe" height="20" width="15" align="top"/> a eu la coupe Argent, <strong>'.$or['pseudo'].'</strong> <img src="images/coupes/3.png" alt="Coupe" height="20" width="15" align="top"/> a eu la coupe Or !<br/></p>';	
	if($random == 3)
	{
		$message = $message.'<p class="cron3">  <strong>'.$diamant['pseudo'].'</strong> <img src="images/coupes/4.png" alt="Coupe" height="20" width="15" align="top"/> a eu la coupe Diamant ! Félicitations !</p>';	
	}
	else
	{
		$message = $message.'<p class="cron3">  Personne n\'a eu la coupe diamant aujourd\'hui !</p>';	
	}
	$req4 = $bdd->prepare('INSERT INTO mess_priv(recepteur,expediteur,titre,message,date_mp) VALUES (?,?,?,?,?)');
	$req4->execute(array($membre['pseudo'],'Maître des informations',$titre,$message,$temps));
}


$req51 = $bdd->prepare('UPDATE membres SET coupe=? WHERE id=?');
$req51->execute(array(1,$coupe_bronze));

$req52 = $bdd->prepare('UPDATE membres SET coupe=? WHERE id=?');
$req52->execute(array(2,$coupe_argent));

$req53 = $bdd->prepare('UPDATE membres SET coupe=? WHERE id=?');
$req53->execute(array(3,$coupe_or));

if($random == 3)
{
	$req54 = $bdd->prepare('UPDATE membres SET coupe=? WHERE id=?');
	$req54->execute(array(4,$coupe_diamant));
}

?>





















