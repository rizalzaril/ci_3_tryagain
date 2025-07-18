<!-- ✅ Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ✅ SweetAlert2 (Opsional untuk pop-up) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ Intro.js -->
<link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css" />
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

<div class="container mt-5">
	<div class="card shadow-lg">
		<div class="card-header bg-primary text-white" id="step-header">
			<h4 class="mb-0">Tambah Data Band</h4>
		</div>
		<div class="card-body">
			<div id="msg"></div>

			<button onclick="introJs().start()" class="btn btn-outline-secondary mb-3">Panduan Isi Form</button>

			<form id="formBand">
				<div class="mb-3" id="step-nama">
					<label for="nama_band" class="form-label">Nama Band</label>
					<input type="text" name="nama_band" class="form-control" placeholder="Nama Band" required>
				</div>
				<div class="mb-3" id="step-genre">
					<label for="genre" class="form-label">Genre</label>
					<input type="text" name="genre" class="form-control" placeholder="Genre Musik" required>
				</div>
				<div class="mb-3" id="step-contact">
					<label for="contact_band" class="form-label">Contact</label>
					<input type="text" name="contact_band" class="form-control" placeholder="Nomor / Email Kontak" required>
				</div>
				<button type="submit" class="btn btn-success" id="step-submit">Kirim</button>
			</form>
		</div>
	</div>
</div>

<script>
	// ⏩ Intro.js setup
	document.addEventListener('DOMContentLoaded', function() {
		document.getElementById('step-header').setAttribute('data-intro', 'Form ini digunakan untuk menambahkan data band ke dalam sistem.');
		document.getElementById('step-header').setAttribute('data-step', '1');

		document.getElementById('formBand').setAttribute('data-intro', 'Silakan isi semua data band dengan lengkap sebelum mengirim.');
		document.getElementById('formBand').setAttribute('data-step', '2');

		document.getElementById('step-nama').setAttribute('data-intro', 'Masukkan nama band di sini.');
		document.getElementById('step-nama').setAttribute('data-step', '3');

		document.getElementById('step-genre').setAttribute('data-intro', 'Tulis genre musik band ini, misalnya: Rock, Jazz, Pop.');
		document.getElementById('step-genre').setAttribute('data-step', '4');

		document.getElementById('step-contact').setAttribute('data-intro', 'Masukkan kontak person dari band seperti email atau nomor HP.');
		document.getElementById('step-contact').setAttribute('data-step', '5');

		document.getElementById('step-submit').setAttribute('data-intro', 'Klik tombol ini untuk menyimpan data band.');
		document.getElementById('step-submit').setAttribute('data-step', '6');

		// Only run once
		if (!localStorage.getItem('intro_done')) {
			introJs().start();
			localStorage.setItem('intro_done', 'true');
		}
	});
</script>

<script>
	// ⏩ Ambil CSRF dari PHP
	let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
	let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

	$('#formBand').on('submit', function(e) {
		e.preventDefault();

		let formData = $(this).serializeArray();
		formData.push({
			name: csrfName,
			value: csrfHash
		});

		$.ajax({
			url: '<?= site_url('Home/simpan_data_ajax') ?>',
			type: 'POST',
			data: formData,
			dataType: 'json',
			success: function(res) {
				let alertClass = 'alert-danger';

				if (res.status === 'success') {
					alertClass = 'alert-success';

					Swal.fire({
						title: 'Berhasil!',
						text: res.message,
						icon: 'success',
						timer: 2000,
						showConfirmButton: false
					});

					$('#formBand')[0].reset();
				} else if (res.status === 'validation_error') {
					alertClass = 'alert-warning';

					Swal.fire({
						title: 'Validasi Gagal',
						html: res.message,
						icon: 'warning'
					});
				} else {
					Swal.fire('Gagal!', res.message, 'error');
				}

				// Tambahkan juga pesan biasa jika mau (opsional)
				$('#msg').html(`
					<div class="alert ${alertClass} alert-dismissible fade show mt-2" role="alert">
						${res.message}
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				`);

				// Update token CSRF jika dikirim kembali dari server
				if (res.csrfHash) csrfHash = res.csrfHash;
			},
			error: function() {
				Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan data.', 'error');
			}
		});
	});
</script>