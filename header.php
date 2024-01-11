
<link rel="stylesheet" href="header.css?rand<?php echo rand (1,90);?>">

<header>
    <nav>
        <ul>
            <li><a href="home.php">Domov</a></li>
            <li><a href="info.php">O nás</a></li>
            <li><a href="kontakt.php">Kontakt</a></li>
        </ul>
    </nav>
    
    <form action="search.php" method="GET">
    <input type="text" name="search" placeholder="Hľadaj .. ">
    <input type="submit" value="Search">
    </form>

    
<?php

    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        // Perform a database query to search for products
        $sql = "SELECT * FROM referaty WHERE title LIKE '%$search%'";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Hľadaj...:</h2>";
            while ($row = $result->fetch_assoc()) {
                echo "Názov referátu: " . $row["title"] . "<br>";
                // Output other product information as needed
            }
        } else {
            echo "No results found.";
        }
    }
   if (isset($user) || isset($admin)): ?>
        <div class="button-container">
            <button class="logout-button"><a href="logout.php">Odhlásiť</a></button>
            <button class="add-button"><a href="referaty.php">Pridaj referáty</a></button>
        </div>
    <?php else: ?>
        <div class="button-container">
            <button class="register-button"><a href="register.php">Register</a></button>
            <button class="login-button"><a href="login.php">Prihlásiť sa</a></button>
        </div>
    <?php endif;

    ?>
</header>