# Home Page Architecture & User Flow

## Overview

This document explains the technical flow when a user lands on the home page (`index.php`). The page is dynamically assembled using PHP to combine reusable templates, database content, and layout logic.

## 1. Initialization & Structure

**File:** `index.php`
The page execution begins by establishing necessary connections and loading the visual framework.

- **Database Connection** (`includes/db.php`): Connects to the MySQL database to enable data retrieval.
- **Header & Navigation** (`includes/header.php`):
  - Starts the PHP session.
  - Loads HTML `<head>` (Bootstrap 5, FontAwesome, Custom CSS).
  - Renders the top Navigation Bar (Home, Login, Dashboard links).

## 2. The Hero Section

**File:** `index.php`
A static "Welcome" banner is displayed immediately after the header.

- **Visuals**: A full-width background image with an overlay and welcome text ("Welcome to BlogSphere").
- **Purpose**: To provide immediate visual impact and branding.

## 3. Content Generation (The Loop)

**File:** `index.php`
The core content area displays blog posts dynamically fetched from the database.

### A. Pagination Logic

Before querying for posts, the script calculates which subset of posts to display.

- **Logic**: Checks `$_GET['page']` to determine the offset.
- **Limit**: Defaults to 5 posts per page.

### B. Database Query

The application queries the `posts` table with specific criteria:

```sql
SELECT * FROM posts
WHERE post_status = 'published'
ORDER BY post_id DESC
LIMIT $start, $per_page
```

- **Filters**: Only shows 'published' posts (hides drafts).
- **Ordering**: Shows the newest posts first (`DESC`).

### C. Display Logic (Spotlight vs. Grid)

The loop iterates through the fetched posts and applies different HTML layouts based on the post's position:

1.  **Spotlight Post (First Item)**

    - **Condition**: The very first post on the first page.
    - **Layout**: Full-width card (`col-12`).
    - **Elements**: Large image, full metadata, and a prominent "Read Story" button.

2.  **Grid Posts (Subsequent Items)**
    - **Condition**: All other posts.
    - **Layout**: Two-column grid (`col-md-6`).
    - **Elements**: Smaller image, title, and a shorter excerpt.

## 4. The Sidebar

**File:** `includes/sidebar.php`
Rendered alongside the blog posts (on large screens), providing utility widgets.

- **Search Widget**: A form submitting to `search.php`.
- **Categories Widget**:
  - Queries the `categories` table.
  - Displays a list of topics with a dynamic count of posts in each category.
  - Links to `category.php` for filtering.

## Summary Flowchart

1.  **Request**: User visits `index.php`.
2.  **Bootstrap**: Server loads DB connection and Header.
3.  **Query**: Server fetches the 5 latest published posts.
4.  **Render Loop**:
    - _Post 1_ → **Spotlight Layout**
    - _Post 2-5_ → **Grid Layout**
5.  **Sidebar**: Server loads Search and Category widgets.
6.  **Response**: Complete HTML page is sent to the user's browser.
