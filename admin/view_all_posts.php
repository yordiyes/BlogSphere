<?php
if(isset($_GET['delete'])){
    $the_post_id = $_GET['delete'];
    if(isset($_SESSION['user_role'])){
        $query = "DELETE FROM posts WHERE post_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$the_post_id]);
        echo "<script>window.location.href='posts.php';</script>";
    }
}
?>

<div class="table-responsive">
    <table class="table table-hover table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Author</th>
                <th>Title</th>
                <th>Status</th>
                <th>Image</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM posts ORDER BY post_id DESC";
            $stmt = $pdo->query($query);

            while($row = $stmt->fetch()){
                $post_id = $row['post_id'];
                $post_author = $row['post_author'];
                $post_title = $row['post_title'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                $post_date = $row['post_date'];

                // Status Badge Color
                $status_badge = ($post_status == 'published') ? 'badge bg-success' : 'badge bg-warning text-dark';

                echo "<tr>";
                echo "<td>$post_id</td>";
                echo "<td><i class='fa-regular fa-user'></i> $post_author</td>";
                echo "<td class='fw-bold'>$post_title</td>";
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