<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php
if(isset($_POST['register'])) {
    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $password  = $_POST['password'];
    $csrf_token = $_POST['csrf_token'];

    if(!validate_csrf_token($csrf_token)) {
        $message = "CSRF Token Validation Failed";
    } elseif(!empty($username) && !empty($email) && !empty($password)) {
        
        // Server-side Validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format";
        } elseif (strlen($password) < 6) {
            $message = "Password must be at least 6 characters long";
        } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password)) {
            $message = "Password must contain at least one uppercase and one lowercase letter";
        } else {
            // Check for existing username or email
            $check_query = "SELECT username, user_email FROM users WHERE username = ? OR user_email = ?";
            $check_stmt = $pdo->prepare($check_query);
            $check_stmt->execute([$username, $email]);
            
            if($check_stmt->rowCount() > 0) {
                $message = "Username or Email already exists";
            } else {
                // Hash Password
                $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

                $query = "INSERT INTO users (username, user_email, user_firstname, user_lastname, user_password, user_role) VALUES (?, ?, ?, ?, ?, 'subscriber')";
                $stmt = $pdo->prepare($query);
                
                try {
                    if($stmt->execute([$username, $email, $firstname, $lastname, $password])) {
                        $message = "Registration successful! <a href='login.php'>Login Here</a>";
                    } else {
                        $message = "Something went wrong.";
                    }
                } catch(PDOException $e) {
                    $message = "Error: " . $e->getMessage();
                }
            }
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

                    <form action="register.php" method="post" class="fade-up">
                        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">First Name</label>
                                <input type="text" name="firstname" class="form-control p-3 bg-light border-0 shadow-none" placeholder="First Name" required style="border-radius: 12px;">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label small fw-bold text-muted text-uppercase">Last Name</label>
                                <input type="text" name="lastname" class="form-control p-3 bg-light border-0 shadow-none" placeholder="Last Name" required style="border-radius: 12px;">
                            </div>
                        </div>
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
                        <button type="submit" name="register" class="btn btn-primary w-100 py-3 rounded-pill shadow-sm">Register</button>
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