<?php
include '../config.php';
session_start();

// page redirect
$usermail = "";
if(isset($_SESSION['usermail'])) {
    $usermail = $_SESSION['usermail'];
} else {
    header("location: http://localhost/hotelmanage_system/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/admin.css">
    <!-- loading bar -->
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="../css/flash.css">
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin</title>
    <style>
        /* Styles spécifiques pour corriger les problèmes d'affichage */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        #mobileview {
            display: none;
            background-color: #dc3545;
            color: white;
            text-align: center;
            padding: 10px;
            font-weight: bold;
        }
        
        .uppernav {
            background-color: #3e566fff;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 70px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 5px;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin: 0;
        }
        
        .logout .btn {
            background-color: #e74c3c;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            font-weight: 600;
        }
        
        .logout .btn:hover {
            background-color: #c0392b;
        }
        
        .sidenav {
            background-color: #34495e;
            color: white;
            width: 250px;
            padding: 20px 0;
            position: fixed;
            top: 70px;
            left: 0;
            bottom: 0;
            z-index: 900;
            overflow-y: auto;
        }
        
        .sidenav ul {
            list-style: none;
            padding: 0;
        }
        
        .sidenav li {
            padding: 15px 30px;
            margin: 5px 0;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .sidenav li:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidenav li.active {
            background-color: #3498db;
        }
        
        .sidenav li img {
            width: 24px;
            height: 24px;
            margin-right: 15px;
            filter: invert(1);
        }
        
        .mainscreen {
            margin-left: 250px;
            margin-top: 70px;
            padding: 20px;
            height: calc(100vh - 70px);
        }
        
        .frames {
            width: 100%;
            height: 100%;
            display: none;
            border: none;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .frames.active {
            display: block;
        }
        
        @media (max-width: 992px) {
            #mobileview {
                display: block;
            }
            
            .sidenav {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
            }
            
            .sidenav ul {
                display: flex;
                overflow-x: auto;
            }
            
            .sidenav li {
                white-space: nowrap;
            }
            
            .mainscreen {
                margin-left: 0;
                margin-top: 0;
                height: auto;
            }
        }
        
        @media (max-width: 576px) {
            .uppernav {
                flex-direction: column;
                height: auto;
                padding: 10px;
                position: relative;
            }
            
            .logo {
                margin-bottom: 10px;
            }
            
            .mainscreen {
                padding: 10px;
            }
            
            .frames {
                height: 500px;
            }
        }
    </style>
</head>

<body>
    <!-- mobile view -->
    <div id="mobileview">
        <h5><i class="fas fa-exclamation-triangle"></i> Admin panel doesn't show properly in mobile view</h5>
    </div>
  
    <!-- nav bar -->
    <nav class="uppernav">
        <div class="logo">
            <img src="../image/airlogo-removebg-preview.png" class="logo-img" alt="Vaya Logo">
            <p class="logo-text"> VAYA</p>
        </div>
        <div class="logout">
            <a href="../logout.php"><button class="btn btn-primary"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
        </div>
    </nav>
    
    <nav class="sidenav">
        <ul>
            <li class="pagebtn active"><img src="../image/icon/dashboard.png" alt="Dashboard">&nbsp;&nbsp;Dashboard</li>
            <li class="pagebtn"><img src="../image/icon/bed.png" alt="Booking">&nbsp;&nbsp;Room Booking</li>
            <li class="pagebtn"><img src="../image/icon/bedroom.png" alt="Rooms">&nbsp;&nbsp;Rooms</li>
            <li class="pagebtn"><img src="../image/icon/staff.png" alt="Staff">&nbsp;&nbsp;Staff</li>
        </ul>
    </nav>

    <!-- main section -->
    <div class="mainscreen">
        <iframe class="frames frame1 active" src="./dashboard.php" frameborder="0"></iframe>
        <iframe class="frames frame2" src="./roombook.php" frameborder="0"></iframe>
        <iframe class="frames frame3" src="./room.php" frameborder="0"></iframe>
        <iframe class="frames frame4" src="./staff.php" frameborder="0"></iframe>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navigation functionality
        document.querySelectorAll('.pagebtn').forEach((button, index) => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.pagebtn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Hide all iframes
                document.querySelectorAll('.frames').forEach(frame => {
                    frame.classList.remove('active');
                });
                
                // Show the corresponding iframe
                document.querySelector(`.frame${index + 1}`).classList.add('active');
            });
        });

        // Load the active iframe on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.frame1.active').src = "./dashboard.php";
        });
    </script>
</body>
</html>