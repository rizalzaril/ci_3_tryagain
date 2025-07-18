<?php
defined('BASEPATH') or exit('No direct script access allowed');

class My404 extends CI_Controller
{

	public function index()
	{
		// Atur header 404
		$this->output->set_status_header('404');

		// Tampilkan view error_404
		$this->load->view('errors/html/error_404');
	}
}
