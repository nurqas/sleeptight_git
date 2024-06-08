<!DOCTYPE html>
<html>
<head>
    <title>Predicted Sleep Disorder Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333333;
        }
        .result {
            text-align: center;
            font-size: 24px;
            color: #007BFF;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Predicted Sleep Disorder Result</h2>
        <div class="result">
            <?php
            // URL of the Flask server
            $url = 'http://127.0.0.1:5000/predict';

            // Send a GET request to the Flask server
            $result = file_get_contents($url);

            // Decode the JSON response
            $response = json_decode($result, true);

            // Display the predicted sleep disorder result
            if (isset($response['prediction'])) {
                $prediction = $response['prediction'][0]; // Assuming we get a list of predictions and we want the first one
                // Mapping the prediction to a meaningful result
                $result_text = ($prediction == 1) ? "Sleep Disorder Detected" : "No Sleep Disorder Detected";
                echo $result_text;
            } else {
                echo "Error: " . $response['error'];
            }
            ?>
        </div>
    </div>
</body>
</html>
