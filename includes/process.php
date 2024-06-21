<?php
// Includes
require_once 'db.php';

// Get user input
$age = $_POST['age'];
$bmi = $_POST['bmi'];
// Get more variables as needed

// Function to call the Python script
function predict_disease($age, $bmi) {
    $command = escapeshellcmd("python3 ../models/predict.py $age $bmi");
    $output = shell_exec($command);
    return $output;
}

// Call the prediction function
$result = predict_disease($age, $bmi);

// Save to database
$query = "INSERT INTO predictions (age, bmi, result) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iis', $age, $bmi, $result);
$stmt->execute();
$stmt->close();
$conn->close();

// Display the result
echo "Prediction Result: " . $result;
?>
