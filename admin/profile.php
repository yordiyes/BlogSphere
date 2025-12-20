<?php include "includes/header.php"; ?>
<?php include "includes/navigation.php"; ?>

<?php
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $row = $stmt->fetch();

    $user_id = $row['user_id'];
    $user_password = $row['user_password'];
    $user_firstname = $row['user_firstname'];
    $user_lastname = $row['user_lastname'];
    $user_email = $row['user_email'];
    $user_image = $row['user_image'];
}

if(isset($_POST['edit_user'])){
    $csrf_token = $_POST['csrf_token'];
    if(!validate_csrf_token($csrf_token)) {
        die("CSRF Token Validation Failed");
    }
    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_email = $_POST['user_email'];
    $username = $_POST['username'];
    $user_password = $_POST['user_password'];

    $user_image = $_FILES['image']['name'];
    $user_image_temp = $_FILES['image']['tmp_name'];

    if(empty($user_image)) {
        $user_image = $row['user_image'];
    } else {
        move_uploaded_file($user_image_temp, "../images/$user_image");
    }

    // Password hashing if changed
    if(!empty($user_password)){
        $user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 12));
    } else {
        $user_password = $row['user_password'];
    }

    $query = "UPDATE users SET user_firstname = ?, user_lastname = ?, user_email = ?, username = ?, user_password = ?, user_image = ? WHERE user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_firstname, $user_lastname, $user_email, $username, $user_password, $user_image, $user_id]);

    // Update session if username changed
    $_SESSION['username'] = $username;

    echo "<div class='alert alert-success mt-3'>Profile Updated. <a href='index.php'>Go to Dashboard</a></div>";
}
?>

<h1 class="mt-4">My Profile</h1>
<hr>

<div class="row">
    <div class="col-md-4 text-center">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <img class="img-fluid rounded-circle mb-3" src="../images/<?php echo $user_image ? $user_image : 'default-user.png'; ?>" alt="profile" style="width: 150px; height: 150px; object-fit: cover;">
                <h4><?php echo $_SESSION['username']; ?></h4>
                <p class="text-muted"><?php echo $_SESSION['user_role']; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input value="<?php echo $user_firstname; ?>" type="text" class="form-control" name="user_firstname">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input value="<?php echo $user_lastname; ?>" type="text" class="form-control" name="user_lastname">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input value="<?php echo $user_email; ?>" type="email" class="form-control" name="user_email">
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input value="<?php echo $username; ?>" type="text" class="form-control" name="username">
            </div>

            <div class="mb-3">
                <label class="form-label">Password (leave blank to keep current)</label>
                <input type="password" class="form-control" name="user_password">
            </div>

            <div class="mb-3">
                <label class="form-label">Profile Image</label>
                <input type="file" class="form-control" name="image">
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary" name="edit_user">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<?php include "includes/footer.php"; ?>
