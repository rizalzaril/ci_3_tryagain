<h2>Tambah Data Band</h2>
<?= validation_errors(); ?>

<!-- Flash message -->
<?php if ($this->session->flashdata('success')): ?>
	<p style="color: green;"><?= html_escape($this->session->flashdata('success')) ?></p>
<?php endif; ?>

<?= form_open('Home/simpan_data'); ?>

<input type="text" name="nama_band" placeholder="Nama Band" value="<?= set_value('nama_band') ?>"><br>
<input type="text" name="genre" placeholder="Genre" value="<?= set_value('genre') ?>"><br>
<input type="text" name="contact_band" placeholder="Contact" value="<?= set_value('contact_band') ?>"><br>

<button type="submit">Kirim</button>
<?= form_close(); ?>
