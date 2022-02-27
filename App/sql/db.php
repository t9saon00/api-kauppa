<?php

namespace Slimapp\Sql;

use mysqli;

class DbConnect
{

    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db_name = DB_NAME;

    public $no_results_message = array("status" => "NO RESULTS", "message" => "No Results");

    private function connect_db () {
		
        if ( $this->conn === NULL ){
            
         $this->conn = mysqli_init();
            
         if ( DB_SSL !== false ){
             mysqli_ssl_set($this->conn,DB_SSL['key'],DB_SSL['cert'],DB_SSL['ca'],DB_SSL['capath'],DB_SSL['cipher']);
         }
         
         if ( strlen( $this->db_port ) ){
             mysqli_real_connect($this->conn,$this->host, $this->user, $this->pass, $this->db_name, $this->db_port) or die("Yhdistäminen ei onnistunut!");
         } else {
             mysqli_real_connect($this->conn,$this->host, $this->user, $this->pass) or die("Yhdistäminen ei onnistunut!");
         }
         
            
        }
            
        return $this->conn;
     }

    public static function connect(){
		$self = (new self);
		$mysqli = $self->connect_db();
		$mysqli->select_db($self->db_name);
		mysqli_set_charset($mysqli, "utf8");

		return $mysqli;
	}

    private function query($sql, $params, $insert_query = false) {
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		//$mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db_name);
		$mysqli = $this->connect_db();
		$mysqli->select_db($this->db_name);
		mysqli_set_charset($mysqli, "utf8");

		if (mysqli_connect_errno()) {
		    return array("status" => "ERROR", "message" => "Connection Error");
		    $mysqli->close();
		}
		
		$stmt = $mysqli->stmt_init();
		$stmt = $mysqli->prepare($sql);
		
		if ( !$stmt ) { 
			return array("status" => "ERROR", "message" => $stmt);
		}
		
		if ( $params ){
			$refs = $this->refValues($params);
       		call_user_func_array(array($stmt, 'bind_param'), $refs);	
		}

		try {
			$stmt->execute();	
		}
		catch(\Exception $e){
			return array("status" => "ERROR", "message" => $e->getMessage());
		}

		if ( $insert_query === true ) {
			$last_id = ( $stmt->insert_id ) ? $stmt->insert_id : null;
			$return = array("rows_affected" => $stmt->affected_rows, "id" => $last_id);	
		}
		else {
			$return = $stmt->get_result();	
		}   	

		$stmt->close();	
		$mysqli->close();
		
		
		return $return;

	}

    private function refValues($arr)
    {
        $refs = array();
        foreach ($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }

    private function get_results($query_results)
    {
        $data = [];
        while ($row = $query_results['results']->fetch_assoc()) {
            $data[] = $row;
        }
        if ($data) {
            $return = $data;
        } else {
            $return = false;
        }

        return $return;
    }

    private function get_result($query_results)
    {
        $data = [];
        while ($row = $query_results['results']->fetch_assoc()) {
            $data[] = $row;
        }
        if ($data) {
            $return = $data[0];
        } else {
            $return = false;
        }

        return $return;
    }

    private function get_response_with_status($query)
    {

        if (isset($query['status']) && $query['status'] === "ERROR") {
            return $query;
        }

        return array(
            "status" => "OK",
            "message" => array(
                "query" => $query,
            )
        );
    }
    /**
	 * Returns single row
	 *
	 * @param [string] $query - Query to run
	 * @param [array] $params - prepared args
	 * @return void
	 */
	public static function get_row($query, $params) {

		$self = (new self);

		$query_results = $self->query($query, $params);
		
        return $self->get_result($query_results);
	}

	public static function insert($query, $params){
		
		$self = (new self);

		$insert = $self->query($query, $params, true);
		
		if ( is_array($insert) && isset($insert['status']) && $insert['status'] === "ERROR" ) {		
			return $insert;
		}

		return $self->get_response_with_status($insert);	

	}

	public static function delete($query, $params){
		
		$self = (new self);
		
		$delete = $self->query($query, $params, true);

		$return = array(
			"delete" => $delete,
		);

		return $return;

    }

	public static function get_array($query, $params) {

		$self = (new self);

		$query_results = $self->query($query, $params);
		
        return $self->get_results($query_results);
	}

    private function get_query_count($query, $params)
    {
        $self = (new self);
        $result = $self->query($query, $params);
        if ($result['status'] === "ERROR") {
            $count = $result['message'];
        } else {
            $rows = [];
            while ($row = $result['results']->fetch_assoc()) {
                $rows[] = $row;
            }
            if ($rows) {
                $count = $rows[0]['countAll'];
            }
        }
        return $count;
    }

    public static function test(){
        $self = (new self);
  
        $query = $self->query("select * from Users", []);
 
        return $self->get_response_with_status($self->get_results($query));
    }

    public static function dbAuthorization($un, $pw){
        $self = (new self);
        $user = $un;
        $password = $pw;        
        $query = "select * from Users where name='" . $user . "' and password='" . $password . "' ";
        $params = array('ss', $user, $password);
        $result = $self->query($query, $params);

        return $self->get_result($result);

    }

    public static function user_check($user){
        $self = (new self);
        $query = "select * from Users where name=?";
        $params = array('s', $user);
        $result = $self->query($query, $params);

        if(mysqli_num_rows($result) > 0){
            return false;
        } else {
            return true;
        }
        
    }

    public static function user_authorization($user, $pass){
        $self = (new self);
 
		$query = "select * from Users where name = ?";
        $params = array('s', $user);
		$result = $self->query($query, $params);
        
		if ( is_array( $result ) && $result['status'] === "ERROR" ) {
			$return = $result;
		}
		else {
			$data = [];
			while ( $row = $result->fetch_assoc() ) {
				$data[] = $row;
			}
			if ( $data ){
				$user = $data[0];
				
				$pwd_salted = hash_hmac("sha256", $pass, AUTH_SALT);

				if ( password_verify($pwd_salted, $user['password']) ) {
					$return = $user;
				}
				else { 
					$return = false;
				}	
				
			}
			else {
				$return = false;
			}
		}
		return $return;
    }

    public static function token_authorization($token){
        $self = (new self);
 
		$query = "select * from Users where api_token = ?";
        $params = array('s', $token);
		$result = $self->query($query, $params);
		if ( mysqli_num_rows($result) > 0 ) {
			$return = true;
		} else {
            $result = false;
        }
        return $result;
    }

    public static function get_secret($user){
        $self = (new self);
        $query = "select * from Users where name = ?";
        $params = array('s', $user);
		$result = $self->query($query, $params);

        $row = $result->fetch_assoc()['secret'];

        return $row;

    }

    public static function get_user_data($user){
        $self = (new self);
        $query = "select * from Users where name = ?";
        $params = array('s', $user);
		$result = $self->query($query, $params);

        $row = $result->fetch_assoc();

        return $row;
    }

    public static function add_user($args){
        $self = (new self);
        $user = $args['name'];
        $password = $args['password'];
        $email = $args['email'];
        $phone = $args['phone'];
        $secret = $args['secret'];
        $api_secret = bin2hex(openssl_random_pseudo_bytes(16));
        $api_token = '';

        $pwd_hashed = password_hash(hash_hmac("sha256", $password, AUTH_SALT), PASSWORD_DEFAULT); 
        
        $query = "insert into Users (name, password, email, phone, secret, api_secret, api_token) values (?, ?, ?, ?, ?, ?, ?)";
        $params = array('sssssss', $user, $pwd_hashed, $email, $phone, $secret, $api_secret, $api_token);

        $user_insert = $self->query($query, $params, true);
		
		if ( is_array($user_insert) && isset($user_insert['status']) && $user_insert['status'] === "ERROR" ) {		
			return $user_insert;
		}

		return $self->get_response_with_status($user_insert);	 

    }

    public static function add_token($un, $jwt_token){ 
        $self = (new self);
        $query = "UPDATE Users SET api_token=? where name='" . $un . "' "; 
        $params = array('s', $jwt_token);

        $insert_token = $self->query($query, $params, true);
        return $self->get_response_with_status($insert_token);	 
    }

    //PRODUCT FUNCTIONS

    public static function add_product($args){
        $self = (new self);

        $title = $args['title'];
        $desc = $args['description'];
        $cat = $args['category'];
        $location = $args['location'];
        $price = $args['price'];
        //$image = $args['image'];

        $query = "insert into Products (Title, Description, Category, Location, Price) values (?, ?, ?, ?, ?)";
        $params = array('sssss', $title, $desc, $cat, $location, $price);

        $user_insert = $self->query($query, $params, true);
		
		if ( is_array($user_insert) && isset($user_insert['status']) && $user_insert['status'] === "ERROR" ) {		
			return $user_insert;
		}

		return $self->get_response_with_status($user_insert);	  

    }

    public static function get_products(){
        $self = (new self);
        $query = "select * from Products";
        //$params = array('s', $user);
		$result = $self->query($query, []);

        $row = $result->fetch_all(MYSQLI_ASSOC);

        return $row;
    }

};
 