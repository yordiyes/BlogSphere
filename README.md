# 🌐 BlogSphere - Modern Blogging Platform

BlogSphere is a premium, minimalist blogging platform built with **PHP, MySQL, and Bootstrap 5**. It features a dynamic "Spotlight Grid" layout, smooth animations, and a responsive design optimized for all devices.

## ✨ Key Features

- **Spotlight Grid Layout**: Features the latest story with a cinematic spotlight and subsequent stories in a 2-column grid.
- **Premium UI**: Clean, modern aesthetics using Poppins/Open Sans typography and vibrant gradients.
- **Responsive Navigation**: Fixed-top navigation and intuitive "Back to Home" navigation for post reading.
- **Admin Dashboard**: backend management for posts, categories, and users (work in progress).
- **Authentication**: Fully functional login and registration system with secure password hashing.

## 🛠️ Technology Stack

- **Backend**: Native PHP (PDO)
- **Database**: MySQL
- **Frontend**: Bootstrap 5, Custom CSS, FontAwesome 6
- **Typography**: Google Fonts (Poppins & Open Sans)

## 🚀 Setup Instructions

Follow these steps to set up the project locally on your machine:

### 1. Requirements

- **XAMPP / WAMP / MAMP** (Apache & MySQL)
- **PHP 7.4+**

### 2. Database Configuration

1.  Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
2.  Create a new database named `blogsphere`.
3.  Click on the `blogsphere` database, go to the **Import** tab.
4.  Choose the `database.sql` file located in the root directory of this project and click **Go**.

### 3. Application Setup

1.  Move the project folder into your `htdocs` directory (e.g., `C:\xampp\htdocs\blogsphere`).
2.  Check the database connection in `includes/db.php`:
    ```php
    $db_host = 'localhost';
    $db_name = 'blogsphere';
    $db_user = 'root'; // Your MySQL username
    $db_pass = '';     // Your MySQL password
    ```
3.  Access the site in your browser at `http://localhost/blogsphere`.
