<?php
    // Avvia la sessione
    session_start();
    // Verifica che l'utente sia già loggato, in caso positivo va direttamente alla home
    if(isset($_SESSION["myfootball_user_id"])){
        // Vai alla home
        header("Location: myhome.php");
        exit;
    }
    // Se username e password sono stati inviati, cioè sono settati
    if(!empty($_POST["username"]) && !empty($_POST["password"]) )
    {   
        //mi connetto al database  
        $conn = mysqli_connect('localhost', 'root', '', 'myfootball') or die(mysqli_error($conn));

        // Preparazione
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // ID e Username per sessione, password per controllo
        $query = "SELECT id, username, password FROM users WHERE username = '$username'";
        // Esecuzione
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        
        if (mysqli_num_rows($res) > 0) {
            // Ritorna una sola riga se è presente l utente nel db
            $entry = mysqli_fetch_assoc($res);
            if (password_verify($_POST['password'], $entry['password']))
            {          
                // Imposto una sessione dell'utente      
                $_SESSION['myfootball_username'] = $entry['username']; 
                $_SESSION['myfootball_user_id'] = $entry['id'];
                header("Location: myhome.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
        }
        // Se l'utente non è stato trovato o la password non ha passato la verifica
        $error = "Username e/o password errati.";
        
    }
    else if (isset($_POST["username"]) || isset($_POST["password"])) {
        // Se solo uno dei due è impostato
        $error = "Inserisci username e password.";
    }

?>


<html>
    <head>
        <link rel='stylesheet' href='./style/mysignup.css'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="icon.jpg">
    </head>
    <title>MyFootball - Accedi</title>
    <body>
        <main class="login">

            <?php
                // Verifica la presenza di errori
                if(isset($error))
                {
                    echo "<span class='error'>$error</span>";
                }
            ?>
        
            <h1>Benvenuto su MyFootball</h1>
                <form name='login' method='post' autocomplete="off" >
                    <div class="username">
                        <div><input type='text' name='username' placeholder='Username'<?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>  ></div>
                    </div>

                    <div class="password">
                        <div><input type='password' name='password' placeholder='Password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?> ></div>
                    </div>

                    <div>
                        <button type='submit'>Accedi</button>
                    </div>
                </form>
                <div class="box_signup">Non hai un account? <a href="mysignup.php">Registrati</a>
        </main>
    </body>
</html>