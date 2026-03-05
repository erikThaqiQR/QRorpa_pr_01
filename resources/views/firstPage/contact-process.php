<?php
if (isset($_POST['Email'])) {

    // EDIT THE 2 LINES BELOW AS REQUIRED
    $location = "https://qrorpa.ch";
    $email_to = "info@qrorpa.ch";
    $email_subject = "Neue Nachricht von qrorpa.ch";

    function problem($error)
    {
        echo "We are very sorry, but there were error(s) found with the form you submitted. ";
        echo "These errors appear below.<br><br>";
        echo $error . "<br><br>";
        echo "Please go back and fix these errors.<br><br>";
        die();
    }

    // validation expected data exists
    if (
        !isset($_POST['Name']) ||
        !isset($_POST['Email'])||
        !isset($_POST['Subject'])||
        !isset($_POST['Agb'])||
        !isset($_POST['Message'])
    ) {
        problem('Es tut uns leid, aber es scheint ein Problem mit dem von Ihnen eingereichten Formular zu geben.');
    }

    $name = $_POST['Name']; // required
    $email = $_POST['Email']; // required
    $subject = $_POST['Subject']; // required
    $agb = $_POST['Agb']; // required
    $message = $_POST['Message']; // required

    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if (!preg_match($email_exp, $email)) {
        $error_message .= 'Die von Ihnen eingegebene E-Mail-Adresse scheint nicht gültig zu sein.<br>';
    }

    $string_exp = "/^[A-Za-z .'-]+$/";

    if (!preg_match($string_exp, $name)) {
        $error_message .= 'Der von Ihnen eingegebene Name scheint nicht gültig zu sein.<br>';
    }

  

    $email_message = "Neue Nachricht von qrorpa.ch/:\n\n";

    function clean_string($string)
    {
        $bad = array("content-type", "bcc:", "to:", "cc:", "href");
        return str_replace($bad, "", $string);
    }

    $email_message .= "Name: " . clean_string($name) . "\n";
    $email_message .= "Email: " . clean_string($email) . "\n";
    $email_message .= "Subject: " . clean_string($subject) . "\n";
    $email_message .= "Agb: " . clean_string($agb) . "\n";
    $email_message .= "Message: " . clean_string($message) . "\n";

    // create email headers
    $headers = 'From: ' . $email . "\r\n" .
        'Reply-To: ' . $email . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    @mail($email_to, $email_subject, $email_message, $headers);
?>

    <!-- include your success message below -->


<?php
header( "Location: $location" );
}
?>