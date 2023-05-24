<div class="listing-article">
<?php

// Vérification si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
	header('Location: index.php'); // Redirection vers la page d'accueil si l'utilisateur est déjà connecté
	exit();
}

// Traitement de la soumission du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Récupération des données du formulaire en méthode POST
$count_error = 0;
	if (isset($_POST['login']) && $_POST['login'] != "" && strlen($_POST['login']) > 1 && strlen($_POST['login']) < 100) {
		$login = htmlentities($_POST['login']);
	}
	else{
		$count_error++;
		echo "<div class='alert alert-danger' role='alert'>Login invalide !</div>";
	}

	if (isset($_POST['password']) && $_POST['password'] != "" && isset($_POST['repeatpassword']) && $_POST['repeatpassword'] != "" && $_POST['password'] == $_POST['repeatpassword']) {
		$password = $_POST['password'];
		$repeatpsswd=$_POST['repeatpassword'];
	}
	else{
		$count_error++;
		echo "<div class='alert alert-danger' role='alert'>Mot de passe invalide !</div>";
	}

	if (isset($_POST['nom']) && $_POST['nom'] != "" && strlen($_POST['nom']) > 1 && strlen($_POST['nom']) < 100) {
		$nom = htmlentities($_POST['nom']);
	}
	else{
		$count_error++;
		echo "<div class='alert alert-danger' role='alert'>Nom invalide !</div>";
	}

	if (isset($_POST['prenom']) && $_POST['prenom'] != "" && strlen($_POST['prenom']) > 1 && strlen($_POST['prenom']) < 100) {
		$prenom = htmlentities($_POST['prenom']);
	}
	else{
		$count_error++;
		echo "<div class='alert alert-danger' role='alert'>Prénom invalide !</div>";
	}

	if (isset($_POST['tel']) && $_POST['tel'] != "" && strlen($_POST['tel']) > 7 && strlen($_POST['tel']) < 12 && is_numeric($_POST['tel'])) {
		$tel = htmlentities($_POST['tel']);
	}
	else{
		$count_error++;
		echo "<div class='alert alert-danger' role='alert'>Tél invalide !</div>";
	}

	if (isset($_POST['e_mail']) && $_POST['e_mail'] != "") {
		  // Valider l'email
		if(filter_var($_POST['e_mail'], FILTER_VALIDATE_EMAIL)){
			$e_mail = htmlentities($_POST['e_mail']);
			
			
	// Vérification si l'utilisateur existe déjà dans la base de données
	$host = "localhost"; // Nom d'hôte de la base de données
	$user = "root"; // Nom d'utilisateur de la base de données
	$password_db = ""; // Mot de passe de la base de données
	$dbname = "greengarden"; // Nom de la base de données

	try {
		$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password_db);
		// configuration pour afficher les erreurs pdo
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}

	$stmt = $conn->prepare("SELECT * FROM t_d_client WHERE Mail_Client=:e_mail");
	$stmt->bindValue(':e_mail', $e_mail);
	$stmt->execute();
	$mail = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($mail) {
		// L'e-mail existe déjà, affichage d'un message d'erreur
		$count_error++;
		echo "<div class='alert alert-danger' role='alert'>E-mail déjà enregistré !</div>";
	}
			
			
			
			
			
		}else{
			$count_error++;
			echo "<div class='alert alert-danger' role='alert'>E-mail invalide !</div>";
		}
	}
	else{
		$count_error++;
		echo "<div class='alert alert-danger' role='alert'>E-mail invalide !</div>";
	}

	if (isset($_POST['type_client']) && $_POST['type_client'] != ""){
		if($_POST['type_client'] != "particulier" && $_POST['type_client'] != "entreprise"){
			$count_error++;
			echo "<div class='alert alert-danger' role='alert'>Client type invalide !</div>";
		}
		else{
			if($_POST['type_client'] == "entreprise"){
				if (isset($_POST['nom_soci']) && $_POST['nom_soci'] != "" && strlen($_POST['nom_soci']) > 1 && strlen($_POST['nom_soci']) < 100) {
					$nom_soci= htmlentities($_POST['nom_soci']);
					$id_type_client=2;
				}else{
					$count_error++;
					echo "<div class='alert alert-danger' role='alert'>Nom de l'entreprise invalide !</div>";
				}
			}
			else{
				$nom_soci=null;
				$id_type_client=1;
			}
		}
	}
	else{
		$count_error++;
		echo "<div class='alert alert-danger' role='alert'>Client type invalide !</div>";
	}



	if($count_error == 0){
	
	// Vérification si l'utilisateur existe déjà dans la base de données
	$host = "localhost"; // Nom d'hôte de la base de données
	$user = "root"; // Nom d'utilisateur de la base de données
	$password_db = ""; // Mot de passe de la base de données
	$dbname = "greengarden"; // Nom de la base de données

	try {
		$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password_db);
		// configuration pour afficher les erreurs pdo
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}

	$stmt = $conn->prepare("SELECT * FROM t_d_user WHERE login=:login");
	$stmt->bindValue(':login', $login);
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user) {
		// L'utilisateur existe déjà, affichage d'un message d'erreur
		$error_message = "Ce login est déjà utilisé par un autre utilisateur.";
	} else {
		// Insertion de l'utilisateur dans la base de données
		$password_hash = password_hash($password, PASSWORD_DEFAULT); // Hashage du mot de passe
		$stmt = $conn->prepare("INSERT INTO t_d_user (Login, Password,Id_UserType) 
		VALUES (:login, :mot_de_passe,1)"); //on force le type utilisateur à client
		$stmt->bindValue(':login', $login);
		$stmt->bindValue(':mot_de_passe', $password_hash);
		$stmt->execute();

		// Récupération de l'identifiant de l'utilisateur inséré
		$user_id = $conn->lastInsertId();


		//On rempli la table client
		$stmt = $conn->prepare("SELECT * FROM t_d_client WHERE Id_User=:user_id");
		$stmt->bindValue(':user_id', $user_id);
		$stmt->execute();
		$client = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($client) {
			// L'utilisateur existe déjà, affichage d'un message d'erreur
			$error_message = "Ce client est déjà présent.";
		} else {
			// Insertion de l'utilisateur dans la base de données
			$stmt = $conn->prepare("INSERT INTO t_d_client (
				Nom_Societe_Client,
				Nom_Client, 
				Prenom_Client,
				Mail_Client,
				Tel_Client,
				Id_Commercial,
				Id_Type_Client,
				Id_User
				) VALUES (
				:nom_soci,
				:nom, 
				:prenom,
				:mail,
				:tel,
				1,
				:id_type_client,
				:user_id
				)"); //on force le type utilisateur à client
			$stmt->bindValue(':nom_soci', $nom_soci);
			$stmt->bindValue(':nom', $nom);
			$stmt->bindValue(':prenom', $prenom);
			$stmt->bindValue(':mail', $e_mail);
			$stmt->bindValue(':tel', $tel);
			$stmt->bindValue(':id_type_client', $id_type_client);
			$stmt->bindValue(':user_id', $user_id);
			$stmt->execute();
		}

		// Connexion automatique de l'utilisateur après son inscription
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user_type'] = 1;
		$_SESSION['surf_id_product'] = 0;
        $_SESSION['surf_return_id_product'] = 0;		
        $_SESSION['logged_in'] = true;

		header('Location: index.php'); // Redirection vers la page d'accueil
		exit();
	}
  }
}
?>

<?php if (isset($error_message)) : ?>
	<p><?php echo $error_message; ?></p>
<?php endif; ?>
	

        <div class="card margin-card-produit">
          <div class="row g-0">

            <div class="col-md-12 col-lg-12 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form method="POST" id="form_inscription" onSubmit="return checkForm(this);">
				
                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <span class="h1 fw-bold mb-0"><h1>Inscription</h1></span>
                  </div>

				  <small><font color="red">*</font> Ces zones sont obligatoires pour envoyer le formulaire.</small>
				<br><br>

				<input type="radio" class="btn-check" name="type_client" id="particulier" autocomplete="off" value="particulier" checked>
				<label class="btn btn-outline-primary" for="particulier">Je suis un Particulier</label>

				<input type="radio" class="btn-check" name="type_client" id="entreprise" autocomplete="off" value="entreprise">
				<label class="btn btn-outline-success" for="entreprise">Je suis une Entreprise</label>


<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12">			
                  <div class="form-outline mb-4">
                    <label class="form-label">Votre Login<font color="red">*</font> :</label>
                    <input class="form-control form-control-lg" type="login" id="login" name="login" value="<?php if(isset($_POST['login'])): echo $_POST['login']; endif; ?>" required />
                  </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12">	
                  <div class="form-outline mb-4">
                    <label class="form-label">Mot de passe<font color="red">*</font> :</label>
                    <input class="form-control form-control-lg" type="password" id="password" name="password" required />
                  </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12">	
				<div class="form-outline mb-4">
					<label class="form-label">Répéter le Mot de passe<font color="red">*</font> :</label>
					<input class="form-control form-control-lg" type="password" id="repeatpassword" name="repeatpassword" required />
				</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12">	
				  <div class="form-outline mb-4">
                    <label class="form-label">Nom<font color="red">*</font> :</label>
                    <input class="form-control form-control-lg" type="text" id="nom" name="nom" value="<?php if(isset($_POST['nom'])): echo $_POST['nom']; endif; ?>" required />
                  </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12">	
				  <div class="form-outline mb-4">
                    <label class="form-label">Prénom<font color="red">*</font> :</label>
                    <input class="form-control form-control-lg" type="text" id="prenom" name="prenom" value="<?php if(isset($_POST['prenom'])): echo $_POST['prenom']; endif; ?>" required />
                  </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12">	
				  <div class="form-outline mb-4">
                    <label class="form-label">numéro de téléphone<font color="red">*</font> :</label>
                    <input class="form-control form-control-lg" type="tel" id="tel" name="tel" pattern="^(0|\+33\s?|0033\s?)[1-9](\s?\d{2}){4}$" value="<?php if(isset($_POST['tel'])): echo $_POST['tel']; endif; ?>" required />
                  </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12">	
				  <div class="form-outline mb-4">
                    <label class="form-label">e-mail<font color="red">*</font> :</label>
                    <input class="form-control form-control-lg" type="email"  id="e_mail" name="e_mail" value="<?php if(isset($_POST['e_mail'])): echo $_POST['e_mail']; endif; ?>" required />
                  </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12">	
				 <div class="form-outline mb-4" id="block_nom_soci" style="display:none;">
                    <label class="form-label">nom société :</label>
                    <input class="form-control form-control-lg" type="text" id="nom_soci" name="nom_soci" value="<?php if(isset($_POST['nom_soci'])): echo $_POST['nom_soci']; endif; ?>" />
                  </div>
</div>
</div>
                  <div class="pt-1 mb-4">
                    <button class="btn btn-dark btn-lg btn-block" type="submit" value="S'inscrire">S'inscrire</button>
                  </div>

                </form>
				<p>Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>
              </div>
            </div>
          </div>
        </div>
</div>