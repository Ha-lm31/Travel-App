<?php

$server = "localhost";
$username = "vaya_user";
$password = "password";
$database = "vaya";

$conn = mysqli_connect($server,$username,$password,$database);

if(!$conn){
    die("<script>alert('connection Failed.')</script>");
}
// else{
//     echo "<script>alert('connection successfully.')</script>";
// }
?>