<?php 
include_once 'database.php';

/**
 * 
 */
class maintenance extends dbh
{
	public $output = "";	
	public $output2 = "";
	public $process_output ="";

	// Block Maintenance
	public function Get_Block()
	{
		$sql = pg_query($this->con,"SELECT * FROM tbl_blocks");
		$row1 = pg_fetch_all($sql);
		foreach ($row1 as $key=>$value) {
			$this->output .= "<tr>";
			$this->output .= "<td>".($key+1)."</td>";
			$this->output .= "<td>".$value['blocks']."</td>";
			$this->output .= "<td style='background-color:".$value['color']."'></td>";
			$this->output .= "<td><button class='btn btn-danger btn-del-block' data-func='delblock' data-id='".$value['id']."'>Delete</button></td>";
			$this->output .= "</tr>";
		}
		echo $this->output;
	}
	public function AddBlock($block,$color)
	{
		try {
			pg_query("BEGIN");
			$query = pg_query($this->con,"INSERT INTO tbl_blocks(blocks,color)VALUES('$block','$color')");
			if ($query) {
				echo "success";
				pg_query("COMMIT");
			}
			else{
				echo "failed";
			}
			
		} catch (Exception $e) {
			pg_query("ROLLBACK");
			echo "Error: ".$e->getMessage();
		}
	}
	public function delete_block($id)
	{
		try {
			pg_query("BEGIN");
			$query = pg_query($this->con,"DELETE FROM tbl_blocks WHERE id = $id");
			if ($query) {
				echo "success";
				pg_query("COMMIT");
			}
			else{
				echo "failed";
			}
			
		} catch (Exception $e) {
			pg_query("ROLLBACK");
			echo "Error: ".$e->getMessage();
		}
	}

	// Process Maintenance
	public function Get_Process()
	{
		$sql = pg_query($this->con,"SELECT * FROM tbl_affected_process ORDER BY block ASC, process ASC");
		$row = pg_fetch_all($sql);
		foreach ($row as $key=>$value) {
			$this->output2 .= "<tr>";
			$this->output2 .= "<td>".($key+1)."</td>";
			$this->output2 .= "<td>".$value['block']."</td>";
			$this->output2 .= "<td>".$value['process']."</td>";
			$this->output2 .= "<td><button class='btn btn-danger btn-del-process'  data-func='delproc' data-id='".$value['id']."'>Delete</button></td>";
			$this->output2 .= "</tr>";
		}
		echo $this->output2;
	}
	public function select_proc()
	{
		$sql = pg_query($this->con,"SELECT * FROM tbl_blocks");
		$row = pg_fetch_all($sql);
		foreach ($row as $value) {
			$this->process_output .= "<option value='".$value['blocks']."'>".$value['blocks']."</option>";
		}
		echo $this->process_output;
	}
	public function add_process($block,$process)
	{
		try {
			pg_query("BEGIN");
			$query = pg_query($this->con,"INSERT INTO tbl_affected_process(block,process)VALUES('$block','$process')");
			if ($query) {
				echo "success";
				pg_query("COMMIT");
			}
			else{
				echo "failed";
			}
			
		} catch (Exception $e) {
			pg_query("ROLLBACK");
			echo "Error: ".$e->getMessage();
		}
	}
	public function del_proc($id)
	{
		try {
			pg_query("BEGIN");
			$query = pg_query($this->con,"DELETE FROM tbl_affected_process WHERE id = $id");
			if ($query) {
				echo "success";
				pg_query("COMMIT");
			}
			else{
				echo "failed";
			}
			
		} catch (Exception $e) {
			pg_query("ROLLBACK");
			echo "Error: ".$e->getMessage();
		}
	}
}


?>