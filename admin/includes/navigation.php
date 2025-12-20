<!-- Sidebar -->
<div id="sidebar-wrapper">
    <div class="sidebar-heading"><i class="fa-solid fa-gauge-high"></i> AdminPanel</div>
    <div class="list-group list-group-flush">
        <a href="index.php" class="list-group-item list-group-item-action">
            <i class="fa-solid fa-house"></i> Dashboard
        </a>
        <a href="posts.php" class="list-group-item list-group-item-action">
            <i class="fa-solid fa-file-pen"></i> View All Posts
        </a>
        <a href="posts.php?source=add_post" class="list-group-item list-group-item-action">
            <i class="fa-solid fa-plus-circle"></i> Add New Post
        </a>
        <?php if($_SESSION['user_role'] == 'admin'): ?>
        <a href="users.php" class="list-group-item list-group-item-action">
            <i class="fa-solid fa-users"></i> Users
        </a>
        <?php endif; ?>
        <a href="profile.php" class="list-group-item list-group-item-action">
            <i class="fa-solid fa-user-circle"></i> Profile
        </a>
        <a href="../index.php" class="list-group-item list-group-item-action">
            <i class="fa-solid fa-globe"></i> View Public Site
        </a>
        <a href="../logout.php" class="list-group-item list-group-item-action text-danger">
            <i class="fa-solid fa-power-off"></i> Logout
        </a>
    </div>
</div>
<!-- /#sidebar-wrapper -->

<!-- Page Content Wrapper -->
<div id="page-content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom mb-4">
        <div class="container-fluid">
            <span class="navbar-text ms-auto">
                Welcome, <strong><?php echo $_SESSION['username']; ?></strong>
            </span>
        </div>
    </nav>
    <div class="container-fluid px-4">