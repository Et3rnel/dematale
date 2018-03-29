<?php session_start(); 
if(!isset($_SESSION['pseudo'])) {header('Location:index.php');}
include_once'actu.php';
include_once'connectes.php';

if(isset($_POST['subally']))
{
	$req20=$bdd->prepare('UPDATE membres SET id_alliance=0 WHERE id=?');
	$req20->execute(array($_SESSION['id']));
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Dematale, le jeu de strategie en ligne</title>
    </head>
    <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>

	
	<?php include_once'menu.php'; ?>
	<div id="corps">					
	<h1>Alliance</h1>
	<div class="corps2">


<?php
$req9 = $bdd->prepare('SELECT id_alliance,apply FROM membres WHERE pseudo=?');
$req9->execute(array($_SESSION['pseudo']));
$donnees = $req9->fetch();

//----------------------AUCUNE ALLIANCE ET PAS D'APPLY-----------------------------
if ($donnees['id_alliance']==0)
{
	
	if(isset($_GET['msg']) && $_GET['msg']=='succes')
	{
		echo '<p class="mp_vert">Vous avez quitté votre alliance avec succès !</p>';
	}
	?>
	<h3>Vous n'appartenez à aucune alliance</h3>
	<p>Pour <strong>creer une alliance</strong> vous devez :<br/>
	<ul><li>- Être level 2 minimum</ul></li></p>
	<p>Cela vous coutera :<br/>
	<ul><li>- 10 000 <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/></li>
	<li>- 10 000 <img src="images/bois_icon.png" alt="Icon bois" align="top" title="Bois"/></li>
	<li>- 10 000 <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/></li>
	<li>- 20 000 <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/></li></ul></p>
	<p>Pour <strong>rejoindre une alliance</strong> vous devez :<br/>
	<ul><li>- Être level 1 minimum</ul></li></p><br/>
	<p class="ally_opt"><a href="alliance/create.php">Creer une alliance</a> | <a href="alliance/join.php">Rejoindre une alliance</a></p>	
	<?php 
}
//----------------------LE MEMBRE EST DANS UNE ALLIANCE---------------------------
elseif($donnees['id_alliance']!=0 && $donnees['apply']==1)
{ 
	$req15 = $bdd->prepare('SELECT chef,nom,modele,nbr_membre,message FROM alliance WHERE id = ?');		
	$req15->execute(array($donnees['id_alliance']));																			
	$ally = $req15->fetch(); 
	
	
	$req16=$bdd->prepare('SELECT pseudo FROM membres WHERE id_alliance=? AND apply=1');
	$req16->execute(array($donnees['id_alliance']));
	
	?>
	<p class="menu_alliance"><a href="chat_alliance.php">Chat</a> | <a href="developpement.php">Développement</a> | <a href="coffre.php">Coffre-fort</a> | <a href="alliance/guerre.php">Guerre</a></p>
	<p class="nomalliance"><?php echo $ally['nom'];?></p>
	
	<?php
	if(isset($_GET['msg']) && $_GET['msg']=='lead')
	{
		echo '<p class="erreur_chat">Vous devez choisir un nouveau chef avant de quitter votre alliance.</p>';
	}
	?>
	
	<h2>Modèle de l'alliance : <em>
	<?php 
	if($ally['modele'] == 0)
	{
		echo 'Aucun</em></h2>';
	}
	elseif($ally['modele'] == 1)
	{
		echo 'économique</em></h2><p class="description_modele"><strong>La production de paysans de l\'alliance est doublée.</strong></p>';
	}
	else
	{
		echo 'militaire<br/></em></h2><p class="description_modele"><strong>Le coût des unités de l\'alliance est réduit.</strong></p>';
	}

	
	//-------------------SEUL LE CHEF PEUT CHANGER LE MODELE->A CHANGER DANS LA VERIF PHP----
	if($ally['chef'] == $_SESSION['pseudo'])
	{
		$req1 = $bdd->prepare('SELECT modele FROM timer_alliance WHERE id=?');		
		$req1->execute(array($donnees['id_alliance']));																			
		$timer_alliance = $req1->fetch(); 
	
		$temps = time();
		$dif_temps = $temps - $timer_alliance['modele'];
		if($dif_temps >= 518400)
		{ ?>
			<form method="post" action="traitement/alliance/modele.php">
				<p class="marg70">
					<input type="radio" name="choix" value="eco" id="eco" <?php if($ally['modele']==1) echo 'checked="checked"'; ?> /> <label for="eco"><strong>Economique :</strong> Production des paysans doublée.</label><br />
					<input type="radio" name="choix" value="mil" id="mil" <?php if($ally['modele']==2) echo 'checked="checked"'; ?>/> <label for="mil"><strong>Militaire :</strong> Coûts des unités de l'alliance réduit.</label><br />			
					<input  type="submit" name="modele" value="Changer le modèle de l'alliance" />	
				</p>
			</form>
		<?php } 
		if(isset($_GET['msg']) && $_GET['msg']=='same') echo '<p class="padd-r"><strong>Vous avez choisi le même modèle.<br/> Votre modèle reste actif tant que vous n\'en choisissez pas un autre</strong></p>'; 
	}?>
	
	<hr>
	
	<?php 
	$req10=$bdd->prepare('SELECT popularite FROM niveau_alliance WHERE id=?');
	$req10->execute(array($donnees['id_alliance']));
	$niveau = $req10->fetch();
	if($ally['modele']==1)
	{
		$nbr_paysan = $ally['nbr_membre']*2;
		if($niveau['popularite']>1) $nbr_paysan = $nbr_paysan*$niveau['popularite'];
	}
	else
	{
		$nbr_paysan = $ally['nbr_membre'];
		if($niveau['popularite']>1) $nbr_paysan = $nbr_paysan*$niveau['popularite'];
	}


	if($dif_temps < 518400){ 
	$jours=floor($dif_temps/86400);
	$reste=$dif_temps%86400;
	$heures=floor($reste/3600);
	$reste=$reste%3600;
	$minutes=floor($reste/60);
	//--
	$true_day = 5-$jours;
	$true_houre = 23-$heures;
	$true_minutes = 59-$minutes; ?>
	<p class="simple_purple">Il vous reste <strong><?php if($true_day>=1)echo $true_day.' jour(s),';?></strong> <strong><?php if($true_houre>=1)echo $true_houre.' heure(s)</strong> et';?> 
	<strong><?php if($true_houre>=1)echo $true_minutes.' minute(s)';?> </strong> avant de pouvoir changer le modèle de votre alliance.</p> <?php } ?>
	
	
	<p class="description_modele"><em>Votre alliance vous apporte <strong><?php echo $nbr_paysan; ?></strong> paysan(s) par jour, ajoutés lors du récapitulatif de 21h.</em></p>
	
	
	<p class="simple_purple">Liste des membres : </p>
	<table class="margin-a">
		<?php
		while($membres = $req16->fetch())
		{
			if($membres['pseudo'] != $ally['chef'])
			{ ?>
				<tr><td class="list_mbr"><a href="profil.php?pseudo=<?php echo $membres['pseudo']; ?>"><?php echo $membres['pseudo']; ?></a></td></tr>
			<?php }
			else
			{ ?>
				<tr><td class="list_mbr"><a href="profil.php?pseudo=<?php echo $membres['pseudo']; ?>"><?php echo $membres['pseudo']; ?></a> - <img src="images/couronne.png" alt="Chef" align="bottom"/></td></tr>
			<?php }
		}?><br/>
	</table><br/>
	
	
	<?php if($ally['chef'] != $_SESSION['pseudo'])
	{
		?>  <form class="center_align" method="post" action="traitement/alliance/leave.php">
				<input type="submit" name="leave" value="Quitter l'alliance"/>
			</form>
		<?php
	} 
	
	if($ally['chef'] == $_SESSION['pseudo'])
	{
		?>  <form class="center_align" method="post" action="traitement/alliance/dissoudre.php">
				<input type="submit" name="dissoudre" onclick="return(confirm('Etes-vous sûr de vouloir dissoudre votre alliance ?'));"  value="Dissoudre l'alliance"/>
			</form>
<?php } ?>

			
			
			


		
			
			<?php 
			if(($ally['chef'] == $_SESSION['pseudo']) && ($ally['nbr_membre']>1))
			{ ?>
			
			
			
			<table class="margin-a">
				<tr>
					<td class="pritd">Changer le leader de l'alliance</td>
					<td class="pritd">Expulser un membre</td>
				</tr>
				<tr>
			
			<td class="textprivate"><form method="post" action="alliance/lead.php">
				<select name="leader" id="leader">
					<?php
					
					$req3 = $bdd->prepare('SELECT pseudo FROM membres WHERE id_alliance=?');
					$req3->execute(array($donnees['id_alliance']));
					while($membre = $req3->fetch())
					{	
						if($membre['pseudo'] != $ally['chef'])
						{
							?>
							<option value="<?php echo $membre['pseudo'];?>"><?php echo $membre['pseudo']?></option>
							<?php 
						}
					}
					?>
				</select>
				<br/><br/><input type="submit" class="allygogo" name="lead" value="Nommer ce membre chef"/>
			</form>
			
			
		</td>		
		<td class="textprivate">
			<form method="post" action="traitement/alliance/kick.php">
				<select name="kicked" id="kicked">
				<?php $req3 = $bdd->prepare('SELECT pseudo FROM membres WHERE id_alliance=? AND apply=1');
				$req3->execute(array($donnees['id_alliance']));
				while($membre = $req3->fetch())
				{	
					if($membre['pseudo'] != $ally['chef'])
					{
						?>
						<option value="<?php echo $membre['pseudo'];?>"><?php echo $membre['pseudo']?></option>
						<?php 
					}
				}
				?>
				</select>
				<br/><br/><input type="submit" class="allygogo" name="kickation" value="Expulser ce joueur de l'alliance"/>
				</tr>
			</form>
		</td>
	</table>
			
			
			
			
		
			
			<?php };

	
	if(isset($_GET['msg']) && $_GET['msg']=='val'){echo '<p class="allymsg">Vous n\'etes désormais plus le chef de cette alliance</p>';}
	
	
	if($ally['chef'] == $_SESSION['pseudo']) //Affiché que pour le chef
	{
		?>
		<h2>Le message de l'alliance</h2>
		
		<?php if(isset($_GET['msg']) && $_GET['msg']=='textval' ) { ?> <p class="mp_vert">Vous avez changé le message de votre alliance. Bravo chef !</p> <?php } ?>
		<?php if(isset($_GET['msg']) && $_GET['msg']=='textlength' ) { ?> <p class="mp_rouge">Votre message d'alliance doit faire moins de 500 caractères et au moins 4 caractères.</p> <?php } ?>
		
		<form method="post" action="traitement/alliance/switchtext.php">
			<textarea class="reponse" name="message" id="message" placeholder="Ecrivez votre message ici.." maxlength="500" rows="13" cols="79"/><?php echo $ally['message']; ?></textarea> <!--Cols a une valeur grande pour arriver au bout, la taille est limitée en CSS-->
			<input type="submit" name="substa" class="marg235" value="Changer le message d'alliance" />
		</form>
	
		<h3>Postulants :</h3>
		<?php
		$check_zero = $bdd->prepare('SELECT COUNT(*) pseudo FROM membres WHERE id_alliance=? AND apply=0');
		$check_zero->execute(array($donnees['id_alliance']));
		if($check_zero->fetchColumn() == 0)
		{
			?>
				<p class="center_align"><em>Aucun postulants</em></p> 

			<?php
		}
		else
		{
			$req2=$bdd->prepare('SELECT pseudo FROM membres WHERE id_alliance=? AND apply=0');
			$req2->execute(array($donnees['id_alliance']));
			while($postuleur = $req2->fetch())
			{ 
				?>
				<p class="center_align"><?php echo $postuleur['pseudo']; ?> : <a href="traitement/alliance/accept.php?pseudo=<?php echo $postuleur['pseudo']; ?>">Accepter</a> | <a href="alliance/decline.php?pseudo=<?php echo $postuleur['pseudo'];?>">Refuser</a> </p>
				<?php
			}
		}
	} //Fin de l'affichage pour le chef
}
//----------------------ALLIANCE MAIS EN APPLY----------------------------
else
{	
	?>
	<p class="padd">Vous postulez actuellement dans une alliance</p><br/>
	
	<form class="center_align" method="post" action="alliance.php">
		<input type="submit" name="subally" value="Annuler la candidature"  /><p></p>
	</form>
	
	<?php
} 
 $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
		
	</div></div>
	<?php include_once'footer.php'; ?>
	</section>
	
	
</div>
    </body >
</html>












