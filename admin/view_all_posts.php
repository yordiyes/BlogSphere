<?php
// Handle Bulk Actions
if(isset($_POST['checkBoxArray'])){
    foreach($_POST['checkBoxArray'] as $postValueId ){
        $bulk_options = $_POST['bulk_options'];
        switch($bulk_options) {
            case 'published':
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$postValueId]);
                break;
            case 'draft':
                $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$postValueId]);
                break;
            case 'delete':
                $query = "DELETE FROM posts WHERE post_id = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$postValueId]);
                break;
        }
    }
}

if(isset($_GET['delete'])){
    $the_post_id = $_GET['delete'];
    if(isset($_SESSION['user_role'])){
        // Check ownership if not admin
        if($_SESSION['user_role'] != 'admin') {
            $check_query = "SELECT post_author FROM posts WHERE post_id = ?";
            $check_stmt = $pdo->prepare($check_query);
            $check_stmt->execute([$the_post_id]);
            $post = $check_stmt->fetch();
            if($post['post_author'] != $_SESSION['username']) {
                die("Unauthorized Action");
            }
        }
        $query = "DELETE FROM posts WHERE post_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$the_post_id]);
        echo "<script>window.location.href='posts.php';</script>";
    }
}
?>

<form action="" method='post'>
    <div class="row align-items-center mb-3">
        <div class="col-md-4">
            <select class="form-select" onchange="location = this.value;">
                <?php
                $cat_part = isset($_GET['category']) ? "category=" . $_GET['category'] : "";
                
                $all_status_url = "posts.php" . ($cat_part ? "?" . $cat_part : "");
                
                if($cat_part) {
                    $pub_url = "posts.php?" . $cat_part . "&status=published";
                    $draft_url = "posts.php?" . $cat_part . "&status=draft";
                } else {
                    $pub_url = "posts.php?status=published";
                    $draft_url = "posts.php?status=draft";
                }
                ?>
                <option value="<?php echo $all_status_url; ?>">All Status</option>
                <option value="<?php echo $pub_url; ?>" <?php echo (isset($_GET['status']) && $_GET['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                <option value="<?php echo $draft_url; ?>" <?php echo (isset($_GET['status']) && $_GET['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
            </select>
        </div>
        <div class="col-md-4">
            <select class="form-select" onchange="location = this.value;">
                <?php
                $status_param = isset($_GET['status']) ? "&status=" . $_GET['status'] : "";
                $all_cats_url = "posts.php" . ($status_param ? "?status=" . $_GET['status'] : "");
                ?>
                <option value="<?php echo $all_cats_url; ?>">All Categories</option>
                <?php
                $query_cat_filter = "SELECT * FROM categories";
                $stmt_cat_filter = $pdo->query($query_cat_filter);
                while($row_cat = $stmt_cat_filter->fetch()) {
                    $cat_id = $row_cat['cat_id'];
                    $cat_title = $row_cat['cat_title'];
                    $selected = (isset($_GET['category']) && $_GET['category'] == $cat_id) ? "selected" : "";
                    $url = "posts.php?category={$cat_id}{$status_param}";
                    echo "<option value='{$url}' {$selected}>{$cat_title}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4 text-end">
            <a class="btn btn-primary" href="posts.php?source=add_post"><i class="fa-solid fa-plus-circle"></i> Add New Post</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th><input id="selectAllBoxes" type="checkbox"></th>
                    <th>ID</th>
                    <th>Author</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Build Query based on Permissions and Category Filter
            $where_clauses = [];
            $params = [];

            if($_SESSION['user_role'] != 'admin') {
                $where_clauses[] = "post_author = ?";
                $params[] = $_SESSION['username'];
            }

            if(isset($_GET['category'])) {
                $where_clauses[] = "post_category_id = ?";
                $params[] = $_GET['category'];
            }

            if(isset($_GET['status'])) {
                $where_clauses[] = "post_status = ?";
                $params[] = $_GET['status'];
            }

            $query = "SELECT * FROM posts";
            if(!empty($where_clauses)) {
                $query .= " WHERE " . implode(" AND ", $where_clauses);
            }
            $query .= " ORDER BY post_id DESC";

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            while($row = $stmt->fetch()){
                $post_id = $row['post_id'];
                $post_author = $row['post_author'];
                $post_title = $row['post_title'];
                $post_category_id = $row['post_category_id'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                $post_date = $row['post_date'];

                // Get Category Title
                $query_cat = "SELECT cat_title FROM categories WHERE cat_id = ?";
                $stmt_cat = $pdo->prepare($query_cat);
                $stmt_cat->execute([$post_category_id]);
                $cat_row = $stmt_cat->fetch();
                $post_category_title = ($cat_row) ? $cat_row['cat_title'] : "Uncategorized";

                // Status Badge Color
                $status_badge = ($post_status == 'published') ? 'badge bg-success' : 'badge bg-warning text-dark';

                echo "<tr>";
                ?>
                <td><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="<?php echo $post_id; ?>"></td>
                <?php
                echo "<td>$post_id</td>";
                echo "<td><i class='fa-regular fa-user'></i> $post_author</td>";
                echo "<td class='fw-bold'>$post_title</td>";
                echo "<td>$post_category_title</td>";
                echo "<td><span class='{$status_badge}'>$post_status</span></td>";
                echo "<td><img class='rounded' width='50' src='../images/$post_image' alt='post img'></td>";
                echo "<td>$post_date</td>";
                echo "<td>
                        <a class='btn btn-primary btn-sm' href='posts.php?source=edit_post&p_id={$post_id}'><i class='fa-solid fa-pen'></i> Edit</a>
                        <a class='btn btn-danger btn-sm' onClick=\"javascript: return confirm('Are you sure you want to delete?'); \" href='posts.php?delete={$post_id}'><i class='fa-solid fa-trash'></i> Delete</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</form>

<script>
document.getElementById('selectAllBoxes').onclick = function() {
    var checkboxes = document.getElementsByClassName('checkBoxes');
    for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
}
</script>