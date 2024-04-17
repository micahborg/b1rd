<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_application'])) {
    $reason = $_POST['reason'];
    $confirmPassword = $_POST['confirmPassword'];
    $bird_id = $_POST['bird_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $con->prepare("SELECT * FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    $user_password = $user_data['user_password'];

    if ($user_password === $confirmPassword) {
        $stmt = $con->prepare("INSERT INTO adoptionapplication(bird_id, user_id, application_date, application_status, reason_for_adoption) VALUES (?, ?, NOW(), 'Pending', ?)");
        $stmt->bind_param("iis", $bird_id, $user_id, $reason);
        $stmt->execute();
        echo "Adoption application submitted.";
    } else {
        echo "Incorrect password.";
    }
}
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
        <div class="row justify-content-center" style="padding: 50px 0px 10px 0px;">
            <h1>Discover</h1>
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
            <div class="col-md-2">
                <input type="text" name="shelter_name" placeholder="Shelter Name" class="form-control">
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
        </form>

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

            if (!empty($_GET['shelter_name'])) { // if shelter name is set and add to the query conditions
                $conditions[] = "Shelter.shelter_name LIKE ?";
                $params[] = "%" . $_GET['shelter_name'] . "%";
                $types .= 's';
            }

            $query = "SELECT * FROM Bird JOIN Shelter ON Bird.shelter_id = Shelter.shelter_id";
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
                    $modalId = "adoptModal" . htmlspecialchars($row['bird_id']);
            ?>
                    <div class="card">
                        <div class="card-body">
                            <?php echo '<img class="card-img-top" src="data:image/jpeg;base64,' . base64_encode($row['bird_image']) . '" alt="Card image cap">' ?>
                            <h5 class="card-title"><?= htmlspecialchars($row['bird_name']) ?></h5>
                            <p class="card-text">Species: <?= htmlspecialchars($row['species']) ?></p>
                            <p>Type: <?= htmlspecialchars($row['bird_type']) ?></p>
                            <p>Age: <?= htmlspecialchars($row['bird_age']) ?></p>
                            <p>Medical History: <?= htmlspecialchars($row['medical_history']) ?></p>
                            <p>Behavior: <?= htmlspecialchars($row['behavior']) ?></p>
                            <p>Adoption Status: <?= htmlspecialchars($row['adoption_status']) ?></p>
                            <a class="btn btn-primary" data-toggle="modal" data-target="#<?= $modalId ?>">Adopt Now</a>

                            <!-- Adopt Now Modal -->
                            <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" role="dialog" aria-labelledby="adoptModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="adoptModalLabel">Submit Adoption Application</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <?php if (htmlspecialchars($row['adoption_status']) == "Not Available"): ?>
                                                <p>This bird is not available for adoption.</p>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <?php else: ?>
                                            <form action="" method="POST">
                                                <div class="form-group">
                                                    <label for="reason">Reason for Adoption</label>
                                                    <textarea class="form-control" id="reason" name="reason" placeholder="Enter your 'why' for adopting this bird"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="confirmPassword">Confirm Password</label>
                                                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm password">
                                                </div>
                                                <input type="hidden" name="bird_id" value="<?= htmlspecialchars($row['bird_id']) ?>">
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" name="submit_application">Submit</button>
                                                </div>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Adopt Now Modal -->

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