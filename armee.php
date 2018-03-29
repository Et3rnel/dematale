<?php session_start();
if(!isset($_SESSION['pseudo'])) header('Location:index.php');
include_once'actu.php'; 
include_once'connectes.php'; ?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Armée</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
		<?php include_once'menu.php'; ?>
		<div id="corps">
		<h1>Armée</h1>
	
	
<p class="padd">Ici, vous pouvez entrainer des unités afin de combattre vos ennemis. Vous pouvez entrainer différents types d'unités avec des caractéristiques différentes. Plus vous aurez d'unités, plus vous pourrez 
transporter de ressources lors de vos pillages ! Mais attention, les troupes les plus chères ne sont pas forcément celles qui peuvent porter le plus de ressources.</p><br/>

<form method="post" action="traitement/creer_armee.php">
	  <br/>
	  <?php	$req001 = $bdd->prepare('SELECT temple FROM niveau WHERE id=?');
		$req001->execute(array($_SESSION['id']));
		$level = $req001->fetch();
		
		$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
		$req01->execute(array($_SESSION['id']));
		$info = $req01->fetch();
	  
	  	$req21=$bdd->prepare('SELECT prix,pillage,nombre FROM armee WHERE type=? AND joueur=?');
		$req21->execute(array('recrue',$_SESSION['pseudo']));
		$recrue = $req21->fetch();
	
		$req22=$bdd->prepare('SELECT prix,pillage,nombre FROM armee WHERE type=? AND joueur=?');
		$req22->execute(array('capitaine',$_SESSION['pseudo']));
		$capitaine = $req22->fetch();
	
		$req23=$bdd->prepare('SELECT prix,pillage,nombre FROM armee WHERE type=? AND joueur=?');
		$req23->execute(array('lieutenant',$_SESSION['pseudo']));
		$lieutenant = $req23->fetch();
	
		$req24=$bdd->prepare('SELECT prix,pillage,nombre FROM armee WHERE type=? AND joueur=?');
		$req24->execute(array('commandant',$_SESSION['pseudo']));
		$commandant = $req24->fetch();
	
	  	$req1 = $bdd->prepare('SELECT gold,guerrisseur FROM ressources WHERE id=?');
		$req1->execute(array($_SESSION['id']));
		$armee=$req1->fetch();
	
		$capa_pillage = $recrue['pillage']*$recrue['nombre'] + $lieutenant['pillage']*$lieutenant['nombre'] + $capitaine['pillage']*$capitaine['nombre'] + $commandant['pillage']*$commandant['nombre'];
	 
	 
		if($info['coupe']==2 || $info['coupe']==4)
		{	
			$prix_recrue = floor($recrue['prix']-$recrue['prix']*0.1);
			$prix_lieutenant = floor($lieutenant['prix']-$lieutenant['prix']*0.1);
			$prix_capitaine = floor($capitaine['prix']-$capitaine['prix']*0.1);
			$prix_commandant = floor($commandant['prix']-$commandant['prix']*0.1);
		}
		else
		{
			$prix_recrue = $recrue['prix'];
			$prix_lieutenant = $lieutenant['prix'];
			$prix_capitaine = $capitaine['prix'];
			$prix_commandant = $commandant['prix'];
		}
		
		$nbr_recrue = floor($armee['gold']/$prix_recrue);	
		$nbr_capitaine = floor($armee['gold']/$prix_capitaine);	
		$nbr_lieutenant = floor($armee['gold']/$prix_lieutenant);	
		$nbr_commandant = floor($armee['gold']/$prix_commandant);
		$nbr_guerrisseur = floor($ressources['paysan']/50);
	 
	 
	 
	 ?>
	 
	<div class="armee1">
		<img src="images/arm_3.png" alt="Icon d'armée" class="img_arm" height="25" width="25"/> <label for ="recrue"><em>Nombre de recrues :</em></label>	<input type="text" name="recrue" id="recrue" placeholder="Prix : <?php echo $prix_recrue;?> or"  <?php if (isset($_GET['recrue'])) echo 'value="'.$_GET['recrue'].'"'; ?> /> <a href="armee.php?recrue=<?php echo $nbr_recrue;?>" class="green"><?php echo number_format($nbr_recrue, 0, '.', ' ');?> Max.</a> 
	</div>
	<div class="armee2">
		<p class="armee3"><em>6 att | 4 def | <?php echo $recrue['pillage'];?> ressources</em></p>
	</div><br/>
	<div class="armee1">
		<img src="images/arm_2.png" alt="Icon d'armée" class="img_arm" height="25" width="25"/> <label for ="lieutenant"><em>Nombre de lieutenants :</em></label> <input type="text" name="lieutenant" id="lieutenant" placeholder="Prix : <?php echo $prix_lieutenant;?> or" <?php if (isset($_GET['lieutenant'])) echo 'value="'.$_GET['lieutenant'].'"'; ?>/> <a href="armee.php?lieutenant=<?php echo $nbr_lieutenant;?>" class="green"><?php echo number_format($nbr_lieutenant, 0, '.', ' ');?> Max.</a> 
	</div>
	<div class="armee2">
		<p class="armee3"><em>15 att | 20 def | <?php echo $lieutenant['pillage'];?> ressources</em></p>
	</div><br/>
	<div class="armee1">
		<img src="images/arm_4.png" alt="Icon d'armée" class="img_arm" height="25" width="25"/> <label for ="capitaine"><em>Nombre de capitaines :</em></label>	<input type="text" name="capitaine" id="capitaine" placeholder="Prix : <?php echo $prix_capitaine;?> or"  <?php if (isset($_GET['capitaine'])) echo 'value="'.$_GET['capitaine'].'"'; ?> /> <a href="armee.php?capitaine=<?php echo $nbr_capitaine;?>" class="green"><?php echo number_format($nbr_capitaine, 0, '.', ' ');?> Max.</a> 
	</div>
	<div class="armee2">
		<p class="armee3"><em>25 att | 15 def | <?php echo $capitaine['pillage'];?> ressources</em></p>
	</div><br/>
	<div class="armee1">
		<img src="images/arm_1.png" alt="Icon d'armée" class="img_arm" height="25" width="25"/> <label for ="commandant"><em>Nombre de commandants :</em></label>	<input type="text" name="commandant" id="commandant" placeholder="Prix : <?php echo $prix_commandant;?> or"  <?php if (isset($_GET['commandant'])) echo 'value="'.$_GET['commandant'].'"'; ?> /> <a href="armee.php?commandant=<?php echo $nbr_commandant;?>" class="green"><?php echo number_format($nbr_commandant, 0, '.', ' ');?> Max.</a> 
	</div>
	<div class="armee2">
		<p class="armee3"><em>50 att | 50 def | <?php echo $commandant['pillage'];?> ressources</em></p>
	</div>
	<?php
	$req2 = $bdd->prepare('SELECT level FROM membres WHERE id=?');		
	$req2->execute(array($_SESSION['id']));							
	$info = $req2->fetch();	
	if($info['level']>=3){ 
	$nbr_slot_restant = ($level['temple']*2)-$armee['guerrisseur']; ?>  	
	<div class="armee1">
		<img src="images/img22.png" alt="Guerrisseur" class="img_arm" height="25" width="25"/> <label for ="guerrisseur"><em>Nombre de guerrisseurs :</em></label>	<input type="text" name="guerrisseur" id="guerrisseur" placeholder="Prix : 50 paysans"  <?php if (isset($_GET['guerrisseur'])) echo 'value="'.$_GET['guerrisseur'].'"'; ?> /> <a href="armee.php?guerrisseur=<?php echo $nbr_guerrisseur;?>" class="green"><?php echo number_format($nbr_guerrisseur, 0, '.', ' ');?> Max.</a> 
	</div>
	<div class="armee2">
		<p class="armee3"><em>Réssucite 0.5% des troupes à chaque bataille, ne peut pas être tué</em></p>
	</div> 
	<p class="simple_purple">Il vous reste <?php echo $nbr_slot_restant; ?> places pour des guerrisseurs</p><?php } ?><br/>
	
		<?php if((isset($_GET['error'])) && ($_GET['error']=='empty')) { ?> <p class="mp_rouge">Vous n'avez rempli aucun des champs.</p> <?php } ?>
		<?php if((isset($_GET['error'])) && ($_GET['error']=='money')) { ?> <p class="mp_rouge">Vous n'avez pas assez de ressources.</p> <?php } ?>
		<?php if((isset($_GET['error'])) && ($_GET['error']=='slot')) { ?> <p class="mp_rouge">Vous n'avez pas assez d'espace pour recruter des guerrisseurs.</p> <?php } ?>
		
		<p class="center_align">Vous pouvez piller <span class="capa_pillage"><?php echo number_format($capa_pillage, 0, '.', ' '); ?></span> unités de chaque ressource.</p>
		
	
		<br/><input type="submit" class="marg270" name="updateofdeathlol" value="Entrainer les troupes !"/>
</form>
	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
	
	</div>
	<?php include_once'footer.php'; ?>
	</section>
	
	</div>
    </body >
</html>