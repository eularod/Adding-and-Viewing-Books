<?php

include "../library/book.php";
$bookObj = new Book();

$search = "";
$genre = "";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';
}

function e($str) {
return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$books = $bookObj->viewBooks($search, $genre);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Books</title>
    <link rel="stylesheet" href="viewBooks.css">
</head>
<body>
    <div class="container">
        <h1>View Books</h1>


        <form method="get" action="" class="search-container" aria-label="Search books">
            <label for="search">Search</label>
            <input type="search" id="search" name="search" value="<?= e($search) ?>" placeholder="Search by title or author">


            <label for="genre">Genre</label>
            <select id="genre" name="genre">
                <option value="" <?= ($genre === "") ? 'selected' : '' ?>>All</option>
                <option value="History" <?= ($genre === "History") ? 'selected' : '' ?>>History</option>
                <option value="Science" <?= ($genre === "Science") ? 'selected' : '' ?>>Science</option>
                <option value="Fiction" <?= ($genre === "Fiction") ? 'selected' : '' ?>>Fiction</option>
            </select>


            <input type="submit" value="Search">
            <br><br>
        </form>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Publication Year</th>
                    <th>Publisher</th>
                    <th>Copies</th>
                    <th>Action</th>
                </tr>
                <?php
                    $no = 1;
                    foreach($bookObj->viewBooks($search, $genre) as $book){
                        $message = "Are you sure you want to delete the book? " . $book["title"] . "?";
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $book['title'] ?></td>
                    <td><?= $book['author'] ?></td>
                    <td><?= $book['genre'] ?></td>
                    <td><?= $book['publication_year'] ?></td>
                    <td><?= $book['publisher'] ?></td>
                    <td><?= $book['copies'] ?></td>
                    <td>
                        <a href="editBooks.php?id=<?= $book["id"] ?>">Edit</a>
                        <a href="deleteBooks.php?id=<?= $book["id"] ?>" onclick="return confirm('<?= $message ?>')">Delete</a>
                 </tr>
                <?php
                }
                ?>
            </table>
            <button class="button" onclick="window.location.href='addBooks.php'">Add Books</button></a>
</body>
</html>
