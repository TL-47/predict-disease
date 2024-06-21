<?php
// Database connection
include 'includes/db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $cp = $_POST['cp'];
    $trestbps = $_POST['trestbps'];
    $chol = $_POST['chol'];
    $fbs = $_POST['fbs'];
    $restecg = $_POST['restecg'];
    $thalach = $_POST['thalach'];
    $exang = $_POST['exang'];
    $oldpeak = $_POST['oldpeak'];
    $slope = $_POST['slope'];
    $ca = $_POST['ca'];
    $thal = $_POST['thal'];
    
    // Add other variables as needed

    // Call the Python API
    $data = json_encode(array(
        "age" => $age,
        "sex" => $sex,
        "cp" => $cp,
        "trestbps" => $trestbps,
        "chol" => $chol,
        "fbs" => $fbs,
        "restecg" => $restecg,
        "thalach" => $thalach,
        "exang" => $exang,
        "oldpeak" => $oldpeak,
        "slope" => $slope,
        "ca" => $ca,
        "thal" => $thal
    ));
    
    $ch = curl_init('http://localhost:5000/predict');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    
    // Display the result
    echo "Prediction: " . $result['prediction'];
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
    <form method="post" action="index.php">
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required><br>
        <label for="sex">Sex:</label>
        <input type="number" id="sex" name="sex" required><br>
        <label for="cp">Chest Pain Type:</label>
        <input type="number" id="cp" name="cp" required><br>
        <label for="trestbps">Resting Blood Pressure:</label>
        <input type="number" id="trestbps" name="trestbps" required><br>
        <label for="chol">Cholesterol:</label>
        <input type="number" id="chol" name="chol" required><br>
        <label for="fbs">Fasting Blood Sugar:</label>
        <input type="number" id="fbs" name="fbs" required><br>
        <label for="restecg">Resting ECG:</label>
        <input type="number" id="restecg" name="restecg" required><br>
        <label for="thalach">Max Heart Rate:</label>
        <input type="number" id="thalach" name="thalach" required><br>
        <label for="exang">Exercise Induced Angina:</label>
        <input type="number" id="exang" name="exang" required><br>
        <label for="oldpeak">Oldpeak:</label>
        <input type="number" id="oldpeak" name="oldpeak" step="0.1" required><br>
        <label for="slope">Slope:</label>
        <input type="number" id="slope" name="slope" required><br>
        <label for="ca">Number of Major Vessels:</label>
        <input type="number" id="ca" name="ca" required><br>
        <label for="thal">Thal:</label>
        <input type="number" id="thal" name="thal" required><br>
        <!-- Add other input fields as needed -->
        <input type="submit" value="Predict">
    </form>
</body>
</html>
