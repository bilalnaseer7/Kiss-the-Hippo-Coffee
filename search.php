<?php
//connect to database
$path = "/home/ek4138/databases";
$db = new SQLite3($path . "/users.db");

//create table if table does not exist 
$sqlCreateTable = " CREATE TABLE IF NOT EXISTS search (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    description TEXT NOT NULL,
                    image TEXT NOT NULL,
                    link TEXT NOT NULL
); "; 
$db->exec($sqlCreateTable);

//insert coffee beans & equipments
$empty = $db->query("SELECT id FROM search");
$check = $empty->fetchArray(); 

if (!$check){
    $sqlInsert = "INSERT INTO search (name, description, image, link) VALUES
        ('Christmas Blend', 'Limited for a reason. Meaningful by design. Crafted with care, from origin to roast, delivering festive flavour with purpose.', 'images/christmasblend.png', 'shopchristmas.html'),
        ('Honduras, Nancy Parainema Blend', 'Fruit-driven coffee with enough richness to stay balanced. Effortless to brew and perfect for any time of day.', 'images/NancyP.png', 'shopnancy.html'),
        ('Colombia, Leonid Ramirez Blend', 'Colombian coffee with rich notes of fermented plum, raisin, and buttery shortbread. Deep, sweet, and irresistibly smooth.', 'images/LeonidR.png', 'shopleonid.html'),
        ('Fruitopia Blend', 'A vibrant cup layered with fresh fruit sweetness and soft caramel depth. Elegant, expressive, and beautifully bright.', 'images/fruitopia.png', 'shopfruit.html'),
        ('George Street Blend', 'Layered with chocolate richness, gentle berry notes, and a lingering butterscotch sweetness. Elegant and satisfying.', 'images/georgestreet.png', 'shopgeorge.html'),
        ('Peru, La Montana Field Blend', 'Elegant and expressive. Pairs fresh green apple acidity with caramel richness and subtle nutty depth.', 'images/peru.png', 'shopperu.html'),
        ('Fellow Ode V2 Grinder', 'Designed for precision and ease, the Fellow Ode Gen 2 delivers consistent, café-quality grinding in a refined, modern form.', 'images/fellowgrinder.png', 'coffeebean.html'),
        ('Wilfa Uniform Coffee Grinder', 'Refined grinder by Tim Wendelboe, delivering quiet precision and exceptional flavour in a minimalist Scandinavian form.', 'images/wilfa.png', 'coffeebean.html'),
        ('Wilfa Classic Tall Brewer', 'Delivers quality coffee with precise temperature control. Brews 10 cups in minutes with consistently smooth, balanced flavor.', 'images/brewer.png', 'coffeebean.html')
    ";
    $db->exec($sqlInsert);
}

//get the search term from form 
$searchterm = $_GET['searchterm'];

//select query 
$sqlSelect = "SELECT id, name, description, image, link FROM search 
                WHERE name LIKE :keyword OR
                description LIKE :keyword;"; 

//prepare sql & execute query
$stmt = $db->prepare($sqlSelect); 
$stmt->bindValue(':keyword', '%' . $searchterm . '%', SQLITE3_TEXT); 
$result = $stmt->execute(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results for: 
        <?php 
            echo $searchterm; 
        ?>
    </title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;            
            background-color: #F5F1EB;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .searchresult {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center; 
            margin-top: 20px; 
            justify-content: center; 
        }

        .coffee-card {
            width: 100%; 
            max-width: 600px; 
            text-align: center; 
            background-color: #ffffff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .coffee-image {
            width: 100%;
            display: flex;
            align-items: center; 
            justify-content: center; 
            padding: 40px 0; 
        }

        .coffee-grid {
            display: flex;
            justify-content: center; 
        }

        .coffee-image img {
            width: 35%;
            height: auto;
            display: block; 
            max-width: 250px; 
        }

        .coffee-info {
            justify-content: center; 
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .coffee-name {
            font-family: Georgia, "Times New Roman", Times, serif;
            font-size: 22px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 10px;
            color: #1a1a1a;
        }

        .coffee-desc {
            font-family: Georgia, "Times New Roman", Times, serif;
            font-size: 14px;
            line-height: 1.7;
            color: #666666;
            margin: 0 auto 15px; 
            text-align: center; 
            max-width: 95%; 
            width: auto; 
        }

        .coffee-link {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            letter-spacing: 1px;
            color: #1a1a1a;
            text-decoration: underline;
            cursor: pointer;
        }

        .home-link {
            display: inline-block;
            margin-top: 30px;
            padding: 15px 40px;
            background-color: #1a1a1a;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            letter-spacing: 1px;
        }

        .home-link:hover {
            background-color: #333;
        }


        .footer-text {
            font-size: 12px;
            color: #999;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<!--display search result-->
<div class="searchresult">
    <?php $exist = false; 

        while ($row = $result->fetchArray()) {
                $exist = true; 
                $name = $row['name']; 
                $description = $row['description'];
                $image = $row['image'];
                $link = $row['link']; 

                echo "
                <div class='coffee-grid'>
                    <div class='coffee-card'>
                        <div class='coffee-image'>
                                <img src='$image' alt='$name'>
                        </div>

                        <div class='coffee-info'>
                            <h3 class='coffee-name'>$name</h3>
                            <p class='coffee-desc'>$description</p>
                            <a href='$link' class='coffee-link'>Learn More</a>
                        </div>
                    </div>
                </div>
                ";
        }

        //if search term does not exist 
        if (!$exist) {
            echo "<p>Apologies, no products match your search</p>";
        }

        //close db
        $db->close();
    ?>
    
</body>
</html> 