<?= $this->extend('/layouts/user_layout') ?>
<?= $this->section('customStyles') ?>
<link rel="stylesheet" href="/css/checkout.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php 
     if (isset($validation)):?>
            <div class="alert alert-danger">
                <?= $validation->listErrors()?> 
            </div>
        <?php endif; ?>

<div class="title">
    <h1>Checkout</h1>
    <p>Sebelum pulang, checkout dulu yuk<p>
</div>

<div class="row justify-content-center">
<div class="col-md-12"> 
    <form action="<?= base_url('/checkout')?>" method="POST">
        <div class="row">
        <div class="mb-3 col-md-6 col-sm-6">
            <label class="form-label">Tanggal:</label>
            <input type="date" class="form-control" name="tanggalKeluar">
        </div>
        <div class="mb-3 col-md-6 col-sm-6">
            <label class="form-label">Jam keluar:</label>
            <input type="time" class="form-control" name="jamKeluar">
        </div>
        </div>
        <div class="mb-3 col-md-12">
            <label class="form-label">Progress:</label>
            <textarea rows="5" class="form-control" name="Progress" placeholder="Masukkan progress anda hari ini.."></textarea>
        </div>
        <div class="mb-3 col-md-12">
            <label class="form-label">Lokasi:</label>
           
            <input type="hidden" class="form-control" id="latitude_checkout" name="latitude_checkout">
            <input type="hidden" class="form-control" id="longitude_checkout" name="longitude_checkout">
            <div id="map"></div>
        </div>
    
        <div class="d-grid">
                <button type="submit" class="btn mb-2" style="background-color: #130C90; color:white;"><strong>Simpan</strong></button>
            </div>
    </form>


   
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script >
    var map = L.map('map').fitWorld();
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

    map.locate({
        setView:true,
        maxZoom:16
    });

    function showPosition(position) {
        var radius = position.accuracy;
        var latiLongi = position.latlng;

        //masukkan longitude dan latitude ke form
        document.getElementById('latitude_checkout').value = latiLongi.lat;
        document.getElementById('longitude_checkout').value = latiLongi.lng;

        L.marker(latiLongi).addTo(map)
            .bindPopup("Lokasi anda saat ini")
            .openPopup();
        L.circle(latiLongi).addTo(map);
       
    }map.on('locationfound',showPosition );

    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("Pengguna menolak permintaan Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Informasi lokasi tidak tersedia.");
                break;
            case error.TIMEOUT:
                alert("Permintaan lokasi pengguna timeout.");
                break;
            case error.UNKNOWN_ERROR:
                alert("Terjadi kesalahan yang tidak diketahui.");
                break;
        }
    }map.on('locationerror',showError);

</script>

</div>
</div>

<!-- Your content here -->
<?= $this->endSection() ?>
