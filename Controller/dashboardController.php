<?php  
include_once 'database.php';
/**
 * 
 */
class dashboard extends dbh
{
	// Login
	public function Login($username, $password)
	{
		$username = trim($username);
		$password = trim($password);

		if (empty($username) and empty($password)) {
			echo "empty";
		}
		else{
			$this->validate($username,$password);
		}
	}

	private function validate($username,$password)
	{
		try {
			$sql = pg_query($this->con,"SELECT * FROM tbl_accounts where id_no = '$username' AND password ='$password' AND status = 1");
			$valid = pg_num_rows($sql);
			$sql2 = pg_query($this->con,"SELECT * FROM tbl_accounts where id_no = '$username' AND password ='$password' AND status = 0");
			$valid2 = pg_num_rows($sql);
			if ($valid == 1) {
				echo "valid";
				session_start();
				while ($row = pg_fetch_array($sql)) {
					$_SESSION['user_id'] = $row['id_no'];
					$_SESSION['name'] = $row['first_name']." ".$row['last_name'];
					$_SESSION['type'] = $row['account_type'];
					$_SESSION['block'] = $row['section'];

					$task = "Access the system";
					$this->SaveLogs($task);
				}
			}
			elseif($valid2 == 1){
				echo "deactivate";
			}
			else{
				echo "invalid";
			}
		} catch (Exception $e) {
			echo "Error: ".$e->getMessage();
		}
		
	}
	public function logout()
	{
		$task = "Logout from the system";
		$this->SaveLogs($task);
		session_destroy();
		session_unset();
		echo "Success";
	}
}


?>