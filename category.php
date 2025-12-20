<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<div class="container">
    <div class="row">
        <!-- Blog Entries Column -->
        <div class="col-lg-8">
            <!-- Back Button -->
            <a href="index.php" class="btn btn-outline-primary rounded-pill mb-4 mt-4 fade-up">
                <i class="fa-solid fa-arrow-left me-2"></i> Back to Home
            </a>

            <?php
            if(isset($_GET['category'])){
                $the_post_category_id = $_GET['category'];

                // Get Category Title
                $cat_title_query = "SELECT cat_title FROM categories WHERE cat_id = ?";
                $stmt_cat = $pdo->prepare($cat_title_query);
                $stmt_cat->execute([$the_post_category_id]);
                $cat_row = $stmt_cat->fetch();
                $category_name = ($cat_row) ? $cat_row['cat_title'] : "Unknown Category";

                echo "<h1 class='my-4'>Category: {$category_name}</h1>";

                $query = "SELECT * FROM posts WHERE post_category_id = ? AND post_status = 'published' ORDER BY post_id DESC";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$the_post_category_id]);
                $count = $stmt->rowCount();

                if($count == 0) {
                    echo "<h3>No posts found in this category.</h3>";
                } else {
                    echo '<div class="row">';
                    while($row = $stmt->fetch()){
                        $post_id = $row['post_id'];
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = date("F j, Y", strtotime($row['post_date']));
                        $post_image = $row['post_image'];
                        $post_content = substr($row['post_content'], 0, 150);
            ?>
                        <!-- Grid Item Post -->
                        <div class="col-md-6 mb-4 fade-up">
                            <article class="card card-grid shadow-sm h-100">
                                <?php if($post_image): ?>
                                    <div class="overflow-hidden">
                                        <img class="card-img-top w-100" src="images/<?php echo $post_image; ?>" alt="<?php echo $post_title; ?>" style="transition: transform 0.8s ease;">
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <div class="card-meta">
                                        <span><i class="fa-regular fa-user"></i> <?php echo $post_author; ?></span>
                                        <span><i class="fa-regular fa-calendar-days"></i> <?php echo $post_date; ?></span>
                                    </div>
                                    <h3 class="card-title h5 mb-3"><a href="post.php?p_id=<?php echo $post_id; ?>" class="text-decoration-none text-dark fw-bold"><?php echo $post_title; ?></a></h3>
                                    <p class="card-text text-secondary small mb-4"><?php echo $post_content; ?>...</p>
                                    <a href="post.php?p_id=<?php echo $post_id; ?>" class="btn btn-outline-primary btn-sm rounded-pill mt-auto">Read More</a>
                                </div>
                            </article>
                        </div>
            <?php 
                    } // end while
                    echo '</div>';
                }
            } else {
                header("Location: index.php");
            }
            ?>
        </div>

        <!-- Sidebar Widgets Column -->
        <div class="col-md-4">
            <?php include "includes/sidebar.php"; ?>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
