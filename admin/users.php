<?php include "includes/header.php"; ?>
<?php include "includes/navigation.php"; ?>

<?php
if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: index.php");
}

// Handle User Deletion
if(isset($_GET['delete'])){
    $the_user_id = $_GET['delete'];
    $query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$the_user_id]);
    header("Location: users.php");
}

// Handle Role Change
if(isset($_GET['change_to_admin'])){
    $the_user_id = $_GET['change_to_admin'];
    $query = "UPDATE users SET user_role = 'admin' WHERE user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$the_user_id]);
    header("Location: users.php");
}

if(isset($_GET['change_to_sub'])){
    $the_user_id = $_GET['change_to_sub'];
    $query = "UPDATE users SET user_role = 'author' WHERE user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$the_user_id]);
    header("Location: users.php");
}

// Handle User Addition
if(isset($_POST['create_user'])){
    $csrf_token = $_POST['csrf_token'];
    if(!validate_csrf_token($csrf_token)) {
        die("CSRF Token Validation Failed");
    }

    $user_firstname = $_POST['user_firstname'];
    $user_lastname = $_POST['user_lastname'];
    $user_role = $_POST['user_role'];
    $username = $_POST['username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    $user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 10));

    $query = "INSERT INTO users(user_firstname, user_lastname, user_role, username, user_email, user_password) ";
    $query .= "VALUES(?,?,?,?,?,?)";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_firstname, $user_lastname, $user_role, $username, $user_email, $user_password]);
    
    echo "<div class='alert alert-success'>User Created: " . " " . "<a href='users.php'>View Users</a></div>";
}
?>

<h1 class="mt-4">User Management</h1>
<hr>

<?php
if(isset($_GET['source'])){
    $source = $_GET['source'];
} else {
    $source = '';
}

switch($source){
    case 'add_user';
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
        <div class="mb-3">
            <label class="form-label">Firstname</label>
            <input type="text" class="form-control" name="user_firstname">
        </div>
        <div class="mb-3">
            <label class="form-label">Lastname</label>
            <input type="text" class="form-control" name="user_lastname">
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="user_role" class="form-control">
                <option value="author">Select Options</option>
                <option value="admin">Admin</option>
                <option value="author">Author</option>
                <option value="reader">Reader</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="user_email">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="user_password">
        </div>
        <button type="submit" class="btn btn-primary" name="create_user">Add User</button>
    </form>
    <?php
    break;

    default:
    ?>
    <div class="mb-3 text-end">
        <a href="users.php?source=add_user" class="btn btn-primary"><i class="fa-solid fa-plus-circle"></i> Add New User</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Admin</th>
                    <th>Author</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM users";
                $stmt = $pdo->query($query);
                while($row = $stmt->fetch()){
                    $user_id = $row['user_id'];
                    $username = $row['username'];
                    $user_firstname = $row['user_firstname'];
                    $user_lastname = $row['user_lastname'];
                    $user_email = $row['user_email'];
                    $user_role = $row['user_role'];

                    echo "<tr>";
                    echo "<td>$user_id</td>";
                    echo "<td>$username</td>";
                    echo "<td>$user_firstname</td>";
                    echo "<td>$user_lastname</td>";
                    echo "<td>$user_email</td>";
                    echo "<td><span class='badge bg-info text-dark'>$user_role</span></td>";
                    
                    echo "<td><a class='btn btn-outline-success btn-sm' href='users.php?change_to_admin={$user_id}'>Admin</a></td>";
                    echo "<td><a class='btn btn-outline-primary btn-sm' href='users.php?change_to_sub={$user_id}'>Author</a></td>";
                    echo "<td><a class='btn btn-danger btn-sm' onClick=\"javascript: return confirm('Are you sure you want to delete this user?'); \" href='users.php?delete={$user_id}'><i class='fa-solid fa-trash'></i></a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    break;
}
?>

<?php include "includes/footer.php"; ?>
