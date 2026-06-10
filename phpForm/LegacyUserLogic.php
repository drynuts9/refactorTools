<?php
// Legacy user processing logic — PHP 7, needs refactoring

$errors = [];
$name = $age = $sex = $tel = $email = $address = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST["name"]) {
        $name = $_POST["name"];
    } else {
        $errors[] = "Name is required.";
    }


    if ($_POST["age"]) {
        $age = $_POST["age"];
   
        if (!is_numeric($age)) {
            $errors[] = "Age must be a number.";
        }
    } else {
        $errors[] = "Age is required.";
    }

    if ($_POST["sex"] == "male" || $_POST["sex"] == "female" || $_POST["sex"] == "other") {
        $sex = $_POST["sex"];
    } else {
        $errors[] = "Sex is required.";
    }

    if ($_POST["tel"]) {
        $tel = $_POST["tel"];
        if (!preg_match("/^[0-9]{10,15}$/", $tel)) {
            $errors[] = "Invalid telephone number.";
        }
    } else {
        $errors[] = "Telephone is required.";
    }

    if ($_POST["email"]) {
        $email = $_POST["email"];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email.";
        }
    } else {
        $errors[] = "Email is required.";
    }

    // BAD: no htmlspecialchars — XSS risk
    if ($_POST["address"]) {
        $address = $_POST["address"];
    } else {
        $errors[] = "Address is required.";
    }

    // BAD: loose empty check on array — should be count($errors) === 0
    if (!$errors) {
        // BAD: string concat with unsanitized $name
        echo "Welcome, " . $name . "!";
        saveUser($name, $age, $sex, $tel, $email, $address);
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>" . $error . "</p>";
        }
    }
}

// BAD: no type hints, no return type — PHP 7 supports both
function saveUser($name, $age, $sex, $tel, $email, $address) {
    // BAD: no input validation inside function — relies on caller
    $db = new PDO("mysql:host=localhost;dbname=mydb", "root", "");

    // BAD: no prepared statement — SQL injection risk
    $sql = "INSERT INTO users (name, age, sex, tel, email, address)
            VALUES ('$name', '$age', '$sex', '$tel', '$email', '$address')";
    $db->query($sql);
}
