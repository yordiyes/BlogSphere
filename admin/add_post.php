<?php
if(isset($_POST['create_post'])) {
    $post_title = $_POST['title'];
    $post_author = $_SESSION['username'];
    $post_category_id = 1; // Default to 1 for now, or add a select input
    $post_status = $_POST['post_status'];

    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];

    $post_tags = $_POST['post_tags'];
    $post_content = $_POST['post_content'];
    $post_date = date('d-m-y');

    move_uploaded_file($post_image_temp, "../images/$post_image");

    $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status) ";
    $query .= "VALUES(?, ?, ?, now(), ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$post_category_id, $post_title, $post_author, $post_image, $post_content, $post_tags, $post_status]);

    echo "<div class='alert alert-success'>Post Created. <a href='posts.php'>View Posts</a></div>";
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Post Title</label>
        <input type="text" class="form-control" name="title">
    </div>

    <div class="mb-3">
        <label>Post Status</label>
        <select name="post_status" class="form-control">
            <option value="draft">Select Options</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Post Image</label>
        <input type="file" class="form-control" name="image">
    </div>

    <div class="mb-3">
        <label>Post Tags</label>
        <input type="text" class="form-control" name="post_tags">
    </div>

    <div class="mb-3">
        <label>Post Content</label>
        <textarea class="form-control" name="post_content" rows="10"></textarea>
    </div>

    <button type="submit" class="btn btn-primary" name="create_post">Publish Post</button>
</form>