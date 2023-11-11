<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>Home</title>
</head>
<body>
    <?php include 'includes/header.php';?>


    <div class="container">
        <h1>ACCUEIL</h1>
        <p><a href="../view/login.php">Login</a></p>
        <p><a href="../view/register.php">Register</a></p>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
