<?php session_start(); 
if(!isset($_SESSION['pseudo']) || (!isset($_GET['nom']))) header('Location:index.php'); 
include_once'actu.php';
include_once'connectes.php';

$req1 = $bdd->prepare('SELECT id,chef FROM alliance WHERE nom = ?');
$req1->execute(array($_GET['nom']));
$alliance = $req1->fetch();
if (empty($alliance['id'])){
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>Alliance inconnu</title>
    </head>
    <body id="white">
	<h1 class="h1-w">Alliance inconnu</h1>	
<div class="form">
	<p class="decal">L'alliance à laquel vous tentez d'acceder n'existe pas.<br/><br/><a href="classement.php">Aller au classement</a></p>
</div>
	</body>
</html>

<?php die();} ?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title><?php echo $_GET['nom']; ?></title>
    </head>
        <body onload="augmentation_ressource()">
	
<?php include_once'header.php'; ?>
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
		<?php include_once'menu.php'; ?>
		<div id="corps">
		<h1>Presentation de l'alliance</h1>
		<div class="corps2">
					
					<div class="pro1">
					<div class="pro2"><?php echo $_GET['nom']; ?></div>
					<table class="center_align"><tr>	
						<td class="pro3">Embleme<br/>d'alliance</td>
						
						<?php
						$req2 = $bdd->prepare('SELECT id_alliance,apply,level FROM membres WHERE id=?');
						$req2->execute(array($_SESSION['id']));
						$check = $req2->fetch(); 
						
						$req3 = $bdd->prepare('SELECT nbr_membre,message FROM alliance WHERE id=?');
						$req3->execute(array($alliance['id']));
						$ally = $req3->fetch();?>
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
	
						<td class="pro3">
						<?php 
		
		if($check['id_alliance']==0 && $ally['nbr_membre']<15)
		{
			if($check['level']>0)
			{ ?> 
				<a href="trait_join.php?id=<?php echo $alliance['id']; ?>">Postuler</a> <?php 
			}else
			{ ?> 
				<p>Vous ne pouvez pas postuler.</p> <?php 
			}  
		}
		elseif($check['id_alliance']==0 && $ally['nbr_membre']=15) 
		{ ?> 
			Cette alliance est complète ! <?php 
		}
		elseif($alliance['id']==$check['id_alliance'] && $check['apply']==0) 
		{ ?> 
			Vous postulez dans cette alliance ! <?php 
		}
		elseif($alliance['id']==$check['id_alliance'] && $check['apply']==1) 
		{ ?> 
			C'est votre alliance ! <?php 
		}else 
		{ ?> 
			<em>Proposer un pacte à cette alliance(bientôt)</em> <?php 
		} ?>
						
						
						</td>
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						<td class="pro4">Chef : <?php echo $alliance['chef']; ?></td>		
					</tr></table><div class="pro5">Membres : <?php echo $ally['nbr_membre']; ?><hr><strong><?php if(empty($ally['message'])){echo 'Cette alliance n\'a pas laissé de message.';}else{ echo $ally['message'];}?></strong></div></div>
			<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>			
	
	</div></div>
	<?php include_once'footer.php'; ?>
	</section>
	
	
</div>
    </body>
</html>

















