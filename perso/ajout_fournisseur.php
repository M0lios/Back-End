<?php include 'header.php';
require 'functions.php'; ?>

<?php
// peut mettre <?= à la place de <?php




// Récupération des informations de l'utilisateur connecté
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification si le formulaire a été soumis
    if (
        isset($_POST['nom_fournisseur']))
     
        $nom_fournisseur = escape_string($_POST['nom_fournisseur']);
        
        $stmt = $conn->prepare("SELECT * from t_d_fournisseur where Nom_Fournisseur=:nomfour");
        $stmt->bindValue(':nomfour', $_POST['nom_fournisseur']);
        $stmt->execute();
        $fournisseur =  $stmt->fetch(PDO::FETCH_ASSOC);
        

        
            // Ajout du produit à la base de données
            try {
                $stmt = $conn->prepare("INSERT INTO t_d_fournisseur (Nom_Fournisseur)

				 VALUES (:nomfour)");
               
                $stmt->bindValue(':nomfour', $nom_fournisseur);
                $stmt->execute();
                $fournisseur_id = $conn->lastInsertId();
                header('Location: index.php');
                exit();
            
               
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
            exit();
        }  
    }
 
?>

    <h1>Ajout d'un fournisseur</h1>

    <form method="post" enctype="multipart/form-data">
      
    

        <br>
  <div>
            <label for="nomfour">Nom Fournisseur:</label>
            <input type="text" id="nomf" name="nom_fournisseur" required>
        </div>


        <br>
        <button type="submit">Ajouter</button>
    </form>
    <footer>
        <p>Green Garden - Tous droits réservés</p>
        <div>Ce site a été réalisé par PHILIPPE Séverine</div>
    </footer>
</body>
    
<?php include 'footer.php'; ?>