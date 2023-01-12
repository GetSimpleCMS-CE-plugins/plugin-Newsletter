<?php
 
 

$thisfile=basename(__FILE__, ".php");

 

register_plugin(
	$thisfile, //Plugin id
	'newsletter', 	//Plugin name
	'1.1', 		//Plugin version
	'Mateusz Skrzypczak',  //Plugin author
	'http://www.multicolor.stargard.pl', //author website
	'plugin for create newsletter from website', //Plugin description
	'pages', //page type - on which admin tab to display
	'newsletter'  //main function (administration)
);

global $LANG;
i18n_merge($thisfile, substr($LANG,0,2)) || i18n_merge($thisfile,'en_US');

# add a link in the admin tab 'theme'
add_action('pages-sidebar','createSideMenu',[$thisfile, 'Newsletter 📧']);
 

function newsletter() {

    global $SITEURL;
    

    //files 

    
    $sender = @file_get_contents(GSDATAOTHERPATH.'newsletter/sender.txt');
    $sendername = @file_get_contents(GSDATAOTHERPATH.'newsletter/sendername.txt');
    $servername = @file_get_contents(GSDATAOTHERPATH.'newsletter/servername.txt');
    $portname = @file_get_contents(GSDATAOTHERPATH.'newsletter/portname.txt');
    $authcheck = @file_get_contents(GSDATAOTHERPATH.'newsletter/auth.txt');
    $ssl = @file_get_contents(GSDATAOTHERPATH.'newsletter/ssl.txt');
    $emailList = @file_get_contents(GSPLUGINPATH.'newsletter/security/emails');
    $message = @file_get_contents(GSDATAOTHERPATH.'newsletter/messagenewsletter.txt');
    $messagebtn = @file_get_contents(GSDATAOTHERPATH.'newsletter/messagebtn.txt');
    $successinfo = @file_get_contents(GSDATAOTHERPATH.'newsletter/success.txt');
    $errorinfo = @file_get_contents(GSDATAOTHERPATH.'newsletter/errro.txt');



    //security files 

    $passwordfile = @file_get_contents(GSPLUGINPATH.'newsletter/security/pass');
    $emailList = file_get_contents(GSPLUGINPATH.'newsletter/security/emails');
    $newlist = explode(",",$emailList);


echo '<div style="width:100px;padding:10px;background:#fafafa;border:solid 1px #ddd; margin-bottom:10px;width:100%;box-sizing:border-box;">


'.i18n_r("newsletter/INVITATION").'

<code>
&#60;?php newsletterInvitation() ;?&#62;
</code>
<br>


</div>';


    echo '<div class="newsletter-option" style="background:#fafafa;border:solid 1px #ddd;margin-bottom:!0px;display:flex;">
    <button style="background:red;color:#fff;padding:10px;box-sizing:border-box;margin:5px;border:none;">'.i18n_r("newsletter/SENDNEWSLETTER").'</button>
     <button style="border:none;background:red;color:#fff;padding:10px;box-sizing:border-box;margin:5px;">'.i18n_r("newsletter/MAILINGLIST").'</button></div>';

echo'<br>';

    require(GSPLUGINPATH.'newsletter/sendNewsletter.php');

    require(GSPLUGINPATH.'newsletter/settingsNewsletter.php');

echo '
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="box-sizing:border-box;display:grid; width:100%;grid-template-columns:1fr auto; padding:10px;background:#fafafa;border:solid 1px #ddd;margin-top:20px;">
    <p style="margin:0;padding:0;"> '.i18n_r('newsletter/PAYPAL').' </p>
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="KFZ9MCBUKB7GL">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" border="0">
    <img alt="" src="https://www.paypal.com/en_PL/i/scr/pixel.gif" width="1" height="1" border="0">
</form>';


}




# functions
function newsletterInvitation() {


 
    if(isset($_POST['givemenewsletter'])){



        if($_POST['trap']==null && $_POST['emailnewsletter'] !== null){
    
            
            $email = $_POST['emailnewsletter'];
            $files = fopen(GSPLUGINPATH.'newsletter/security/emails','a');
            $result = fwrite($files,$email.',');
            fclose($files);
    
 
            if($result !== false){
                $status = true;
            }else{
                $status = false;
            }

        }else{
            $status = false;
        };
    
 
    };
    $message = @file_get_contents(GSDATAOTHERPATH.'newsletter/messagenewsletter.txt');
        $messagebtn = @file_get_contents(GSDATAOTHERPATH.'newsletter/messagebtn.txt');
        $successinfo = @file_get_contents(GSDATAOTHERPATH.'newsletter/success.txt');
        $errorinfo = @file_get_contents(GSDATAOTHERPATH.'newsletter/error.txt');


	echo '
    <div class="newsletter-invitation">'.$message.'<br>
		<form action="" method="post">
			<input name="emailnewsletter" placeholder="name@example.com" required value="'.@$_POST['emailnewsletter'].'" type="email">
			<input type="text" name="trap" class="newsletter-trap">
			<input name="givemenewsletter" value="'.$messagebtn.'" type="submit">
		</form>
	</div>
    ';
  
    if(isset($status)){
      if($status == true){
            echo '<div class="newsletterinfo">'.$successinfo.'</div>';
        }else{
            echo '<div class="newsletterinfo newsletterinfo-error">'.$errorinfo.'</div>';
        };
    };

}

register_style('newsletterstyle', $SITEURL.'plugins/newsletter/css/newsletterstyle.css', GSVERSION, 'screen');
queue_style('newsletterstyle',GSBOTH);
