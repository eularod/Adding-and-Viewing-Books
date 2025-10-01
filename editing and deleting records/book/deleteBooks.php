<?php
require_once "../library/book.php";
$bookObj = new Book();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"])) {
        $pid = trim(htmlspecialchars($_GET["id"]));
        $book = $bookObj->fetchBook($pid);

        if (!$book) {
            echo "<p class='error-msg'>No book found with ID: " . htmlspecialchars($pid) . "</p>";
            echo "<a href='viewBooks.php'>View Books</a>";
            exit();
        } else {
            $deletedBookTitle = $book["title"];
            $deletedBookAuthor = $book["author"];

            if ($bookObj->deleteBook($pid)) {
                ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Book Deleted</title>
                    <link rel="stylesheet" href="addBooks.css">
                    <style>
                        .container { max-width: 600px; margin: 50px auto; text-align: center; }
                        .success-msg { color: blue; font-weight: bold; }
                        button { margin-top: 20px; padding: 10px 20px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1>Book Deleted</h1>
                        <p class="success-msg">The book "<strong><?= htmlspecialchars($deletedBookTitle) ?></strong>" by <strong><?= htmlspecialchars($deletedBookAuthor) ?></strong> has been successfully deleted.</p>
                        <button onclick="window.location.href='viewBooks.php'">Back to Book List</button>
                    </div>
                </body>
                </html>
                <?php
                exit();
            } else {
                echo "<p class='error-msg'>Failed to delete book with ID: " . htmlspecialchars($pid) . "</p>";
                echo "<a href='viewBooks.php'>View Books</a>";
                exit();
            }
        }
    } else {
        echo "<p class='error-msg'>No book ID provided for deletion.</p>";
        echo "<a href='viewBooks.php'>View Books</a>";
        exit();
    }
}
?>