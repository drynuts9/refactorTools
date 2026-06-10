<?php
// Refactored output — Scanner → Fixer → Verifier pipeline
// Verifier warnings:
//   - age "0" behavior changed (see line ~20): original treated "0" as missing; ?? "" now passes it through
//   - SQL injection in saveUser() still present — NEEDS_HUMAN_REVIEW (replace with prepared statement)

$errors = [];
$name = $age = $sex = $tel = $email = $address = "";

if (($_SERVER["REQUEST_METHOD"] ?? "") === "POST") {

    $name = $_POST["name"] ?? "";
    if ($name === "") {
        $errors[] = "Name is required.";
    }

    $age = $_POST["age"] ?? "";
    if ($age === "") {
        $errors[] = "Age is required.";
    } elseif (!is_numeric($age)) {
        $errors[] = "Age must be a number.";
    }

    $sex = $_POST["sex"] ?? "";
    if ($sex !== "male" && $sex !== "female" && $sex !== "other") {
        $errors[] = "Sex is required.";
    }

    $tel = $_POST["tel"] ?? "";
    if ($tel === "") {
        $errors[] = "Telephone is required.";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $tel)) {
        $errors[] = "Invalid telephone number.";
    }

    $email = $_POST["email"] ?? "";
    if ($email === "") {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email.";
    }

    $address = $_POST["address"] ?? "";
    if ($address === "") {
        $errors[] = "Address is required.";
    }

    if (count($errors) === 0) {
        echo "Welcome, " . $name . "!";
        saveUser($name, $age, $sex, $tel, $email, $address);
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>" . $error . "</p>";
        }
    }
}

// NEEDS_HUMAN_REVIEW: $sql uses raw interpolation — replace with a PDO prepared statement
function saveUser(string $name, string $age, string $sex, string $tel, string $email, string $address): void {
    $db = new PDO("mysql:host=localhost;dbname=mydb", "root", "");

    $sql = "INSERT INTO users (name, age, sex, tel, email, address)
            VALUES ('$name', '$age', '$sex', '$tel', '$email', '$address')";
    $db->query($sql);
}
