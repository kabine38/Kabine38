<?php

require_once("../config/config.php");
require_once("../config/database.php");

if(isset($_SESSION["user_id"])){
    header("Location: dashboard.php");
    exit;
}

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $database = new Database();
    $pdo = $database->connect();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user["password"])){

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];

        header("Location: dashboard.php");
        exit;

    }else{

        $error = "Benutzername oder Passwort falsch.";

    }

}

?>

<!DOCTYPE html>
<html lang="de">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login | KABINE38</title>

<link rel="stylesheet" href="../assets/css/admin.css">

</head>

<body class="login-page">

<div class="login-box">

<h1>KABINE<span>38</span></h1>

<p>Admin Login</p>

<?php if($error): ?>

<div class="error"><?= $error ?></div>

<?php endif; ?>

<form method="POST">

<input
type="text"
name="username"
placeholder="Benutzername"
required>

<input
type="password"
name="password"
placeholder="Passwort"
required>

<button type="submit">
Anmelden
</button>

</form>

</div>

</body>
</html>