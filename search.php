<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <!-- Back Button -->
            <a href="index.php" class="btn btn-outline-primary rounded-pill mb-4 mt-4 fade-up">
                <i class="fa-solid fa-arrow-left me-2"></i> Back to Home
            </a>
            <h1 class="my-4">Search Results</h1>

            <?php
            if(isset($_POST['submit'])){
                $search = $_POST['search'];
                // Secure search query
                $query = "SELECT * FROM posts WHERE post_tags LIKE ? OR post_title LIKE ? OR post_content LIKE ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute(["%$search%", "%$search%", "%$search%"]);
                $count = $stmt->rowCount();

                if($count == 0) {
                    echo "<h1>No Result Found</h1>";
                } else {
                    while($row = $stmt->fetch()) {
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];
            ?>
                <!-- V1 Premium Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <?php if($post_image): ?>
                        <div class="overflow-hidden">
                            <img class="card-img-top" src="images/<?php echo $post_image; ?>" alt="<?php echo $post_title; ?>" style="transition: transform 0.5s ease;">
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h2 class="card-title h4"><a href="post.php?p_id=<?php echo $row['post_id']; ?>" class="text-decoration-none text-dark fw-bold"><?php echo $post_title; ?></a></h2>
                        <p class="card-text text-secondary"><?php echo substr($post_content, 0, 150); ?>...</p>
                        <a href="post.php?p_id=<?php echo $row['post_id']; ?>" class="btn btn-primary rounded-pill">Read Article</a>
                    </div>
                </div>
            <?php 
                    } // end while
                }
            } 
            ?>
        </div>
        <div class="col-md-4">
            <?php include "includes/sidebar.php"; ?>
        </div>
    </div>
</div>
<?php include "includes/footer.php"; ?>