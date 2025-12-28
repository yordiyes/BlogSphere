<?php
if(isset($_POST['create_post'])) {
    $csrf_token = $_POST['csrf_token'];
    if(!validate_csrf_token($csrf_token)) {
        die("CSRF Token Validation Failed");
    }
    $post_title = $_POST['title'];
    $post_author = $_SESSION['username'];
    $post_category_id = $_POST['post_category'];
    $post_status = $_POST['post_status'];

    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];

    $post_tags = $_POST['post_tags'];
    $post_content = $_POST['post_content'];
    $post_date = date('d-m-y');

    if(empty($post_title) || empty($post_category_id) || empty($post_status) || empty($post_image) || empty($post_tags) || empty($post_content)) {
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
        move_uploaded_file($post_image_temp, "../images/$post_image");

        $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status) ";
        $query .= "VALUES(?, ?, ?, now(), ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute([$post_category_id, $post_title, $post_author, $post_image, $post_content, $post_tags, $post_status]);

        echo "<div class='alert alert-success'>Post Created. <a href='posts.php'>View Posts</a></div>";
    }
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
    <div class="mb-3">
        <label>Post Title</label>
        <input type="text" class="form-control" name="title" required>
    </div>

    <div class="mb-3">
        <label>Post Category</label>
        <select name="post_category" class="form-control" required>
            <?php
            $query = "SELECT * FROM categories";
            $stmt = $pdo->query($query);
            while($row = $stmt->fetch()) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
                echo "<option value='{$cat_id}'>{$cat_title}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Post Status</label>
        <select name="post_status" class="form-control" required>
            <option value="">Select Options</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Post Image</label>
        <input type="file" class="form-control" name="image" required>
    </div>

    <div class="mb-3">
        <label>Post Tags</label>
        <input type="text" class="form-control" name="post_tags" required>
    </div>

    <div class="mb-3">
        <label>Post Content</label>
        <textarea class="form-control" name="post_content" rows="10" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary" name="create_post">Publish Post</button>
</form>