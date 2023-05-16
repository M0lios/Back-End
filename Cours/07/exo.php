<html>

<?php
if ( $_SERVER['REQUEST_METHOD'] == "POST") {
if ( isset($_POST['create'])) {
 echo "Le Nom Saisi est : ".$_REQUEST['nom'];
 echo "<br>";
 echo "prenom est : ".$_REQUEST['prenom'];
 echo "<br>";
 echo "mail est : ".$_REQUEST['mail'];
 echo "<br>";
 echo "password est : ".$_REQUEST['password'];
 echo "<br>";
 echo "repeatpassword est : ".$_REQUEST['repeatpassword'];
 }
}
?>

	<form action="" id="form1" method="post">
		<div class="card mb-3">
			<div class="card-header">
				<h1>Inscription</h1>
				<br>
				<small><font color="red">*</font> Ces zones sont obligatoires pour envoyer le formulaire.</small>
			</div>
			<div class="card-body">
				Nom<font color="red">*</font> :
				<input type="text" name="nom" id="nom"/>
				<br><br>
				Prénom<font color="red">*</font> :
				<input type="text" name="prenom" id="prenom"/>
				<br><br>
				E-mail<font color="red">*</font> :
				<input type="email" name="mail" id="mail"/>
				<br><br>
				Mot de passe<font color="red">*</font> :
				<input type="password" name="password" id="password"/>
				<br><br>
				Ressaisir le mot de passe<font color="red">*</font> :
				<input type="password" name="repeatpassword" id="repeatpassword"/>
				<br><br>
			</div>
			<div class="card-footer">
				<input class="btn btn btn-outline-success verticlal-center" type="submit" id="idSubForm1" name="create" value="S'inscrire">
			</div>
		</div>
	</form>


</html>