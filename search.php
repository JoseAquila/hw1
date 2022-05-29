<?php
    // avvia la sessione
    session_start();
    //verifica se l'utente è loggato
    if(!isset($_SESSION["myfootball_user_id"]))
    {
        //Vai al login
        header("Location: mylogin.php");
        exit;
    }
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet' href='./style/search.css'>
        <script src="./scripts/search.js" defer></script>
        <link rel="icon" type="image/png" href="icon.jpg">
    </head>
    
    <title>MyFootball - cerca</title>

    <body>
        <header>
            <nav>
                <h1 id="nome">
                    MyFootball
                </h1>
                <div id="links">
                    <a class="button" href = "myhome.php">Home</a>
                    <a class="button" href="mylogout.php">Logout</a>
                </div>
            </nav>
            <div class="overlay"></div>
        </header>

        <div class="container">

            <div class="search-container">
                <input type="text" class="search_bar" id="username_search" placeholder="Cerca per username" name="search" onkeyup="checkUsername()">
                <button class="search-button" id="search_button" onclick="fetchPosts()" value='Cerca' disabled>Cerca</button>
            </div>

            <div id="posts" class="posts-container"></div>
        </div>
    </body>
</html>