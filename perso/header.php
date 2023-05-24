



  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="style/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>

<body>
<?php	if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
  <header>
    <nav class="navbar navbar-expand-xl navbar-light bg-light">
      <div class="container-fluid">
        <img alt="logo" src="img/logo.jpg" class="logo">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo2" aria-controls="navbarTogglerDemo2" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse show" id="navbarTogglerDemo2">
          <ul class="navbar-nav me-auto mb-2 mb-xl-0">
            <li class="nav-item">
              <a class="nav-link " href="index.php">Accueil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="catalogue.php">Catalogue</a>
            </li>
            <?php 

            
            if (    isset($_SESSION['user_id']) && isset($_SESSION['user_type'])    
             && ($_SESSION['user_type'] == 'Commercial' || $_SESSION['user_type'] == 'Admin') ) {
              echo ' <li class="nav-item">
              <a class="nav-link" href="ajout_produit.php">Ajout Produit</a>
            </li>';
            echo ' <li class="nav-item">
              <a class="nav-link" href="ajout_fournisseur.php">Ajout Fournisseur</a>
            </li>';
            echo ' <li class="nav-item">
              <a class="nav-link" href="ajout_categorie.php">Ajout Categorie</a>
            </li>';
            }

            ?>
          
            <li class="nav-item">
              <a class="nav-link " href="panier.php">Panier</a>
            </li>


          </ul>

        </div>
      </div>
    </nav>

    <?php


    if (isset($_SESSION['user_id'])) :
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

      $stmt = $conn->prepare("SELECT * FROM t_d_user WHERE Id_User=:id");
      $stmt->bindValue(':id', $_SESSION['user_id']);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

    ?>
      <p>Bonjour <?php echo $user['Login']; ?> !</p>

      <p><a href="deconnexion.php">Déconnecter</a></p>
    <?php else : ?>
      <p><a href="login.php">Se connecter</a> ou <a href="inscription.php">s'inscrire</a></p>
    <?php endif; ?>

  </header>