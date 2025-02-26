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
    <meta name="keywords" content="Facebook, Profile Guard, sécurité Facebook, trhacknon, hacking, fun, dark">
    <meta name="robots" content="index, follow">

    <title>Facebook Profile Guard | trhacknon</title>

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="shieldicon.png">

    <!-- Google Font Hacker Style -->
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <style>
        /* Background Image */
        body {
            background: url('https://d.top4top.io/p_3343jptff1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #0f0;
            font-family: 'Press Start 2P', cursive;
            text-align: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Dark Overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: -1;
        }

        .navbar {
            background: rgba(0, 255, 0, 0.3);
            border-bottom: 3px solid #0f0;
        }

        .navbar-brand {
            font-size: 14px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0,255,0,0.7);
        }

        .container {
            max-width: 500px;
            margin-top: 20px;
        }

        h1 {
            font-size: 18px;
            color: #0f0;
            text-shadow: 2px 2px 6px #0f0, -2px -2px 6px #f0f;
            animation: glitch 1s infinite alternate;
        }

        @keyframes glitch {
            0% { text-shadow: 2px 2px 6px #0f0, -2px -2px 6px #f0f; }
            100% { text-shadow: -2px -2px 6px #0f0, 2px 2px 6px #f0f; }
        }

        .btn-glitch {
            font-size: 14px;
            background-color: #0f0;
            border: 2px solid #f0f;
            color: black;
            font-weight: bold;
            text-shadow: 1px 1px 5px #000;
            transition: 0.3s;
        }

        .btn-glitch:hover {
            background-color: #f0f;
            color: white;
            box-shadow: 0 0 10px #0f0, 0 0 20px #f0f;
            text-shadow: 0 0 10px white;
        }

        .bg-neon {
            font-size: 12px;
            background: rgba(0, 255, 0, 0.2);
            border: 2px solid #0f0;
            border-radius: 10px;
            padding: 10px;
        }

        .footer {
            padding: 10px;
            font-size: 12px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #0f0;
        }

        /* Audio Controls */
        .audio-control {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: rgba(0, 0, 0, 0.5);
            border: 2px solid #0f0;
            padding: 8px;
            border-radius: 50px;
            box-shadow: 0 0 10px #0f0;
        }

        .audio-control button {
            background: transparent;
            border: none;
            color: #0f0;
            font-size: 16px;
            cursor: pointer;
        }

        .audio-control button:hover {
            color: #f0f;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand" href="#">FacebookShield | trhacknon</a>
</nav>

<!-- Message Session -->
<div class="container">
    <?php if(isset($_SESSION['msg'])): ?>
        <div class="alert alert-warning text-center">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
    <?php endif; ?>
</div>

<!-- Main Content -->
<div class="container">
    <img src="shieldicon.png" alt="Profile Guard" style="width: 120px;">
    <h1>Profile Guard Enabler</h1>

    <div class="bg-neon">
        <p class="text-white">
            ⚠️ First try may not work.<br>
            Login into your device and confirm the request.
        </p>
        <hr>
        <p class="text-white">
            🔐 2FA enabled accounts won't work.<br>
            Turn it off temporarily if needed.
        </p>
    </div>

    <!-- Form -->
    <form method="POST" action="shield.php" class="mt-4 p-3 bg-dark rounded">
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
        <button class="btn btn-glitch btn-block" name="submit">⚡ Submit ⚡</button>
    </form>
</div>

<!-- Footer -->
<div class="footer">
    <p>Created by <b>trhacknon</b> | <a href="https://github.com/trh4ckn0n" target="_blank" style="color:#f0f">GitHub</a></p>
    <p>Inspired by <a href="https://github.com/0xearl/PhpFbGuard" target="_blank" style="color:#0ff">0xearl/PhpFbGuard</a></p>
</div>

<!-- Music Player -->
<audio id="bg-music" loop autoplay>
    <source src="https://l.top4top.io/m_33436xhwn0.mp3" type="audio/mpeg">
</audio>

<!-- Play/Pause Button -->
<div class="audio-control">
    <button onclick="toggleMusic()">🎵</button>
</div>

<script>
function toggleMusic() {
    var audio = document.getElementById("bg-music");
    if (audio.paused) {
        audio.play();
    } else {
        audio.pause();
    }
}
</script>

</body>
</html>
