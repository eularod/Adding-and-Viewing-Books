<?php

require_once "../library/book.php";
$bookObj = new Book();

$pid = $_GET["id"] ?? null;

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

if ($_SERVER["REQUEST_METHOD"] == "GET" && $pid) {
    $book = $bookObj->fetchBook($pid);

    if (!$book) {
        echo "<p class='error-msg'>No book found with ID: " . htmlspecialchars($pid) . "</p>";
        echo "<a href='viewBooks.php'>View Books</a>";
        exit();
    } else {
        $title = $book["title"];
        $author = $book["author"];
        $genre = $book["genre"];
        $publication_year = $book["publication_year"];
        $publisher = $book["publisher"];
        $copies = $book["copies"];
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $pid = $_POST["id"];

    $title = trim($_POST["title"] ?? "");
    $author = trim($_POST["author"] ?? "");
    $genre = trim($_POST["genre"] ?? "");
    $publication_year = trim($_POST["publication_year"] ?? "");
    $publisher = trim($_POST["publisher"] ?? "");
    $copies = trim($_POST["copies"] ?? "");

    if (empty($title)) $title_error = "Title is required";
    if (empty($author)) $author_error = "Author is required";
    if (empty($genre)) $genre_error = "Genre is required";

    if (empty($publication_year)) {
        $publication_year_error = "Publication year is required";
    } elseif (!filter_var($publication_year, FILTER_VALIDATE_INT)) {
        $publication_year_error = "Invalid publication year";
    } elseif ($publication_year > date("Y")) {
        $publication_year_error = "Publication year cannot be in the future";
    }

    if (empty($publisher)) $publisher_error = "Publisher is required";

    if (empty($copies)) {
        $copies_error = "Copies is required";
    } elseif (!filter_var($copies, FILTER_VALIDATE_INT) || $copies < 1) {
        $copies_error = "Copies must be a positive integer";
    }

    if (!$title_error && !$author_error && !$genre_error && !$publication_year_error && !$publisher_error && !$copies_error) {
        $bookObj->title = $title;
        $bookObj->author = $author;
        $bookObj->genre = $genre;
        $bookObj->publication_year = $publication_year;
        $bookObj->publisher = $publisher;
        $bookObj->copies = $copies;

        if ($bookObj->editBook($pid)) {
            header("Location: viewBooks.php?updated=1");
            exit();
        } else {
            echo "<p class='error-msg'>Error updating book.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="addBooks.css">
    <style>
        label{ display:block; margin-top:10px; }
        span.error{ color:red; font-size:13px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Book</h1>

        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($pid) ?>">

            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required>
            <span class="error"><?= $title_error ?></span>

            <label>Author</label>
            <input type="text" name="author" value="<?= htmlspecialchars($author) ?>" required>
            <span class="error"><?= $author_error ?></span>

            <label>Genre</label>
            <select name="genre" required>
                <option value="">Select Genre</option>
                <option value="History" <?= ($genre == "History") ? "selected" : "" ?>>History</option>
                <option value="Science" <?= ($genre == "Science") ? "selected" : "" ?>>Science</option>
                <option value="Fiction" <?= ($genre == "Fiction") ? "selected" : "" ?>>Fiction</option>
            </select>
            <span class="error"><?= $genre_error ?></span>

            <label>Publication Year</label>
            <input type="number" name="publication_year" value="<?= htmlspecialchars($publication_year) ?>" required>
            <span class="error"><?= $publication_year_error ?></span>

            <label>Publisher</label>
            <input type="text" name="publisher" value="<?= htmlspecialchars($publisher) ?>" required>
            <span class="error"><?= $publisher_error ?></span>

            <label>Copies</label>
            <input type="number" name="copies" min="1" value="<?= htmlspecialchars($copies ?: 1) ?>" required>
            <span class="error"><?= $copies_error ?></span>

            <div class="button">
                <button type="button" class="view-button" onclick="window.location.href='viewBooks.php'">View Books</button>
                <button type="submit" class="save-button">Update Book</button>
            </div>
        </form>
    </div>
</body>
</html>
