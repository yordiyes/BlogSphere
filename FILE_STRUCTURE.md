# Project File Structure & Purpose

## 1. Root Directory (Public Facing)

These files constitute the public interface of the BlogSphere application.

- **`index.php`** (Home Page): The main entry point. Dynamically fetches and displays the latest blog posts in a magazine-style layout.
- **`post.php`** (Single Post): Displays the full content of a specific blog post when a user clicks "Read More".
- **`category.php`** (Category Archive): Filters and displays posts belonging to a specific category (e.g., "Technology").
- **`search.php`** (Search Results): Handles search queries and displays matching posts.
- **`login.php`** (Login Form): Interface for users to authenticate.
- **`register.php`** (Registration): Interface for new users to create an account.
- **`logout.php`** (Session Destroyer): Terminates the user session and redirects to the home page.
- **`database.sql`** (Schema): SQL script defining the database structure (`users`, `posts`, `categories`) and initial seed data.

## 2. Includes Directory (`includes/`)

Reusable components and logic shared across the public pages.

- **`db.php`** (Database Connection): Establishes the PDO connection to the MySQL database.
- **`header.php`** (Page Header): Contains HTML `<head>`, CSS imports, and the main Navigation Bar.
- **`footer.php`** (Page Footer): Contains closing HTML tags and JavaScript imports.
- **`sidebar.php`** (Sidebar Widget): Displays the Search bar and Category list on the side of blog pages.
- **`functions.php`** (Helper Library): Contains utility functions, primarily for security (CSRF token generation/validation).
- **`login_process.php`** (Auth Logic): Backend script that processes login form submissions, verifies credentials, and sets session variables.

## 3. Admin Directory (`admin/`)

The protected content management system (CMS) for administrators.

- **`index.php`** (Dashboard): The landing page for logged-in admins, displaying overview statistics.
- **`posts.php`** (Controller): A routing file that determines which post-related view to load (View All, Add, or Edit).
- **`view_all_posts.php`** (Post List): Displays a table of all posts with options to edit or delete.
- **`add_post.php`** (Create Form): Interface for publishing new blog posts.
- **`edit_post.php`** (Update Form): Interface for modifying existing posts.
- **`categories.php`** (Category Manager): Interface for adding and deleting post categories.
- **`users.php`** (User Manager): Interface for managing registered users and their roles.
- **`profile.php`** (Profile Settings): Allows the logged-in admin to update their personal details.
- **`admin/includes/`**: Contains `header.php`, `footer.php`, and `navigation.php` specific to the admin panel layout.

## 4. Assets

- **`css/style.css`**: Custom stylesheets for the application.
- **`images/`**: Storage directory for uploaded post images and user avatars.
