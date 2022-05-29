<?php 
/*********************************************
    Ritorna un JSON con i risultati dell'API 
**********************************************/


// Se la sessione è scaduta, esco
session_start();
//verifica se l'utente è loggato
if(!isset($_SESSION["myfootball_user_id"]))
{
    //Vai al login
    header("Location: mylogin.php");
    exit;
}

// Imposto l'header della risposta
header('Content-Type: application/json');

// A seconda del tipo scelto, eseguo una funzione diversa
switch($_GET['type']) {
    case 'cat': cat(); break;
    default: break;
}
function cat() {
    $url = 'https://api.thecatapi.com/v1/images/search';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $res=curl_exec($ch);
    curl_close($ch);
    echo $res;
}
?>
