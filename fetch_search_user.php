<?php

    //avvia la sessione
    session_start();
    //verifica se l'utente è loggato
    if(!isset($_SESSION["myfootball_user_id"]))
    {
        //Vai al login
        header("Location: mylogin.php");
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        exit;
    }

    header('Content-Type: application/json');

    $conn = mysqli_connect('localhost', 'root', '', 'myfootball');

    if(!isset($_POST['username']) || empty($_POST['username'])){
        echo json_encode(array('ok' => false));
        exit;
    }
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $query = "SELECT id FROM users WHERE username = '".$username."';";
    
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if($res){
        $entry = mysqli_fetch_assoc($res);
        $userid = $entry['id'];
        
        $query = "SELECT p.id AS id, p.content AS content, p.time AS time, u.username AS username
        FROM posts AS p LEFT JOIN users AS u ON p.user = u.id
        WHERE p.id IN (
            SELECT l.post
            FROM users AS u
            LEFT JOIN likes AS l
            ON u.id = l.user
            WHERE u.id = $userid
        ) OR 
        p.id IN (
            SELECT DISTINCT c.post
            FROM users AS u
            LEFT JOIN comments AS c
            ON u.id = c.user
            WHERE u.id = $userid
        )";

        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        
        if($res){
            $returnarray = array();
            while($entry = mysqli_fetch_assoc($res)) {
                $returnarray[] = array('postid' => $entry['id'], 'content' => json_decode($entry['content']), 'time' => $entry['time'], 'username' => $entry['username']);
            }
            echo json_encode($returnarray);
        }
        else{
            echo json_encode(array('ok' => false));
        }
    }
    else{
        echo json_encode(array('ok' => false));
        exit;
    }
        
    exit;
?>