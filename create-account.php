<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/signup.css">
        
    <title>Create Account</title>
    <style>
        .container{
            animation: transitionIn-X 0.5s;
        }

        .terms-popup {
            display: none; /* Initially hidden */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 30px;
            width: 500px;
            max-height: 80%;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .overlay {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .close-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            color: #333;
        }

        .btn-sign-up {
            padding: 10px 20px;
            width: 150px; /* Fixed width for consistent size */
            border: none;
            border-radius: 5px;
            cursor: not-allowed;
            background-color: #f0f0f0; /* Grey background color */
            color: #999; /* Lighter text color */
        }

        .btn-sign-up-enabled {
            padding: 10px 20px;
            width: 150px; /* Fixed width for consistent size */
            border: none;
            border-radius: 5px;
            background-color: #007bff; /* Blue background color */
            color: #fff; /* White text color */
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php

//learn from w3schools.com
//Unset all the server side variables

session_start();

$_SESSION["user"]="";
$_SESSION["usertype"]="";

// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');

$_SESSION["date"]=$date;


//import database
include("connection.php");





if($_POST){

    $result= $database->query("select * from webuser");

    $fname=$_SESSION['personal']['fname'];
    $lname=$_SESSION['personal']['lname'];
    $name=$fname." ".$lname;
    $address=$_SESSION['personal']['address'];
    $nic=$_SESSION['personal']['nic'];
    $dob=$_SESSION['personal']['dob'];
    $email=$_POST['newemail'];
    $tele=$_POST['tele'];
    $newpassword=$_POST['newpassword'];
    $cpassword=$_POST['cpassword'];
    
    if ($newpassword==$cpassword){
        $result= $database->query("select * from webuser where email='$email';");
        if($result->num_rows==1){
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
        }else{
            
            $database->query("insert into patient(pemail,pname,ppassword, paddress, pnic,pdob,ptel) values('$email','$name','$newpassword','$address','$nic','$dob','$tele');");
            $database->query("insert into webuser values('$email','p')");

            //print_r("insert into patient values($pid,'$email','$fname','$lname','$newpassword','$address','$nic','$dob','$tele');");
            $_SESSION["user"]=$email;
            $_SESSION["usertype"]="p";
            $_SESSION["username"]=$fname;

            header('Location: patient/index.php');
            $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>';
        }
        
    }else{
        $error='<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Confirmation Error! Reconfirm Password</label>';
    }



    
}else{
    //header('location: signup.php');
    $error='<label for="promter" class="form-label"></label>';
}

?>


    <center>
    <div class="container">
        <table border="0" style="width: 69%;">
            <tr>
                <td colspan="2">
                    <p class="header-text">Let's Get Started</p>
                    <p class="sub-text">It's Okey, Now Create User Account.</p>
                </td>
            </tr>
            <tr>
                <form action="" method="POST" >
                <td class="label-td" colspan="2">
                    <label for="newemail" class="form-label">Email: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="email" name="newemail" class="input-text" placeholder="Email Address" required>
                </td>
                
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="tele" class="form-label">Mobile Number: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="tel" name="tele" class="input-text"  placeholder="ex: 0712345678" pattern="[0]{1}[0-9]{9}" >
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="newpassword" class="form-label">Create New Password: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="newpassword" class="input-text" placeholder="New Password" required>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="cpassword" class="form-label">Confirm Password: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="cpassword" class="input-text" placeholder="Conform Password" required>
                </td>
            </tr>
     
            <tr>
                
                <td colspan="2">
                    <?php echo $error ?>

                </td>
            </tr>
            
            <tr>
                <!-- <td>
                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >
                </td> -->
                <td>
                    <!-- <input type="submit" value="Sign Up" class="login-btn btn-primary btn"> -->
                    <div onload="checkTermsStatus()"></div>

                    <form id="signUpForm" action="your_signup_handler.php" method="post">
                        <input type="checkbox" id="termsCheckbox" name="termsCheckbox" onclick="toggleSignUpButton()">
                        <label for="termsCheckbox">I agree to the <a href="#" onclick="openTermsPopup(event)">Terms and Conditions</a></label>
                        <br><br>
                        <input type="submit" class="btn-sign-up" value="Sign Up" name="signup" disabled>
                    </form>

                    <!-- Terms and Conditions Popup -->
                    <div class="overlay" id="termsOverlay"></div>
                    <div class="terms-popup" id="termsPopup">
                        <div class="terms-content">
                            <span class="close-icon" onclick="closeTermsPopup()">&#10006;</span>
                            <h2>Terms and Conditions</h2>
                            <p>Welcome to Sleep Tight, the Sleep Monitoring Website provided by SleepTight ("Company"). These Terms and Conditions ("Terms") govern your use of Sleep Tight. By accessing or using Sleep Tight, you agree to be bound by these Terms. If you do not agree with any part of these Terms, you may not use Sleep Tight.</p>

                            <h3>1. Data Collection and Processing:</h3>
                            <p>a. Sleep Tight collects various physiological data, including Heart Rate, Blood Oxygen Saturation, and Respiration Rate through the smartwatch, to monitor individuals at risk of Obstructive Sleep Apnea ("OSA").</p>
                            <p>b. The collected data is processed to categorise individuals into mild, moderate, and severe risk of OSA.</p>
                            <p>c. "Sleep Tight is classified as Software as a Medical Device (SaMD) category II in compliance with the Medical Device Regulation (EU) 2017/745 (MDR) and follows the requirements for long-term, active diagnosis devices. In Italy, it also complies with the Italian Legislative Decree 46/97, which implements the EU Medical Device Regulation at the national level."</p>

                            <h4>1.1 Web Bluetooth API Communication:</h4>
                            <p>a. Bluetooth Connectivity: Sleep Tight uses the Web Bluetooth API to communicate with your smartwatch. By enabling Bluetooth on your device and allowing connectivity, you consent to the transmission of your physiological data from your smartwatch to our servers.</p>
                            <p>b. Data Security during Transmission: We employ industry-standard encryption protocols to ensure that data transmitted between your smartwatch and our servers is secure. Despite these measures, we cannot guarantee absolute security, and you acknowledge that you use this feature at your own risk.</p>
                            <p>c. User Responsibilities: You are responsible for maintaining the security of your Bluetooth-enabled devices. Ensure that your smartwatch and any device used to connect to Sleep Tight are secure and protected against unauthorised access.</p>

                            <h3>2. Data Privacy:</h3>
                            <p>a. Protecting your privacy is paramount to us. We handle your data in strict accordance with the General Data Protection Regulation (GDPR) rules.</p>
                            <p>b. Your data is stored securely and will not be shared with third parties without your explicit consent, except as required by law.</p>
                            <p>c. Data Encryption: In addition to storing your data securely, we use advanced encryption methods to protect your data during transmission and storage. Your data is encrypted both in transit and at rest to prevent unauthorised access.</p>
                            <p>d. Access Controls: Access to your data is restricted to authorised personnel only. We implement strict access controls and regularly audit our systems to ensure compliance with our data protection policies.</p>

                            <h3>3. Right to Opt-In:</h3>
                            <p>a. You have the right to opt-in or opt-out of data collection and processing at any time.</p>
                            <p>b. To manage your preferences, please adjust your settings in Sleep Tight's user interface.</p>

                            <h3>4. Limitation of Liability:</h3>
                            <p>a. While we strive for accuracy, we make no warranties or representations regarding the completeness or reliability of the data collected and processed by Sleep Tight.</p>
                            <p>b. The Company shall not be liable for any direct, indirect, incidental, special, or consequential damages arising from your use of Sleep Tight.</p>

                            <h3>5. Modification of Terms:</h3>
                            <p>a. We reserve the right to modify these Terms at any time without prior notice.</p>
                            <p>b. Your continued use of Sleep Tight after any modifications to the Terms constitutes your acceptance of the modified Terms.</p>

                            <h3>6. User Support and Contact Information:</h3>
                            <p>a. Support Services: If you encounter any issues with the Bluetooth connectivity or have any questions about data privacy, please contact our support team at sleepycustomerservice@pillow.com or call +39 7856 3456123. Our team is available 24/7 to assist you.</p>

                            <h3>7. Governing Law:</h3>
                            <p>a. These Terms shall be governed by and construed in accordance with the laws of Italy, regulations of the European Union and the Italian Data Protection Code (Legislative Decree No. 196/2003) without regard to its conflict of law principles.</p>
                            <p>b. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts of Italy.</p>

                            <p>By using Sleep Tight, you acknowledge that you have read, understood, and agree to be bound by these Terms. If you have any questions or concerns regarding these Terms, please contact us at +39 7856 3456123 Via Roma, 23 - 00184 Rome RM - Italy or email us to sleepycustomerservice@pillow.com. Thank you for choosing Sleep Tight for your sleep monitoring needs.</p>
                        </div>
</div>
                </td>

            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                    <a href="login.php" class="hover-link1 non-style-link">Login</a>
                    <br><br><br>
                </td>
            </tr>

                    </form>
            </tr>
        </table>

    </div>
</center>

<script>
        function openTermsPopup(event) {
            event.preventDefault();
            document.getElementById("termsPopup").style.display = "block";
            document.getElementById("termsOverlay").style.display = "block";
        }

        function closeTermsPopup() {
            document.getElementById("termsPopup").style.display = "none";
            document.getElementById("termsOverlay").style.display = "none";
        }

        function toggleSignUpButton() {
            var termsCheckbox = document.getElementById("termsCheckbox");
            var signUpButton = document.getElementsByName("signup")[0];
            if (termsCheckbox.checked) {
                signUpButton.disabled = false;
                signUpButton.classList.add("btn-sign-up-enabled");
                signUpButton.classList.remove("btn-sign-up");
                localStorage.setItem("termsAccepted", "true");
            } else {
                signUpButton.disabled = true;
                signUpButton.classList.remove("btn-sign-up-enabled");
                signUpButton.classList.add("btn-sign-up");
                localStorage.setItem("termsAccepted", "false");
            }
        }

        function checkTermsStatus() {
            var termsAccepted = localStorage.getItem("termsAccepted");
            var termsCheckbox = document.getElementById("termsCheckbox");
            var signUpButton = document.getElementsByName("signup")[0];
            if (termsAccepted === "true") {
                termsCheckbox.checked = true;
                signUpButton.disabled = false;
                signUpButton.classList.add("btn-sign-up-enabled");
                signUpButton.classList.remove("btn-sign-up");
            } else {
                termsCheckbox.checked = false;
                signUpButton.disabled = true;
                signUpButton.classList.remove("btn-sign-up-enabled");
                signUpButton.classList.add("btn-sign-up");
            }
        }

        // Call the function to check the terms status on page load
        document.addEventListener('DOMContentLoaded', checkTermsStatus);
    </script>

</body>
</html>