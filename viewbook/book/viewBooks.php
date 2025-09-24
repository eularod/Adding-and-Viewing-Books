<?php

include "../library/book.php";

$bookObj = new Book();

$search = $category = "";

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $search = isset($_GET["search"]) ? trim(htmlspecialchars($_GET["search"])) : "";
    $genre = isset($_GET["genre"]) ? trim(htmlspecialchars($_GET["genre"])) : "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Books</title>
    <link rel="stylesheet" href="viewBooks.css">
</head>
<body>
    <div class="container">
        <h1>View Books</h1>

        <form action="" method="get">
            <div class="search-container">
            <label for="search">Search:</label>
            <input type="search" name="search" id="search" value="<?= $search ?>">
    
            <select name="genre" id="genre">
                <option value="">All</option>
                <option value="History" <?= (isset($genre) && $genre == "History")? "selected":"" ?>>History</option>
                <option value="Science" <?= (isset($genre) && $genre == "Science")? "selected":"" ?>>Science</option>
                <option value="Fiction" <?= (isset($genre) && $genre == "Fiction")? "selected":"" ?>>Fiction</option>
            </select>

            <input type="submit" value="Search">
            <br>
            <br>
            </div>
        </form>

        <table border="1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Publication Year</th>
                    <th>Publisher</th>
                    <th>Copies</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            $books = $bookObj->viewBooks($search, $genre);
            if (!empty($books)) {
                foreach ($books as $book) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $book["title"] ?></td>
                        <td><?= $book["author"] ?></td>
                        <td><?= $book["genre"] ?></td>
                        <td><?= $book["publication_year"] ?></td>
                        <td><?= $book["publisher"] ?></td>
                        <td><?= $book["copies"] ?></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='7' >No books found!</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <br>
        <button class="button" onclick="window.location.href='addBooks.php'">Add Books</button></a>
    </div>
</body>
</html>

