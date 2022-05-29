<?php
/*******************************************************
        Inserisce nel database il post da pubblicare 
    ********************************************************/
// avvia la sessione
session_start();
    
//verifica se l'utente è loggato
if(!isset($_SESSION["myfootball_user_id"]))
{
    //Vai al login
    header("Location: mylogin.php");
    exit;
}

switch($_POST['type']) {
    case 'text': text(); break;
    default: break;
}

function text(){
    if(!empty($_POST['text'])){

        $conn = mysqli_connect('localhost', 'root', '', 'myfootball') or die(mysqli_error($conn));

        # Costruisco la query
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $text = mysqli_real_escape_string($conn, $_POST['text']);
        $userid = $_SESSION['myfootball_user_id'];
        $userid = mysqli_real_escape_string($conn, $userid);///////

        $query = "INSERT INTO posts(user, content) VALUES('.$userid.', JSON_OBJECT('type', '$type', 'text', '$text'))";


        if(mysqli_query($conn, $query)) {
            echo json_encode(array('ok' => true));
            header("Location: myhome.php");
        }
    } 
    echo json_encode(array('ok' => false));
}
?>