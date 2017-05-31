<?php
/*
 * |-------------------------------------------------------
 * | Validate Input
 * |-------------------------------------------------------
 */
function validate_input($data) {
  	$data = trim($data);
  	$data = stripslashes($data);
  	$data = htmlspecialchars($data);
  	return $data;
}

/*
 * |-------------------------------------------------------
 * | Display error message
 * |-------------------------------------------------------
 */
function display_error($class_name,$message) {
  	echo "<div class='alert $class_name'>$message</div>";
}

/*
 * |-------------------------------------------------------
 * | Fetch single data
 * |-------------------------------------------------------
 */
function fetch_single($table,$field,$key,$value) {
  	$sql = "SELECT $field FROM $table WHERE $key = '$value' LIMIT 1";
	$result = $GLOBALS['conn']->query($sql);
	if ($result->num_rows > 0) {
    	$data = $result->fetch_assoc();
    	return $data;
	}else{
		return FALSE;
	}	
}

/*
 * |-------------------------------------------------------
 * | Fetch multiple data
 * |-------------------------------------------------------
 */
function fetch_multiple($table,$field,$key,$value) {
  	$sql = "SELECT $field FROM $table WHERE $key = '$value'";
	$result = $GLOBALS['conn']->query($sql);
	if ($result->num_rows > 0) {
    	$data = mysqli_fetch_all($result,MYSQLI_ASSOC);
    	return $data;
	}else{
		return FALSE;
	}	
}

/*
 * |-------------------------------------------------------
 * | Fetch data with custom query
 * |-------------------------------------------------------
 */
function fetch_custom($sql) {
	$result = $GLOBALS['conn']->query($sql);
	if ($result->num_rows > 0) {
    	$data = mysqli_fetch_all($result,MYSQLI_ASSOC);
    	return $data;
	}else{
		return FALSE;
	}	
}

/*
 * |-------------------------------------------------------
 * | Insert array of data
 * |-------------------------------------------------------
 */
function insert($table,$data) {
	// retrieve the keys of the array (column titles)
    $fields = array_keys($data);
    // build the query
    $sql = "INSERT INTO ".$table." (`".implode('`,`', $fields)."`) VALUES('".implode("','", $data)."')";
	if ($GLOBALS['conn']->query($sql) === TRUE) {
    	echo "New record created successfully";
	} else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

/*
 * |-------------------------------------------------------
 * | Get user id by using email id
 * |-------------------------------------------------------
 */
function get_user_id($email){
	$data = fetch_single('user','id','email',$email);
	if($data){
		return $data;
	}else{
		return FALSE;
	}
}

/*
 * |-------------------------------------------------------
 * | check login violation
 * |-------------------------------------------------------
 */
function check_brute($user_id) {
    // Get timestamp of current time 
    $now = time();
    // All login attempts are counted from the past 10 min. 
    $valid_attempts = $now - (30 * 60);
 
    $sql = "SELECT time FROM login_attempts WHERE user_id = $user_id AND time > '$valid_attempts'";
    $data = fetch_custom($sql);
    // If there have been more than 5 failed logins 
	if(count($data) > 5) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/*
 * |-------------------------------------------------------
 * | Validate user login
 * |-------------------------------------------------------
 */
function validate_user($email,$password){
	//encript password to md5
	$password = md5($password);
	$sql = "SELECT * FROM user WHERE email='$email' AND password='$password' LIMIT 1";
	$data = fetch_custom($sql);
	if($data){
		//fill the result to session variable
		$_SESSION['MEMBER_ID'] = $data[0]['id'];
		$_SESSION['FIRST_NAME'] = $data[0]['first_name'];
		$_SESSION['LAST_NAME'] = $data[0]['last_name'];
		return TRUE;
	}else{
		return FALSE;
	}
}

/*
 * |-------------------------------------------------------
 * | User logout
 * |-------------------------------------------------------
 */
function logout_user(){
	unset($_SESSION['MEMBER_ID']);
	unset($_SESSION['FIRST_NAME']);
	unset($_SESSION['LAST_NAME']);
}

?>