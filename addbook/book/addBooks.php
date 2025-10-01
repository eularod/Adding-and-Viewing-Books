<?php

require_once "../library/book.php";
$bookObj = new Book();

$title = "";
$author = "";
$genre = "";
$publication_year = "";
$publisher = "";
$copies = "";

$title_error = "";
$author_error = "";
$genre_error = "";
$publication_year_error = "";
$publisher_error = "";
$copies_error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim(htmlspecialchars($_POST["title"] ?? ""));
    $author = trim(htmlspecialchars($_POST["author"] ?? ""));
    $genre = trim(htmlspecialchars($_POST["genre"] ?? ""));
    $publication_year = trim(htmlspecialchars($_POST["publication_year"] ?? ""));
    $publisher = trim(htmlspecialchars($_POST["publisher"] ?? ""));
    $copies = trim(htmlspecialchars($_POST["copies"] ?? ""));

    if (empty($title)) {
        $title_error = "Title is required";
    }

    if (empty($author)) {
        $author_error = "Author is required";
    }

    if (empty($genre)) {
        $genre_error = "Genre is required";
    }

    if (empty($publication_year)) {
        $publication_year_errorr = "Publication year is required";
    } else if (!filter_var($publication_year, FILTER_VALIDATE_INT)) {
        $publication_year_error = "Invalid publication year";
    } else if ($publication_year > date("Y")) {
        $publication_year_error = "Publication year cannot be in the future";
    }

    if (empty($publisher)) {
        $publisher_error = "Publisher is required";
    }

    if (empty($copies)) {
        $copies_error = "Copies is required";
    } else if (!filter_var($copies, FILTER_VALIDATE_INT) || $copies < 1) {
        $copies_error = "Copies must be a positive integer";
    }

    if(empty($title_error) && empty($author_error) && empty($genre_error) 
        && empty($publication_year_error) && empty($publisher_error) && empty($copies_error)) {
        
        try {
            $db = new Library();
            $conn = $db->connect();

            $stmt = $conn->prepare("INSERT INTO book (title, author, genre, publication_year, publisher, copies) 
                                    VALUES (:title, :author, :genre, :publication_year, :publisher, :copies)");
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":author", $author);
            $stmt->bindParam(":genre", $genre);
            $stmt->bindParam(":publication_year", $publication_year, PDO::PARAM_INT);
            $stmt->bindParam(":publisher", $publisher);
            $stmt->bindParam(":copies", $copies, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: viewBooks.php");
                exit();
            } else {
                echo "Error saving book.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
    <link rel="stylesheet" href="addBooks.css">
</head>
<body>
    <div class="container">
        <h1>Add Book</h1>
         <?php
        if (!empty($error)) {
            foreach ($error as $field => $msg) {
                if ($msg) {
                    echo "<p class='error-msg'>$msg</p>";
                }
            }
        }
        ?>

        <form method = "POST">
        <input type="text" name="title" placeholder="Title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"><br><br>

        <input type="text" name="author" placeholder="Author" required value="<?php echo isset($_POST['author']) ? htmlspecialchars($_POST['author']) : ''; ?>"><br><br>

        <select name="genre" id="genre" required>
            <option value="">Select Genre</option>
            <option value="history" <?php if(isset($_POST['genre']) && strtolower($_POST['genre'])=='history') echo 'selected'; ?>>History</option>
            <option value="science" <?php if(isset($_POST['genre']) && strtolower($_POST['genre'])=='science') echo 'selected'; ?>>Science</option>
            <option value="fiction" <?php if(isset($_POST['genre']) && strtolower($_POST['genre'])=='fiction') echo 'selected'; ?>>Fiction</option>
        </select><br><br>

        <input type="number" name="publication_year" placeholder="Publication Year" required value="<?php echo isset($_POST['publication_year']) ? htmlspecialchars($_POST['publication_year']) : ''; ?>"><br><br>

        <input type="text" name="publisher" placeholder="Publisher" required value="<?php echo isset($_POST['publisher']) ? htmlspecialchars($_POST['publisher']) : ''; ?>"><br><br>

        <input type="number" name="copies" placeholder="Copies" required value="<?php echo isset($_POST['copies']) ? htmlspecialchars($_POST['copies']) : '1'; ?>" min="1" required><br><br>


        <div class="button">
            <button class="view-button" onclick="window.location.href='viewBooks.php'">View Books</button></a>
            <button type="submit" class="save-button">Save Book</button>
        </div>
        </form>
</body>
</html>
