<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=memorama_db", "root", "");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["action"] === "login") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && $user["password"] === $password) {
            $_SESSION["username"] = $username;
            header("Location: game.php");
            exit();
        } else {
            $error = "Credenciales incorrectas.";
        }

    } elseif ($_POST["action"] === "register") {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        if ($username === "" || $password === "") {
            $error = "No dejes campos vacíos.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->rowCount() > 0) {
                $error = "Ese usuario ya existe.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->execute([$username, $password]);
                $_SESSION["username"] = $username;
                header("Location: game.php");
                exit();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Memorama Login</title>
    <style>
        body {
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            width: 300px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        h2 {
            margin-bottom: 20px;
            color: #ff4e50;
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #ff4e50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background: #ff6a6a;
        }
        .tabs {
            display: flex;
            margin-bottom: 16px;
        }
        .tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            background: #eee;
            cursor: pointer;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
        }
        .tab.active {
            background: #fff;
            color: #ff4e50;
            border-bottom: 2px solid #ff4e50;
        }
        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Memorama</h2>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <div class="tabs">
        <div class="tab active" onclick="showTab('login')">Iniciar sesión</div>
        <div class="tab" onclick="showTab('register')">Registrarse</div>
    </div>

    <form method="post" id="login" class="form-section active">
        <input type="hidden" name="action" value="login">
        <input type="text" name="username" placeholder="Usuario">
        <input type="password" name="password" placeholder="Contraseña">
        <input type="submit" value="Ingresar">
    </form>

    <form method="post" id="register" class="form-section">
        <input type="hidden" name="action" value="register">
        <input type="text" name="username" placeholder="Nuevo usuario">
        <input type="password" name="password" placeholder="Contraseña">
        <input type="submit" value="Registrarse">
    </form>
</div>

<script>
    function showTab(tabId) {
        document.querySelectorAll(".tab").forEach(el => el.classList.remove("active"));
        document.querySelectorAll(".form-section").forEach(el => el.classList.remove("active"));
        document.getElementById(tabId).classList.add("active");
        event.target.classList.add("active");
    }
</script>
</body>
</html>
