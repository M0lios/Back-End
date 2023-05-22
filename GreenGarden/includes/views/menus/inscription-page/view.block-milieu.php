<?php

// Vérification si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
	header('Location: index.php'); // Redirection vers la page d'accueil si l'utilisateur est déjà connecté
	exit();
}

// Traitement de la soumission du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Récupération des données du formulaire en méthode POST
	$login = $_POST['login'];
	$password = $_POST['password'];

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

		// Connexion automatique de l'utilisateur après son inscription
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user_type'] = 1;

		header('Location: index.php'); // Redirection vers la page d'accueil
		exit();
	}
}
?>

<?php if (isset($error_message)) : ?>
	<p><?php echo $error_message; ?></p>
<?php endif; ?>
	
<section>
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card">
          <div class="row g-0">
		  
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="styles/images/logo/gg.jpg" alt="login form" class="img-fluid"/>
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form method="POST">

                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <span class="h1 fw-bold mb-0"><h1>Inscription</h1></span>
                  </div>

                  <div class="form-outline mb-4">
                    <label class="form-label" for="login">Votre Login :</label>
                    <input class="form-control form-control-lg" type="login" id="login" name="login" required />
                  </div>

                  <div class="form-outline mb-4">
                    <label class="form-label" for="password">Mot de passe :</label>
                    <input class="form-control form-control-lg" type="password" id="password" name="password" required />
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
    </div>
  </div>
</section>