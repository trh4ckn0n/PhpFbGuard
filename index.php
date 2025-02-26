<?php 
session_start();
error_reporting(0);
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="trhacknon">
    <meta name="description" content="Facebook Profile Guard - Activez la protection de votre photo de profil Facebook.">
    <meta name="keywords" content="Facebook, Profile Guard, sécurité Facebook, trhacknon">
    <meta name="robots" content="index, follow">

    <!-- OpenGraph (Facebook / Twitter Preview) -->
    <meta property="og:title" content="Facebook Profile Guard - trhacknon">
    <meta property="og:description" content="Activez la protection de votre photo de profil Facebook avec notre outil en ligne.">
    <meta property="og:image" content="https://trkn-fb-guardn.glitch.me/shieldicon.png">
    <meta property="og:url" content="https://trkn-fb-guardn.glitch.me/">
    <meta name="twitter:card" content="summary_large_image">

    <title>Facebook Profile Guard | trhacknon</title>

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gZ9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="shieldicon.png">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
        body {
            background-color: #1a1a1a;
            color: white;
        }
        .navbar, .footer {
            background-color: #222;
        }
        .navbar-brand {
            font-weight: bold;
            color: #0f9d58 !important;
        }
        .footer {
            padding: 15px;
            text-align: center;
        }
        .bg-info {
            background-color: #0f9d58 !important;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="#">FacebookShield | trhacknon</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="https://github.com/trh4ckn0n" target="_blank">GitHub</a></li>
    </ul>
  </div>
</nav>

<!-- Message Session -->
<div class="container mt-3">
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-warning text-center">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
    <?php endif; ?>
</div>

<!-- Main Content -->
<div class="container">
    <br>
    <center>
        <img src="shieldicon.png" alt="Profile Guard" style="width: 150px;">
        <h1 class="mt-3">Profile Guard Enabler</h1>
    </center>

    <div class="w-75 bg-info p-3 m-auto text-center">
        <p class="display-4 font-weight-bold text-white">FAQ</p>
        <p class="text-white font-weight-bold">
            First try may not work.<br>
            To fix this problem, login into your device and confirm the request.
        </p>
        <hr>
        <p class="text-white font-weight-bold">
            Accounts with 2FA enabled won't work.<br>
            Turn 2FA off and then on again if needed.
        </p>
    </div>

    <!-- Form -->
    <form method="POST" action="shield.php" class="mt-4 p-4 bg-dark rounded">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
        </div>
        <div class="form-group">
            <label for="select">Action</label>
            <select id="select" class="form-control" name="active">
                <option value="true">Enable</option>
                <option value="false">Disable</option>
            </select>
        </div>
        <button class="btn btn-success btn-block" name="submit">Submit</button>
    </form>
</div>

<!-- Footer -->
<div class="footer mt-5">
    <p>Created by <b>trhacknon</b> | <a href="https://github.com/trh4ckn0n" target="_blank">GitHub</a></p>
</div>

</body>
</html>
