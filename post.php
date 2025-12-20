<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Back Button -->
            <a href="index.php" class="btn btn-outline-primary rounded-pill mb-4 fade-up">
                <i class="fa-solid fa-arrow-left me-2"></i> Back to Home
            </a>
            <?php
            if(isset($_GET['p_id'])){
                $the_post_id = $_GET['p_id'];
                
                $query = "SELECT * FROM posts WHERE post_id = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$the_post_id]);
                
                while($row = $stmt->fetch()){
                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_date = date("M j, Y", strtotime($row['post_date']));
                    $post_image = $row['post_image'];
                    $post_content = $row['post_content'];
            ?>
                <!-- Post Title -->
                <h1 class="fw-bold mt-4 mb-3" style="font-size: 2.5rem; color: #333;"><?php echo $post_title; ?></h1>
                
                <!-- Author and Date -->
                <div class="d-flex align-items-center mb-4 text-muted">
                    <div class="me-3">
                        <i class="fa-regular fa-user me-1"></i> <span class="fw-bold text-dark"><?php echo $post_author; ?></span>
                    </div>
                    <div>
                        <i class="fa-regular fa-calendar me-1"></i> <?php echo $post_date; ?>
                    </div>
                </div>
                
                <hr class="mb-5">

                <!-- Featured Image -->
                <?php if($post_image): ?>
                <div class="card border-0 shadow-sm mb-5">
                    <img class="img-fluid rounded shadow-sm" src="images/<?php echo $post_image; ?>" alt="<?php echo $post_title; ?>" style="max-height: 500px; object-fit: cover;">
                </div>
                <?php endif; ?>

                <!-- Post Content -->
                <article class="mb-5 text-dark" style="font-size: 1.15rem; line-height: 1.8;">
                    <?php echo nl2br($post_content); ?>
                </article>

                <hr class="my-5">

            <?php } 
            } else {
                header("Location: index.php");
            }
            ?>
        </div>
        
        <div class="col-md-4">
            <?php include "includes/sidebar.php"; ?>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>