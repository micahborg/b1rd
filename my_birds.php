<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");

$user_id = $_SESSION['user_id'];
$shelter_id = $_SESSION['shelter_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_a_bird'])) {
    $species = $_POST['species'];
    $bird_type = $_POST['bird_type'];
    $bird_age = $_POST['bird_age'];
    $medical_history = $_POST['medical_history'];
    $adoption_status = $_POST['adoption_status'];
    $behavior = $_POST['behavior'];
    $bird_name = $_POST['bird_name'];
    $bird_image = $_POST['bird_image'];

    $query = "INSERT INTO Bird (shelter_id, species, bird_type, bird_age, medical_history, adoption_status, behavior, bird_name, bird_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("issssssss", $shelter_id, $species, $bird_type, $bird_age, $medical_history, $adoption_status, $behavior, $bird_name, $bird_image);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Bird added successfully.";
    } else {
        echo "Failed to add bird.";
    }

    $stmt->close();

    // refresh page to show new bird
    header("Location: my_birds.php");
    die;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Birds | B1RD</title>
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
        <div class="row justify-content-center" style="padding: 50px 0px 10px 0px;">
            <h1>My Birds</h1>
        </div>

        <!-- Search Form -->
        <form action="discover.php" method="get">
        <div class="row justify-content-md-center">
            <div class="col-md-2">
                <select name="species" class="form-control">
                    <option value="">Select Species</option>
                    <?php
                    $species_query = "SELECT DISTINCT species FROM Bird";
                    $species_result = $con->query($species_query);
                    if ($species_result->num_rows > 0):
                        while ($species_row = $species_result->fetch_assoc()):
                    ?>
                        <option value="<?= htmlspecialchars($species_row['species']) ?>"><?= htmlspecialchars($species_row['species']) ?></option>
                    <?php
                        endwhile;
                    endif;
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="adoption_status" class="form-control">
                    <option value="">Select Adoption Status</option>
                    <option value="Available">Available</option>
                    <option value="Not Available">Not Available</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="bird_name" placeholder="Bird Name" class="form-control">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-md-auto">
                <button type="button" name="add_a_bird" class="btn btn-primary" data-toggle="modal" data-target="#addBirdModal">Add a Bird</button>
            </div>
        </div>

        <!-- Bird Cards -->
        <div class="container">
        <?php 
            $conditions = []; // array to store query conditions (i.e. WHERE clauses)
            $params = []; // array to store query parameters (i.e. values to be substituted in the query conditions)
            $types = ''; // string to store query parameter types (i.e. 's' for string, 'i' for integer, etc.)

            if (!empty($_GET['species'])) { // if species is set and add to the query conditions
                $conditions[] = "species = ?";
                $params[] = $_GET['species'];
                $types .= 's';
            }

            if (!empty($_GET['adoption_status'])) { // if adoption status is set and add to the query conditions
                $conditions[] = "adoption_status = ?";
                $params[] = $_GET['adoption_status'];
                $types .= 's';
            }

            if (!empty($_GET['bird_name'])) { // if bird name is set and add to the query conditions
                $conditions[] = "bird_name LIKE ?";
                $params[] = "%" . $_GET['bird_name'] . "%";
                $types .= 's';
            }

            $query = "SELECT * FROM Bird JOIN Shelter ON Bird.shelter_id = Shelter.shelter_id WHERE Bird.shelter_id = ?";
            $params[] = $shelter_id;
            $types .= 'i';
            if (!empty($conditions)) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }

            $stmt = $con->prepare($query);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
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
                            <p>Behavior: <?= htmlspecialchars($row['behavior']) ?></p>
                            <p>Adoption Status: <?= htmlspecialchars($row['adoption_status']) ?></p>
                            <a href="#" class="btn btn-danger" onclick="deleteBird(<?= $row['bird_id'] ?>)">Delete</a>
                        </div>
                        <script>
                            function deleteBird(bird_id) {
                                if (confirm("Are you sure you want to delete this bird?")) {
                                    $query = "DELETE FROM Bird WHERE bird_id = ?";
                                }
                            }
                        </script>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No birds found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add a Bird Modal -->
    <div class="modal fade" id="addBirdModal" tabindex="-1" role="dialog" aria-labelledby="addBirdModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBirdModalLabel">Add a Bird</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add a Bird Form -->
                    <form action="" method="POST">
                        <div class="form-group row">
                            <label for="species" class="col-sm-2 col-form-label">Species</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="species" name="species">
                            </div>

                            <label for="bird_type" class="col-sm-2 col-form-label">Type</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="bird_type" name="bird_type">
                            </div>

                            <label for="bird_age" class="col-sm-2 col-form-label">Age</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="bird_age" name="bird_age">
                            </div>
                            
                            <label for="medical_history" class="col-sm-2 col-form-label">Medical History</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="medical_history" name="medical_history">
                            </div>

                            <label for="adoption_status" class="col-sm-2 col-form-label">Adoption Status</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="adoption_status" name="adoption_status">
                            </div>

                            <label for="behavior" class="col-sm-2 col-form-label">Behavior</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="behavior" name="behavior">
                            </div>

                            <label for="bird_name" class="col-sm-2 col-form-label">Bird Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="bird_name" name="bird_name">
                            </div>

                            <label for="bird_image" class="col-sm-2 col-form-label">Image</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" id="bird_image" name="bird_image">
                            </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="add_a_bird">Add Bird</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Add a Bird Modal -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>