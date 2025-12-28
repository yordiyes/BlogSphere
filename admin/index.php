<?php include "includes/header.php"; ?>
<?php include "includes/navigation.php"; ?>

<h1 class="mt-4"><?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') ? 'Admin Dashboard' : 'Author Dashboard'; ?></h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Activity Summary</li>
</ol>

<!-- WIDGETS ROW -->
<div class="row">
    
    <!-- POSTS WIDGET -->
    <div class="col-md-3">
        <div class="card-counter primary">
            <i class="fa-solid fa-file-lines"></i>
            <?php
            $query = "SELECT * FROM posts";
            $stmt = $pdo->query($query);
            $post_count = $stmt->rowCount();
            ?>
            <span class="count-numbers"><?php echo $post_count; ?></span>
            <span class="count-name">Posts</span>
        </div>
    </div>

    <!-- PUBLISHED WIDGET -->
    <div class="col-md-3">
        <div class="card-counter success">
            <i class="fa-solid fa-check-circle"></i>
            <?php
            $query = "SELECT * FROM posts WHERE post_status = 'published'";
            $stmt = $pdo->query($query);
            $pub_count = $stmt->rowCount();
            ?>
            <span class="count-numbers"><?php echo $pub_count; ?></span>
            <span class="count-name">Published</span>
        </div>
    </div>

    <!-- DRAFTS WIDGET -->
    <div class="col-md-3">
        <div class="card-counter danger">
            <i class="fa-solid fa-pen-ruler"></i>
            <?php
            $query = "SELECT * FROM posts WHERE post_status = 'draft'";
            $stmt = $pdo->query($query);
            $draft_count = $stmt->rowCount();
            ?>
            <span class="count-numbers"><?php echo $draft_count; ?></span>
            <span class="count-name">Drafts</span>
        </div>
    </div>

    <!-- USERS WIDGET -->
    <div class="col-md-3">
        <div class="card-counter info">
            <i class="fa-solid fa-users"></i>
            <?php
            $query = "SELECT * FROM users";
            $stmt = $pdo->query($query);
            $user_count = $stmt->rowCount();
            ?>
            <span class="count-numbers"><?php echo $user_count; ?></span>
            <span class="count-name">Users</span>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Recent Posts
            </div>
            <div class="card-body">
                <!-- Simple Table for recent activity -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM posts ORDER BY post_id DESC LIMIT 5";
                        $stmt = $pdo->query($query);
                        while($row = $stmt->fetch()){
                            echo "<tr>";
                            echo "<td>{$row['post_title']}</td>";
                            echo "<td>{$row['post_author']}</td>";
                            echo "<td>{$row['post_date']}</td>";
                            $status_class = ($row['post_status'] == 'published') ? 'text-success' : 'text-warning';
                            echo "<td class='{$status_class} fw-bold'>".ucfirst($row['post_status'])."</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>