<?php

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Traitement de la soumission du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {	
	
$login = $_POST["login"];
$password = $_POST["password"];

$host = "localhost";
$user = "root";
$pwd = "";
$dbname = "greengarden";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);
} catch (PDOException $e) {
    echo "Connection failed " . $e->getMessage();
}

$stmt = $conn->prepare('SELECT * FROM t_d_user WHERE login=:login');
$stmt->bindValue(':login', $login);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['Password'])) {
    $_SESSION['user_id'] = $user['Id_User'];
    //pour récupérer le user type faire une requete sur la table t_d_usertype
    $_SESSION['user_type'] = $user['Id_UserType'];
    header('Location: index.php');
    exit();
}


}



?>

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
                    <span class="h1 fw-bold mb-0"><h1>Se connecter</h1></span>
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
                    <button class="btn btn-dark btn-lg btn-block" type="submit" value="Se connecter">Se connecter</button>
                  </div>

                </form>
				<p>Pas de compte ? <a href="inscription.php">S'inscrire</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>