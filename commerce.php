<?php session_start(); 
include_once'actu.php';
include_once'connectes.php';
$req55 = $bdd->prepare('SELECT level FROM membres WHERE id=?');
$req55->execute(array($_SESSION['id']));
$membrette = $req55->fetch();
if(!isset($_SESSION['pseudo']) || $membrette['level']<2) header('Location:index.php');
 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Commerce</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Echange de ressources</h1>


	
<?php $req2 = $bdd->prepare('SELECT commerce FROM niveau WHERE id=?');
$req2->execute(array($_SESSION['id']));
$niveau = $req2->fetch();

if($niveau['commerce']<=1){ ?>
<p class="center_align"><strong>Commerce non débloqué.</strong><br/></p><p class="center_align">Pour pouvoir faire des échanges avec les autres joueurs, vous devez allez<br/> dans l’onglet 
<strong>Technologies</strong> et acheter le <strong>Commerce.</strong></p>

<p class="center_align">Cela vous coutera :<strong> 30 000<img src="images/bois_icon.png" alt="Icon bois" align="top" title="Bois"/> | 30 000 <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/> | 30 000 
<img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> | 50 000</strong> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/></p>
<?php } 
else{ ?>

<?php if(isset($_GET['msg']) && $_GET['msg']=='canceled'){?><p class="mp_rouge">Vous n'avez pas assez de ressources pour faire cet échange.</p><?php }; ?>

<table class="margin-a">
	<tr> 
        <th class="trcom">Joueur</th>
		<th class="trcom">Vends</th>
		<th class="trcom">Ratio de l'échange</th>
		<th class="trcom">Recherche</th>
		<th class="trcom">Echanger !</th>
   </tr>
   
   
   
	<?php 
	$req135 = $bdd->query('SELECT id,pseudo FROM membres');
	while($info = $req135->fetch())
	{
		$pseudo_membre[$info['id']] = $info['pseudo'];
	}
		
	include_once'cnx.php';
	$req01 = $bdd->query('SELECT * FROM  commerce');
	while($joueur = $req01->fetch())
	{ 
	$ratio = round($joueur['qt1']/$joueur['qt2'], 1); ?>
	<tr>
		<td class="tdcom1"><a href="profil.php?pseudo=<?php echo $pseudo_membre[$joueur['vendeur']]; ?>"><?php echo $pseudo_membre[$joueur['vendeur']]; ?></td>
		<td class="tdcom2"><?php echo number_format($joueur['qt2'], 0, '.', ' '); ?>  <img src="images/<?php echo $joueur['seek']; ?>_icon.png" alt="Icon de ressources" height="13" title="Icon de ressources" /></td>
		<td class="tdcom3"><?php echo $ratio; ?></td>
		<td class="tdcom4"><?php echo number_format($joueur['qt1'], 0, '.', ' '); ?>  <img src="images/<?php echo $joueur['give']; ?>_icon.png" alt="Icon de ressources" height="13" title="Icon de ressources" /></td>
		<td class="tdcom3"><form method="post" action="traitement/commerce/acheter.php?id=<?php echo $joueur['id']; ?>?test=wtfff"><input type="submit" name="subech" value="Echange !"/></form></td>
	</tr>
	<?php } ?>
	
		
</table>
<hr>
	
	
	
	
	<?php 
		$req02 = $bdd->prepare('SELECT fer,roche,bois,gold,commerce FROM niveau WHERE id=?');
		$req02->execute(array($_SESSION['id'])); 
		$niveau = $req02->fetch(); 
		
		$ress_max = ($niveau['fer']+$niveau['roche']+$niveau['bois']+$niveau['gold'])*50*$membrette['level']; 
		
		$req2 = $bdd->prepare('SELECT COUNT(*) FROM commerce WHERE vendeur=?');
		$req2->execute(array($_SESSION['id']));
		$nbr_trade_done = $req2->fetchColumn();
		
		$nbr_trade_restant = ($niveau['commerce']-1) - $nbr_trade_done; ?>
	
	
	
	
	
	
	
<form method="post" class="center_align" action="traitement/commerce/poser.php">
   <p>
       <label for="nbr1">Je recherche</label>
	   <input type="text" name="nbr1" id="nbr1" />
       <select name="ressource1" id="ressource1">
           <option value="fer">de fer </option>
           <option value="bois">de bois</option>
           <option value="roche">de roche</option>
           <option value="gold">d'or</option>
       </select>
	   
	   <label for="nbr2">contre</label>
	   <input type="text" name="nbr2" id="nbr2" />	   
	   <select name="ressource2" id="ressource2">
           <option value="fer">de fer</option>
           <option value="bois">de bois</option>
           <option value="roche">de roche</option>
           <option value="gold">d'or</option>
       </select>
	      	
		   
		<?php if(isset($_GET['msg']) && $_GET['msg']=='succes'){?><p class="mp_vert">Votre échange à été enregistré avec succès.</p><?php }; ?>
		<?php if(isset($_GET['msg']) && $_GET['msg']=='sameplayer'){?><p class="mp_rouge">Vous ne pouvez pas acheter vos propres ressources !</p><?php }; ?>
		<?php if(isset($_GET['msg']) && $_GET['msg']=='same'){?><p class="mp_rouge">Vous ne pouvez pas demander un échange entre 2 ressources identiques !</p><?php }; ?>
		<?php if(isset($_GET['msg']) && $_GET['msg']=='ressource'){?><p class="mp_rouge">Vous n'avez pas assez de ressources !</p><?php }; ?>
		<?php if(isset($_GET['msg']) && $_GET['msg']=='ratio'){?><p class="mp_rouge">Le ratio entre les ressources doit être compris entre <strong>0.5</strong> et <strong>2</strong> !</p><?php }; ?>
		<?php if(isset($_GET['msg']) && $_GET['msg']=='limit'){?><p class="mp_rouge">Vous ne pouvez plus creer d'échanges, vous avez atteint votre limite !</p><?php }; ?>
		<?php if(isset($_GET['msg']) && $_GET['msg']=='amount'){?><p class="mp_rouge">Vous ne pouvez pas échanger autant de ressources !<br/> Verifiez que vous n'avez pas dépassé votre limite.</p><?php }; ?>
		<?php if(isset($_GET['msg']) && $_GET['msg']=='qt'){?><p class="mp_rouge">Vous devez échanger un minimum de <strong>50</strong> ressources.</p><?php }; ?>
	   
		<input type="submit" name="subposer" value="Proposer l'échange"/>
   </p>
</form>
			
			<p class="simple_purple">Vous pouvez proposer jusqu'à<strong> <?php echo number_format($ress_max, 0, '.', ' '); ?> </strong>d'une ressources par échange.<br/>
		<?php if($nbr_trade_restant>0){ ?>Vous pouvez mettre encore<strong> <?php echo $nbr_trade_restant; ?> </strong>échange(s) sur le commerce. <?php }else
		{ ?>Vous n’avez <strong>plus d’emplacement libre</strong> pour vendre des ressources.<br/>Montez votre technologie commerce ou annulez une de vos ventes. <?php } ?></p>

		
<?php }
	
	$req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
	</div>
	<?php include_once'footer.php'; ?>
	</section>
	
	</div>
    </body>
</html>