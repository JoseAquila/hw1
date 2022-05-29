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
      <link rel="stylesheet" href="./style/create_post.css" />
      <script src='./scripts/create_post.js' defer></script>
      <link rel="icon" type="image/png" href="icon.jpg">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>MyFootball - New post</title>
    </head>
    
    <body>
        <header>
            <nav>
                <h1 id="nome">
                    MyFootball
                </h1>
                <div id="links">
                    <a class="button" href="myhome.php">MyHome</a>
                    <a class="button" href="mylogout.php">Logout</a>
                </div>
            </nav>
            <div class="overlay"></div>
        </header>

        <section id="container">
                <div id="newpost">
                    <form class = 'scelta'  autocomplete="off">
                        <h4>Condividi qualcosa su MyHome con i tuoi amici</h4>
                        <div class="think"><button id="think"> Scrivi post</button></div>
                    </form>
                        <div id='pensiero' class='hidden'>
                            <form class = 'invia_post'>
                                    <textarea name="text"></textarea>
                                    <input type="submit" value='Pubblica'>
                            </form>
                        </div>
                    <div id="dispatch_result_success" class="hidden">
                        <span>Post salvato con successo</span><br>
                        <button id="newpost_success">Nuovo post</button><br>
                        <a href="myhome.php">Vai alla home</a>
                    </div>
                    <div id="dispatch_result_fail" class="hidden">
                        <span>Errore nella creazione del post</span><br>
                        <a class="button_fail" href="create_post.php">Riprova</a>
                        <a class="button_fail" href="myhome.php">MyHome</a>
                    </div>
                </div>
        </section>
    </body>
</html>
