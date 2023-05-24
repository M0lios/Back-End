<?php
// Démarrage de la session
session_start();

if($page != "produit"){
    $_SESSION['surf_id_product'] = 0;
    $_SESSION['surf_return_id_product'] = 0;
}


//Si je veux me déco
if(isset($_GET['logout']) == true):
    session_unset();
	session_destroy();
	header('Location: index.php');
    exit();
endif;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="icon" type="image/png" href="styles/images/logo/gg.jpg"/>
	<link rel="apple-touch-icon" sizes="120x120" href="styles/images/logo/gg.jpg" />
	<link rel="apple-touch-icon" sizes="152x152" href="styles/images/logo/gg.jpg" />
    <link rel="stylesheet" href="styles/css/normalize.css" />
    <link rel="stylesheet" href="styles/css/bootstrap.min.css" />
    <link rel="stylesheet" href="styles/css/bootstrap.css" />
    <link rel="stylesheet" href="styles/css/all.css" />
    <link rel="stylesheet" href="styles/css/<?php echo $page; ?>.css" />
    <title>GreenGarden</title>
</head>
<body>