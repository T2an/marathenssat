<!DOCTYPE html>
<html>
<head>
    <title>Image en gros plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        #image-container {
            max-width: 80%;
            margin: 0 auto;
        }

        img {
            max-width: 100%;
            border: 2px solid #333;
            background-color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        a {
            display: block;
            text-align: center;
            font-size: 18px;
            text-decoration: none;
            margin-top: 10px;
            background-color: #333;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }

        a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div id="image-container">
        <?php
        if (isset($_GET['image'])) {
            $imagePath = htmlspecialchars($_GET['image']); // Échapper la valeur de l'URL
            echo '<img src="' . $imagePath . '" alt="Image en gros plan">';
        } else {
            echo 'Aucune image sélectionnée.';
        }
        ?>
    </div>
    <a href="javascript:history.back()">Retour</a>
</body>
</html>
