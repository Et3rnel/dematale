<?php 
session_start();
if(!isset($_SESSION['id']))
{
?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8" />
			<link rel="stylesheet" href="design.css" />
			<title>Accès au chat</title>
		</head>
		<body id="white">
			<h1 class="h1-w">Acceder au chat</h1>
			<div class="form">
				<p class="decal">
					Vous devez être connecté pour accèder<br/> au chat !<br/><br/>
					<a href="connexion.php">Cliquez ici</a> pour vous connecter, ou inscrivez vous <a href="register.php">ici</a>.
				</p>
			</div>
			<p class="retour_index"><a href="index.php">Retour à l'index</a></p>
		</body>
	</html>
<?php 
}
else
{
	include_once'actu.php';
	include_once'connectes.php';
	$req20 = $bdd->prepare('UPDATE membres SET notif_chat=0 WHERE id=?');
	$req20->execute(array($_SESSION['id']));
?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8" />
			<link rel="stylesheet" href="design.css" />
			<title>Chat</title>
		</head>
		<body onload="augmentation_ressource()">
			<?php include_once'header.php'; ?>
			<div id="g_section">
				<div id="band_l"></div>	<div id="band_r"></div>
				<section>
					<?php include_once'menu.php'; ?>
					<div id="corps">
						<h1>Chat</h1>

						
						<table class="chattab">
							<tr>
								<td class="tdchat1">
									<form method="post" action="traitement/chat.php"> 
										<input type="text" name="message" id="message" placeholder="Ecrivez votre message ici.." size="82" maxlength="500" />
										<input type="submit"  value="Envoyer le message"/>

										<?php if((isset($_GET['msg'])) && ($_GET['msg']=='time')) { ?> <p class="mp_rouge">Vous avez déjà posté un message sur le chat ou sur le forum il y a moins de 15 secondes.<br/>Veuillez réessayer après ce délais</p> <?php } ?>
										<?php if((isset($_GET['length'])) && ($_GET['length']=='low')) { ?> <p class="mp_rouge">Votre message est trop court ! Vous devez envoyer au moins 2 caractères.</p> <?php } ?>

									</form>
								</td>
							</tr>
							<?php
							$reponse = $bdd->query('SELECT pseudo,message,date_chat FROM chat ORDER BY ID DESC LIMIT 0, 25'); 
							while ($donnees = $reponse->fetch())
							{
							?>
							<tr>
								<td class="tdchat2">
									<strong><a href="profil.php?pseudo=<?php echo $donnees['pseudo']; ?>">
									<?php if($donnees['pseudo'] == 'ZeroTernel'){ ?>
									<span class="admin"><?php echo htmlspecialchars($donnees['pseudo']); ?></span> <?php }else{ echo htmlspecialchars($donnees['pseudo']); } ?></a></strong>
								<?php if($donnees['pseudo']!='Chef de guerre'){$msg = htmlspecialchars($donnees['message']);}else{$msg = '<span class="declarationguerre">'.$donnees['message'].'</span>';}
								$msg = preg_replace('#https?://\S+#i', '<a href="$0" title="lien">$0</a>', $msg); //Liens cliquables auto sous la forme http://... ou https://... 
								?> <span class="heure"><?php echo date('H\hi', $donnees['date_chat']); ?> </span>: <span class="liens_a"><?php echo $msg; ?></span><br/>
								</td>
							</tr>
							<?php
							}
							$reponse->closeCursor(); 
							?>
						</table>
						
					
						<?php 
						$req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
						$connectes = $req_connecte->fetchColumn(); 
						?>
					</div>
					<?php include_once'footer.php'; ?>
				</section>
			</div>
		</body>
	</html>
<?php 
} 
?>