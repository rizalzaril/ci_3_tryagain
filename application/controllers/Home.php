<?php
defined('BASEPATH') or exit('No direct script access allowed');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;



class Home extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->model('Data_model');
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
}
