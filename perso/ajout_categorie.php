<?php
include 'header.php';//insérer son header dans la page
require 'functions.php';

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
        isset($_POST['nom_categorie']) && isset($_POST['cat_idparent'])) {

        $nom_categorie = escape_string($_POST['nom_categorie']);
        $cat_parent = $_POST['cat_idparent'];

        if ($cat_parent!=""){
            $stmt = $conn->prepare("SELECT COUNT(*) AS total from t_d_categorie where Libelle=:libc");
            $stmt->bindValue(':libc', $nom_categorie);
            $stmt->execute();
            $categorie =  $stmt->fetch(PDO::FETCH_ASSOC);
            $totalcat=$categorie['total'];

            if ($totalcat<1){
            try {
                $stmt = $conn->prepare("INSERT INTO t_d_categorie (Libelle, Id_Categorie_Parent)
					 VALUES (:libc, :catparent)");
                $stmt->bindValue(':libc', $nom_categorie);
                $stmt->bindValue(':catparent',  $cat_parent);
                $stmt->execute();
                $categorie_id = $conn->lastInsertId();
            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                exit();
            }
            }
            else{
                echo 'Catégorie déjà existante';
           }
        }
        else{
            $stmt = $conn->prepare("INSERT INTO t_d_categorie (Libelle)
					 VALUES (:libc)");
                $stmt->bindValue(':libc', $nom_categorie);
                $stmt->execute();
                $categorie_id = $conn->lastInsertId();

        }
    }
    }
?>

<h1>Ajout d'une catégorie</h1>

<div class="formulaire">
    <form method="post" enctype="multipart/form-data">
        <div>
            <label for="libc">Nouvelle catégorie: </label>
            <input type="text" id="libc" name="nom_categorie" required>
        </div>
        <div>
            <label for="categorie">Catégorie parent: </label>
            <select name="cat_idparent">
                <?php
                $stmt = $conn->query("SELECT * from t_d_categorie WHERE Id_Categorie_Parent IS NULL");
                echo "<option></option>";
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch()) {
                        echo "<option value=" . $row['Id_Categorie'] . ">" . $row['Libelle'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <button id="button" type="submit">Ajouter</button>
    </form>
</div>

<?php include 'footer.php'; ?>