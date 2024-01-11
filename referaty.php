<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pridavanie referátu</title>
    <link rel="stylesheet" href="referaty.css?rand<?php echo rand (1,90);?>">

</head>
<body>
<?php

require_once("connect.php");

$err = 0;
$errReferat = false;
?>


<div class="container">
        <h1>Pridanie referátu</h1>
        <form action="process-pridanie.php" method="post">
                <label for="title">Názov</label><br>
                <input type="text" id="title" name="title">

                <label for="category_name">Kategória:</label>
                <select name="category_id" id="category_name">
                    <option value="-1"></option>
                    <?php
                        $sqlSelect = "SELECT category_id, category_name FROM category";
                        $resultCategory = $mysqli->query($sqlSelect);

                        if ($resultCategory->num_rows > 0) {
                            $categories = $resultCategory->fetch_all(MYSQLI_ASSOC);
                        }

                        foreach ($categories as $cat) {
                            ?>
                            <option value="<?php echo $cat['category_id'] ?>">
                                <?php echo $cat['category_name'] ?>
                            </option>
                            <?php
                        }
                    ?>
                </select>
                
                <label for="skola">Vyber školu:</label>
                <select id="skola" name="skola">
                    <option value="ZŠ">Základná škola</option>
                    <option value="SŠ">Stredná škola</option>
                    <option value="VŠ">Vysoká škola</option>
                </select>

                <label for="pdfFile">Sem vlož PDF:</label>
                <input type="file" name="content" id="content" accept=".pdf" required>
                            
                    </br>
                <input type="submit" value="Pridať" name="pridat">

        </form>
</div>

</body>
</html>