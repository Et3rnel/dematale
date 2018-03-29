<?php session_start(); 
if(!isset($_SESSION['pseudo'])) {header('Location:../index.php');}
include_once'../actu.php';
include_once'../connectes.php'; 

$req1 = $bdd->prepare('SELECT id_alliance,apply FROM membres WHERE id=?');
$req1->execute(array($_SESSION['id']));
$membres = $req1->fetch();

$req2 = $bdd->prepare('SELECT id,chef,nbr_membre,recrue,lieutenant,capitaine,commandant,modele,en_guerre,victory FROM alliance WHERE id=?');
$req2->execute(array($membres['id_alliance']));
$alliance = $req2->fetch(); 

if(empty($alliance['nbr_membre']) || $membres['apply']==0)
{
	header('Location:../index.php?msg=epezf');
}  ?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="../design.css" />
        <title>Guerre entre alliances</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'../header.php'; ?>
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>

	
	<?php include_once'../menu.php'; ?>
	<div id="corps">					
	<h1>Coffre-fort</h1>
	
	
	<div class="corps2">
	<p class="menu_alliance"><a href="../alliance.php">Gestion</a> | <a href="../chat_alliance.php">Chat</a> | <a href="../developpement.php">Développement</a> | <a href="../coffre.php">Coffre-fort</a></p>
	<p class="center_align">Ici, vous pouvez faire la guerre ou acheter une armée !<br/>Choisissez une alliance a attaquer et lancez l'assault !</p>
	
<?php 
		
	$attaque = 6*$alliance['recrue'] + 15*$alliance['lieutenant'] + 25*$alliance['capitaine'] + 50*$alliance['commandant']; 
	$defense = 4*$alliance['recrue'] + 20*$alliance['lieutenant'] + 15*$alliance['capitaine'] + 50*$alliance['commandant']; 
		
	?>
	<p class="simple_purple"><strong> <?php if($alliance['en_guerre']==0){ ?> Vous n'etes actuellement pas en guerre.<?php }else{ ?>Vous êtes en guerre !<?php } ?></strong> </p>
	
	<?php if($alliance['modele']==2){
	$prix_recrue = 25;	
	$prix_lieutenant = 65;
	$prix_capitaine = 80;
	$prix_commandant = 185;
	}else{
	$prix_recrue = 30;	
	$prix_lieutenant = 75;
	$prix_capitaine = 90;
	$prix_commandant = 205;
	}?>
	
	<div class="infoarmy">
	<table>
		<tr class="tarmy">
			<td class="icon_army"><img src="../images/arm_3.png" title="Recrue" alt="Recrue" height="25" width="25"/></td> 
			<td class="nbr_unit"><?php if($alliance['recrue']==0){echo 'Aucun';}else{echo number_format($alliance['recrue'], 0, '.', ' ');} ?></td>
		</tr>
		<tr class="tarmy">		
			<td class="icon_army"><img src="../images/arm_2.png" title="Lieutenant" alt="Lieutenant" height="25" width="25"/></td> 
			<td class="nbr_unit"><?php if($alliance['lieutenant']==0){echo 'Aucun';}else{echo number_format($alliance['lieutenant'], 0, '.', ' ');} ?></td>
		</tr>
		<tr class="tarmy">		
			<td class="icon_army"><img src="..//images/arm_4.png" title="Capitaine" alt="Capitaine" height="25" width="25"/></td> 
			<td class="nbr_unit"><?php if($alliance['capitaine']==0){echo 'Aucun';}else{echo number_format($alliance['capitaine'], 0, '.', ' ');} ?></td>
		</tr>
		<tr class="tarmy">		
			<td class="icon_army"><img src="..//images/arm_1.png" title="Commandant" alt="Commandant" height="25" width="25"/></td> 
			<td class="nbr_unit"><?php if($alliance['commandant']==0){echo 'Aucun';}else{echo number_format($alliance['commandant'], 0, '.', ' ');} ?></td>
		</tr>
		<tr class="tarmy">		
			<td class="icon_army">Attaque</td> 
			<td class="nbr_unit"><em><?php echo number_format($attaque, 0, '.', ' '); ?></em></td>
		</tr>
		<tr class="tarmy">		
			<td class="icon_army">Défense</td> 
			<td class="nbr_unit"><em><?php echo number_format($defense, 0, '.', ' '); ?></em></td>
		</tr>
	</table>
	</div>
	
	
	<div class="allyarmy">
		<form method="post" action="../traitement/alliance/creer_unites.php">
		<div class="armee1">
			<img src="../images/arm_3.png" alt="Icon d'armée" class="img_arm" height="20" width="20"/> <label for ="recrue"><em>Nombre de recrues :</em></label>	<input type="text" name="recrue" id="recrue" placeholder="Prix : <?php echo $prix_recrue; ?> or"/> 	
		</div>
		<div class="armee2">
			<p class="armee3"><em>6 att | 4 def | 5 ressources</em></p>
		</div><br/>
		<div class="armee1">
			<img src="../images/arm_2.png" alt="Icon d'armée" class="img_arm" height="25" width="25"/> <label for ="lieutenant"><em>Nombre de lieutenants :</em></label> <input type="text" name="lieutenant" id="lieutenant" placeholder="Prix : <?php echo $prix_lieutenant; ?>  or"/>
		</div>
		<div class="armee2">
			<p class="armee3"><em>15 att | 20 def | 7 ressources</em></p>
		</div><br/>
		<div class="armee1">
			<img src="../images/arm_4.png" alt="Icon d'armée" class="img_arm" height="25" width="25"/> <label for ="capitaine"><em>Nombre de capitaines :</em></label>	<input type="text" name="capitaine" id="capitaine" placeholder="Prix : <?php echo $prix_capitaine; ?>  or"/>  
		</div>
		<div class="armee2">
			<p class="armee3"><em>25 att | 15 def | 7 ressources</em></p>
		</div><br/>
		<div class="armee1">
			<img src="../images/arm_1.png" alt="Icon d'armée" class="img_arm" height="23" width="23"/> <label for ="commandant"><em>Nombre de commandants :</em></label>	<input type="text" name="commandant" id="commandant" placeholder="Prix : <?php echo $prix_commandant; ?>  or"/> 
		</div>
		<div class="armee2">
			<p class="armee3"><em>50 att | 50 def | 12 ressources</em></p>
		</div>
		<input type="submit" name="suppbro" class="marg110" value="Entraîner ces troupes pour l'alliance !"/><br/>
		</form>
	</div>
				
	
	<?php if((isset($_GET['msg'])) && ($_GET['msg']=='empty')) { ?> <p class="mp_rouge">Veuillez remplir au moins un des champs.</p> <?php } ?>
	<?php if((isset($_GET['msg'])) && ($_GET['msg']=='money')) { ?> <p class="mp_rouge">Vous n'avez pas assez d'or pour acheter ces unités à l'alliance.</p> <?php } ?>
	<?php if((isset($_GET['msg'])) && ($_GET['msg']=='joure')) { ?> <p class="mp_rouge">Vous devez attendre au minimum 1h pour lancer le round suivant.</p> <?php } ?>
	
	
	
<?php if(($alliance['chef']!=$_SESSION['pseudo']) || $alliance['en_guerre']==0)
{ ?>
	<p class="simple_purple"> Liste des alliances attaquables </p>
	
	<table class="margin-a">
	<tr>
		<th class="thguerreally"><strong>Nom</strong></td>
		<th class="thguerreally"><strong>Nombre de membres</strong></td>
	</tr>
			
	<?php 	$req3 = $bdd->prepare('SELECT nom,nbr_membre,id FROM alliance WHERE en_guerre=0 AND id!=?'); 
	$req3->execute(array($alliance['id']));
	while($alliance2 = $req3->fetch())
	{	?><tr>	<td class="tdguerreally">
						<form method="post" action="">
						<input type="submit" class="nostyle" onclick="return(confirm('Etes-vous sur de vouloir attaquer cette alliance ?'));" value="<?php echo $alliance2['nom']; ?>" name="subfight"/>
					</form></td>
				<td class="tdguerreally"><?php echo $alliance2['nbr_membre']; ?></td>
			</tr>
	<?php }	?>
	</table>
	
	<?php 
}
else
{
	if($alliance['chef']==$_SESSION['pseudo'])
	{
		$req3 = $bdd->prepare('SELECT victory FROM alliance WHERE id=?');
		$req3->execute(array($alliance['en_guerre']));
		$opposant = $req3->fetch(); 
		$round = $alliance['victory'] + $opposant['victory'];
		
		if($round==1)
		{ ?>
			<form method="post" action="../traitement/alliance/round.php"><p class="simple_purple"><input type="submit" onclick="return(confirm('Etes-vous sûr de vouloir lancer le deuxième combat ?'));" value="Lancer le 2éme round !" name="subround1"/></p></form>
		<?php }
		else
		{ ?>
			<form method="post" action="../traitement/alliance/round.php"><p class="simple_purple"><input type="submit" onclick="return(confirm('Etes-vous sûr de vouloir lancer le dernier combat ?'));" value="Lancer le dernier round !" name="subround2"/></p></form>
		<?php }
	}

	
} ?>
	
	
	
	
	
	
	
	
	
	
	<?php
	$req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
		
	</div></div>
	<?php include_once'../footer.php'; ?>
	</section>
	
	
</div>
    </body >
</html>












