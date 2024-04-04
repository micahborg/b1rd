<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>B1RD: Online Bird Adoption Platform</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body> 
    <!-- NAVBAR/FOOTER -->  
    <?php include 'objects/nav.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="container-fluid">
        <div class="row justify-content-center">
            <img src="images/lil_peep.png" alt="Bird Image" width="300" height="300">
        </div>
        <div class="row justify-content-center">
            <h1>Welcome to B1RD</h1>
        </div>
        <div class="row justify-content-center" style="padding: 0px 0px 50px;">
            <p>A database of avians, rescue shelters, and adopters.</p>
        </div>
        <div class="row no-gutters justify-content-center">
            <div class="col-3 text-center">
                <h2>Adopt</h2>
                <p>Find your perfect bird companion today!</p>
                <a href="login.php" class="btn btn-outline-secondary">Adopt Now</a>
            </div>
            <div class="col-3 text-center">
                <h2>Discover</h2>
                <p>Discover the perfect bird for you!</p>
                <a href="#" class="btn btn-outline-secondary">Discover Now</a>
            </div>
            <div class="col-3 text-center">
                <h2>Learn</h2>
                <p>Learn more about birds and how to care for them!</p>
                <a href="#" class="btn btn-outline-secondary">Learn More</a>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>