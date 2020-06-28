<?php
// Checks if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function post_captcha($user_response) {
        $fields_string = '';
        $fields = array(
            'secret' => '6LdAfKoZAAAAAJg3qn_-H283HyNVEa0bX7tclRE6',
            'response' => $user_response
        );
        foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    // Call the function post_captcha
    $res = post_captcha($_POST['g-recaptcha-response']);

    if (!$res['success']) {
        // What happens when the CAPTCHA wasn't checked
        echo '<p>Please go back and make sure you check the security CAPTCHA box.</p><br>';
    } else {
        // If CAPTCHA is successfully completed...

       // Check for empty fields
      if(empty($_POST['name'])      ||
      empty($_POST['email'])     ||
      empty($_POST['phone'])     ||
      empty($_POST['message'])   ||
      !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
      {
      echo "No arguments Provided!";
      return false;
      }

      $name = strip_tags(htmlspecialchars($_POST['name']));
      $email_address = strip_tags(htmlspecialchars($_POST['email']));
      $phone = strip_tags(htmlspecialchars($_POST['phone']));
      $message = strip_tags(htmlspecialchars($_POST['message']));

      // Create the email and send the message
      $to = 'adin@3cs.lk'; // Add your email address in between the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
      $email_subject = "Website Contact Form:  $name";
      $email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nPhone: $phone\n\nMessage:\n$message";
      $headers = "From: adin-agency-application.herokuapp.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
      $headers .= "Reply-To: $email_address";
      $headers .= "This is a test email sent from a website created for 3CS Solutions";
      mail($to,$email_subject,$email_body,$headers);
      return true;   
    }
}
?>
