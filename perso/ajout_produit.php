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
        isset($_POST['nom_court']) && isset($_POST['nom_long']) && isset($_POST['reference'])
        && isset($_POST['prix'])
        && isset($_POST['tva']) && isset($_POST['categorie']) && isset($_FILES['photo']) && isset($_POST['fournisseur'])
    ) {
        $noml_produit = $_POST['nom_long'];
        $nomc_produit = $_POST['nom_court'];
        $reference_produit = $_POST['reference'];
        $prix_produit = $_POST['prix'];
        $tva = $_POST['tva'];
        $photo_produit = $_FILES['photo'];
        


        $stmt = $conn->prepare("SELECT * from t_d_categorie where Libelle=:lib");
        $stmt->bindValue(':lib', $_POST['categorie']);
        $stmt->execute();
        $categorie =  $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT * from t_d_fournisseur where Nom_Fournisseur=:nomfour");
        $stmt->bindValue(':nomfour', $_POST['fournisseur']);
        $stmt->execute();
        $fournisseur =  $stmt->fetch(PDO::FETCH_ASSOC);
        

        if (upload_file($photo_produit, "img/")) {
            // Ajout du produit à la base de données
            try {
                $stmt = $conn->prepare("INSERT INTO t_d_produit (Nom_Long, Nom_court
					, Ref_fournisseur, Prix_Achat,
					 Id_Fournisseur, Id_Categorie,
					 Photo,Taux_TVA)

                
					 VALUES (:nomlon,:nomcourt, :reference, :prix,:nomfour,:cat, :photo, :tva)");
                $stmt->bindValue(':nomlon', $noml_produit);
                $stmt->bindValue(':nomcourt', $nomc_produit);
                $stmt->bindValue(':reference', $reference_produit);
                $stmt->bindValue(':prix', $prix_produit);
                $stmt->bindValue(':tva', $tva);
                $stmt->bindValue(':cat', $categorie['Id_Categorie']);
                $stmt->bindValue(':photo', $photo_produit['name']);
                $stmt->bindValue(':nomfour', $fournisseur['Id_Fournisseur']);
                $stmt->execute();
                $product_id = $conn->lastInsertId();
                header("Location: consult_produit.php?id=$product_id");
                exit();
            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                exit();
            }
        } else {
            echo "Le fichier uploadé n'est pas une image";
        }
    } else {
        header('Location: index.php');
        exit();
    }
}

?>

    <?php
  
    if (isset($error)) : ?>
        <p style="color: red"><?= $error ?></p>
    <?php endif ?>

    <?php if (isset($success)) : ?>
        <p style="color: green"><?= $success ?></p>
    <?php endif ?>

    <h1>Ajout d'un produit</h1>

    <form method="post" enctype="multipart/form-data">
        <div>
            <label for="nomcourt">Nom :</label>
            <input type="text" id="nomc" name="nom_court" required>
        </div>

        <br>

        <div>
            <label for="nomlong">Nom long (description):</label>
            <input type="text" id="noml" name="nom_long" required>
        </div>

        <br>

        <div>
            <label for="reference">Référence Fournisseur:</label>
            <input type="text" id="reference" name="reference" required>
        </div>

<br>

        <div>
            <label for="prix">Prix HT Fournisseur:</label>
            <input type="number" id="prix" name="prix" min="0" step="0.01" required>
        </div>

        <br>

        <div>
            <label for="tva">Taux TVA:</label>
            <input type="number" id="tva" name="tva" min="0" step="0.01" required>
        </div>
        <div>
<br>
        <label for="categorie">Nom catégorie:</label>
    
        <select name="categorie">
            <?php
            $stmt = $conn->query("SELECT * from t_d_categorie");

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch()) {
                    echo  "<option>" . $row['Libelle'] . "</option>";
                }
            }
            ?>

        </select>
    </div>
        <br>
        <div>

<label for="fournisseur">Nom fournisseur:</label>

        <select name="fournisseur">
            <?php
            $stmt = $conn->query("SELECT * from t_d_fournisseur");

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch()) {
                    echo "<option>" . $row['Nom_Fournisseur'] . "</option>";
                }
            }
            ?>

        </select>
    </div>
    <br>
        <div>
            <label for="photo">Photo :</label>
            <input type="file" id="photo" name="photo" required>
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