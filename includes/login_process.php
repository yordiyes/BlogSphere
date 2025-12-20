<?php include "db.php"; ?>
<?php session_start(); ?>

<?php
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify Password
    if($user && password_verify($password, $user['user_password'])) {
        // Set Session Variables
        $_SESSION['username'] = $user['username'];
        $_SESSION['firstname'] = $user['user_firstname'];
        $_SESSION['lastname'] = $user['user_lastname'];
        $_SESSION['user_role'] = $user['user_role'];

        header("Location: ../admin"); // Redirect to Dashboard
    } else {
        header("Location: ../index.php"); // Redirect back
    }
}
?>