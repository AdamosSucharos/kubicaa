<?php
session_start();

$mysqli = require __DIR__ . "/connect.php";

// Check if referat_id is set in the URL
if (isset($_GET['referat_id'])) {
    $referat_id = $_GET['referat_id'];

    // Fetch paper details from the database
    $sql = "SELECT referaty.*, category.category_name 
            FROM referaty 
            JOIN category ON referaty.category_id = category.category_id 
            WHERE referaty.referat_id = $referat_id";
    $result = $mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
        $paperDetails = $result->fetch_assoc();
        // You can now use $paperDetails to display the paper details on the page
    } else {
        // Handle the case where the paper with the given referat_id is not found
        echo "Paper not found.";
    }
} else {
    // Handle the case where referat_id is not set in the URL
    echo "Invalid request.";
}

$mysqli->close();
?>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        p {
            margin-bottom: 15px;
        }
    </style>
<!DOCTYPE html>
<html lang="en">

<body>
    <?php require_once("header.php"); ?>

    <div class="container">
        <!-- Display paper details here using $paperDetails -->

        <!-- Example: -->
        <h1><?php echo $paperDetails['title']; ?></h1>
        <p>Category: <?php echo $paperDetails['category_name']; ?></p>
        <p>Content: <?php echo $paperDetails['content']; ?></p>
        <!-- Add more details as needed -->
    </div>

</body>
</html>

