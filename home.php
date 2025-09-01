<?php
include 'config.php';
session_start();

// Vérification de la session
if(!isset($_SESSION['usermail'])) {
    header("location: index.php");
    exit();
}

// Récupération du filtre ville
$search_city = $_GET['search_city'] ?? '';
if($search_city) {
    $rooms = mysqli_query($conn, "SELECT * FROM room WHERE city = '".mysqli_real_escape_string($conn, $search_city)."'");
}

// Traitement du formulaire de réservation
if (isset($_POST['guestdetailsubmit'])) {
    // Récupération et sécurisation des données du formulaire
    $Name = mysqli_real_escape_string($conn, $_POST['Name']);
    $Email = mysqli_real_escape_string($conn, $_POST['Email']);
    $Country = mysqli_real_escape_string($conn, $_POST['Country']);
    $Phone = mysqli_real_escape_string($conn, $_POST['Phone']);
    $RoomType = mysqli_real_escape_string($conn, $_POST['RoomType']);
    $Bed = mysqli_real_escape_string($conn, $_POST['Bed']);
    $NoofRoom = (int)$_POST['NoofRoom'];
    $Meal = mysqli_real_escape_string($conn, $_POST['Meal']);
    $cin = mysqli_real_escape_string($conn, $_POST['cin']);
    $cout = mysqli_real_escape_string($conn, $_POST['cout']);
    $num_persons = (int)$_POST['num_persons'];
    
    // Calcul du nombre de jours
    $nodays = (strtotime($cout) - strtotime($cin)) / (60 * 60 * 24);
    
    // Vérification des champs obligatoires
    if(empty($Name) || empty($Email) || empty($Country) || empty($Phone) || empty($RoomType) || empty($Bed) || empty($NoofRoom) || empty($Meal) || empty($cin) || empty($cout) || empty($num_persons)) {
        echo "<script>swal('Error', 'Please fill all required details', 'error');</script>";
    } else {
        $sta = "NotConfirm";
        
        // Requête d'insertion adaptée aux colonnes de votre table
        $sql = "INSERT INTO roombook (
                    Name, 
                    Email, 
                    Country, 
                    Phone, 
                    RoomType, 
                    Bed, 
                    Meal, 
                    NoofRoom, 
                    cin, 
                    cout, 
                    nodays, 
                    stat, 
                    num_persons
                ) VALUES (
                    '$Name',
                    '$Email',
                    '$Country',
                    '$Phone',
                    '$RoomType',
                    '$Bed',
                    '$Meal',
                    '$NoofRoom',
                    '$cin',
                    '$cout',
                    '$nodays',
                    '$sta',
                    '$num_persons'
                )";
        
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>
                swal('Success', 'Reservation successful', 'success');
                setTimeout(() => document.getElementById('guestdetailpanel').style.display = 'none', 1500);
            </script>";
        } else {
            echo "<script>swal('Error', '".mysqli_error($conn)."', 'error');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/home.css">
    <title>Hotel</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Sweet Alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./admin/css/roombook.css">
    <style>
        #guestdetailpanel {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(16, 15, 15, 0.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .guestdetailpanelform {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            width: 95%;
            max-width: 1000px;
            box-shadow: 0 8px 15px rgba(0,0,0,0.3);
            max-height: 95vh;
            overflow-y: auto;
        }

        .guestdetailpanelform .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #0a2463;
        }

        .guestdetailpanelform .head h3 {
            margin: 0;
            color: #0a2463;
            font-size: 1.8rem;
        }

        .guestdetailpanelform .head i {
            cursor: pointer;
            font-size: 1.5rem;
            color: #666;
            transition: color 0.3s;
            position:center;

        }

        .guestdetailpanelform .head i:hover {
            color: #ff0000;
        }

        .guestdetailpanelform .middle {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .guestinfo, .reservationinfo {
            flex: 1;
            min-width: 300px;
        }

        .guestdetailpanelform h4 {
            color: #0a2463;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .guestdetailpanelform input,
        .guestdetailpanelform select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        .guestdetailpanelform input:focus,
        .guestdetailpanelform select:focus {
            outline: none;
            border-color: #3e92cc;
            box-shadow: 0 0 5px rgba(62, 146, 204, 0.5);
        }

        .line {
            width: 1px;
            background: #ddd;
            margin: 0 10px;
        }

        .datesection {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .datesection span {
            flex: 1;
            min-width: 150px;
        }

        .datesection label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }

        .guestdetailpanelform .footer {
            text-align: right;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .guestdetailpanelform .footer button {
            padding: 12px 25px;
            font-size: 1.1rem;
        }
        .reserve-btn {
    background-color: #28a745;
    color: #fff;
    border: none;
    padding: 12px 35px;
    font-size: 1.1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.reserve-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

        /* SECTION FACILITIES */
        #thirdsection {
            padding: 80px 5%;
            background: linear-gradient(135deg, #0a2463 0%, #3e92cc 100%);
            color: white;
            text-align: center;
        }
        
        .head {
            font-size: 3rem;
            margin-bottom: 50px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            display: inline-block;
        }
        
        .head::before, .head::after {
            content: "★";
            margin: 0 15px;
            color: #e1cd58ff;
        }
        
        .facility {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .facility .box {
            height: 380px;
            width: 300px;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transition: all 0.4s ease;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }
        
        .facility .box:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
            border-color: #ffd700;
        }
        
        .facility .box h2 {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            color: white;
            text-align: center;
            font-size: 1.8rem;
            z-index: 2;
            transition: all 0.3s ease;
        }
        
        .facility .box:hover h2 {
            background: linear-gradient(transparent, rgba(0, 64, 255, 0.8));
            padding-bottom: 30px;
        }
        
        /* Images avec versioning pour éviter le cache */
        .box.swimming-pool {
            background: url('./image/swim.JPG'); 
        }
        
        .box.spa {
            background: url('./image/spa.png') center/cover no-repeat;
        }
        
        .box.restaurant {
            background: url('./image/food.JPG') center/cover no-repeat;
        }
        
        .box.gym {
            background: url('./image/gym.jpg') center/cover no-repeat;
        }
        
        /* Overlay effect */
        .box::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 1;
        }
        
        .box:hover::after {
            background: rgba(0, 64, 255, 0.2);
        }
        
        /* Style barre de recherche */
        .search-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            background: #cee1f4;
            border-radius: 8px;
        }
        .search-box {
            display: flex;
            gap: 10px;
        }
        .search-box select {
            padding: 10px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        /* Style pour les résultats de recherche */
        .search-results {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .room-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background: white;
            transition: transform 0.3s;
        }
        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .ourroom {
             text-align: center;
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 1200px) {
            .facility {
                gap: 20px;
            }
            
            .facility .box {
                width: 280px;
                height: 350px;
            }
        }
        
        @media (max-width: 992px) {
            .facility {
                gap: 15px;
            }
            
            .facility .box {
                width: 45%;
                height: 320px;
            }
            
            .head {
                font-size: 2.5rem;
            }
            
            .guestdetailpanelform .middle {
                flex-direction: column;
            }
            
            .line {
                width: 100%;
                height: 1px;
                margin: 10px 0;
            }
        }
        
        @media (max-width: 768px) {
            #thirdsection {
                padding: 60px 5%;
            }
            
            .facility .box {
                width: 100%;
                max-width: 400px;
                height: 300px;
            }
            
            .head {
                font-size: 2.2rem;
            }
            
            .guestdetailpanelform {
                padding: 20px;
                width: 95%;
            }
        }
        
        @media (max-width: 480px) {
            .head {
                font-size: 1.8rem;
            }
            
            .facility .box h2 {
                font-size: 1.5rem;
                padding: 15px;
            }
            
            .guestinfo, .reservationinfo {
                min-width: 100%;
            }
            
            .datesection span {
                min-width: 100%;
            }
        }
    </style>
</head>

<body>
  <nav>
    <div class="logo">
        <img src="./image/airlogo.avif" alt="Vaya Logo" class="logo-img">
        <p class="logo-text">VAYA</p>
    </div>
    <ul>
      <li><a href="#firstsection">Home</a></li>
      <li><a href="#secondsection">Rooms</a></li>
      <li><a href="#thirdsection">Facilities</a></li>
      <li><a href="#contactus">Contact us</a></li>
      <a href="./logout.php"><button class="btn btn-danger">Logout</button></a>
    </ul>
  </nav>

  <!-- Barre de recherche améliorée -->
  <div class="search-container">
    <form method="GET" action="">
      <div class="search-box">
        <select name="search_city" class="form-select" required>
          <option value="">Select a city</option>
          <option value="Mostaganem" <?= ($search_city == 'Mostaganem') ? 'selected' : '' ?>>Mostaganem</option>
          <option value="Oran" <?= ($search_city == 'Oran') ? 'selected' : '' ?>>Oran</option>
          <option value="Alger" <?= ($search_city == 'Alger') ? 'selected' : '' ?>>Alger</option>
        </select>
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if(!empty($search_city)): ?>
          <a href="home.php" class="btn btn-secondary">Clear</a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- Affichage des résultats de recherche -->
  <?php if(!empty($search_city)): ?>
  <div class="search-results">
    <h2>Available rooms in <?= htmlspecialchars($search_city) ?></h2>
    
    <?php if(isset($rooms) && mysqli_num_rows($rooms) > 0): ?>
      <div class="row">
        <?php while($room = mysqli_fetch_assoc($rooms)): ?>
          <div class="col-md-4 mb-4">
            <div class="room-card">
              <h3><?= htmlspecialchars($room['type']) ?></h3>
              <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($room['city']) ?></p>
              <p><i class="fas fa-money-bill-wave"></i> Price: <?= htmlspecialchars($room['price']) ?> DA</p>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No rooms found in <?= htmlspecialchars($search_city) ?></div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <section id="firstsection" class="carousel slide carousel_section" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="carousel-image" src="./image/hot1.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/hot2.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/hot3.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/hotel4.jpg">
        </div>

        <div class="welcomeline">
          <h1 class="welcometag">Discover Your Next Adventure</h1>
        </div>
    </div>
  </section>
    
  <!-- Section Chambres (affichage original) -->
  <section id="secondsection"> 
    <img src="./image/homeanimatebg.svg">
    <div class="ourroom">
      <h1 class="head">≼ Our room ≽</h1>
      <div class="roomselect">
        <div class="roombox">
          <div class="hotelphoto h1"></div>
          <div class="roomdata">
            <h2>Superior Room</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
              <i class="fa-solid fa-spa"></i>
              <i class="fa-solid fa-dumbbell"></i>
              <i class="fa-solid fa-person-swimming"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('Superior Room')">Book</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h2"></div>
          <div class="roomdata">
            <h2>Delux Room</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
              <i class="fa-solid fa-spa"></i>
              <i class="fa-solid fa-dumbbell"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('Deluxe Room')">Book</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h3"></div>
          <div class="roomdata">
            <h2>Guest Room</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
              <i class="fa-solid fa-spa"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('Guest House')">Book</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h4"></div>
          <div class="roomdata">
            <h2>Single Room</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('Single Room')">Book</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FORMULAIRE DE RÉSERVATION CORRIGÉ -->
  <div id="guestdetailpanel">
    <form action="" method="POST" class="guestdetailpanelform">
        <div >
            <i class="fa-solid fa-circle-xmark" onclick="closebox()"></i>
        </div>
        <div class="middle">
            <div class="guestinfo">
                <h4>Guest information</h4>
                <input type="text" name="Name" placeholder="Enter Full name" required>
                <input type="email" name="Email" placeholder="Enter Email" required>

                <?php
                $countries = array("Oran", "Alger", "Mostaganem", "Tlemcen");
                ?>

                <select name="Country" class="selectinput" required>
                    <option value="">Select your city</option>
                    <?php foreach($countries as $country): ?>
                        <option value="<?= $country ?>"><?= $country ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="Phone" placeholder="Enter Phone number" required>
            </div>

            <div class="line"></div>

            <div class="reservationinfo">
                <h4>Reservation information</h4>
                <input type="hidden" name="RoomType" id="selectedRoomType" value="">
                <select name="Bed" class="selectinput" required>
                    <option value="">Bedding Type</option>
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                    <option value="Triple">Triple</option>
                </select>
                <input type="number" name="NoofRoom" min="1" placeholder="Number of Rooms" required>
                <select name="Meal" class="selectinput" required>
                    <option value="">Meal Plan</option>
                    <option value="Room only">Room only</option>
                    <option value="Breakfast">Breakfast</option>
                </select>
                <input type="number" name="num_persons" min="1" placeholder="Number of Persons" required>
                <div class="datesection">
                    <span>
                        <label>Check-In</label>
                        <input name="cin" type="date" required>
                    </span>
                    <span>
                        <label>Check-Out</label>
                        <input name="cout" type="date" required>
                    </span>
                </div>
            </div>
        </div>
        <div class="footer">
             <button class="reserve-btn" name="guestdetailsubmit">Submit</button>
        </div>
    </form>
  </div>

   <!-- Facilities Section -->
    <section id="thirdsection">
        <h1 class="head">≼ Facilities ≽</h1>
        <div class="facility">
            <div class="box swimming-pool">
                <h2>Swimming Pool</h2>
            </div>
            <div class="box spa">
                <h2>Spa </h2>
            </div>
            <div class="box restaurant">
                <h2>24/7 Restaurant</h2>
            </div>
            <div class="box gym">
                <h2>Fitness Gym</h2>
            </div>
        </div>
    </section>

  <section id="contactus">
    <div class="social">
      <i class="fa-brands fa-instagram"></i>
      <p> vaya_officielle </p>

      <i class="fa-brands fa-facebook"></i>
      <p> vaya_officielle </p>

      <i class="fa-solid fa-envelope"></i>
      <p> vaya@gmail.com</p>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Fonctions pour le formulaire de réservation
    function openbookbox(roomType) {
      document.getElementById('guestdetailpanel').style.display = 'flex';
      document.getElementById('selectedRoomType').value = roomType;
      document.body.style.overflow = 'hidden'; // Empêche le défilement de la page
    }
    
    function closebox() {
      document.getElementById('guestdetailpanel').style.display = 'none';
      document.body.style.overflow = 'auto'; // Réactive le défilement
    }

    window.onclick = function(event) {
      if (event.target == document.getElementById('guestdetailpanel')) {
        closebox();
      }
    }
    
    // Fermer avec la touche Échap
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        closebox();
      }
    });
  </script>
</body>
</html>