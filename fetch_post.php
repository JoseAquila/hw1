<?php 
    /*******************************************************
        Preleva gli ultimi 10 post o tutti, se ce ne sono 
        meno di 10
    ********************************************************/
    // avvia la sessione
    session_start();

    //verifica se l'utente è loggato
    if(!isset($_SESSION["myfootball_user_id"])){
        //Vai al login
        header("Location: mylogin.php");
        exit;
    }

    header('Content-Type: application/json');

    $conn = mysqli_connect('localhost', 'root', '', 'myfootball');

    $userid =  $_SESSION['myfootball_user_id'];
    
    // Prendo tutti i post e tutti i like che l'utente loggato ha messo ai post ritornati
    $query = "SELECT users.id AS userid, users.name AS name, users.surname AS surname, users.username AS username, 
    posts.id AS postid, posts.content AS content, posts.time AS time, posts.nlikes AS nlikes, posts.ncomments AS ncomments,
    EXISTS(SELECT user FROM likes WHERE post = posts.id AND user = $userid) AS liked 
    FROM posts JOIN users ON posts.user = users.id  ORDER BY postid DESC LIMIT 10";

    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    
    $postArray = array();
    while($entry = mysqli_fetch_assoc($res)) {
        // Scorro i risultati ottenuti e creo l'elenco di post
        $time = getTime($entry['time']);
        $postArray[] = array('userid' => $entry['userid'], 'name' => $entry['name'], 'surname' => $entry['surname'], 
                            'username' => $entry['username'],'postid' => $entry['postid'], 'content' => json_decode($entry['content']), 'nlikes' => $entry['nlikes'], 
                            'ncomments' => $entry['ncomments'], 'time' => "$time", 'liked' => $entry['liked']);
    }
    echo json_encode($postArray);
    
    exit;

    function getTime($timestamp) {      
        // Calcola il tempo trascorso dalla pubblicazione del post       
        $old = strtotime($timestamp); 
        $diff = time() - $old;           
        $old = date('d/m/y', $old);

        if ($diff /60 <1) {
            return intval($diff%60)." secondi fa";
        } else if (intval($diff/60) == 1)  {
            return "Un minuto fa";  
        } else if ($diff / 60 < 60) {
            return intval($diff/60)." minuti fa";
        } else if (intval($diff / 3600) == 1) {
            return "Un'ora fa";
        } else if ($diff / 3600 <24) {
            return intval($diff/3600) . " ore fa";
        } else if (intval($diff/86400) == 1) {
            return "Ieri";
        } else if ($diff/86400 < 30) {
            return intval($diff/86400) . " giorni fa";
        } else {
            return $old; 
        }
    }


?>