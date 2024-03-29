<?php
session_start();

if (isset($_SESSION["id"])) {
    $mysqli = require __DIR__ . "/connect.php";
    
    $sql = "SELECT * FROM user WHERE user_id = {$_SESSION["id"]}";
    $resultUser = $mysqli->query($sql);

    // Fetch user details
    $userDetails = $resultUser->fetch_assoc();
    $user_id = $userDetails['user_id'];
} else {
    echo "Najskor sa prihlas!!!";
}

if (isset($_POST['pridat'])) {
    $category_id = $_POST['category_id'];
    $title = $_POST['title'];
    $skola = $_POST['skola'];
    $content = $_POST['content'];

    if ($category_id == "" || $user_id == "" || $title == "" || $skola == "" || $content == "") {
        $err = true;
    } else if ($category_id == "-1") {
        $errReferat = true;
    } else {
        $sql = "INSERT INTO referaty (user_id, category_id, title, skola, content) VALUES ('$user_id','$category_id', '$title','$skola','$content')";
        $isInserted = $mysqli->query($sql);
        
        if ($isInserted) {
            // Handle PDF upload
            if ($_FILES["pdfFile"]["error"] === UPLOAD_ERR_OK) {
                $pdfName = $mysqli->real_escape_string($_FILES["pdfFile"]["name"]);
                $pdfData = file_get_contents($_FILES["pdfFile"]["tmp_name"]);
                
                $insertPdfSql = sprintf("INSERT INTO pdf_documents (name, data) VALUES ('%s', '%s')",
                    $pdfName,
                    $mysqli->real_escape_string($pdfData)
                );
                
                $resultPdf = $mysqli->query($insertPdfSql);
                
                if ($resultPdf) {
                    echo "PDF and Referat uploaded successfully.";
                } else {
                    echo "Error uploading PDF.";
                }
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
            }
            header("Location: home.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
        }
    }
}
?>