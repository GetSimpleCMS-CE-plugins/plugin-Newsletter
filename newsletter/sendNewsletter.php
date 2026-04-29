<?php

if(isset($_POST['sendnewsletter'])){


     if (!class_exists('PHPMailer')) {

        require(GSPLUGINPATH."newsletter/PHPMailer/src/PHPMailer.php");
        require(GSPLUGINPATH."newsletter/PHPMailer/src/SMTP.php");
        require(GSPLUGINPATH."newsletter/PHPMailer/src/Exception.php");
     }

    
    $subject = $_POST['title'];
    $message = $_POST['contentnewsletter'];
    
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->IsSMTP();
    $mail->CharSet="UTF-8";
    $mail->Host = $servername; /* Zależne od hostingu poczty*/
    $mail->SMTPDebug =0; // 0 for no debug, 2 to debug
    $mail->Port = $portname; /* Zależne od hostingu poczty, czasem 587 */

    if($ssl == "true"){
       //$mail->SMTPSecure = 'ssl';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    };


    if($authcheck  == "true"){
        $mail->SMTPAuth = true;
    }



    $mail->IsHTML(true);
    $mail->Username = $senderemail; /* login do skrzynki email często adres*/
    $mail->Password =  base64_decode($password) ; /* Hasło do poczty */
    $mail->setFrom($senderemail, $sendername); /* adres e-mail i nazwa nadawcy */
    
    
    $mail->Subject = $subject; /* Tytuł wiadomości */
    if (trim($message) !="") {
        $mail->Body = html_entity_decode($message.'<br/><br/>'.$mailfooter);
    }
    else {
        $mail->Body = html_entity_decode($message);
    }
    
    $explodedmaillist = explode(",",$maillist);
    foreach($explodedmaillist as $email){
    
        if($email !== '' ){
            $mail->addBCC($email);
        }
    
    };
    $sended = false;
    if (trim($message) !="") {
        try {
            if(!$mail->Send()){
                $sended = false;
            } else {
                $sended = true;
            }
        } catch (Exception $e) {
            error_log('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
        $mail->clearAllRecipients();
    }

    if($sended == true){
        echo "<div class='isended'>".i18n_r('newsletter/MESSAGESUCCESS')."</div>";
    }else{
        echo "<div class='inotsended'>".i18n_r('newsletter/MESSAGEERROR')." <br>".$mail->ErrorInfo."</div>";
    };

    };

    echo '
    <div class="sendns">
		<form action="" method="post">
			<h3>'.i18n_r('newsletter/SENDNEWNEWSLETTER').'</h3>
			
			<label>'.i18n_r('newsletter/NEWSLETTERTITLE').'</label>
			<br>
			<input style="width:100%;padding:10px;box-sizing:border-box;margin-bottom:20px;border:solid 1px #ddd;" name="title" type="input" >
			
			<label>'.i18n_r('newsletter/NEWSLETTERCONTENT').'</label>
			<br>
			<textarea name="contentnewsletter" id="post-content2" style="width:100%;padding:10px;box-sizing:border-box;height:400px;">
			</textarea>
			<input type="submit" name="sendnewsletter" style="background:green;color:#fff;border:none;padding:10px 15px;margin-top:10px" value="'.i18n_r('newsletter/SEND').'">
		</form>
    </div>
    
    ';

    echo '<script type="text/javascript" src="template/js/ckeditor/ckeditor.js?t=3.3.16"></script>
		<script type="text/javascript">
		CKEDITOR.timestamp = "3.3.16";
		var editor = CKEDITOR.replace( "post-content2", {
				skin : "getsimple",
				forcePasteAsPlainText : true,
				language : "en",
				defaultLanguage : "en",
									entities : false,
				height: "300px",
				baseHref : "'.$SITEURL.'",
				tabSpaces:10,
				filebrowserBrowseUrl : "filebrowser.php?type=all",
				filebrowserImageBrowseUrl : "filebrowser.php?type=images",
				filebrowserWindowWidth : "730",
				filebrowserWindowHeight : "500"
				,toolbar: "advanced"										
		});
	</script>';

?>
