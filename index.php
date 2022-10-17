<?php
session_start();

require('src/connection.php');

if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirmPassword'])){

// VARIABLES
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPass = $_POST['confirmPassword'];

    // TEST SI PASSWORD = CONFIRMPASSWORD
    if($password != $confirmPass){
        header('location: ./?error=1&pass=1');
        exit();
    }

    // TEST SI EMAIL UTILISE
    $req = $db->prepare('SELECT count(*) AS numberEmail FROM users WHERE email=?');
    $req->execute(array($email));

    while($emailVerif = $req->fetch()){
        if($emailVerif['numberEmail'] != 0){
            header('location: ./?error=1&email=1');
            exit();
        }
    }

    // HASH
    $secret = sha1($email).time();
    $secret = sha1($secret).time().time();

    // CRYPTAGE PASSWORD
    $password = 'aq1'.sha1($password.'1254').'25'; // aq1, sha1, 1254(salt/grain de sel), 25 : méthode d'encryptage sécurisé

    // ENVOI DE LA REQUETE VERS LA BD
    $req = $db->prepare("INSERT INTO users(pseudo, email, `password`, secret_key) VALUE(?, ?, ?, ?)");
    $value = $req->execute(array($pseudo, $email, $password, $secret));

    header('location: index.php?success=1');
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PHP - espace membre</title>
    <link rel="stylesheet" type="text/css" href="design/default.css">
</head>
<body>
    <h1>Inscription</h1>

    <?php
    if(!isset($_SESSION['connect'])){ ?>

        <p> Bienvenue sur mon site, pour accéder au conntenu, inscrivez-vous, sinon, <a href="login.php">connectez-vous</a></p>
        
        <?php 
            if(isset($_GET['error'])){

                if(isset($_GET['pass'])){
                    echo'<p class="error">Mots de passe non identiques</P>';
                }
                else if(isset($_GET['email'])){
                    echo'<p class="error">Cette adresse email existe déjà</p>';
                }
            } 
            else if(isset($_GET['success'])){
                echo'<p id="success">Votre inscription a bien été prise en compte</p>';
            }
        ?>
        
        <form action="index.php" method="post">
        <input type="text" name="pseudo" placeholder="pseudo" required="required">
        <input type="email" name="email" placeholder="e-mail" required="required">
        <input type="password" name="password" placeholder="mot de passe" required="required">
        <input type="password" name="confirmPassword" placeholder="confirmez votre mot de passe" required="required">
        <button type="submit" >Valider l'inscription</button>
        </form>
    <?php } else{ ?> 
            <p> Bonjour <?php echo$_SESSION['pseudo']; ?></p>
            <p><a href="disconnection.php">Deconnexion</p>

   <?php } ?>
</body>
</html>