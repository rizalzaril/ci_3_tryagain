<!DOCTYPE html>
<html>

<head>
	<title>Real-time Notification</title>
	<!-- Pusher -->
	<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
	<h1>Real-time Notifikasi</h1>
	<div id="notifikasi"></div>

	<script>
		// Enable logging for debug
		Pusher.logToConsole = true;

		const pusher = new Pusher('9affa39c1ea1ad8e2045', {
			cluster: 'mt1'
		});

		const channel = pusher.subscribe('my-channel');
		channel.bind('my-event', function(data_notif) {
			// Menampilkan data.message dengan SweetAlert2
			const prettyData = JSON.stringify(data_notif, null, 2);
			Swal.fire({
				title: 'Notifikasi Baru!',
				html: prettyData,
				icon: 'info',
				confirmButtonText: 'OK'
			});
		});
	</script>
</body>

</html>