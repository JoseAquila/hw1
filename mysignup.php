<?php
    // Avvia la sessione
    session_start();
    // Verifica che l'utente sia già loggato, in caso positivo va direttamente alla home
    if(isset($_SESSION["myfootball_user_id"])){
        // Vai alla home
        header("Location: myhome.php");
        exit;
    }

    // Verifica l'esistenza di dati POST
    if (    !empty($_POST["username"]) && !empty($_POST["password"]) && 
            !empty($_POST["email"]) && !empty($_POST["name"]) && 
            !empty($_POST["surname"]) && !empty($_POST["confirm_password"]) && !empty($_POST["allow"])  )
    {
        $error = array();
        $conn = mysqli_connect('localhost', 'root', '', 'myfootball') or die(mysqli_error($conn));

        # USERNAME
        // Controlla che l'username rispetti il pattern specificato
        if(!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $_POST['username'])) {
            $error[] = "Username non valido";
        } else {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            // Cerco se l'username esiste già 
            $query = "SELECT username FROM users WHERE username = '$username'";
            $res = mysqli_query($conn, $query);
            if (mysqli_num_rows($res) > 0) {
                $error[] = "Username già utilizzato";
            }
        }
        # PASSWORD
        if (strlen($_POST["password"]) < 8) {
            $error[] = "Caratteri password insufficienti";
        } 
        # CONFERMA PASSWORD
        if (strcmp($_POST["password"], $_POST["confirm_password"]) != 0) {
            $error[] = "Le password non coincidono";
        }
        # EMAIL
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = "Email non valida";
        } else {
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
            $res = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
            if (mysqli_num_rows($res) > 0) {
                $error[] = "Email già utilizzata";
            }
        }
        
        # REGISTRAZIONE NEL DATABASE
        if (count($error) == 0) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $surname = mysqli_real_escape_string($conn, $_POST['surname']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $password = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO users(name, surname, username, email, password) VALUES('$name', '$surname', '$username', '$email', '$password')";
            
            if (mysqli_query($conn, $query)) {
                $_SESSION["myfootball_username"] = $_POST["username"];
                $_SESSION["myfootball_user_id"] = mysqli_insert_id($conn);
                mysqli_close($conn);
                header("Location: myhome.php");
                exit;
            } else {
                $error[] = "Errore di connessione al Database";
            }
        }

        mysqli_close($conn);
    }
    else if (isset($_POST["username"])) {
        $error = array("Riempi tutti i campi");
    }



?>

<html>

    <head>
        <link rel='stylesheet' href='./style/mysignup.css'>
        <script src='./scripts/mysignup.js' defer></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="icon.jpg">
        <title>MyFootball - Iscriviti</title>
    </head>

    <body>
    <main class="signup">
        <section class='signup'>
            <h1>Inserisci i tuoi dati</h1>
            <form name='signup' method='post' autocomplete="off">
                <div class="names">
                    <div class="name">
                        <div><label for='name'>Nome</label></div>
                        <!-- Se il submit non va a buon fine, il server reindirizza su questa stessa pagina, quindi va ricaricata con 
                                i valori precedentemente inseriti --> 
                        <div><input type='text' name='name' <?php if(isset($_POST["name"])){echo "value=".$_POST["name"];} ?> ></div><!-- questi controlli perche se ad esempio nel form ho qualche dato non inserito o non correttamente inserito mi faccio stampare il valore precedentemente inserito, motivo per cui metto tuto nella stessa pagina-->
                        <span>Nome non valido</span>
                    </div>

                    <div class="surname">
                        <div><label for='surname'>Cognome</label></div>
                        <div><input type='text' name='surname' <?php if(isset($_POST["surname"])){echo "value=".$_POST["surname"];} ?> ></div>
                        <span>Cognome non valido</span>
                    </div>
                </div>

                <div class="username">
                    <div><label for='username'>Nome utente</label></div>
                    <div><input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?> ></div>
                    <span>Username non valido</span>
                </div>

                <div class="email">
                    <div><label for='email'>Email</label></div>
                    <div><input type='text' name='email' <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];} ?> ></div>
                    <span>Email non valida</span>
                </div>

                <div class="password">
                    <div><label for='password'>Password</label></div>
                    <div><input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?> ></div>
                    <span>Inserisci almeno 8 caratteri</span>
                </div>

                <div class="confirm_password">
                    <div><label for='confirm_password'>Conferma Password</label></div>
                    <div><input type='password' name='confirm_password' <?php if(isset($_POST["confirm_password"])){echo "value=".$_POST["confirm_password"];} ?> ></div>
                    <span>Le password non coincidono</span>
                </div>

                <div class="allow"> 
                    <div><input type='checkbox' name='allow' value="1" <?php if(isset($_POST["allow"])){echo $_POST["allow"] ? "checked" : "";} ?> ></div>
                    <div><label for='allow'>Acconsento al trattamento dei dati personali</label></div>
                    <span>Accetta la condizione</span>
                </div>
                <div class="submit">
                    <input type="submit"  value="Registrati" id="submit" disabled>
                </div>
            </form>
            
                <div class="box_signin">
                    Hai già un account? <a href="mylogin.php">Accedi</a>
                </div>
            
        </section>
    </main>
    </body>
</html>