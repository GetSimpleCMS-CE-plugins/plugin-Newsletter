<?php

	if(isset($_POST['savenewsletter'])){

    //info from post 
        $senderemail = $_POST['senderemail'];
        $passwordpost =  base64_encode($_POST['password']);
        $sendername = $_POST['sendername'];
        $servername = $_POST['servername'];
        $portname = $_POST['portname'];
        $auth = $_POST['auth'];
        $ssl = $_POST['ssl'];
        $maillist = $_POST['maillist'];
        $message = $_POST['messagenewsletter'];
        $messagebtn = $_POST['messagebtn'];

        $successinfo = $_POST['successinfo'];
        $errorinfo = $_POST['errorinfo'];

        //file to save

        $folder = GSDATAOTHERPATH.'newsletter/';
        $file = $folder.'sender.txt';
        $filesendername = $folder.'sendername.txt';
        $fileservername = $folder.'servername.txt';
        $fileportname = $folder.'portname.txt';
        $filessl = $folder.'ssl.txt';
        $filemessage = $folder.'messagenewsletter.txt';
        $filemessagebtn = $folder.'messagebtn.txt';

        $filesuccess = $folder.'success.txt';
        $fileerror = $folder.'error.txt';


        $fileauth = $folder.'auth.txt';

        $password = GSPLUGINPATH.'newsletter/security/pass';
        $chmod = 0755;
        $file_exist= file_exists($folder) || mkdir($folder,$chmod);

        if($file_exist){
            file_put_contents($file,$senderemail);
            file_put_contents($filesendername,$sendername);
            file_put_contents($fileservername,$servername);
            file_put_contents($fileportname,$portname);
            file_put_contents($password,$passwordpost);
            file_put_contents($filessl,$ssl);
            file_put_contents($fileauth,$auth);
            file_put_contents($filemessage,$message);
            file_put_contents($filemessagebtn,$messagebtn);

            file_put_contents($filesuccess,$successinfo);
            file_put_contents($fileerror,$errorinfo);
        };

        file_put_contents(GSPLUGINPATH."newsletter/security/emails", $maillist);
		
    }

	echo '<div class="mailns">

	<form action="" method="post">

		<h3>'.i18n_r('newsletter/NEWSLETTERSETTINGS').'</h3>

		<label>'.i18n_r('newsletter/SMTPSENDER').'</label>
		<br>
		<input type="email" value="'.$sender.'" placeholder="'.i18n_r('newsletter/SMTPMAIL').'" name="senderemail" placeholder="example@example.com"style="width:100%;padding:10px;box-sizing:border-box;margin-bottom:20px;border:solid 1px #ddd;">

		<label>'.i18n_r('newsletter/SENDERNAME').'</label>
		<br>
		<input type="text" name="sendername" value="'.$sendername.'" placeholder="'.i18n_r('newsletter/SENDERNAMEPLACEHOLDER').'" style="width:100%;padding:10px;box-sizing:border-box;margin-bottom:20px;border:solid 1px #ddd;">

		<label>'.i18n_r('newsletter/SMTPPASSWORD').'</label>
		<br>
		<input type="password" name="password" value="'.base64_decode($passwordfile).'" style="width:100%;padding:10px;box-sizing:border-box;margin-bottom:20px;border:solid 1px #ddd;">

		<label>'.i18n_r('newsletter/SMTPSERVER').'</label>
		<br>
		<input type="text" placeholder="'.i18n_r('newsletter/SMTPSERVERPLACEHOLDER').'" name="servername" value="'. $servername.'" style="width:100%;padding:10px;box-sizing:border-box;margin-bottom:20px;border:solid 1px #ddd;" >

		<label>'.i18n_r('newsletter/SMTPPORT').'</label>
		<br>
		<input type="text" name="portname" placeholder="465" value="'. $portname.'" style="width:100%;padding:10px;box-sizing:border-box;margin-bottom:20px;border:solid 1px #ddd;" >




		<label>'.i18n_r('newsletter/REQAUTH').'</label>
		<br>
		<select style="width:100%;height:40px;margin-bottom:10px;border:none;padding:10px;" name="auth">
			<option  value="true">'.i18n_r('newsletter/YES').'</option>
			<option   value="false">'.i18n_r('newsletter/NO').'</option>
		</select>


		<script>
			if("'. $authcheck.'" == "true"){
				document.querySelector(`select[name="auth"]`).value = "true";
			}else{
				document.querySelector(`select[name="auth"]`).value = "false";
			}
		</script>


		<label>'.i18n_r('newsletter/REQSSL').'</label>
		<br>
		<select style="width:100%;height:40px;margin-bottom:10px;border:none;padding:10px;" name="ssl">
			<option  value="true">'.i18n_r('newsletter/YES').'</option>
			<option   value="false">'.i18n_r('newsletter/NO').'</option>
		</select>

		<script>
			if("'. $ssl.'" == "true"){
				document.querySelector(`select[name="ssl"]`).value = "true";
			}else{
				document.querySelector(`select[name="ssl"]`).value = "false";
			}
		</script>
		
		<hr style="margin: 20px 0; border: 0; height: 1px;background: #333;background-image: linear-gradient(to right, #ccc, #333, #ccc);">
		
		<label>'.i18n_r('newsletter/NEWSLETTERECP').'</label>
		<small>'.i18n_r('newsletter/NEWSLETTERECPSMALL').'</small>
		<br>
		<textarea name="maillist" style="box-sizing:border-box;padding:10px;height:250px;width:100%;border:solid 1px #ddd;">'.$emailList.'</textarea>
		
		<hr style="margin: 20px 0; border: 0; height: 1px;background: #333;background-image: linear-gradient(to right, #ccc, #333, #ccc);">



<label>'.i18n_r('newsletter/SUBSUCCESS').' </label>
<br>
<input type="text" name="successinfo" style="width:100%;padding:10px;box-sizing:border-box;margin-bottom:20px;border:solid 1px #ddd;" placeholder="'.i18n_r('newsletter/SUBSUCCESSVALUE').'"   value="'.$successinfo.'">


<label>'.i18n_r('newsletter/SUBERROR').'</label>
<br>
<input type="text" name="errorinfo" style="width:100%;padding:10px;box-sizing:border-box;margin-bottom:20px;border:solid 1px #ddd;" 
placeholder="'.i18n_r('newsletter/SUBERRORVALUE').'
" value="'.$errorinfo.'">


<label>'.i18n_r('newsletter/SUBVALUE').'</label>
<br>
<input type="text" name="messagebtn" style="width:100%;padding:10px;box-sizing:border-box;margin-bottom:20px;border:solid 1px #ddd;" placeholder="'.i18n_r('newsletter/SUBVALUEVALUE').'" value="'.$messagebtn.'">


		<label>'.i18n_r('newsletter/FORMSUBSCRIBE').'</label>
		<br>
		<textarea name="messagenewsletter" id="post-content">'.$message.'</textarea>

		<input type="submit" name="savenewsletter" style="background:green;color:#fff;border:none;padding:10px 15px;margin-top:10px" value="'.i18n_r('newsletter/SAVE').'">
	
	</form>
	
	</div>';


    echo '<script type="text/javascript" src="template/js/ckeditor/ckeditor.js?t=3.3.16"></script>

    <script type="text/javascript">
		CKEDITOR.timestamp = "3.3.16";
		var editor = CKEDITOR.replace( "post-content", {
				skin : "getsimple",
				forcePasteAsPlainText : true,
				language : "en",
				defaultLanguage : "en",
									entities : false,
				height: "200px",
				baseHref : "'.$SITEURL.'",
				tabSpaces:10,
				filebrowserBrowseUrl : "filebrowser.php?type=all",
				filebrowserImageBrowseUrl : "filebrowser.php?type=images",
				filebrowserWindowWidth : "730",
				filebrowserWindowHeight : "500"
				,toolbar: "advanced"										
		});
	</script>';

    echo '<script>
		document.querySelector(".mailns").style.display="none";
		
		const sendbtn= document.querySelector(".newsletter-option button:nth-child(1)");
		const optionbtn= document.querySelector(".newsletter-option button:nth-child(2)");

		sendbtn.addEventListener("click",()=>{

			document.querySelector(".sendns").style.display="block";
			document.querySelector(".mailns").style.display="none";

		});

		optionbtn.addEventListener("click",()=>{

			document.querySelector(".sendns").style.display="none";
			document.querySelector(".mailns").style.display="block";

		});
    </script>';

;?>