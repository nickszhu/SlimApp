<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

$app->get('/',function(Request $request, Response $response) {
    include('register_page.html');
});

$app->post('/register', function (Request $request, Response $response) {
    $conn = new mysqli('localhost', 'root', '', 'Store');
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }
    $userName = $_POST["username"];
    $password = $_POST["password"];
    $sql_insert = "INSERT INTO Customers Values ('$userName', '$password')";
    $insert_result = $conn->query($sql_insert);
    $sql_display = "SELECT * FROM Customers WHERE 1";
    if($insert_result === TRUE){
        $display_result = $conn->query($sql_display);
        if($display_result->num_rows > 0){
            echo'<li>';
            while($row = $display_result->fetch_assoc()){
                echo '
                    <ul>UserName: '.$row["username"].'  Password: '.$row['password'].'</ul>
                ';
            }
            echo'</li>';
        }
        else{
            echo 'No data in the database.';
        }
    }
    else{
        if($conn->errno == 1062){
            echo 'Username existed';
        }
        else{
            echo 'ERROR: Insert has failed';
        }
    }
});

$app->run();
