# BlogSphere Project Guide

This document serves as a comprehensive guide for defending the **BlogSphere** project. It covers the technical architecture, database design, application flow, and key features implemented in the system.

---

## 1. Project Overview

**BlogSphere** is a dynamic content management system (CMS) designed for blogging. It allows users to read posts, register as subscribers, and for administrators to manage content, users, and categories.

### **Core Technologies Used**

- **Backend Language:** PHP (Hypertext Preprocessor)
- **Database:** MySQL (Relational Database Management System)
- **Database Interaction:** PDO (PHP Data Objects) for secure and object-oriented database access.
- **Frontend:** HTML5, CSS3, Bootstrap 5 (for responsive design).
- **Server Environment:** Apache (via XAMPP).

---

## 2. Database Architecture

The application uses a relational database named `blogsphere`.

### **Tables & Structure**

The database consists of **3 main tables**:

1.  **`users`**
    - **Purpose:** Stores user account information.
    - **Key Columns:** `user_id` (PK), `username`, `user_password` (Hashed), `user_email`, `user_role` (admin/subscriber).
2.  **`categories`**

    - **Purpose:** Organizes posts into different topics.
    - **Key Columns:** `cat_id` (PK), `cat_title`.

3.  **`posts`**
    - **Purpose:** Stores the main blog content.
    - **Key Columns:** `post_id` (PK), `post_category_id` (FK), `post_title`, `post_author`, `post_content`, `post_status` (draft/published).

### **Entity Relationships (ER Diagram Context)**

- **Users to Posts:** _One-to-Many_
  - One User (Author) can write multiple Posts.
  - Linked via `post_author` (or `post_user`) in the `posts` table.
- **Categories to Posts:** _One-to-Many_
  - One Category can have multiple Posts.
  - Linked via `post_category_id` in the `posts` table referencing `cat_id` in `categories`.

---

## 3. Technical Implementation Details

### **Object-Oriented Programming (OOP) Usage**

While the core application logic is procedural (using functions and script files), **OOP** is strictly used for Database Interaction via **PDO (PHP Data Objects)**.

- **Why PDO?** It provides a consistent interface for accessing databases and supports **Prepared Statements**, which are crucial for preventing SQL Injection attacks.
- _Example:_ `$pdo->prepare("SELECT * FROM users WHERE id = ?")`

### **Security Measures**

1.  **SQL Injection Prevention:** All database queries use **Prepared Statements** with bound parameters.
2.  **Password Security:** Passwords are never stored in plain text. We use `password_hash()` (Bcrypt) for storage and `password_verify()` for login.
3.  **CSRF Protection:** Cross-Site Request Forgery tokens are generated (`bin2hex(random_bytes(32))`) and validated on forms (Login, Register) to prevent unauthorized form submissions.
4.  **Session Management:** `session_start()` is used to track logged-in users and restrict access to admin pages.
5.  **Form Validation:**
    - **Client-Side:** HTML5 `required` attributes ensure fields are not submitted empty.
    - **Server-Side:** PHP checks ensure data integrity (e.g., unique usernames/emails, password length, valid email format) before processing.

### **Data Handling**

- **Image Storage:**
  - Images are **NOT** stored directly in the database (BLOB).
  - Instead, the physical image files are uploaded to the server's file system (specifically the `images/` directory).
  - The database only stores the **filename** (string) as a reference to the image.
  - _Why?_ This keeps the database lightweight and improves performance.

---

## 4. Application Flow

### **Public Area (Frontend)**

1.  **Entry Point (`index.php`):**
    - Displays the latest "Published" posts.
    - Includes a Hero/Welcome section.
    - Pagination is implemented to limit posts per page.
2.  **Single Post (`post.php`):**
    - Clicking a post title takes the user here.
    - Displays full content, author, date, and image.
3.  **Authentication:**
    - **Login (`login.php`):** Validates credentials and starts a session.
    - **Register (`register.php`):** Creates a new user with the default role of 'subscriber'.

### **Admin Dashboard (Backend)**

_Access is restricted to logged-in users._

1.  **Dashboard (`admin/index.php`):**
    - Shows statistics (Total Posts, Published Posts, Drafts, Users).
    - Visual widgets for quick insights.
2.  **Post Management (`admin/posts.php`):**
    - **View All:** Table listing all posts with Edit/Delete actions.
    - **Add Post:** Form to create new content (Title, Category, Image, Content).
    - **Edit Post:** Form to update existing content.
3.  **Category Management (`admin/categories.php`):**
    - Add, Edit, and Delete categories.
4.  **User Management (`admin/users.php`):**
    - _Admin Only:_ View all users, change roles (Subscriber <-> Admin), delete users.
5.  **Profile (`admin/profile.php`):**
    - Allows the logged-in user to update their own details (Password, Email, Name).

---

## 5. Key Questions & Answers

**Q: How many tables do you have?**
_A: We have 3 primary tables: Users, Posts, and Categories._

**Q: What is the relationship between Posts and Categories?**
_A: It is a One-to-Many relationship. One category can contain many posts._

**Q: Did you use OOP?**
_A: Yes, we utilized Object-Oriented Programming specifically for the database layer using the PDO (PHP Data Objects) library to ensure security and scalability._

**Q: How do you handle security?**
_A: We implemented Prepared Statements to stop SQL Injection, Bcrypt hashing for passwords, and CSRF tokens to prevent form forgery._

**Q: How does the login system work?**
_A: It verifies the username against the database, checks the hashed password using `password_verify()`, and if successful, stores the user's ID and Role in the `$_SESSION` superglobal._

**Q: Where are the images stored?**
_A: The actual image files are stored in the `images/` folder on the server. The database only saves the name of the file (e.g., `image.jpg`) so the application knows which image to display._

**Q: How do you validate user input?**
_A: We use a dual-layer approach. HTML5 attributes provide immediate feedback in the browser, while PHP scripts perform strict checks on the server (checking for empty fields, valid email formats, and unique usernames) to ensure data integrity._
