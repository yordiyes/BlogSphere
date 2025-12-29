# Object-Oriented Programming (OOP) Architecture in BlogSphere

## Overview

While the core logic of **BlogSphere** (routing, page rendering, form handling) is written in a **Procedural** style, the application leverages **Object-Oriented Programming (OOP)** exclusively for its **Database Abstraction Layer**.

This hybrid approach provides the simplicity of procedural scripts for frontend views while utilizing the security, robustness, and flexibility of OOP for database interactions via **PDO (PHP Data Objects)**.

There are no custom classes defined in your project (e.g., no class User or class Post). Instead, the project relies on PHP's built-in PDO **(PHP Data Objects)** class.

---

## 1. Class Instantiation (The `new` Keyword)

The entry point for OOP in this project is the creation of the Database Connection object.

**File:** `includes/db.php`

```php
// 'new' keyword creates a new Object instance from the PDO Class blueprint
$pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
```

- **Concept:** **Instantiation**.
- **Explanation:** We are taking the generic `PDO` class blueprint and building a specific "house" (object) configured with our specific database credentials. This `$pdo` variable is now an object that "knows" how to talk to our database.

---

## 2. Method Calls (The Arrow Operator `->`)

Once the `$pdo` object exists, we interact with it using **Methods** (functions that belong to an object) rather than global functions.

**File:** `index.php`

```php
// Calling the 'query' method belonging to the $pdo object
$stmt = $pdo->query($query);

// Calling the 'fetch' method belonging to the $stmt (Statement) object
while($row = $stmt->fetch()) { ... }
```

- **Concept:** **Encapsulation**.
- **Explanation:** The complex logic of communicating with MySQL is hidden _inside_ the `$pdo` object. We access this logic through its public interface (methods like `query()`, `prepare()`, `fetch()`). We don't need to know _how_ it fetches data, only that it does.

---

## 3. Prepared Statements (Object Interaction)

For security (specifically to prevent SQL Injection), the application uses **Prepared Statements**. This involves the interaction between two different types of objects: the Connection object (`PDO`) and the Statement object (`PDOStatement`).

**File:** `includes/login_process.php`

```php
// 1. The prepare() method returns a NEW object: a PDOStatement
$stmt = $pdo->prepare($query);

// 2. We call methods on this new $stmt object to bind data and run it
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
```

- **Concept:** **Object Chaining / Factory Pattern**.
- **Explanation:** The `$pdo` object acts as a factory that creates a `$stmt` object. This `$stmt` object is specialized; it holds the SQL query structure separate from the user data, ensuring that malicious code cannot be injected into the query logic.

---

## 4. Class Constants (The Scope Resolution Operator `::`)

The application uses static constants defined within the PDO class to configure behavior.

**File:** `includes/db.php`

```php
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
```

- **Concept:** **Static Constants**.
- **Explanation:** `PDO::FETCH_ASSOC` and `PDO::ERRMODE_EXCEPTION` are values that belong to the _Class_ itself, not any specific object instance. They act as global configuration flags.
  - `PDO::FETCH_ASSOC`: Instructs the object to return data as associative arrays (e.g., `$row['title']`) rather than numbered indices.

---

## 5. Exception Handling (`try...catch`)

OOP allows for structured error handling using **Exceptions**, which prevents the application from crashing silently or exposing sensitive error data to users.

**File:** `includes/db.php`

```php
try {
    $pdo = new PDO(...);
} catch(PDOException $e) {
    // If the object fails to build, it 'throws' an Exception object ($e)
    die("Connection failed: " . $e->getMessage());
}
```

- **Concept:** **Exceptions**.
- **Explanation:** If the connection fails, the PDO constructor "throws" a `PDOException` object. We "catch" this object in variable `$e` and use its `getMessage()` method to gracefully handle the error (e.g., logging it or showing a user-friendly message).
