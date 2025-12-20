<?php
if(isset($_GET['p_id'])){
    $the_post_id = $_GET['p_id'];

    $query = "SELECT * FROM posts WHERE post_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$the_post_id]);
    $row = $stmt->fetch();

    $post_title = $row['post_title'];
    $post_status = $row['post_status'];
    $post_content = $row['post_content'];
    $post_tags = $row['post_tags'];
    $post_image = $row['post_image'];
}

if(isset($_POST['update_post'])) {
    $post_title = $_POST['title'];
    $post_status = $_POST['post_status'];
    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];
    $post_content = $_POST['post_content'];
    $post_tags = $_POST['post_tags'];

    // Keep old image if new one isn't uploaded
    if(empty($post_image)) {
        $post_image = $row['post_image'];
    } else {
        move_uploaded_file($post_image_temp, "../images/$post_image");
    }

    $query = "UPDATE posts SET post_title = ?, post_status = ?, post_image = ?, post_content = ?, post_tags = ? WHERE post_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$post_title, $post_status, $post_image, $post_content, $post_tags, $the_post_id]);

    echo "<div class='alert alert-success'>Post Updated. <a href='posts.php'>View Posts</a></div>";
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Post Title</label>
        <input value="<?php echo $post_title; ?>" type="text" class="form-control" name="title">
    </div>

    <div class="mb-3">
        <label>Post Status</label>
        <select name="post_status" class="form-control">
            <option value="<?php echo $post_status; ?>"><?php echo $post_status; ?></option>
            <?php
            if($post_status == 'published'){
                echo "<option value='draft'>Draft</option>";
            } else {
                echo "<option value='published'>Published</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <img width="100" src="../images/<?php echo $post_image; ?>" alt="">
        <input type="file" class="form-control" name="image">
    </div>

    <div class="mb-3">
        <label>Post Tags</label>
        <input value="<?php echo $post_tags; ?>" type="text" class="form-control" name="post_tags">
    </div>

    <div class="mb-3">
        <label>Post Content</label>
        <textarea class="form-control" name="post_content" rows="10"><?php echo $post_content; ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary" name="update_post">Update Post</button>
</form>