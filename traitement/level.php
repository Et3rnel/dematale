<?php 
		$get_points = $bdd->prepare('SELECT points,level FROM membres WHERE id=?');
		$get_points->execute(array($_SESSION['id']));
		$points = $get_points->fetch();
		$new_level = floor($points['points']/100);
		if($new_level > $points['level'])
		{
			$titre = 'Niveau '.$new_level.' débloqué !';
			$message = '<p class="lvlup"><img src="images/lvlup.png"  align="top" alt="Niveau suivant !" title="Niveau suivant !" />
			</br></br>Félicitations ! Vous passez au niveau '.$new_level.' !<br/><br/>Vous débloquez : ';
			
			if($new_level==1){
			$message=$message.'<strong>Hotel des ventes</strong> dans l\'onglet <strong>Technologies.</strong></br></p>';}
			if($new_level==2){
			$message=$message.'<strong>Commerce</strong> dans l\'onglet <strong>Technologies.</strong></br></p>';}
			if($new_level==3){
			$message=$message.'<strong>Temple des voeux</strong> dans l\'onglet <strong>Bâtiments.</strong></br></p>';}
			if($new_level==5){
			$message=$message.'<strong>Forge</strong> dans l\'onglet <strong>Bâtiments.</strong></br>Vous débloquez aussi une nouvelle ressource : 
			le <strong>Titane</strong> <img src="images/icon/titane.png" alt="Titane"/> <br/></p>';}	
			
			$temps = time();
		
			$add_msg = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp) VALUES (?,?,?,?,?)');
			$add_msg->execute(array($_SESSION['pseudo'],'Chef de guerre',$titre,$message,$temps));
		
			$update_level = $bdd->prepare('UPDATE membres SET level=level+1 WHERE id=?');
			$update_level->execute(array($_SESSION['id']));
			
			$req10 = $bdd->query('SELECT MAX(id) FROM mess_priv');
			$id = $req10->fetch();
			
			header('Location:../../mp.php?id='.$id[0].'');
			die();
		}
?>