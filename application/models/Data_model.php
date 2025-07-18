<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_model extends CI_Model
{
	public function find_all()
	{
		return $this->db->get('band')->result();
	}

	public function find_all_by_array_results()
	{
		return $this->db->get('band')->result_array();
	}
}
