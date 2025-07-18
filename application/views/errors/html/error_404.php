<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<title>404 - Halaman Tidak Ditemukan</title>
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

		body {
			margin: 0;
			padding: 0;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-direction: column;
			height: 100vh;
			font-family: 'Roboto', sans-serif;
			background: linear-gradient(135deg, #fceabb, #f8b500);
			color: #333;
			text-align: center;
		}

		.container {
			animation: fadeIn 1s ease-in-out;
		}

		h1 {
			font-size: 120px;
			margin: 0;
			animation: bounce 1.5s infinite alternate;
			color: #fff;
			text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
		}

		p {
			font-size: 24px;
			margin-top: 10px;
			color: #444;
		}

		a {
			display: inline-block;
			margin-top: 20px;
			padding: 12px 24px;
			background-color: #fff;
			color: #f8b500;
			border-radius: 8px;
			text-decoration: none;
			font-weight: bold;
			transition: all 0.3s ease;
			box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
		}

		a:hover {
			background-color: #f8b500;
			color: #fff;
		}

		@keyframes bounce {
			from {
				transform: translateY(0);
			}

			to {
				transform: translateY(-20px);
			}
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
			}

			to {
				opacity: 1;
			}
		}
	</style>
</head>

<body>
	<div class="container">
		<h1>404</h1>
		<p>Oops! Halaman yang kamu cari tidak ditemukan.</p>
		<p><a href="<?= base_url('/') ?>">Kembali ke Beranda</a></p>
	</div>
</body>

</html>
