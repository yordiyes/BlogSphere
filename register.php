<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php
if(isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if(!empty($username) && !empty($email) && !empty($password)) {
        // Hash Password
        $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

        $query = "INSERT INTO users (username, user_email, user_password, user_role) VALUES (?, ?, ?, 'subscriber')";
        $stmt = $pdo->prepare($query);
        
        if($stmt->execute([$username, $email, $password])) {
            $message = "Registration successful! <a href='login.php'>Login Here</a>";
        } else {
            $message = "Something went wrong.";
        }
    } else {
        $message = "Fields cannot be empty";
    }
} else {
    $message = "";
}
?>

<!-- Modern Registration Form -->
<div class="container py-5" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <div class="row w-100 justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Branding Header -->
                <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3 class="fw-bold mb-0">Create Account</h3>
                    <p class="small mb-0 opacity-75">Join the BlogSphere community today</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if($message != ""): ?>
                        <div class="alert alert-success border-0 rounded-3 small mb-4"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form action="register.php" method="post">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Username</label>
                            <input type="text" name="username" class="form-control p-3 bg-light border-0 shadow-none" placeholder="Choose a username" required style="border-radius: 12px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                            <input type="email" name="email" class="form-control p-3 bg-light border-0 shadow-none" placeholder="your@email.com" required style="border-radius: 12px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                            <input type="password" name="password" class="form-control p-3 bg-light border-0 shadow-none" placeholder="Create a password" required style="border-radius: 12px;">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100 py-3 rounded-pill shadow-sm">Register</button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="text-muted small mb-0">Already a member? <a href="login.php" class="text-primary fw-bold text-decoration-none">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>