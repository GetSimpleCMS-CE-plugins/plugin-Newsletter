<?php
 
 # Prevent direct access
if (!defined('IN_GS')) {
    die('You cannot load this page directly.');
}


$thisfile=basename(__FILE__, ".php");

register_plugin(
	$thisfile, //Plugin id
	'newsletter', 	//Plugin name
	'1.3', 		//Plugin version
	'CE Team',  //Plugin author
	'https://getsimple-ce.ovh/donate', //author website
	'Plugin for creating a newsletter via website', //Plugin description
	'pages', //page type - on which admin tab to display
	'newsletter'  //main function (administration)
);

global $LANG;
i18n_merge($thisfile, substr($LANG,0,2)) || i18n_merge($thisfile,'en_US');

# add a link in the admin tab 'theme'
add_action('pages-sidebar','createSideMenu',[$thisfile, 'Newsletter 📧']);

// register shortcode for subcription
add_filter('content', 'newsletterShortcode');

// register shortcode for unscubscription
add_filter('content', 'newsletterUnregisterShortcode');

function newsletter() {

    global $SITEURL;
    

    //files 
    
    $senderemail = @file_get_contents(GSDATAOTHERPATH.'newsletter/sender.txt');
    $sendername = @file_get_contents(GSDATAOTHERPATH.'newsletter/sendername.txt');
    $servername = @file_get_contents(GSDATAOTHERPATH.'newsletter/servername.txt');
    $portname = @file_get_contents(GSDATAOTHERPATH.'newsletter/portname.txt');
    $authcheck = @file_get_contents(GSDATAOTHERPATH.'newsletter/auth.txt');
    $ssl = @file_get_contents(GSDATAOTHERPATH.'newsletter/ssl.txt');

    $mailfooter = @file_get_contents(GSDATAOTHERPATH.'newsletter/mailfooter.txt');
    $message = @file_get_contents(GSDATAOTHERPATH.'newsletter/messagenewsletter.txt');
    $messagebtn = @file_get_contents(GSDATAOTHERPATH.'newsletter/messagebtn.txt');
    $successinfo = @file_get_contents(GSDATAOTHERPATH.'newsletter/success.txt');
    $errorinfo = @file_get_contents(GSDATAOTHERPATH.'newsletter/error.txt');

    $removemessage = @file_get_contents(GSDATAOTHERPATH.'newsletter/removemessagenewsletter.txt');
    $removemessagebtn = @file_get_contents(GSDATAOTHERPATH.'newsletter/removemessagebtn.txt');
    $removesuccessinfo = @file_get_contents(GSDATAOTHERPATH.'newsletter/removesuccess.txt');
    $removeerrorinfo = @file_get_contents(GSDATAOTHERPATH.'newsletter/removeerror.txt');

    $useSiteTheme = @file_get_contents(GSDATAOTHERPATH.'newsletter/sitetheme.txt');



    //security files 
    $password = @file_get_contents(GSPLUGINPATH.'newsletter/security/pass');
    $maillist = @file_get_contents(GSPLUGINPATH.'newsletter/security/emails');


echo '<div style="width:100px;padding:10px;background:#fafafa;border:solid 1px #ddd; margin-bottom:10px;width:100%;box-sizing:border-box;">


'.i18n_r("newsletter/INVITATION").'
<br/>
<code>
&#60;?php newsletterInvitation() ;?&#62;
</code>
<br/>
<code>
&#60;?php newsletterUnregister() ;?&#62;
</code>
<br/><br/>
'.i18n_r("newsletter/INVITATION_SHORTCODE").'
<br/>
<code>
[newsletterInvitation]
</code>
<br/>
<code>
[newsletterUnregister]
</code>
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


/**
 * Process shortcodes in content
 */
function newsletterShortcode($content) {
    // [newsletterInvitation]
    $content = preg_replace_callback(
        '/\[newsletterInvitation\]/',
        'buildNewsletterInvitationContent',
        $content
    );
    return $content;
}

/**
 * Process shortcodes in content
 */
function newsletterUnregisterShortcode($content) {
    $content = preg_replace_callback(
        '/\[newsletterUnregister\]/',
        'buildNewsletterUnregisterContent',
        $content
    );
    return $content;
}

# functions
function buildNewsletterInvitationContent() {
 
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

    $output='';
    if(!isset($status) || $status != true){
        $output=$output. '
        <div class="newsletter-invitation">'.$message.'<br>
            <form action="" method="post">
                <input name="emailnewsletter" placeholder="name@example.com" required value="'.@$_POST['emailnewsletter'].'" type="email">
                <input type="text" name="trap" class="newsletter-trap">
                <input name="givemenewsletter" value="'.$messagebtn.'" type="submit">
            </form>
        </div>
        ';
    }
  
    if(isset($status)){
      if($status == true){
            $output=$output. '<div class="newsletterinfo">'.$successinfo.'</div>';
        }else{
            $output=$output. '<div class="newsletterinfo newsletterinfo-error">'.$errorinfo.'</div>';
        };
    };

    return $output;

}

# functions
function newsletterInvitation() {
    echo buildNewsletterInvitationContent();
}

# functions
function buildNewsletterUnregisterContent() {

    error_log('buildNewsletterUnregisterContent ' );
    if(isset($_POST['removemenewsletter'])){
        error_log('buildNewsletterUnregisterContent  have post data' );
        if($_POST['trap']==null && $_POST['emailnewsletter'] !== null){
            error_log('buildNewsletterUnregisterContent  have valid post data' );
            $email = $_POST['emailnewsletter'];

            $emailList = explode(',',@file_get_contents(GSPLUGINPATH.'newsletter/security/emails'));

            foreach($emailList  as $currentmail){


                    error_log('buildNewsletterUnregisterContent $currentmail= '.$currentmail );



            };

            $index = array_search($email,$emailList);
            error_log('buildNewsletterUnregisterContent $email= '.$email.' index='.$index);
            if($index !== FALSE){
                unset($emailList[$index]);
               // array_push($emailList, ""); //empty last item to have final separator using implode function
                file_put_contents(GSPLUGINPATH.'newsletter/security/emails',implode(',',$emailList));
            }
            $status = true;

        }else{
            $status = false;
        };

    };
    $message = @file_get_contents(GSDATAOTHERPATH.'newsletter/removemessagenewsletter.txt');
    $messagebtn = @file_get_contents(GSDATAOTHERPATH.'newsletter/removemessagebtn.txt');
    $successinfo = @file_get_contents(GSDATAOTHERPATH.'newsletter/removesuccess.txt');
    $errorinfo = @file_get_contents(GSDATAOTHERPATH.'newsletter/removeerror.txt');

    $output='';
    if(!isset($status) || $status != true){
        $output=$output. '
        <div class="newsletter-invitation">'.$message.'<br>
        <form action="" method="post">
        <input name="emailnewsletter" placeholder="name@example.com" required value="'.@$_POST['emailnewsletter'].'" type="email">
        <input type="text" name="trap" class="newsletter-trap">
        <input name="removemenewsletter" value="'.$messagebtn.'" type="submit">
        </form>
        </div>
        ';
    }

    if(isset($status)){
        if($status == true){
            $output=$output. '<div class="newsletterinfo">'.$successinfo.'</div>';
        }else{
            $output=$output. '<div class="newsletterinfo newsletterinfo-error">'.$errorinfo.'</div>';
        };
    };

    return $output;

}

# functions
function newsletterUnregister() {
    echo buildNewsletterUnregisterContent();
}

register_style('newsletterstyle', $SITEURL.'plugins/newsletter/css/newsletterstyle.css', GSVERSION."-rev1", 'screen');
queue_style('newsletterstyle',GSBOTH);
$useSiteTheme = @file_get_contents(GSDATAOTHERPATH.'newsletter/sitetheme.txt');
if($useSiteTheme=="false") {
    register_style('newsletterinvitation', $SITEURL.'plugins/newsletter/css/newsletterinvitation.css', GSVERSION."-rev1", 'screen');
    queue_style('newsletterinvitation',GSBOTH);
}
