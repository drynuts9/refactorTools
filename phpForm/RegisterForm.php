<?php
// Initialize error messages and sanitized variables
$errors = [];
$name = $age = $sex = $tel = $email = $address = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Validate Name (Letters and spaces only)
    if (empty($_POST["name"])) {
        $errors[] = "Name is required.";
    } else {
        $name = htmlspecialchars(trim($_POST["name"]));
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $errors[] = "Only letters and white space allowed for name.";
        }
    }

    // 2. Validate Age (Numeric check)
    if (empty($_POST["age"])) {
        $errors[] = "Age is required.";
    } else {
        $age = $_POST["age"];
        if (!filter_var($age, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 120]])) {
            $errors[] = "Please enter a valid age between 1 and 120.";
        }
    }

    // 3. Validate Sex (Dropdown/Radio check)
    if (empty($_POST["sex"])) {
        $errors[] = "Sex is required.";
    } else {
        $sex = $_POST["sex"];
        $allowed = ["male", "female", "other"];
        if (!in_array($sex, $allowed)) {
            $errors[] = "Invalid option selected for sex.";
        }
    }

    // 4. Validate Telephone (Regex for 10-15 digits)
    if (empty($_POST["tel"])) {
        $errors[] = "Telephone is required.";
    } else {
        $tel = trim($_POST["tel"]);
        if (!preg_match("/^[0-9]{10,15}$/", $tel)) {
            $errors[] = "Enter a valid 10-15 digit telephone number.";
        }
    }

    // 5. Validate Email (Native filter)
    if (empty($_POST["email"])) {
        $errors[] = "Email is required.";
    } else {
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
    }

    // 6. Validate Address (Non-empty check)
    if (empty($_POST["address"])) {
        $errors[] = "Address is required.";
    } else {
        $address = htmlspecialchars(trim($_POST["address"]));
    }

    // Process success or show errors
    if (empty($errors)) {
        echo "Registration successful for " . $name;
        // Proceed with database insertion here
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>
