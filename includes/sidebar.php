<!-- Search Widget -->
<div class="card sidebar-widget mb-4">
    <h5 class="fw-bold mb-3">Search</h5>
    <form action="search.php" method="post">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded-start-pill border-end-0" placeholder="Search stories...">
            <button class="btn btn-primary rounded-end-pill px-4" name="submit" type="submit">Go</button>
        </div>
    </form>
</div>

<!-- Login Widget (Only show if NOT logged in) -->
<?php if(!isset($_SESSION['user_role'])): ?>
<div class="card sidebar-widget mb-4">
    <h5 class="fw-bold mb-4">Member Login</h5>
    <form action="login.php" method="post">
        <div class="mb-3">
            <input name="username" type="text" class="form-control rounded-pill" placeholder="Username">
        </div>
        <div class="mb-3">
            <input name="password" type="password" class="form-control rounded-pill" placeholder="Password">
        </div>
        <button class="btn btn-primary w-100 rounded-pill" name="login" type="submit">Sign In</button>
    </form>
</div>
<?php endif; ?>

<!-- Categories Widget -->
<div class="card sidebar-widget mb-4">
    <h5 class="fw-bold mb-4">Categories</h5>
    <ul class="list-unstyled mb-0">
        <?php
        $query = "SELECT categories.cat_id, categories.cat_title, COUNT(posts.post_id) as post_count 
                  FROM categories 
                  LEFT JOIN posts ON categories.cat_id = posts.post_category_id AND posts.post_status = 'published' 
                  GROUP BY categories.cat_id 
                  LIMIT 6";
        $stmt = $pdo->query($query);
        while($row = $stmt->fetch()) {
            $cat_title = $row['cat_title'];
            $cat_id = $row['cat_id'];
            $post_count = $row['post_count'];
            echo "<li><a href='category.php?category={$cat_id}' class='d-flex justify-content-between align-items-center mb-2'>
                    <span>{$cat_title}</span>
                    <span class='badge bg-light text-primary rounded-pill'>{$post_count}</span>
                  </a></li>";
        }
        ?>
    </ul>
</div>
