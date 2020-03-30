<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once 'database.php';
/**
 * 
 */
class mail extends dbh
{
    public function verify($email,$function)
    {
        // echo $email;
       try {
           $prepare_info = pg_query($this->con,"SELECT * FROM tbl_accounts where email_address = '$email'");
           $check = pg_num_rows($prepare_info);
           if ($check == 1) {
              $get_info = pg_fetch_row($prepare_info);
              $name = $get_info['2']." ".$get_info['4'];
              $password = $get_info['11'];
              $id_num = $get_info['1'];
              if ($function == "forgotPassword") {
                  $body = "Good Day ".$name.",<br><br><br>Here is your current password <b>".$password."</b>. Once you've access the e-DRB web app please change your password immediately. <br><br><br><br> <i>***This mail is from <a href='http://192.168.53.221/edrb/dashboard'>e-DRB System</a></i>";
                  $alt = "Good Day ".$name.", Here is your current password ".$password.". Once you've access the e-DRB web app please change your password immediately";
                  $subject = "e-DRB system Email - Forgot Password";
                  $this->forgot_password_mail($email,$name,$id_num,$subject,$body,$alt);
              }
           }
           else{
            echo "No Email";
           }
           
       } catch (Exception $e) {
           
       }
    }
    // $email,$name,$id_num,$body,$alt
    public function forgot_password_mail($email,$name,$id_num,$subject,$body,$alt)
    {
    // Load Composer's autoloader
        require '../assets/plugins/vendor/autoload.php';
        $mail = new PHPMailer(true);
            try {
                    //Server settings
                    //$mail->SMTPDebug = 5;                                       
                    $mail->isSMTP();                                            
                    $mail->Host       = '192.168.53.146';  
                    $mail->SMTPAuth   = false;                                   
                    $mail->SMTPSecure = false;
                    $mail->SMTPAutoTLS = false;
                    $mail->Username   = 'null';                     
                    $mail->Password   = 'null';                               
                    $mail->SMTPSecure = 'null';                                  
                    $mail->Port       = 25;                                    

                    //Recipients
                    $mail->setFrom('e-DRB@noreply.com.ph', 'e-DRB System Mailer');
                    $mail->addAddress($email, $name);     

                    //Content
                    $mail->isHTML(true);                                  
                    $mail->Subject = $subject;
                    $mail->Body    = $body;
                    $mail->AltBody = $alt;

                    $mail->send();
                    echo 'Sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
    }
}
?>