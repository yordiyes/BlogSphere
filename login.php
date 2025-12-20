<?php include "includes/db.php"; ?>
<?php session_start(); ?>
<?php include "includes/header.php"; ?>

<?php
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['user_password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['firstname'] = $user['user_firstname'];
        $_SESSION['lastname'] = $user['user_lastname'];
        $_SESSION['user_role'] = $user['user_role'];

        header("Location: admin/index.php");
    } else {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Invalid Credentials. <a href='login.php'>Try Again</a></div></div>";
    }
}
?>

<!-- Modern Login Form -->
<div class="container py-5" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="row w-100 justify-content-center">
        <div class="col-lg-4 col-md-6 col-sm-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Branding Header -->
                <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3 class="fw-bold mb-0">Welcome Back</h3>
                    <p class="small mb-0 opacity-75">Sign in to your BlogSphere account</p>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="login.php" method="post">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Username</label>
                            <input name="username" type="text" class="form-control p-3 bg-light border-0 shadow-none" placeholder="Enter username" style="border-radius: 12px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                            <input name="password" type="password" class="form-control p-3 bg-light border-0 shadow-none" placeholder="Enter password" style="border-radius: 12px;">
                        </div>
                        <button class="btn btn-primary w-100 py-3 rounded-pill shadow-sm" name="login" type="submit">Sign In</button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="text-muted small mb-0">Don't have an account? <a href="register.php" class="text-primary fw-bold text-decoration-none">Sign Up</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>