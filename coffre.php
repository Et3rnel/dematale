<?php session_start(); 
if(!isset($_SESSION['pseudo'])) {header('Location:index.php');}
include_once'actu.php';
include_once'connectes.php'; 

$req1 = $bdd->prepare('SELECT id_alliance,apply FROM membres WHERE id=?');
$req1->execute(array($_SESSION['id']));
$players = $req1->fetch();

$req2 = $bdd->prepare('SELECT coffre,chef,nbr_membre FROM alliance WHERE id=?');
$req2->execute(array($players['id_alliance']));
$alliance = $req2->fetch(); 

$req3 = $bdd->prepare('SELECT corruption FROM niveau_alliance WHERE id=?');
$req3->execute(array($players['id_alliance']));
$niveau = $req3->fetch(); 

if(empty($alliance['nbr_membre']) || $players['apply']==0)
{
	header('Location:index.php');
} 

$taxe = 30-($niveau['corruption']-1); ?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Coffre-fort</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>

	
	<?php include_once'menu.php'; ?>
	<div id="corps">					
	<h1>Coffre-fort</h1>
	
	
	<div class="corps2">
	
	<p class="menu_alliance"><a href="alliance.php">Gestion</a> | <a href="chat_alliance.php">Chat</a> | <a href="developpement.php">Développement</a> | <a href="alliance/guerre.php">Guerre</a></p>
	
	<p>L'or contenu dans le coffre permet d'ameliorer l'alliance, ou encore d'être distribué aux joueurs. Attention, l'or donné aux joueurs est taxé de <strong><?php echo $taxe.'%.'; ?></strong></p>

	
	<table class="coffre">
	<tr>
		<td class="coffre1"><img src="images/coffre.png" alt="Coffre-fort" align="top"/></td>
	</tr>
	<tr>
		<td class="coffre2"><?php echo number_format($alliance['coffre'], 0, '.', ' '); ?> <img src="images/gold_icon.png" alt="Or" class="img_coffre"/></td>
	</tr>
	</table>
	
	
	<div class="nextcoffre">
		<p style="text-decoration:underline;">Mettre de l'argent dans le coffre</p>
		<form method="post" action="traitement/alliance/donner_or.php">
			<label for="argent">Je souhaite donner </label>
			<input type="text" name="argent" id="argent" size="5"/> <img src="images/gold_icon.png" alt="Or" class="img_coffre"/><br/>			
			<input type="submit" name="coffre_money" value="Donner de l'or à l'alliance !" />	
		</form>
		<?php if((isset($_GET['msg'])) && ($_GET['msg']=='argent')) { ?> <p class="red">Vous n'avez pas assez d'or.</p> <?php } ?>
		<?php if((isset($_GET['msg'])) && ($_GET['msg']=='succes')) { ?> <p class="green">Vous avez fais un don d'or à votre alliance.</p> <?php } ?>
		<?php if((isset($_GET['msg'])) && ($_GET['msg']=='minimum')) { ?> <p class="red">Vous devez donner au moins 50 or.</p> <?php } 
		
		
		$reqX = $bdd->prepare('SELECT donner_or_membre FROM timer_alliance WHERE id=?');
		$reqX->execute(array($players['id_alliance']));
		$timer = $reqX->fetch();
		
		$temps = time();
		$dif_temps = $temps - $timer['donner_or_membre'];
		
		if($alliance['chef'] == $_SESSION['pseudo'])
		{ 
			if($dif_temps >= 86400)
			{ ?>
			<p style="text-decoration:underline;">Donner de l'or à un membre de l'alliance</p>
			<form method="post" action="traitement/alliance/donner_or_membre.php">
				<label for="argent">Je suis le chef et je décide de donner </label>
				<input type="text" name="argent" id="argent" size="5"/> <img src="images/gold_icon.png" alt="Or" class="img_coffre"/><br/>
				<label for="joueur">au joueur</label>
				<select name="joueur" id="joueur">
					<?php
					
					$req3 = $bdd->prepare('SELECT pseudo FROM membres WHERE id_alliance=?');
					$req3->execute(array($players['id_alliance']));
					while($joueur = $req3->fetch())
					{	
						?>
						<option value="<?php echo $joueur['pseudo'];?>"><?php echo $joueur['pseudo']?></option>
						<?php 					
					}
					?>
				</select>
				<br/><input type="submit" name="donner_argent" value="Donner de l'or à ce membre"/>
			</form>
			<?php 
			}
			else
			{
				$heures=floor($dif_temps/3600);
				$reste=$dif_temps%3600;
				$minutes=floor($reste/60);
				$true_houres = 23-$heures;
				$true_minutes = 59-$minutes;
				?>
				<p class="simple_purple">Il vous reste <?php if($true_houres>0)echo $true_houres.' heures et'; ?> <?php echo $true_minutes; ?> minutes avant de de pouvoir donner de l'argent à un membre</p>
				<?php
			}
		} 
		if((isset($_GET['msg'])) && ($_GET['msg']=='or')) { ?> <p class="red">Vous ne pouvez pas donner autant d'or à ce joueur. Verfier son nombre de points.</p> <?php }
		if((isset($_GET['msg'])) && ($_GET['msg']=='argent_coffre')) { ?> <p class="red">Il n'y a pas assez d'or dans le coffre.</p> <?php }
		if((isset($_GET['msg'])) && ($_GET['msg']=='succes_give')) { ?> <p class="green">Vous avez distribué de l'or avec succes !</p> <?php } 
		if((isset($_GET['msg'])) && ($_GET['msg']=='minimum2')) { ?> <p class="red">Vous devez donner au moins 50 or.</p> <?php } ?>
		
	<p>Le chef peut donner des ressources à chaque joueur ainsi qu'à lui-même. Le montant d'or qu'il est possible de donner dépend du nombre de points du joueur.<br/><strong> Nombre_or = Points_du_joueur * 1000</strong></p>	
	</div>
	
	
	<?php
	$req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
		
	</div></div>
	<?php include_once'footer.php'; ?>
	</section>
	
	
</div>
    </body >
</html>












