<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discover | B1RD</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .card {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .card h3 {
            color: #333;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body> 
    <!-- NAVBAR/FOOTER -->  
    <?php include 'objects/nav.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="container-fluid">
        <div class="row justify-content-center" style="padding: 50px 0px 0px 0px;">
            <h1>Discover</h1>
        </div>
        <div class="container">
        <?php 
        $query = "SELECT * FROM Bird";
        $result = $con->query($query);
        if ($result->num_rows > 0):    ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div class="card-body">
                        <?php
                            echo '<img class="card-img-top" src="data:image/jpeg;base64,'.base64_encode($row['bird_image']).'" alt="Card image cap">'
                        ?>
                        <h5 class="card-title"><?= htmlspecialchars($row['bird_name']) ?></h5>
                        <p class="card-text">Species: <?= htmlspecialchars($row['species']) ?></p>
                        <p>Type: <?= htmlspecialchars($row['bird_type']) ?></p>
                        <p>Age: <?= htmlspecialchars($row['bird_age']) ?></p>
                        <p>Medical History: <?= htmlspecialchars($row['medical_history']) ?></p>
                        <p>Adoption Status: <?= htmlspecialchars($row['adoption_status']) ?></p>
                        <p>Behavior: <?= htmlspecialchars($row['behavior']) ?></p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No birds found.</p>
        <?php endif; ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>