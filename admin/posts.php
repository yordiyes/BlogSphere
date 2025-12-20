<?php include "includes/header.php"; ?>
<?php include "includes/navigation.php"; ?>

<h1 class="mt-4">Post Management</h1>
<hr>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <?php
        if(isset($_GET['source'])){
            $source = $_GET['source'];
        } else {
            $source = '';
        }

        switch($source) {
            case 'add_post':
                include "add_post.php";
                break;
            case 'edit_post':
                include "edit_post.php";
                break;
            default:
                include "view_all_posts.php";
                break;
        }
        ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>