<?php
session_start();
require_once __DIR__ . '/../config.php';
if(isset($_POST['submit'])) {
    if(!empty($_POST['name']) AND !empty($_POST['mail']) AND !empty($_POST['pass'])) {
        // Valeurs
        $name = htmlspecialchars($_POST['name']);
        $mail = htmlspecialchars($_POST['mail']);
        $pass = sha1($_POST['pass']);

        // Creer l'utilisateur
        $insertUser = $db->prepare('INSERT INTO users(name, mail, pass)VALUES(?, ?, ?)');

        // Verifier si le compte existe deja
        $getMail = $db->prepare('SELECT * FROM users WHERE mail = ?');
        $getMail->execute(array($mail));
        $getName = $db->prepare('SELECT * FROM users WHERE name = ?');
        $getName->execute(array($name));
        if($getMail->rowCount() == 0 && $getName->rowCount() == 0) {
            $insertUser->execute(array($name, $mail, $pass));
        }

        // Recuperer son ID
        $getUser = $db->prepare('SELECT * FROM users WHERE mail = ? AND pass = ?');
        $getUser->execute(array($mail, $pass));

        // Creer sa session
        if ($getUser->rowCount() > 0) {
            $_SESSION['name'] = $name;
            $_SESSION['mail'] = $mail;
            $_SESSION['pass'] = $pass;
            $_SESSION['id'] = $getUser->fetch()['id'];
            header('Location: /web');
        }
    }
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
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