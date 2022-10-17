<?php
session_start();

// if(isset($_SESSION['connect'])){
//      header('location: ../');
// }

require('src/connection.php');

if(!empty($_POST['email']) && !empty($_POST['password'])){

    //VARIABLES
    $email = $_POST['email'];
    $password = $_POST['password'];
    $error = 1;

    // CRYPTAGE PASSWORD
    $password = 'aq1'.sha1($password.'1254').'25';

    echo $password;

    // REQUETE CONNEXION (VÉRIF EMAIL & MDP)
    $req = $db->prepare('SELECT * FROM users WHERE email = ?');
    $req->execute(array($email));

     while($user = $req->fetch()){
        if($password == $user['password']){
            $error = 0;
            $_SESSION['connect'] = 1;
            $_SESSION['pseudo'] = $user['pseudo'];
            
            if(isset($_POST['connect'])){
                setcookie('log', $user["secret"], time()+365*24*3600, '/', null, false, true);
            }
            header('location: login.php?success=1');
            exit();
        }
    }
    if($error ==1){        
        header('location: login.php?error=1');
        exit();
    }

}

?> 

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="design/default.css">
</head>
<body>
    <h1>Connexion</h1>
        <p> Connectez-vous pour accéder au contenu, si vous n'êtes pas inscrit, <a href="index.php">inscrivez-vous</a></p>

        <?php 
        if(isset($_GET['error'])){
                echo'<p class="error">Email ou mot de passe non valide</P>';
            }   
        else if(isset($_GET['success'])){
		    echo'<p id="success">Vous êtes maintenant connecté.</p>';
			}
        ?>

        <form action="login.php" method="post">
        <input type="email" name="email" placeholder="e-mail" required="required">
        <input type="password" name="password" placeholder="mot de passe" required="required">
        <p class="connection"><label><input class="connection" type="checkbox" name="connect" checked>Connexion automatique</label></P>
        <button type="submit" >Se connecter</button>
        </form>

</body>
</html>