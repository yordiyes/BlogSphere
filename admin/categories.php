<?php include "includes/header.php"; ?>
<?php include "includes/navigation.php"; ?>

<?php
if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: index.php");
}

// Handle Category Addition
if(isset($_POST['submit'])){
    $csrf_token = $_POST['csrf_token'];
    if(!validate_csrf_token($csrf_token)) {
        die("CSRF Token Validation Failed");
    }

    $cat_title = $_POST['cat_title'];
    if($cat_title == "" || empty($cat_title)){
        echo "<div class='alert alert-danger'>This field should not be empty</div>";
    } else {
        $query = "INSERT INTO categories(cat_title) VALUE(?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$cat_title]);
        header("Location: categories.php");
    }
}

// Handle Category Deletion
if(isset($_GET['delete'])){
    $the_cat_id = $_GET['delete'];
    $query = "DELETE FROM categories WHERE cat_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$the_cat_id]);
    header("Location: categories.php");
}
?>

<h1 class="mt-4">Category Management</h1>
<hr>

<div class="row">
    <!-- Add Category Form -->
    <div class="col-md-6">
        <form action="" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
            <div class="mb-3">
                <label class="form-label">Add Category</label>
                <input type="text" class="form-control" name="cat_title" placeholder="Category Name">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Add Category</button>
        </form>
        
        <?php
        // Handle Edit logic if source=edit
        if(isset($_GET['edit'])){
            $cat_id = $_GET['edit'];
            $query = "SELECT * FROM categories WHERE cat_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$cat_id]);
            $row = $stmt->fetch();
            if($row){
                $cat_title = $row['cat_title'];
                ?>
                <hr>
                <form action="" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                    <input type="hidden" name="cat_id_to_update" value="<?php echo $cat_id; ?>">
                    <div class="mb-3">
                        <label class="form-label">Update Category</label>
                        <input value="<?php echo $cat_title; ?>" type="text" class="form-control" name="cat_title_update">
                    </div>
                    <button type="submit" class="btn btn-info" name="update">Update Category</button>
                </form>
                <?php
            }
        }

        // Handle Category Update
        if(isset($_POST['update'])){
            $cat_id = $_POST['cat_id_to_update'];
            $cat_title = $_POST['cat_title_update'];
            $query = "UPDATE categories SET cat_title = ? WHERE cat_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$cat_title, $cat_id]);
            header("Location: categories.php");
        }
        ?>
    </div>

    <!-- Category Table -->
    <div class="col-md-6">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Category Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM categories";
                    $stmt = $pdo->query($query);
                    while($row = $stmt->fetch()){
                        $cat_id = $row['cat_id'];
                        $cat_title = $row['cat_title'];
                        echo "<tr>";
                        echo "<td>$cat_id</td>";
                        echo "<td>$cat_title</td>";
                        echo "<td>
                                <a class='btn btn-sm btn-outline-info' href='categories.php?edit={$cat_id}'><i class='fa-solid fa-edit'></i></a>
                                <a class='btn btn-sm btn-outline-danger' onClick=\"javascript: return confirm('Delete Category?'); \" href='categories.php?delete={$cat_id}'><i class='fa-solid fa-trash'></i></a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
