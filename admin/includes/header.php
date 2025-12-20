<?php ob_start(); ?>
<?php session_start(); ?>
<?php 
// Adjust paths
if(file_exists("../includes/functions.php")) {
    include "../includes/functions.php";
}
if(file_exists("../includes/db.php")) {
    include "../includes/db.php";
} elseif(file_exists("../../includes/db.php")) {
    include "../../includes/db.php";
}
?>

<?php
if(!isset($_SESSION['user_role'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; overflow-x: hidden; }
        
        /* Sidebar Styles */
        #wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar-wrapper {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: #212529; /* Dark Sidebar */
            color: #fff;
            transition: all 0.3s;
        }
        .sidebar-heading { padding: 1.5rem 1.25rem; font-size: 1.2rem; font-weight: bold; background: #00000020; border-bottom: 1px solid #444; }
        .list-group-item { 
            background-color: transparent; 
            color: #ccc; 
            border: none; 
            padding: 15px 20px; 
            font-size: 0.95rem;
        }
        .list-group-item:hover { background-color: #343a40; color: #fff; text-decoration: none; border-left: 4px solid #0d6efd; }
        .list-group-item i { margin-right: 10px; width: 20px; text-align: center; }
        
        /* Page Content */
        #page-content-wrapper { width: 100%; }
        .navbar-light { background-color: #fff !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        
        /* Cards */
        .card-counter{
            box-shadow: 2px 2px 10px #DADADA;
            margin: 5px;
            padding: 20px 10px;
            background-color: #fff;
            height: 100px;
            border-radius: 5px;
            transition: .3s linear all;
        }
        .card-counter:hover{ box-shadow: 4px 4px 20px #DADADA; transform: scale(1.02); }
        .card-counter.primary{ background-color: #007bff; color: #FFF; }
        .card-counter.success{ background-color: #198754; color: #FFF; }
        .card-counter.danger{ background-color: #dc3545; color: #FFF; }
        .card-counter.info{ background-color: #0dcaf0; color: #FFF; }  

        .card-counter i{ font-size: 5em; opacity: 0.2; position: absolute; right: 15px; top: 15px; }
        .card-counter .count-numbers{ position: absolute; right: 35px; top: 20px; font-size: 32px; display: block; font-weight: bold; }
        .card-counter .count-name{ position: absolute; right: 35px; top: 65px; font-style: italic; text-transform: capitalize; opacity: 0.8; display: block; font-size: 18px; }
    </style>
</head>
<body>
<div id="wrapper">