<?php
class Admin_model extends N8_Model {

	function Admin_model(){
			parent::__construct();
	}

	function checkExisting() {
		$data = array('Username' => $this->input->post('username'));
		$query = $this->db->get_where('users',$data);
		$num = $query->num_rows();
		return $num;
	}

	function undoUser($id = null,$date = null,$user = null) {
		if($id != null) {
			$this->db->delete('users',array('ID' => $id));
		} elseif($date != null && $user != null) {
			$user['dateAdded'] = $date;
			$this->db->delete('users',$user);
		}
	}

	/**
	 * Creates a new user account upon registration
	 *
	 */
	function createUser() {
		$date = date("Y-m-d H:i:s");
		$user_info = array('Username' => $this->input->post('username'),
							'Password' => md5($this->input->post('password')),
							'Email' => $this->input->post('email'),
							'agree_to_terms' => $date,
							'dateAdded' => $date);
		$this->db->insert('users',$user_info);

		$query = $this->db->get_where('users',$user_info);//get user id.
		$num_rows = $query->num_rows();
		if($num_rows == 1){
			$row = $query->row();
				return $row->ID;
		} elseif($num_rows > 1) {
			$id = null;
			try {
				$this->undoUser($id,$date,$user_info);
			} catch(Exception $e) {
				//TODO log $e here
			}
		}
		return false;
	}

	function createCharitableAcct($id) {
		$account_info = array('CA_ID' => $id);
		if(!empty($_POST['caName']))
			$account_info['CA_Name'] = $this->input->post('caName');
		else
			$account_info['CA_Name'] = 'CharitableAccount_'.$id;

		if($_POST['calc'] != 3) {
			$account_info['multiplier'] = $this->input->post('multiplier');
			$account_info['priority'] = $this->input->post('priority');
		}
		$account_info['MultiplierType'] = $this->input->post('calc');
		$account_info['active'] = 1;
		try {
			$this->db->insert('charitableaccounts',$account_info);
		} catch(Exception $e) {
			return 0;
			//TODO: log $e here
		}
		return 1;
	}
}
?>
