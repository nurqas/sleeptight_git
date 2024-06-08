<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <title>Dashboard</title>
    

    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
        .sub-table,.anime{
            animation: transitionIn-Y-bottom 0.5s;
        }
        #container {
        text-align: center;
        }
        
        #startButton {
        margin-bottom: 20px; /* Add space between button and progress bar */
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        background-color: #007bff;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        }
        
        #progressBar {
        width: 0%;
        height: 20px;
        background-color: #4CAF50;
        }
        
        #result {
        margin-top: 20px; /* Add space between progress bar and result */
        }

        #startButtonContainer {
        margin-bottom: 20px; /* Add space between button and progress bar */
        }
    </style>
    
    
</head>
<body>
    <?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");
    $userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];


    //echo $userid;
    // echo $username;

    $url = "http://127.0.0.1:5000/predict";

     // Send a GET request to the Flask server
    $pred_result = file_get_contents($url);

    // Decode the JSON response
    $response = json_decode($pred_result, true);

    // Display the predicted sleep disorder result
    if (isset($response['prediction'])) {
        $prediction = $response['prediction'][0]; // Assuming we get a list of predictions and we want the first one
        // Mapping the prediction to a meaningful result
        $result_text = ($prediction == 1) ? "Detected" : "Not Detected";
    } else {
        $result_text = "Error: " . $response['error'];
    }
    
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                    </table>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-home menu-active menu-icon-home-active" >
                        <a href="survey.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Prediction Quiz</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-home menu-active menu-icon-home-active" >
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                        
                        <tr >
                            
                            <td colspan="1" class="nav-bar" >
                            <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Home</p>
                          
                            </td>
                            <td width="25%">

                            </td>
                            <td width="15%">
                                <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                                    Today's Date
                                </p>
                                <p class="heading-sub12" style="padding: 0;margin: 0;">
                                    <?php 
                                date_default_timezone_set('Asia/Kolkata');
        
                                $today = date('Y-m-d');
                                echo $today;


                                $patientrow = $database->query("select  * from  patient;");
                                $doctorrow = $database->query("select  * from  doctor;");
                                $appointmentrow = $database->query("select  * from  appointment where appodate>='$today';");
                                $schedulerow = $database->query("select  * from  schedule where scheduledate='$today';");


                                ?>
                                </p>
                            </td>
                            <td width="10%">
                                <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                            </td>
        
        
                        </tr>
                <tr>
                    <td colspan="4" >
                        
                    <center>
                    <table class="filter-container doctor-header patient-header" style="border: none;width:95%" border="0" >
                    <tr>
                    <td style="text-align: center;">
                        <button id="startButton" style="margin-bottom: 20px; padding: 10px 20px; font-size: 16px; border: none; background-color: #007bff; color: white; border-radius: 5px; cursor: pointer;">Get SleepTight's Sleep Apnea Prediction</button>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">
                        <div id="progressBar" style="width: 0%; height: 20px; background-color: #4CAF50;"></div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">
                        <div id="result" style="margin-top: 20px;"></div>
                        </td>
                    </tr>
                    </table>
                    </center>
                </td>
                </tr>
                <tr>
                    <td colspan="4" >
              <center>
              <div style="width: 80%; max-width: 80vw; padding: 20px; border: 1px solid #ccc; background-color: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <h1>Survey</h1>
                <form id="surveyForm">
                    <div class="question" style="margin-bottom: 15px;">
                        <label>1. Do they tell you that you snore? <br>[Le dicono che russa?]</label><br>
                        <input type="radio" name="q1" value="yes"> Yes
                        <input type="radio" name="q1" value="no"> No
                    </div>
                    <div class="question" style="margin-bottom: 15px;">
                        <label>2. Do they tell you that sometimes you stop breathing or have sleep apnea?<br> [Le dicono che talvolta smette di respirare o ha apnee durante il sonno?]</label><br>
                        <input type="radio" name="q2" value="yes"> Yes
                        <input type="radio" name="q2" value="no"> No
                    </div>
                    <div class="question" style="margin-bottom: 15px;">
                        <label>3. Do you wake up during the night with an urge to urinate? <br>[Si sveglia durante la notte con il bisogno urgente di urinare?]</label><br>
                        <input type="radio" name="q3" value="yes"> Yes
                        <input type="radio" name="q3" value="no"> No
                    </div>
                    <div class="question" style="margin-bottom: 15px;">
                        <label>4. Does it happens to you not being satisfied with how you slept? <br>[Le capita di non essere soddisfatto/a di come ha dormito?]</label><br>
                        <input type="radio" name="q4" value="yes"> Yes
                        <input type="radio" name="q4" value="no"> No
                    </div>
                    <div class="question" style="margin-bottom: 15px;">
                        <label>5. Do you frequently feel the desire or need to sleep during the day except after lunch?<br>[Sente frequentemente il desiderio o il bisogno di dormire durante il giorno eccetto dopo pranzo?]</label><br>
                        <input type="radio" name="q5" value="yes"> Yes
                        <input type="radio" name="q5" value="no"> No
                    </div>
                    <div class="question" style="margin-bottom: 15px;">
                        <label>6. Do you take medications for high blood pressure? <br>[Assume farmaci per la pressione arteriosa alta?]</label><br>
                        <input type="radio" name="q6" value="yes"> Yes
                        <input type="radio" name="q6" value="no"> No
                    </div>
                    <button type="button" onclick="calculateResult('<?php echo htmlspecialchars($username); ?>')" style="display: block; width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; cursor: pointer; font-size: 16px;">Submit</button>
                <div id="surveyresult" class="result" style="margin-top: 20px; font-weight: bold;"></div>
            </div>
                </center>
                </td>
                </tr>
            </table>
        </div>
    </div>

    <script>
    function calculateResult(username) {
        const form = document.getElementById('surveyForm');
        let yesCount = 0;
        const questions = [
            "Do they tell you that you snore?",
            "Do they tell you that sometimes you stop breathing or have sleep apnea?",
            "Do you wake up during the night with an urge to urinate?",
            "Does it happens to you not being satisfied with how you slept?",
            "Do you frequently feel the desire or need to sleep during the day except after lunch?",
            "Do you take medications for high blood pressure?"
        ];
        const answers = [];

        for (let i = 1; i <= 6; i++) {
            const question = form['q' + i];
            const answer = question.value === 'yes' ? 'Yes' : 'No';
            if (question.value === 'yes') {
                yesCount++;
            }
            answers.push(`Q${i}. ${questions[i - 1]}: ${answer}`);
        }

        let classification = '';
        if (yesCount >= 5) {
            classification = 'Severe';
        } else if (yesCount >= 3) {
            classification = 'Moderate';
        } else {
            classification = 'Low';
        }

        document.getElementById('surveyresult').innerText = `Sleep Apnea Classification: ${classification}`;

        generatePDF(username, classification, questions, answers);
    }

        function generatePDF(username, classification, questions, answers) {
            fetch('http://127.0.0.1:5000/patient/save_pdf', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: username,
                    classification: classification,
                    questions: questions,
                    answers: answers
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Failed to save PDF:', data.error);
                } else {
                    console.log('PDF saved successfully:', data.pdfPath);
                    // Redirect to download PDF or show success message
                }
            })
            .catch(error => {
                console.error('Error saving PDF:', error);
            });
        }

        function downloadPDF(pdfPath, username) {
        fetch(pdfPath)
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(new Blob([blob]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `${username}_survey_results.pdf`);
            document.body.appendChild(link);
            link.click();
            link.parentNode.removeChild(link);
        })
        .catch(error => {
            console.error('Error downloading PDF:', error);
        });
    }


        // ML Portion
        document.getElementById("startButton").addEventListener("click", function() {
        var progressBar = document.getElementById("progressBar");
        var resultDiv = document.getElementById("result");
        progressBar.style.width = "0%";
        
        // Simulated process
        var progress = 0;
        var interval = setInterval(function() {
            progress += Math.random() * 20; // Increased speed
            if (progress >= 100) {
            clearInterval(interval);
            progressBar.style.width = "100%";
            setTimeout(function() {
                resultDiv.innerHTML = "Our AI model has processed your personal health data and had <b><?php echo $result_text; ?></b> Sleep Apnea";
            }, 500); // Delay result display for better user experience
            } else {
            progressBar.style.width = progress + "%";
            }
        }, 500); // Decreased interval to 500 milliseconds (0.5 seconds)
        });
    </script>


</body>
</html>