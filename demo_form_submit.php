<?php

$error = ""; //used for recaptcha error


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
        $email = $_POST['email'];
    } else {
        exit("ERROR: Proper email address not submitted.  {$_POST['email']}");
    }

    
    if (isset($_POST['recaptcha'])) {
        $captcha = $_POST['recaptcha'];
    } else {
        $captcha = false;
    }
    
    if (!$captcha) {
    //ERROR if captcha not passed.
        exit('ERROR: reCAPTCHA not sent.');
    } else {
        //RECAPTCHA SERVER SECRET
        $secret = '[INSERT YOUR SECRET KEY HERE]';
        $response = file_get_contents(
            "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']
        );
        // use json_decode to extract json response
        $response = json_decode($response);

        if ($response->success === false) {
            //Prepare ERROR to display.
            foreach ($response->{'error-codes'} as $code) {
                $error .=  $code;
            }
            
            exit ("<div class=\"alert alert-danger\" role=\"alert\">reCAPTCHA Error: {$error} </div>");
            //exit("ERROR: {$error}");
        }
    }
    
    $score = $response->score;
    //print "Score: {$score}<br>";
    $score_limit = 0.1; //Scale of .1 to 1.  1 = likely NOT a BOT, .01 is likely a BOT.
    if ($response->success==true && $response->score <= $score_limit) {
        //Do something to denied access
        //exit("ERROR: Failed reCAPTCHA due to a low score of {$score}.");
        exit ("<div class=\"alert alert-danger\" role=\"alert\">reCAPTCHA Failure due to a low score of {$score} which is assumed a BOT by reCAPTCHA service.</div>");
    }
    
    //The Captcha is valid
        
    $dbhost = "localhost";
    $dbname = "test";
    $dbusername = "test";
    $dbpassword = "test1234";

    // Create connection
    $conn = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        //DB Connection Failed.
        die("Connection failed: " . $conn->connect_error);
    } else {
        //DB Connection is Good
        
        $stmt = $conn->prepare("INSERT INTO test(email) VALUES (?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        if ($conn->affected_rows != 1) {
            echo "Error Updating Database.";
        } else {
            echo "{$email} was added to the database.";    
        }
        
    }  
    
} else {
    echo "ERROR: INVALID POST DATA";
}


?>