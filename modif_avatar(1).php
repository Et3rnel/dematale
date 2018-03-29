<?php session_start();
if(!isset($_SESSION['pseudo'])) header('Location:index.php'); 

if(!empty($_FILES))
{
	if($_FILES['avatar']['error'] === UPLOAD_ERR_INI_SIZE)
	{
		header('Location:private.php?erreur=size');
	}
	else
	{
		$taille = filesize($_FILES['avatar']['tmp_name']);
		if($taille<524288)
		{
			$avatar = $_FILES['avatar']['name'];
			$avatar_tmp = $_FILES['avatar']['tmp_name'];
			$image = explode('.',$avatar);
			$image_ext = end($image);
			if (in_array(strtolower($image_ext),array('jpg','jpeg','png')) === false)
			{
				header('Location:private.php?erreur=ext');
			}
			else
			{
				
				$avatar = strtr($avatar,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); 
				//On remplace les lettres accentutées par les non accentuées dans $avatar.
				//Et on récupère le résultat dans fichier
				$avatar = preg_replace('/([^.a-z0-9]+)/i', '-', $avatar);
				include'cnx.php';
				$req1 = $bdd->prepare('SELECT id FROM membres WHERE avatar=?');
				$req1->execute(array($avatar));
				$check = $req1->fetch();
				if (!empty($check['id']))
				{
					header('Location:private.php?erreur=used');
				}
				else
				{
					move_uploaded_file($avatar_tmp,'avatar/'.$avatar);
					$req2 = $bdd->prepare('UPDATE membres SET avatar=? WHERE id=?');
					$req2->execute(array($avatar,$_SESSION['id']));
					header('Location:private.php?msg=val');
				}		
				
			}
		}
		else
		{
			header('Location:private.php?erreur=size');
		}
	}
}
else
{
	header('Location:iprivate.php');
}
?>


