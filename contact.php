<?php include 'includes/db.php'; session_start(); ?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aloqa - Avtomobil Ijarasi</title>

    <!-- SEO -->
    <meta name="description" content="Biz bilan aloqa qiling. Har qanday savol va takliflar uchun formani to‘ldiring.">
    <meta property="og:title" content="Aloqa - Avtomobil Ijarasi Platformasi">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Style -->
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #80deea);
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background-color: #00acc1;
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.3s;
        }

        .navbar .nav-link:hover {
            color: #ffeb3b !important;
            transform: scale(1.05);
        }

        /* Hero section */
        .hero {
            text-align: center;
            padding: 80px 20px 40px;
        }

        .hero h1 {
            font-size: 3em;
            font-weight: bold;
            color: #004d40;
        }

        .hero p {
            font-size: 1.2em;
            color: #006064;
        }

        /* Contact form */
        .contact-form {
            background: white;
            max-width: 600px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .contact-form label {
            font-weight: 600;
        }

        .contact-form button {
            background-color: #00acc1;
            color: white;
            border: none;
            transition: 0.3s ease;
        }

        .contact-form button:hover {
            background-color: #007c91;
        }

        footer {
            background-color: #00acc1;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 60px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="contact.php">Baylanis Bolimi</a>
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon bg-light"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="../index.php">Bas bet</a></li>
               
            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero">
    <h1>Biz benen baylanısıń</h1>
    <p>Soraw yaki pikirlerińizdi jazıń - sizge tez arada juwap beremiz.</p>
</section>

<!-- Contact Form -->
<div class="container">
    <form class="contact-form" action="contact_form.php" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Atınız</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Atınızdı kirgiziń" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Elektron pochta mánzilińiz</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="emailname@gmail.com" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Xabar</label>
            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Xabarıńızdı jazıń" required></textarea>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-lg">Jiberiw</button>
        </div>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Avtomobil Ijarasi. Barlıq huqıqlar qorǵalǵan.</p>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
