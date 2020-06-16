<?php
session_start();

$logged_in = false;
$success = false;
$disabled = '';
$disabledforc = '';

if (isset($_SESSION['name']) ) {
	$logged_in = true;
}

if (isset($_SESSION['type'])) {
    if ($_SESSION['type'] == 'Customer') {
        $disabled = 'disabled';
        $disabledforc = '';
    }
    else {
        $disabledforc = 'disabled';
    }
}

else {
    $disabled = 'disabled';
    $disabledforc = '';
}

if ( isset($_SESSION['success']) ) {
    $success = htmlentities($_SESSION['success']);

    unset($_SESSION['success']);
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "foodshala";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT itemId, itemName, itemDescription, userName, itemPreference FROM menu, users WHERE menu.itemRestaurantId=users.userId");
    $stmt->execute();

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
} 
catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;

if (isset($_GET['item'])) {
    if($_SESSION['type']=='Customer' && isset($_SESSION['id'])){
        $itemNumber = $_GET['item'];
        echo $itemNumber;
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO orders (customerId, itemId)
                VALUES (?, ?)";
            $stmt= $conn->prepare($sql);
            $stmt->execute([$_SESSION['id'], $itemNumber]);
            echo "New Order created successfully";
        } 
        catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }   
        $conn = null;
        $_SESSION['success'] = "The order has been successfully placed.";
        header("Location: index.php");
        return;
    }
    else {
        $_SESSION['failure'] = "Login is required to place order";
        header("Location: login.php");
        return;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <title>Foodshala</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Foodshala</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Menu<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $disabled;?>" href="addMenuItem.php" >Add Menu Item</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $disabled;?>" href="orders.php" >Check Order</a>
                    </li>
                    &nbsp;
                    <?php if($logged_in == false){
                    echo '<li class="nav-item">';
                        echo '<a class="btn btn-outline-success" href="login.php" role="button">Login</a>';
                    echo '</li>';}
                    else {
                        echo '<li class="nav-item">';
                            echo '<a class="btn btn-outline-success" href="#" role="button">Logged in as '.$_SESSION['name'].'</a>';
                        echo '</li>';}
                    ?>
                    &nbsp;
                    <?php if($logged_in == false){}
                    else {
                    echo '<li class="nav-item">';
                        echo '<a class="btn btn-outline-danger" href="logout.php" role="button">Logout</a>';
                    echo '</li>';}
                    ?>
                </ul>
            </div>
        </nav>
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">The Place for Good Food!</h1>
                <p class="lead">This is the only place online which delivers to you the most delicious and fresh food from exclusively selected restaurants.</p>
                <?php
                    if ( $success !== false ) 
                    {
                        echo(
                            '<p style="color: green;">'.
                                htmlentities($success).
                            "</p>\n"
                        );
                    }
                ?>
            </div>
        </div>
        <div class="container-fluid">
            <?php while ($row = $stmt->fetch()) {
            echo '<div class="card">';
                echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row['itemName'];
                    if($row['itemPreference']=='Veg')
                    {
                        echo '&nbsp;<span class="badge badge-success">Veg</span>';
                    }
                    else {
                        echo '&nbsp;<span class="badge badge-danger">non-Veg</span>';
                    }
                    echo '</h5>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">' . $row['userName'] . '</h6>';
                    echo '<p class="card-text">' . $row['itemDescription'];
                        echo '<a  href="index.php?item=' . $row['itemId']. '" class="btn btn-outline-primary float-right '.$disabledforc.'" role="button">Order</a>';
                    echo '</p>';
                echo '</div>';
            echo '</div>';}
            ?>
        </div>
        <br>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>