<?php 

/**
 * 
 */
class dbh
{
	public $con;
	public $shipment;

	public $link_connection = "'host=192.168.53.248 port=5432 dbname=OLAMS user=postgres password=conPG'";
	public $con_prod;
	// public $con_prod = "'host=192.168.53.206 port=5432 dbname=production_control user=postgres password=conPG'";


	function __construct(){
		date_default_timezone_set('Asia/Manila');
		// Postgre EDRB Connection String
		$this->con = pg_connect("host='192.168.53.248' port='5432' dbname='E_DRB_DB' user='postgres' password='conPG'");
		// $this->con = pg_connect("host='192.168.53.221' port='5432' dbname='E_DRB_TEST' user='postgres' password='conPG'");
		$this->con_prod = pg_connect("host='192.168.53.248' port='5432' dbname='production' user='postgres' password='conPG'");
		if (!$this->con) {
			header("Location: 404");
		}

		// SQL Server Connection String
		$servername = "192.168.53.248\IPI_MFG";
		$connectionInfo = array("Database" => "SLI_NUMBERING", "UID" => "conSLI", "PWD" => "conSLI");
		$this->shipment = sqlsrv_connect($servername,$connectionInfo);
	}

	public function SaveLogs($tasked){
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		$user_id = $_SESSION['user_id'];
		$now = date("Y-m-d H:i:s");
		try {
			pg_query($this->con,"INSERT INTO tbl_drb_logs(tasked_do, created_at, user_id) Values('$tasked', '$now','$user_id') ");
		} catch (Exception $e) {
			echo "Error: ".$e->getMessage();
		}
	}
}
?>