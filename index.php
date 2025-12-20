<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<!-- Hero Section (Welcome Banner) -->
<header class="bg-light py-5 mb-5" style="background: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80') no-repeat center center/cover; position: relative; min-height: 400px; display: flex; align-items: center;">
    <div style="background: rgba(0,0,0,0.5); position: absolute; top:0; left:0; width:100%; height:100%;"></div>
    <div class="container h-100 position-relative">
        <div class="row h-100 align-items-center">
            <div class="col-lg-12 text-center text-white">
                <h1 class="display-4 fw-bold">Welcome to BlogSphere</h1>
                <p class="lead mb-0">Discover stories, thinking, and expertise from writers on any topic.</p>
            </div>
        </div>
    </div>
</header>

<div class="container">
    <div class="row">
        <!-- Blog Entries Column -->
        <div class="col-lg-8">
            <h2 class="mb-4 border-start border-4 border-primary ps-3">Latest Stories</h2>

            <?php
            // Pagination Logic
            $per_page = 5;
            if(isset($_GET['page'])){
                $page = $_GET['page'];
            } else {
                $page = "";
            }

            if($page == "" || $page == 1){
                $page_1 = 0;
            } else {
                $page_1 = ($page * $per_page) - $per_page;
            }

            // Count all published posts for pagination
            $post_count_query = "SELECT * FROM posts WHERE post_status = 'published'";
            $find_count = $pdo->query($post_count_query);
            $count_all = $find_count->rowCount();
            $count_pages = ceil($count_all / $per_page);

            $query = "SELECT * FROM posts WHERE post_status = 'published' ORDER BY post_id DESC LIMIT $page_1, $per_page";
            $stmt = $pdo->query($query);
            $count = 0;
            
            echo '<div class="row">'; // Start grid row for sub-posts

            while($row = $stmt->fetch()) {
                $post_id = $row['post_id'];
                $post_title = $row['post_title'];
                $post_author = $row['post_author'];
                $post_date = date("F j, Y", strtotime($row['post_date']));
                $post_image = $row['post_image'];
                $post_content = substr($row['post_content'], 0, 150);
                
                if($count == 0 && ($page == "" || $page == 1)) { ?>
                    <!-- Spotlight (Featured) Post -->
                    <div class="col-12 fade-up">
                        <article class="card-spotlight">
                            <?php if($post_image): ?>
                                <img class="card-img-top" src="images/<?php echo $post_image; ?>" alt="<?php echo $post_title; ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="card-meta text-white-50">
                                    <span><i class="fa-regular fa-user"></i> <?php echo $post_author; ?></span>
                                    <span><i class="fa-regular fa-calendar-days"></i> <?php echo $post_date; ?></span>
                                </div>
                                <h2 class="card-title"><a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a></h2>
                                <p class="card-text"><?php echo $post_content; ?>...</p>
                                <a href="post.php?p_id=<?php echo $post_id; ?>" class="btn btn-primary rounded-pill mt-2 mb-2">Read Story</a>
                            </div>
                        </article>
                    </div>
                <?php } else { ?>
                    <!-- Grid Item Post -->
                    <div class="col-md-6 mb-4 fade-up" style="animation-delay: <?php echo ($count * 0.05); ?>s">
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
                                <p class="card-text text-secondary small mb-4"><?php echo substr($post_content, 0, 120); ?>...</p>
                                <a href="post.php?p_id=<?php echo $post_id; ?>" class="btn btn-outline-primary btn-sm rounded-pill mt-auto">Read More</a>
                            </div>
                        </article>
                    </div>
                <?php }
                $count++;
            }
            echo '</div>'; // End grid row

            // Pagination Links
            echo '<nav aria-label="Page navigation" class="mt-4"><ul class="pagination justify-content-center">';
            for($i=1; $i <= $count_pages; $i++){
                if($i == $page || ($i == 1 && $page == "")){
                    echo "<li class='page-item active'><a class='page-link' href='index.php?page={$i}'>{$i}</a></li>";
                } else {
                    echo "<li class='page-item'><a class='page-link' href='index.php?page={$i}'>{$i}</a></li>";
                }
            }
            echo '</ul></nav>';
            ?>

        </div>

        <!-- Sidebar Widgets Column -->
        <div class="col-md-4">
            <?php include "includes/sidebar.php"; ?>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>