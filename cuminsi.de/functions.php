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

    //generates random verification code
    function generate_verification_code(): string {
        return bin2hex(random_bytes(16));
    }

    //sends mail
    function send_verification_email(string $email, string $verification_code): void {
        ini_set("SMTP", "aspmx.l.google.com");
        ini_set("sendmail_from", VERIFICATION_SENDER_EMAIL_ADDRESS);

        // create the activation link
        $activation_link = APP_URL . "/verify.php?email=$email&activation_code=$verification_code";

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
?>