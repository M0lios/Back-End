<?php
// Fonction pour échapper les caractères spéciaux d'une chaîne
function escape_string($str)
{
    return htmlentities(trim($str), ENT_QUOTES, "UTF-8");
}

// Fonction pour uploader un fichier sur le serveur
function upload_file($file, $target_dir)
{
    $target_file = $target_dir . basename($file["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Vérifier si le fichier est une image
    if (isset($_POST["submit"])) {
        $check = getimagesize($file["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }

    // Vérifier si le fichier existe déjà
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }

    // Vérifier la taille du fichier
    if ($file["size"] > 3000000) {
        $uploadOk = 0;
    }

    // Autoriser certains formats de fichier
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    // Vérifier si $uploadOk est à 0 à cause d'une erreur
    if ($uploadOk == 0) {
        return false;
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return true;
        } else {
            return false;
        }
    }
}

// Fonction pour télécharger un fichier du serveur
function download_file($file_path)
{
    // Vérifier si le fichier existe
    if (file_exists($file_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_path));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        return false;
    }
}

// Fonction pour générer un PDF
// function generate_pdf($html, $filename) {
//     require_once('html2pdf/vendor/autoload.php');
//     try {
//         $pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
//         $pdf->writeHTML($html);
//         $pdf->output($filename, 'D');
//     } catch (Html2PdfException $e) {
//         $pdf->clean();
//         $formatter = new ExceptionFormatter($e);
//         echo $formatter->getHtmlMessage();
//     }
// }

// Fonction pour envoyer un mail
function send_mail($to, $subject, $message, $headers)
{
    return mail($to, $subject, $message, $headers);
}

function afficher_date_fr($datetime_mysql)
{
    $timestamp = strtotime($datetime_mysql);
    $date_fr = date('d/m/Y H:i', $timestamp);
    return $date_fr;
}

function calcul_total_ttc($prix_ttc, $remise)
{
    $montant_remise = $prix_ttc * $remise / 100;
    $prix_ttc_final = $prix_ttc - $montant_remise;
    return $prix_ttc_final;
}


//   Explication de la fonction appel_api_get_json:
//   La fonction curl_init() initialise une session cURL.
//   La fonction curl_setopt() configure les options de la session cURL. Ici, nous définissons l'URL de l'API à appeler (CURLOPT_URL) et nous demandons à cURL de renvoyer la réponse sous forme de chaîne de caractères (CURLOPT_RETURNTRANSFER).
//   La fonction curl_exec() exécute la requête et renvoie la réponse de l'API sous forme de chaîne de caractères.
//   La fonction curl_close() ferme la session cURL.
//   Enfin, la fonction json_decode() convertit la réponse JSON en un tableau PHP.
function appel_api_get_json($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resultat = curl_exec($ch);
    curl_close($ch);
    return json_decode($resultat, true);
}
//   Vous pouvez utiliser cette fonction en appelant appel_api_get_json() avec l'URL de l'API en argument. Par exemple :
//   $url = 'https://api.example.com/data.json';
//   $resultat = appel_api_get_json($url);
//   var_dump($resultat);  


// Explication de la fonction appel_api_post_json:
// La fonction curl_init() initialise une session cURL.
// La fonction json_encode() convertit les données en JSON.
// La fonction curl_setopt() configure les options de la session cURL. 
//    Ici, nous définissons l'URL de l'API à appeler (CURLOPT_URL), 
//    nous indiquons que nous envoyons des données en POST (CURLOPT_POST), 
//    nous spécifions les données à envoyer (CURLOPT_POSTFIELDS), 
//    nous demandons à cURL de renvoyer la réponse sous forme de chaîne de caractères (CURLOPT_RETURNTRANSFER) et nous indiquons que nous envoyons des données en JSON (CURLOPT_HTTPHEADER).
// La fonction curl_exec() exécute la requête et renvoie la réponse de l'API sous forme de chaîne de caractères.
// La fonction curl_close() ferme la session cURL.
// Enfin, la fonction json_decode() convertit la réponse JSON en un tableau PHP.
function appel_api_post_json($url, $donnees)
{
    $ch = curl_init();
    $donnees_json = json_encode($donnees);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $donnees_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $resultat = curl_exec($ch);
    curl_close($ch);
    return json_decode($resultat, true);
}

//   Vous pouvez utiliser cette fonction en appelant appel_api_post_json() avec l'URL de l'API et les données à envoyer en argument. Par exemple :
// $url = 'https://api.example.com/data.json';
// $donnees = array('nom' => 'John', 'age' => 30);
// $resultat = appel_api_post_json($url, $donnees);
// var_dump($resultat);
// Notez que cette fonction ne gère pas les erreurs de requête (par exemple, si l'API renvoie une erreur HTTP). Pour une utilisation en production, il est recommandé d'ajouter une gestion d'erreur robuste.
