<?php
    /*
    functionality: sends data to url and request the response of page
    example usage: for error messages

    example:

    $data = array('error_code' => 1001);
    post_request("URL/error_messages.php", $data);

    */
    function post_request($url, $data): void {
        $postvars = http_build_query($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, count($data));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postvars);
        $result = curl_exec($curl);
        curl_close($curl);
    }

    /*
        function to generate random verification code
    */
    function generate_verification_code(): string {
        return bin2hex(random_bytes(16));
    }

    /*
        function to send verifycationemail
    */
    function send_verification_email(string $email, string $verification_code): void {
        ini_set("SMTP", "aspmx.l.google.com");
        ini_set("sendmail_from", VERIFICATION_SENDER_EMAIL_ADDRESS);

        // create the activation link
        $activation_link = APP_URL . "/logic.php?action=verify&email=$email&activation_code=$verification_code";

        // set email subject & body
        $subject = 'Please verify your account';

        $message = '
                Bitte verifizieren Sie ihren Account, indem Sie <a href="' . $activation_link . '">hier</a> klicken.
            ';

        $message = '<html>
                        <body>
                            <h1>Verify your account</h1>
                            <p>To verify your account, please click <a href="' . $activation_link . '">here</a> to continue.</p>
                            <br>
                            <p>If this mail is in spam folder, please move it to your inbox</p>
                        </body>
                        </html>
        ';

        // email header
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
        $headers .= "From:" . VERIFICATION_SENDER_EMAIL_ADDRESS . "\r\n" .
                    'Reply-To:' . VERIFICATION_SENDER_EMAIL_ADDRESS . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

        // send the email
        try {
            //mail($email, $subject, nl2br($message), $headers);
            mail($email, $subject, $message, $headers);
        } catch(Exception $e) {
            die($e->getMessage());
        }

    }

    /*
        Function for display error / message codes
    */
    function displayMessageOrError() {
        if(isset($_GET['error_code'])) {
            switch($_GET['error_code']) {
                //Error Codes from signup
                case '1001':
                    echo "Der Benutzername ist bereits vorhanden. Bitte wählen sie einen anderen Benutzernamen aus.";
                    break;
                case '1002':
                    echo "Die Email adresse wird bereits verwendet.";
                    break;
                case '1003':
                    echo "Die eingegebenen Passwörter stimmen nicht überein.";
                    break;
                //Error-Codes from Login
                case '2001':
                    echo "Der Benutzer existiert nciht";
                    break;
                case '2002':
                    echo "Password falsch";
                    break;
                //Error-Codes from postmanagement
                case '7001':
                    echo 'could not delete post - none post selected';
                    break;
                //Error codes from/to settings
                case '8002':
                    echo "erneute mail konnte nicht geschcikt werden: sie sind bereits verifiziert";
                    break;
            }
        }
        if(isset($_GET['message'])) {
            switch($_GET['message']) {
                //Message Code from/&to settings
                case '8001':
                    echo "Die verify mail wurde erneut gesendet";
                    break;
                case '8003':
                    echo "email wurde geändrt";
                    break;
                //Message code from signup to login
                case '9001':
                    echo "Benutzer erfolgreich erstellt sie können sich nun einloggen.";
                    break;
                //Message code from logout to login
                case '9002':
                    echo "Sie haben sich erfolgreich ausgeloggt. Sie können sich nun wieder einloggen.";
                    break;
                //Message code from verify to login
                case '9003':
                    echo "erfolgreich verifiviert, bitte melden sie sich erneut an";
                    break;
            }
        }
    }
?>