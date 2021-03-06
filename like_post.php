<?php 
    /*******************************************************
        Aggiunge un like dall'utente loggato
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

    header('Content-Type: application/json');


    $conn = mysqli_connect('localhost', 'root', '', 'myfootball');

    $userid =  $_SESSION['myfootball_user_id'];
    $postid = mysqli_real_escape_string($conn, $_POST["postid"]);

    // Aggiungo un'entry ai like
    $in_query = "INSERT INTO likes(user, post) VALUES($userid, $postid)";
    // Si attiva il trigger che aggiorna il numero di likes
    // Prendo il nuovo numero di like
    $out_query = "SELECT nlikes FROM posts WHERE id = $postid";

    $res = mysqli_query($conn, $in_query) or die ('Unable to execute query. '. mysqli_error($conn));

    if ($res) {

        $res = mysqli_query($conn, $out_query);

        if (mysqli_num_rows($res) > 0) {

            $entry = mysqli_fetch_assoc($res);

            $returndata = array('ok' => true, 'nlikes' => $entry['nlikes']);

            echo json_encode($returndata);

            mysqli_close($conn);

            exit;

        }
    }

    mysqli_close($conn);
    echo json_encode(array('ok' => false));
?>