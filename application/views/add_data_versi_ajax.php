<h2>Tambah Data Band</h2>

<div id="msg"></div>

<form id="formBand" method="post">
	<input type="text" name="nama_band" placeholder="Nama Band"><br>
	<input type="text" name="genre" placeholder="Genre"><br>
	<input type="text" name="contact_band" placeholder="Contact"><br>
	<button type="submit">Kirim</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	// Ambil CSRF token dari PHP
	var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
	var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

	$('#formBand').on('submit', function(e) {
		e.preventDefault();

		// Ambil semua data form
		var formData = $(this).serializeArray();
		// Tambahkan token CSRF ke data
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
				if (res.status === 'success') {
					$('#msg').html('<p style="color:green;">' + res.message + '</p>');
					// $('#formBand')[0].reset();

					// Delay sedikit biar user lihat pesan, lalu refresh
					setTimeout(function() {
						location.reload(); // atau bisa window.location.href = 'URL';
					}, 1000);

				} else if (res.status === 'error') {
					$('#msg').html('<p style="color:red;">' + res.message + '</p>');
				} else if (res.status === 'validation_error') {
					$('#msg').html('<p style="color:orange;">' + res.message + '</p>');
				}

				// Perbarui CSRF token (kalau kamu reload token dari response, bisa juga di sini)
				csrfHash = res.csrfHash || csrfHash;
			},
			error: function() {
				$('#msg').html('<p style="color:red;">Terjadi kesalahan.</p>');
			}
		});
	});
</script>
