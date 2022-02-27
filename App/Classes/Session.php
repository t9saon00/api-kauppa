<?php

namespace Slimapp\Classes;

class Session {

    public static function log_in_user($user){
        $user_data = array(
			'name' => $user['name'],
            'api_secret' => $user['api_secret'],
		);
		
		$_SESSION['logged_in_user'] = true;
		$_SESSION['user'] = $user_data;
    }

    public static function set_user($user){
		$_SESSION['user'] = $user;
    }

    
    public static function logged_in(){
        if( isset( $_SESSION['logged_in_user'] ) ) {
            return true;
        }

        return false;
    }

    public static function clear_all_session_data(){
 
        if ( ini_get( "session.use_cookies" ) ) {
            $params = session_get_cookie_params();
            unset($_COOKIE[session_name()]); 
        } 


        session_unset();
        session_destroy();
 
    }

    public static function the_nonce_field(string $action){
       
        $_SESSION['nonce'][$action] = bin2hex(random_bytes(32));
 
        echo "<input type='hidden' name='_nonce' value=" . $_SESSION['nonce'][$action] . ">";
 
    } 
 
    public static function get_nonce_field(string $action){
        $_SESSION['nonce'][$action] = bin2hex(random_bytes(32));
 
        return "<input type='hidden' name='_nonce' value=" . $_SESSION['nonce'][$action] . ">";
    }
 
    public static function get_nonce_value(string $action){
        $_SESSION['nonce'][$action] = bin2hex(random_bytes(32));
 
        return $_SESSION['nonce'][$action];
    }
 
    public static function validate_nonce($action, $nonce) {
        if ( isset($_SESSION['nonce'][$action]) && $_SESSION['nonce'][$action] === $nonce ){
            return true;
        }
        return false;
    }
 

}