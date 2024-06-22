<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
include 'includes/db.php';

$result = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $cp = $_POST['cp'];
    $trestbps = $_POST['trestbps'];
    $chol = $_POST['chol'];

    // Call the Python API
    $data = json_encode(array(
        "age" => $age,
        "sex" => $sex,
        "cp" => $cp,
        "trestbps" => $trestbps,
        "chol" => $chol
    ));

    $ch = curl_init('http://localhost:5000/predict');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    $response = curl_exec($ch);
    if ($response === false) {
        $result = 'Error: ' . curl_error($ch);
    } else {
        $response_data = json_decode($response, true);
        if (isset($response_data['prediction'])) {
            $result = $response_data['prediction'];
        } else {
            $result = 'Error: Invalid response from prediction API';
        }
    }
    curl_close($ch);

    // Save to database
    $query = "INSERT INTO predictions (age, sex, cp, trestbps, chol, result) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Error: ' . $conn->error);
    }
    $stmt->bind_param('iiiiis', $age, $sex, $cp, $trestbps, $chol, $result);
    if (!$stmt->execute()) {
        die('Error: ' . $stmt->error);
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Disease Prediction</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form id="predictionForm" method="post" action="index.php">
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required><br>

        <label for="sex">Sex:</label>
        <select id="sex" name="sex" required>
            <option value="0">Female</option>
            <option value="1">Male</option>
        </select><br>

        <label for="cp">Chest Pain Type:</label>
        <select id="cp" name="cp" required>
            <option value="0">Typical angina</option>
            <option value="1">Atypical angina</option>
            <option value="2">Non-anginal pain</option>
            <option value="3">Asymptomatic</option>
        </select><br>

        <label for="trestbps">Resting Blood Pressure:</label>
        <select id="trestbps" name="trestbps" required>
            <option value="90">90</option>
            <option value="100">100</option>
            <option value="110">110</option>
            <option value="120">120</option>
            <option value="130">130</option>
            <option value="140">140</option>
            <option value="150">150</option>
            <option value="160">160</option>
            <option value="170">170</option>
        </select><br>

        <label for="chol">Cholesterol:</label>
        <select id="chol" name="chol" required>
            <option value="150">150</option>
            <option value="200">200</option>
            <option value="250">250</option>
            <option value="300">300</option>
            <option value="350">350</option>
        </select><br>

        <input type="submit" value="Predict">
    </form>

    <?php if ($result !== ""): ?>
        <p>Prediction: <?php echo $result; ?></p>
    <?php endif; ?>
</body>
</html>
