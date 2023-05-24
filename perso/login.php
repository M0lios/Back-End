<?php
// Démarrage de la session
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
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

    $stmt = $conn->prepare('SELECT * FROM t_d_user WHERE Login=:login');
    $stmt->bindValue(':login', $login);
    $stmt->execute();
    $use = $stmt->fetch(PDO::FETCH_ASSOC);



    if ($use && password_verify($password, $use['Password'])) {
        $_SESSION['user_id'] = $use['Id_User'];


        //pour récupérer le user type faire une requete sur la table t_d_usertype
        $stmt = $conn->prepare('SELECT * FROM t_d_usertype WHERE Id_UserType=:Id_UserType');
        $stmt->bindValue(':Id_UserType', $use['Id_UserType']);
        $stmt->execute();
        $useType = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['user_type'] = $useType['Libelle'];
        $_SESSION['logged_in'] = true;

        header('Location: index.php');
        exit();
    }
}


?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <h1 class="styleTitre">Connexion</h1>

    <?php if (isset($error_message)) :
    ?>
        <p> <?php echo $error_message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="login">Votre login :</label>
        <input type="login" id="login" name="login" required> <br>
        <label for="password">Votre password :</label>
        <input type="password" id="password" name="password" required> <br>
        <input type="submit" value="Se connecter">
    </form>
    <p>Pas encore inscrit? <a href="inscription.php">S'inscrire</a></p>


    <!-- Footer -->
    <?php include 'footer.php'; ?>