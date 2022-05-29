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
      <title>MyFootball - Home</title>
      <link rel="stylesheet" href="./style/myhome.css" />
      <script src='./scripts/myhome.js' defer></script>
      <script src='./scripts/api.js' defer></script>
      <link rel="icon" type="image/png" href="icon.jpg">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>

  <body>
        <header>
            <nav>
                <h1 id="nome">
                    MyFootball
                </h1>
                <div id="links">
                    <a class="button" href = "myprofile.php">Profilo</a>
                    <a class="button" href="search.php">Cerca i tuoi amici</a>
                    <a class="button" href="mylogout.php">Logout</a>
                </div>
            </nav>
            <div class="overlay"></div>
        </header>

        <div>
            <img id='casetta' src="./images/home.png"/>
            <div id='homepage'>
                Homepage
            </div>
        </div>

        <div id="modal" class="hidden"> <!--  modale per persone a cui piace    -->
            <div class="window">
                <button id="modal_close">Chiudi</button>
                <div class="modal_desc"></div>
                <div id="modal_place">
                </div>
            </div>
        </div>

        <section id='post'>
            
         <a class="button" href = "create_post.php">Nuovo post</a>

        </section>

        <section id="feed"> 
             <!-- qui si caricheranno i post-->
                <template id="post_template">

                    <article class="post">

                        <div class="userinfo">
                            <div class="names">
                                <a>
                                    <div class="name"></div>
                                    <div class="username"></div>
                                </a>
                            </div>
                            <div class="time"></div>
                        </div>

                        <div class="text"></div>

                        <div class="actions">
                            <div class="like"><span></span></div>
                            <div class="comment"><span></span></div> 
                        </div>

                        <div class="comments">
                            <div class="past_messages"></div>

                            <div class="comment_form">
                                <form autocomplete="off">
                                    <input type="text" name="comment" maxlength="254" placeholder="Aggiungi un commento..." required="required">
                                    <input type="submit">
                                    <input type="hidden" name="postid">
                                </form>
                            </div>
                        </div>
                    </article>
                </template>
        </section>

        <div id="apiBox">
              <h2 id="clicca">CLICCA QUI!</h2>
        </div>

    
    <footer>
      <p>
      Josè Luis Aquila<br>
      matricola: 1000001097
      </p>
    </footer>
  </article>
  </body>
</html>
