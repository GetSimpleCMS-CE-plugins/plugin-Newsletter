<?php

if(isset($_POST['sendnewsletter'])){

    require(GSPLUGINPATH."newsletter/PHPMailer/src/PHPMailer.php");
    require(GSPLUGINPATH."newsletter/PHPMailer/src/SMTP.php");
    require(GSPLUGINPATH."newsletter/PHPMailer/src/Exception.php");
    
    $subject = $_POST['title'];
    $message = $_POST['contentnewsletter'];
    
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    $mail->IsSMTP();
    $mail->CharSet="UTF-8";
    $mail->Host = $servername; /* Zależne od hostingu poczty*/
    $mail->SMTPDebug = 0;
    $mail->Port = $portname; /* Zależne od hostingu poczty, czasem 587 */

    if($ssl == "true"){
       $mail->SMTPSecure = 'ssl';
    };


        if($authcheck  == "true"){
    $mail->SMTPAuth = true;
    }



    $mail->IsHTML(true);
    $mail->Username = $sender; /* login do skrzynki email często adres*/
    $mail->Password =  base64_decode($passwordfile) ; /* Hasło do poczty */
    $mail->setFrom($sender, $sendername); /* adres e-mail i nazwa nadawcy */
    
    
    $mail->Subject = $subject; /* Tytuł wiadomości */
    $mail->Body = html_entity_decode($message);
    
    foreach($newlist as $email){
    
        if($email !== '' ){
            $mail->addAddress("$email");
            //$success = $mail->Send();
    
            if(!$mail->Send()){
                $sended = false;
                } else {
              $sended = true; 
                }
    
            $mail->clearAllRecipients();
        }
    
    };
    
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