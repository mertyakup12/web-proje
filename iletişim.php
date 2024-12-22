<?php
// Hataları görüntüleme ayarları
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısı için gerekli bilgiler
$servername = "localhost";
$username = "root"; // XAMPP varsayılan kullanıcı adı
$password = ""; // XAMPP varsayılan şifre boş
$dbname = "iletisim_formu"; // Veritabanı adı

// Veritabanına bağlan
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Zaman dilimini ayarlayın
date_default_timezone_set('Europe/Istanbul');

// Başarı ve hata mesajları için değişkenler
$success = "";
$error = "";

// Formdan gelen verileri işleyin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $message = htmlspecialchars(trim($_POST['message']));
        $tarih = date('Y-m-d H:i:s');

        // E-posta doğrulaması
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Geçersiz e-posta adresi.";
        } else {
            // SQL sorgusunu hazırlayın
            $stmt = $conn->prepare("INSERT INTO form (name, email, message, tarih) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $message, $tarih);

            if ($stmt->execute()) {
                $success = "Mesajınız başarıyla gönderildi!";
            } else {
                $error = "Hata: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        $error = "Lütfen tüm alanları doldurduğunuzdan emin olun.";
    }
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim - EGE MAKİNA</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-nav .nav-item .nav-link {
            position: relative;
            transition: color 0.3s;
        }
        .navbar-nav .nav-item .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background-color: yellow;
            transform: scaleX(0);
            transition: transform 0.3s ease-in-out;
        }
        .navbar-nav .nav-item .nav-link:hover {
            color: yellow;
        }
        .navbar-nav .nav-item .nav-link:hover::after {
            transform: scaleX(1);
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        header {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .navbar-brand img {
            height: 60px;
        }
        .contact-form,
        .contact-info {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .map-container {
            border: 5px solid #007bff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .map-container iframe {
            width: 100%;
            height: 450px;
            border: none;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
        }
        footer {
            background-color: #343a40;
            color: #fff;
            padding: 20px 0;
            margin-top: 30px;
        }
        footer h5,
        footer p {
            margin: 0;
            padding: 5px 0;
        }
        footer .row {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header class="menu navbar navbar-expand-lg navbar-dark bg-dark">
        <nav class="container">
            <a class="navbar-brand" href="index.html">
                <img src="images/logo.jpg" alt="EGE MAKİNA" style="height: 100px;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Ana Sayfa</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="kurumsalDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Kurumsal</a>
                        <div class="dropdown-menu" aria-labelledby="kurumsalDropdown">
                            <a class="dropdown-item" href="hakkımızda.html">Hakkımızda</a>
                            <a class="dropdown-item" href="vizyonumuz.html">Vizyonumuz</a>
                            <a class="dropdown-item" href="misyonumuz.html">Misyonumuz</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Modeller</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="model_star_gh_230.html">STAR GH 230</a>
                            <a class="dropdown-item" href="model_star_eh_230.html">STAR EH 230</a>
                            <a class="dropdown-item" href="model_diyaframli.html">DİYAFRAMLI</a>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="yedekparça.html">Yedek Parça</a></li>
                    <li class="nav-item"><a class="nav-link" href="iletişim.php">İletişim</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container mt-5">
        <h1 class="text-center mb-4">İletişim</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="contact-info">
                    <h2>İletişim Bilgileri</h2>
                    <p><strong>Adres:</strong> Yeni, 33195. Sk. ALP PLAZA D:13/AB, 33200 Mezitli/Mersin</p>
                    <p><strong>Telefon:</strong> +90 535 551 41 52</p>
                    <p><strong>Email:</strong> sahingeyik33@gmail.com</p>
                    <h3>Bizi Ziyaret Edin</h3>
                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1105.5893041828328!2d34.53798512673106!3d36.759145373835786!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x15278b6cf9ef6781%3A0x987f85edd9777923!2sEGE%20MAK%C4%B0NA!5e0!3m2!1str!2str!4v1720726602092!5m2!1str!2str" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="contact-form">
                    <h2>Bize Ulaşın</h2>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php elseif ($success): ?>
                        <div class="alert alert-success"><?= $success; ?></div>
                    <?php endif; ?>
                    <form id="contactForm" action="iletişim.php" method="POST">
                        <div class="form-group">
                            <label for="name">Adınız</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Adınızı girin" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Adresiniz</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email adresinizi girin" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Mesajınız</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Mesajınızı yazın" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gönder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Hakkımızda</h5>
                    <p>EGE MAKİNA, sektördeki lider firmalardan biri olarak, müşterilerine en kaliteli hizmeti sunmayı hedefler.</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <h5>İletişim</h5>
                    <p>Adres: Yeni, 33195. Sk. ALP PLAZA D:13/AB, 33200 Mezitli/Mersin</p>
                    <p>Telefon: +90 535 551 41 52</p>
                    <p>Email: sahingeyik33@gmail.com</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col text-center">
                    <p>&copy; 2024 EGE MAKİNA. Tüm hakları saklıdır.</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
