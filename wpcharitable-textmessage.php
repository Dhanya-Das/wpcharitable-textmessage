<?php
/**
* Plugin Name: WPcharitable Text Message Custom Plugin
* Plugin URI: https://abacies.com
* Description: Customized plugin for text message.
* Version: 0.1
* Author: Abacies
* Author URI: https://abacies.com
**/


if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
define( 'TXT_MSG_URL',     plugin_dir_url( __FILE__ )  );
define( 'TXT_MSG_PATH',    plugin_dir_path( __FILE__ ) );
 
require_once(TXT_MSG_PATH.'twilio-php-main/src/Twilio/autoload.php');
use Twilio\Rest\Client;

add_action('wp_enqueue_scripts', 'scripts_for_txt_msg_button');
function scripts_for_txt_msg_button() {
	wp_enqueue_script('wpcharitable-textmessagejs', TXT_MSG_URL.'assets/js/wpcharitable-textmessage.js', array('jquery'), '1.1.0', true );
}

add_action('wp_enqueue_scripts', 'style_for_ticketing_txt_msg_button');
function style_for_ticketing_txt_msg_button() { 
	wp_enqueue_style('textmessage-wpcharitablecss', TXT_MSG_URL.'assets/css/textmessage-wpcharitable.css');
}

// add_action('init', 'text_message_btn');
add_shortcode('text_message_btn','text_message_btn');
function text_message_btn( $atts, $content = null){
	global $post;
  	$user_id = get_current_user_id();
    if($user_id){
        $author_id = $post->post_author;
        $single_page_link = get_permalink();
        $html = "";
        $cmp_ary = array (
        'post_type' => 'campaign',
        'posts_per_page' => -1,
        'author' => $user_id,
        'order'=> 'ASC');
        $campaign_ary = get_posts($cmp_ary);
        $count = count($campaign_ary);
        extract( shortcode_atts( array(
            
            'text'   => '',
            'color'  => '#000',
            'bg_color' => 'blue',
            'content' =>'Please support the campaign by following the link '
        ), $atts ) );


        foreach($campaign_ary as $result){
            $page_link = get_permalink($result->ID);
            break;
        }
        $html .= " <div id='modalTwo' class='modal-mobile show-mobile-atag'>
                <div class='modal-content'>
                    <div class='contact-form-mobile'>
                        <a class='close-mobile'>&times;</a>
                        <form class='mobile-view-form'>
                            <div class='mobile-form-body'>
                                <h4>Select a Campaign</h4>
                                <div class='radio-listing'>
                                    <select name='postURL' id='selectedURL' class='mobile-radio-btn'>";
                                        foreach($campaign_ary as $result){
                                            $post_url = get_permalink($result->ID); 
                                            $html .= "<option value='$post_url'>$result->post_title</option>" ;
                                        }
                                    $html .= " </select>
                                </div>
                            </div>
                            <div class='mobile-submit'>
                                <a class='show-mobile URLsubmit' id='mobile-submit-btn-and' data-type='sms:?body=' href='sms:?body=$content $page_link'>Send Message</a>
                                <a class='show-mobile-ios URLsubmit' id='mobile-submit-btn-ios' data-type='sms://?&body=' href='sms://?&body=$content $page_link'>Send Message</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>";
        
        
        if($count == 0){
            return true;
        } else if($count == 1){
            foreach($campaign_ary as $result){
                $single_page_link = get_permalink($result->ID);
            }
            // $content = $content . " " . $single_page_link;
            $content_data =  "<p class='show-mobile p-class'><a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' class='show-mobile-atag show-phone txt-btn' id='addURL1' href='sms:?body=$content $single_page_link'>$text</a> </p>";
            $content_data .=  "<p class='show-mobile-ios p-class'> <a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' class='show-mobile-ios-atag show-phone txt-btn' id='addURL' data-modal1='modalTwo' href='sms://?&body=$content $single_page_link'>$text</a> </p>";
            return $content_data;
        } else {
        // if($user_id == $author_id ){
        //   $single_page_link = get_permalink();
            foreach($campaign_ary as $result){
                $single_page_link = get_permalink($result->ID);
                break;
            }
    

            $content_data =  "<p class='show-mobile p-class'><a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' class='show-mobile-atag show-phone txt-btn' id='addURL1' data-modal1='modalTwo' data-android='$content' href='#'>$text</a> </p>";
            // $content_data =  "<p class='show-mobile'><a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' class='show-mobile-atag btn' id='addURL' data-modal1='modalTwo' href='sms:?body=$content $single_page_link'>$text</a> </p>";
            $content_data .=  "<p class='show-mobile-ios p-class'> <a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' class='show-mobile-ios-atag show-phone txt-btn' id='addURL' data-modal1='modalTwo' data-android='$content' href='#'>$text</a> </p>";
            
            /*
            $content_data .= "<p class='show-large p-class'> <a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' href='#' class='button-class-a show-large' data-modal='modalOne'>$text</a> </p>
                        <div id='modalOne' class='modal'>
                            <div class='modal-content'>
                                <div class='contact-form'>
                                    <a class='close'>&times;</a>
                            
                                    <form class='desktop-view-form'action='/'>
                                        <h2>Share by text message</h2>
                                        <div>
                                            <input class='fname' type='text' name='name' name='your_name' id='your_name' placeholder='Your Name*'/>
                                        </div>
                                        <div>
                                            <textarea class='txt-textarea' rows='4' placeholder='Enter or paste phone numbers here'></textarea>
                                        </div>
                                        <span>Message</span>
                                        <div>
                                            <textarea class='txt-textarea' rows='4'> $content 
                                                $single_page_link
                                            </textarea>
                                        </div>
                                        <button class='button-class' type='submit' href='/'>Submit</button>
                                    </form>
                            
                                </div>
                            </div>
                        </div>";
            */
            return $html . $content_data;
            // }
        }
    }

}

// add_action('charitable_user_campaign_summary_after', 'add_text_message_link');
function add_text_message_link($campaigns){
  // print_r($campaigns);
  $user_id = get_current_user_id();
  $author_id = $campaigns->post_author;
  $single_page_link = get_permalink($campaigns->ID);
  $content ="Support the campaign by clicking link ";
  $text="Text your link";
  $bg_color="#447ebd";
  $color="#fff";
  if($user_id == $author_id ){
    echo "<a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' class='show-mobile' href='sms:?body=$content $single_page_link'>$text</a>";
    echo "<a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' class='show-mobile-ios' href='sms://?&body=$content $single_page_link'>$text</a>";
    echo "<p class='show-large p-class'>
            <a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' href='#' class='button' data-modal='modalOne'>$text</a>
            </p>
            <div id='modalOne' class='modal'>
                <div class='modal-content'>
                  <div class='contact-form'>
                    <a class='close'>&times;</a>
                    
                      <form action='/'>
                        <h2>Share by text message</h2>
                        <div>
                          <input class='fname' type='text' name='name' name='your_name' id='your_name' placeholder='Your Name*'/>
                        </div>
                        <div>
                          <textarea class='txt-textarea' rows='4' placeholder='Enter or paste phone numbers here'></textarea>
                        </div>
                        <span>Message</span>
                        <div>
                          <textarea class='txt-textarea' rows='4'> $content 
                          $single_page_link
                          </textarea>
                        </div>
                        <button class='button-class' type='submit' href='/'>Submit</button>
                      </form>
                    
                  </div>
                </div>
              </div>";
  }
	
}
add_shortcode('campaigns_url','camp_link_URL');
function camp_link_URL(){
	
	global $post;
  	$user_id = get_current_user_id();
    if($user_id){
        $author_id = $post->post_author;
        $single_page_link = get_permalink();
        $html = "";
        $cmp_ary = array (
        'post_type' => 'campaign',
        'posts_per_page' => -1,
        'author' => $user_id,
        'order'=> 'ASC');
        $campaign_ary = get_posts($cmp_ary);
        $count = count($campaign_ary);
        extract( shortcode_atts( array(
            
            'text'   => '',
            'color'  => '#000',
            'bg_color' => 'blue',
            'content' =>'Please support the campaign by following the link '
        ), $atts ) );

		$content_url = array();
        foreach($campaign_ary as $result){
            $page_link = get_permalink($result->ID);
			$content_url[] = "<a href =$page_link>$page_link</a>";
            
        }
		$ct = implode("<br/>", $content_url);
		return $ct;
		
	}
	
}
/**
 * User Registration shortcode
 */
add_shortcode('registration_form','user_registration');
function user_registration(){

   $content = "<div class='user-reg-card-main'>
                    <div class='user-reg-card'>
                        <div class='user-reg-container'>
                            <form class='user-reg-form' method='POST'>
                                <div class='row'>
                                    <div class='col-25'>
                                        <label for='email'>Email ID</label>
                                    </div>
                                    <div class='col-75'>
                                        <input type='text' required id='email'name='user_email' placeholder='Your email ID..'>
                                    </div>
                                    <div class='col-25'>
                                    <label for='phone'>Phone</label>
                                    </div>
                                    <div class='col-75'>
                                        <input type='text' required id='phone'name='user_phone' placeholder='Your mobile number..'>
                                    </div>
                                <div>
                                <div class='row'>
                                    <input type='submit' class='user-form-submit' name='user_submit' value='Submit'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";

    if(isset($_POST['user_submit'])){
		
        $user_email = $_POST['user_email'];
        $user_phone = $_POST['user_phone'];
		
		$exists = email_exists($user_email);
		if ( $exists ){
			 echo  '<div class="msg"><label for="msg" class="content-message-show">User is exist!</label></div>';
			echo "<meta http-equiv='refresh' content='0'>";
		}else{
			$username = strtok($user_email, '@');
//         	$hash_password = wp_hash_password($user_phone);

//         $result = wp_create_user( $username, $hash_password, $user_email );
		$user_id = wp_insert_user( wp_slash (array(
					  'user_login' => $username,
					  'user_pass' => $user_phone,
					  'user_email' => $user_email,
					  'first_name' => $username,
                      'user_phone' => $user_phone,
					  'display_name' => $username,
					  'role' => 'subscriber'
					)));

			if(is_wp_error($user_id)){
				$error = $user_id->get_error_message();
			}else{
				$user = get_user_by('id', $user_id);
				wp_update_user([
						'ID' => $user->ID,
						'first_name' => $username
					]);
				$first_name = ucwords($username);
				$subject = "User Registration Completed";
				$message = "<p>Hello <b>$first_name</b></p>";
				$message .= "<p>Your registration has been completed.</p>";
				$message .= "<p>Username : $user_email, and your registered phone number as Password";
				$headers = array('Content-Type: text/html; charset=UTF-8');

				wp_mail( $user_email, $subject,  $message, $headers );
				echo  '<div class="msg"><label for="msg" class="succes-message-show">User Registration Sucessfully completed!</label></div>';
				echo "<meta http-equiv='refresh' content='0'>";
			 }
		}

        
		
    }

    return $content;
}

function twilo_api($url=NULL, $to){
	
    $account_sid =  get_option('twilio_account_sid');
    $auth_token =  get_option('twilio_auth_token');
    $twilio_number =  get_option('twilio_number');

    $body = get_option('twilio_sms_body');
    $sms_body = $body . " " . $url;
	if(!$account_sid){
        // Your Account SID and Auth Token from twilio.com/console
        $account_sid = 'ACc8221e6caae950d54570cde698e89361';
        $auth_token = 'f0b2462208b4cba2e1d71008710a695b';
        // In production, these should be environment variables. E.g.:
        // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

        // A Twilio number you own with SMS capabilities
        $twilio_number = "+15005550006";
    }

	$client = new Client($account_sid, $auth_token);
	$client->messages->create(
		// Where to send a text message (your cell phone?)
		'+' . $to,
		array(
			'from' => $twilio_number,
			'body' => $sms_body
		)
	);
    return "success";
}

function twilio_register_settings_menu_page() {
    $menu_slug = 'twilio-text-message-settings';
    add_menu_page( 'Twilio Settings', 'Twilio Setting', 'manage_options', $menu_slug, 'twilio_settings_page', 'dashicons-chart-pie', 6 );
}
add_action( 'admin_menu', 'twilio_register_settings_menu_page' );

function twilio_settings_page() {
    $account_sid =  get_option('twilio_account_sid');
    $auth_token =  get_option('twilio_auth_token');
    $twilio_number =  get_option('twilio_number');
    $body = get_option('twilio_sms_body');

    $html = "<div class='twilio_settings_form'>";
    $html .= "<h1>Settings</h1>";

    $html .= "<style>
    .twilio_settings_form .form-group {margin : 10px 0;} 
    .twilio_settings_form .form-group input, 
    .twilio_settings_form .form-group textarea {width:300px;}
    .twilio_settings_form .form-group label { margin:10px; width:170px; display:inline-block;}
    .twilio_settings_form input[type='submit'] { margin:10px; background-color:#1f3855; border:1px solid #1f3855; color:#ffffff; width:60px; padding:10px; cursor:pointer;}
    </style>";


    $html .= "<form method='post' action='".admin_url( 'admin.php' )."' name='twilio_settings' enctype='multipart/form-data'>";
    $html .= "<input type='hidden' name='action' value='save_twilio_settings'>";

    $html .= "<div class='form-group'>";
    $html .= "<label for='twilio_account_sid'>Twilio Account SID</label>";
    $html .= "<input type='text' name='twilio_account_sid' value='".$account_sid."' id='twilio_account_sid'>";
    $html .= "</div>";

    $html .= "<div class='form-group'>";
    $html .= "<label for='twilio_auth_token'>Twilio Auth Token</label>";
    $html .= "<input type='text' name='twilio_auth_token' value='".$auth_token."' id='twilio_auth_token'>";
    $html .= "</div>";

    $html .= "<div class='form-group'>";
    $html .= "<label for='twilio_number'>Twilio SMS From Number</label>";
    $html .= "<input type='text' name='twilio_number' value='".$twilio_number."' id='twilio_number'>";
    $html .= "</div>";

    $html .= "<div class='form-group'>";
    $html .= "<label for='twilio_sms_body'>Twilio SMS body</label>";
    $html .= "<textarea name='twilio_sms_body' id='twilio_sms_body'>".$body."</textarea>";
    $html .= "</div>";

    $html .= "<input type='submit' name='twilio_settings' value='Save'>";

    $html .= "</form>";
    $html .= "</div>";
    echo $html;
}

add_action( 'admin_action_save_twilio_settings', 'save_twilio_settings');
function save_twilio_settings() {
    if(isset($_POST['twilio_settings'])){
        update_option('twilio_account_sid', $_POST['twilio_account_sid']);
        update_option('twilio_auth_token', $_POST['twilio_auth_token']);
        update_option('twilio_number', $_POST['twilio_number']);
        update_option('twilio_sms_body', $_POST['twilio_sms_body']);

        // $return_text = "Successfully Saved";
        wp_redirect(esc_url(home_url("/wp-admin/admin.php?page=twilio-text-message-settings")));
            exit();
    }
}

add_filter('charitable_user_registration_fields', 'user_registration_fields');
function user_registration_fields($fields) {
	$fields = array(
		'user_email' => array(
			'label'    => __( 'Email', 'charitable' ),
			'type'     => 'email',
			'required' => true,
			'priority' => 4,
			'value'    => isset( $_POST['user_email'] ) ? $_POST['user_email'] : '',
		),
		'user_pass' => array(
			'label'    => __( 'Phone Number', 'charitable' ),
			'type'     => 'text',
			'priority' => 6,
			'required' => true,
			'value'    => isset( $_POST['user_pass'] ) ? $_POST['user_pass'] : '',
		),
		'user_login' => array(
			'label'    => __( 'Confirm Phone Number', 'charitable' ),
			'type'     => 'text',
			'priority' => 6,
			'required' => true,
			'value'    => isset( $_POST['user_login'] ) ? $_POST['user_login'] : '',
		),
	);
	return $fields;
}

// add_filter('charitable_registration_values', 'user_registration_values');
// function user_registration_values($values, $fields, $form) {
	
// }
// add_action('charitable_after_insert_user', 'update_phone_number', 2);
// function update_phone_number($user_id, $values) {
// 	update_user_meta($user_id, 'phone_number', $values['user_login']);
// }

add_action('user_register', 'update_phone_number', 10, 2);
function update_phone_number($user_id, $userdata) {
    if(is_numeric($userdata['user_login'])){
	    update_user_meta($user_id, 'phone_number', $userdata['user_login']);
    }
}

// you must do this to override
add_action( 'save_post', 'save_campaign_cpt', 20, 3 );
function save_campaign_cpt( $post_id, $post, $update ) {
    // bail out if this is an autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
 
    // bail out if this is not an event item
    if ( 'campaign' !== $post->post_type ) {
        return;
    }
	
	if ( $update ) {
        return;
    }
	
	global $post;
	// 	$author_id=$post->post_author;
	$author = wp_get_current_user();
	$phone = get_user_meta($author->ID, 'phone_number', true);
    if(!$phone){
        $phone = $author->user_login;
        if(is_numeric($phone)){
            update_user_meta($user_id, 'phone_number', $phone);
        }
    }
	$email = $author->user_email;
	$url =  get_the_permalink($post_id);
	$title = get_the_title($post_id);
    if($phone){
	    twilo_api($url, $phone);
    }
		
	$subject = "Campaign Creation Successfull";
	$message = "<p>Hello <b>There</b></p>";
	$message .= "<p>$title Campaign creation has been completed.</p>";
	$message .= "<p>Please find the link to the campaign. </p>";
	$message .= "<p>$url</p>";
	$headers = array('Content-Type: text/html; charset=UTF-8');

	wp_mail( $email, $subject,  $message, $headers );
	
}
// add_action('init', function(){
// 	$author = wp_get_current_user();
// 	update_user_meta($author->ID, 'phone_number', '12064563059');
// 	$phone = get_user_meta($author->ID, 'phone_number', true);
// 	echo "Phone -> " . $phone;
// });