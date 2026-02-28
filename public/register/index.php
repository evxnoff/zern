<?php
session_start();
require_once __DIR__ . '/../config.php';

if(isset($_POST['submit'])) {
    if(!empty($_POST['name']) && !empty($_POST['mail']) && !empty($_POST['pass'])) {
        $name = htmlspecialchars($_POST['name']);
        $mail = htmlspecialchars($_POST['mail']);
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

        // Vérifier si le compte existe déjà
        $getMail = $db->prepare('SELECT * FROM users WHERE mail = ?');
        $getMail->execute([$mail]);
        $getName = $db->prepare('SELECT * FROM users WHERE name = ?');
        $getName->execute([$name]);

        if($getMail->rowCount() > 0 || $getName->rowCount() > 0) {
            $error = 'Compte déjà existant.';
        } else {
            // Créer l'utilisateur
            $insertUser = $db->prepare('INSERT INTO users(name, mail, pass) VALUES(?, ?, ?)');
            $insertUser->execute([$name, $mail, $pass]);

            // Récupérer l'utilisateur nouvellement créé
            $getUser = $db->prepare('SELECT * FROM users WHERE mail = ?');
            $getUser->execute([$mail]);
            $user = $getUser->fetch();

            if ($user) {
                $_SESSION['name'] = $user['name'];
                $_SESSION['mail'] = $user['mail'];
                $_SESSION['id'] = $user['id'];
                header('Location: /web');
                exit;
            }
        }
    } else {
        $error = 'Veuillez remplir tous les champs.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziven</title>
</head>
<body>
    <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form action="" method="post">
        <input type="text" name="name" placeholder="Name" required>
        <br/>
        <input type="email" name="mail" placeholder="E-Mail" required>
        <br/>
        <input type="password" name="pass" placeholder="Password" required>
        <br/>
        <input type="submit" name="submit">
    </form>
</body>
</html>