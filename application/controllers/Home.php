	<?php
	defined('BASEPATH') or exit('No direct script access allowed');

	require APPPATH . '../vendor/autoload.php'; // autoload Pusher

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use Dompdf\Dompdf;
	use Pusher\Pusher;


	class Home extends CI_Controller
	{


		public function __construct()
		{
			parent::__construct();
			$this->load->library('form_validation');
			$this->load->library('session');
			$this->load->model('Data_model');


			$options = array(
				'cluster' => 'mt1',
				'useTLS' => true
			);

			$this->pusher = new Pusher(
				'9affa39c1ea1ad8e2045',
				'4cf433918438b1a04262',
				'1329674',
				$options
			);
		}

		public function send()
		{
			$data['message'] = 'Hello Rizal!';
			$this->pusher->trigger('my-channel', 'my-event', $data);
			echo "Notification sent!";
		}

		public function show()
		{
			$this->load->view('show_notification');
		}

		public function index()
		{



			// Menampilkan pesan awal
			echo "Halo, ini controller Home!<br>";

			// Menjalankan query untuk mengecek koneksi ke database
			$query = $this->db->query("SELECT DATABASE() AS db");

			// Mengecek hasil query
			if ($query->num_rows() > 0) {
				$row = $query->row();
				echo "✅ Database berhasil terhubung: <strong>" . $row->db . "</strong>";
			} else {
				echo "❌ Gagal terhubung ke database.";
			}


			// get data by object
			$data_band = $this->Data_model->find_all();


			$html = "<h3>Daftar Band</h3><table border='1' cellpadding='5'><tr><th>ID</th><th>Nama Band</th></tr>";

			foreach ($data_band as $dt) {
				$html .= 	"<tr>
								<td>{$dt->id_band}</td>
								<td>{$dt->nama_band}</td>
								</tr>";
			}
			$html .= "</table>";


			//Buat file pdf
			$dompdf = new Dompdf();
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();

			// 4. Simpan ke file
			$output = $dompdf->output();
			$file_path = FCPATH . 'uploads/data_band.pdf';
			file_put_contents($file_path, $output);


			// kirim hasil table ke email

			$mail = new PHPMailer(true);

			try {
				//konfigurasi SMTP
				$mail->isSMTP();
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPAuth = 'true';
				$mail->Username = 'zaril.ziral2020@gmail.com';
				$mail->Password = 'tuxpzosvverblpme';
				$mail->SMTPSecure = 'tls';
				$mail->Port = 587;

				// Pengirim & penerima
				$mail->setFrom('zaril.ziral2020@gmail.com', 'Nama Kamu');
				$mail->addAddress('akunisengaja.2020@gmail.com', 'Penerima');

				// Konten Email
				$mail->isHTML(true);
				$mail->Subject = 'Data Band dari Database';
				$mail->Body    = $html . 'Silahkan download PDF';
				$mail->addAttachment($file_path, 'data_band.pdf'); // ⬅️ Lampirkan PDF

				$mail->send();
				echo '✅ Email berhasil dikirim!';
			} catch (Exception $e) {
				echo "❌ Email gagal dikirim. Error: {$mail->ErrorInfo}";
			}


			// echo "ID" . $dt->id_band . "<br>";
			// echo "Nama Band" . $dt->nama_band . "<br>";
			// echo "Genre" . $dt->genre . "<br>";
			// echo "Contact" . $dt->contact_band . "<br>";

		}


		public function add_data()
		{
			$this->load->view('add_data');
		}

		public function simpan_data()
		{
			$this->form_validation->set_rules('nama_band', 'Nama Band', 'required|trim');
			$this->form_validation->set_rules('genre', 'Genre Band', 'required|trim');
			$this->form_validation->set_rules('contact_band', 'Contact Band', 'required|trim');

			if ($this->form_validation->run() == FALSE) {
				$this->load->view('add_data');
			} else {
				$data = [
					'nama_band'    => htmlspecialchars($this->input->post('nama_band', TRUE)),
					'genre'        => htmlspecialchars($this->input->post('genre', TRUE)),
					'contact_band' => htmlspecialchars($this->input->post('contact_band', TRUE))
				];

				$input = "<script>alert('XSS');</script>";
				echo $input;

				if ($this->db->insert('band', $data)) {
					$this->session->set_flashdata('success', 'Data berhasil disimpan!');
					redirect('Home/add_data');
				} else {
					$this->session->set_flashdata('error', 'Gagal menyimpan data.');
					$this->load->view('add_data');
				}
			}
		}


		public function add_data_versi_ajax()
		{
			$this->load->view('add_data_versi_ajax');
		}

		public function simpan_data_ajax()
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nama_band', 'Nama Band', 'required|trim');
			$this->form_validation->set_rules('genre', 'Genre Band', 'required|trim');
			$this->form_validation->set_rules('contact_band', 'Contact Band', 'required|trim');

			if ($this->form_validation->run() === FALSE) {
				echo json_encode([
					'status' => 'validation_error',
					'message' => validation_errors()
				]);
				return;
			}

			$data = [
				'nama_band'    => $this->input->post('nama_band', TRUE),
				'genre'        => $this->input->post('genre', TRUE),
				'contact_band' => $this->input->post('contact_band', TRUE)
			];

			$data_notif['message'] = $data;
			$this->pusher->trigger('my-channel', 'my-event', $data_notif);

			if ($this->db->insert('band', $data)) {

				echo json_encode([
					'status' => 'success',
					'message' => 'Data berhasil disimpan!'
				]);
			} else {
				echo json_encode([
					'status' => 'error',
					'message' => 'Gagal menyimpan data ke database.'
				]);
			}
		}
	}
