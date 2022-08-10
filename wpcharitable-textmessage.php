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
  	$author_id = $post->post_author;
  	$single_page_link = get_permalink();

    $cmp_ary = array (
      'post_type' => 'campaign',
      'posts_per_page' => -1,
      'author' => $author_id,
      'orderby'          => 'post_title',
      'order'            => 'ASC');
    $campaign_ary = get_posts($cmp_ary);

    echo " <div id='show-mobile-atag' class='modal-mobile show-mobile-atag'>
    <div class='modal-content'>
      <div class='contact-form'>
        <a class='close'>&times;</a>
        <form action='/'>";
    foreach($campaign_ary as $result){
      $post_url = get_permalink($result->ID);
            echo "<input type='radio' name='postURL' value='$post_url'><label for='postURLList'>$post_url</label>" ;
    }
        echo "</form>
        </div>
      </div>
    </div>";
  // if($user_id == $author_id ){
  $single_page_link = get_permalink();

  extract( shortcode_atts( array(
		
		'text'   => '',
		'color'  => '#000',
    	'bg_color' => 'blue',
	  	'content' =>'Please support the campaign by following the link '
	), $atts ) );

  $content_data =  "<p class='show-mobile'><a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' class='show-mobile-atag btn' href='sms:?body=$content $single_page_link'>$text</a> </p>";
  $content_data .=  "<p class='show-mobile-ios'> <a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' class='show-mobile-ios-atag btn' href='sms://?&body=$content $single_page_link'>$text</a> </p>";
    
  $content_data .= "<p class='show-large'> <a style='background-color:$bg_color; color:$color; text-decoration: none; border-radius: 5px;' href='#' class='button show-large' data-modal='modalOne'>$text</a> </p>
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
                                <textarea rows='4' placeholder='Enter or paste phone numbers here'></textarea>
                              </div>
                              <span>Message</span>
                              <div>
                                <textarea rows='4'> $content 
                                $single_page_link
                                </textarea>
                              </div>
                              <button type='submit' href='/'>Submit</button>
                            </form>
                          
                        </div>
                      </div>
                    </div>";
              return $content_data;
  // }

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
    echo "<p class='show-large'>
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
                          <textarea rows='4' placeholder='Enter or paste phone numbers here'></textarea>
                        </div>
                        <span>Message</span>
                        <div>
                          <textarea rows='4'> $content 
                          $single_page_link
                          </textarea>
                        </div>
                        <button type='submit' href='/'>Submit</button>
                      </form>
                    
                  </div>
                </div>
              </div>";
  }
	
}