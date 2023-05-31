<div class="listing-article">

<h1>Mes tickets</h1>
<?php if(isset($_SESSION['user_id']) == true && $_SESSION['user_id'] != "") { ?>

<nav class="nav nav-pills flex-column flex-sm-row new-nav">
  <?php if(!isset($_GET['option'])): ?>
	<button class="flex-sm-fill text-sm-center active">Tous mes tickets</button>
  <?php else: ?>
	<a class="flex-sm-fill text-sm-center nav-link" href="<?php echo $page; ?>.php">Tous mes tickets</a>
  <?php endif; ?>
  <?php if(isset($_GET['option']) && $_GET['option'] == "en_cours"): ?>
	<button class="flex-sm-fill text-sm-center active">En cours</button>
  <?php else: ?>
	<a class="flex-sm-fill text-sm-center nav-link" href="<?php echo $page; ?>.php?option=en_cours">En cours</a>
  <?php endif; ?>
  <?php if(isset($_GET['option']) && $_GET['option'] == "termine"): ?>
	<button class="flex-sm-fill text-sm-center active">Terminé</button>
  <?php else: ?>
	<a class="flex-sm-fill text-sm-center nav-link" href="<?php echo $page; ?>.php?option=termine">Terminé</a>
  <?php endif; ?>
  
</nav>
<br>
<?php
require 'includes/class/produit.php';
require 'includes/class/categorie.php';
require 'includes/class/fournisseur.php';

$user_id = $_SESSION['user_id'];

// Récupération des informations de l'utilisateur connecté
$host = "localhost"; // Nom d'hôte de la base de données
$user = "root"; // Nom d'utilisateur de la base de données
$password_db = ""; // Mot de passe de la base de données
$dbname = "greengarden"; // Nom de la base de données

$tot_c = 0;

try {
	$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password_db);
	// configuration pour afficher les erreurs pdo
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	

} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>


<p>
<br>
<a class='btn btn-outline-success float-left' href="catalogue.php">Continuer mes achats</a>
<br><br>
</p>

<?php 
} else {
	header('Location: connexion.php'); // Redirection vers la page de connexion
	exit();
}
?>
</div>