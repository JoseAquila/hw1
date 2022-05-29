<?php
    // avvia la sessione
    session_start();
    //verifica se l'utente è loggato
    if(!isset($_SESSION["myfootball_user_id"])){
        //Vai al login
        header("Location: mylogin.php");
        exit;
    }
?>

<html>

    <?php 
        $username = $_SESSION["myfootball_username"];
        $conn = mysqli_connect('localhost', 'root', '', 'myfootball');
        $query = "SELECT * FROM users WHERE username = '$username' ";
        $res_1 = mysqli_query($conn, $query);
        $userinfo = mysqli_fetch_assoc($res_1);   
    ?>

  <head>
      <title>MyFootball - MyProfile</title>
      <link rel="stylesheet" href="./style/myprofile.css" />
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
                    <a class="button" href = "myhome.php">Home</a>
                    <a class="button" href="mylogout.php">Logout</a>
                </div>
            </nav>
            <div class="overlay"></div>
        </header>


        <div class="box">

            <div class="info">
                <h3>MyFootball - Profilo Utente</h3>
            </div>

            <section class="area">


                <div class="area1">
                    <h4>Name: <span><?php echo $userinfo['name'] ?></span></h4>
                    <h4>Surname: <span><?php echo $userinfo['surname'] ?></span></h4>
                    <h4>Username: <span><?php echo $userinfo['username'] ?></span></h4>
                    <h4>Email: <span><?php echo $userinfo['email'] ?></span></h4>
                    <h4>Post pubblicati: <span class="count"><?php echo $userinfo['numeroPost'] ?></span> </h4>
                </div>

            </section>

        </div>

  </body>
</html>


<?php mysqli_close($conn); ?>