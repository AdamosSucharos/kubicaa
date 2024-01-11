<?php
session_start();

$mysqli = require __DIR__ . "/connect.php";

// Initialize $categories and $subcategories arrays
$categories = [];
$subcategories = [];

if (isset($_SESSION["id"])) {
    $sql = "SELECT * FROM user WHERE user_id = {$_SESSION["id"]}";
    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if (isset($user["id"]) && $user["id"] == 3) {
        $admin = true;
    } else {
        $admin = false;
    }
}

$mysqli->select_db("kubica_soc"); // Select the database

// Fetch data for the table
$referatyData = [];
$result = $mysqli->query("SELECT referaty.referat_id, referaty.title, category.category_name, referaty.created_at, referaty.skola, user.name 
                          FROM referaty 
                          JOIN category ON referaty.category_id = category.category_id 
                          JOIN user ON referaty.user_id = user.user_id");

while ($row = $result->fetch_assoc()) {
    $referatyData[] = $row;
}

// Fetch main categories
$result = $mysqli->query("SELECT * FROM category WHERE parent_category_id IS NULL");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch subcategories
$result = $mysqli->query("SELECT * FROM category WHERE parent_category_id IS NOT NULL");
while ($row = $result->fetch_assoc()) {
    $subcategories[$row['parent_category_id']][] = $row;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?rand<?php echo rand(1, 90); ?>">
    <title>ťaháky</title>
</head>
<body>
    <?php require_once("header.php"); ?>

    <ul id="categoriesList">
        <?php foreach ($categories as $category): ?>
            <li class="category" data-category-id="<?php echo $category['category_id']; ?>">
                <?php echo $category['category_name']; ?>
                <ul>
                    <?php if (isset($subcategories[$category['category_id']])): ?>
                        <?php foreach ($subcategories[$category['category_id']] as $subcategory): ?>
                            <li><?php echo $subcategory['category_name']; ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var categoryElements = document.querySelectorAll(".category");

            categoryElements.forEach(function (categoryElement) {
                categoryElement.addEventListener("click", function () {
                    var subcategoriesList = categoryElement.querySelector("ul");
                    subcategoriesList.style.display = (subcategoriesList.style.display === "none") ? "block" : "none";
                });
            });
        });
    </script>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Created At</th>
                <th>School</th>
                <th>User</th>
                <?php if (isset($admin)): ?>
                    <th>Delete</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($referatyData as $row): ?>
                <tr>
                    <td><a href="view_paper.php?referat_id=<?php echo $row['referat_id']; ?>"><?php echo $row['title']; ?></a></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td><?php echo $row['skola']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <?php if (isset($admin)): ?>
                        <td><button class="delete-button" data-referat-id="<?php echo $row['referat_id']; ?>">Delete</button></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var deleteButtons = document.querySelectorAll(".delete-button");

            deleteButtons.forEach(function (button) {
                button.addEventListener("click", function () {
                    var referatId = button.getAttribute("data-referat-id");

                    if (confirm("Are you sure you want to delete this referat?")) {
                        // Send an AJAX request to delete the referat
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "delete_referat.php", true);
                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                // Reload the page after successful deletion
                                window.location.reload();
                            }
                        };
                        xhr.send("referat_id=" + referatId);
                    }
                });
            });
        });
    </script>

    
</body>
</html>
