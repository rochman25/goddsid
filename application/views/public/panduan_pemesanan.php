
<?php 
$this->load->view('public/header');
$this->load->view('public/heading');
?>
<div class="overlay-desktop"></div>
<!-- header mobile -->
<?php 
$this->load->view('public/m_heading');
?>
<!-- CART -->
<?php 
$this->load->view('public/cart');
?>
<!DOCTYPE html>
<html lang="en">
<title>Garansi</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {font-family: "Lato", sans-serif}
.mySlides {display: none}
</style>
<body>

<!-- Page content -->
<div class="w3-content" style="max-width:2000px;margin-top:0px">

  <!-- Automatic Slideshow Images -->
    <img src="/assets/images/banner-garansi.jpg" style="width:100%">
  
  <!-- The Band Section -->
  <div class="w3-container w3-content w3-center w3-padding-64" style="max-width:800px" id="band">
    <h2 class="w3-wide">PANDUAN PEMESANAN</h2>
    <p class="w3-opacity"><i>di Godds.id</i></p>
    <p class="w3-justify"> Hello Men!, untuk kemudahan dan kenyamanan kamu dalam proses berbelanja, kamu bisa mengikuti langkah langkah di bawah ini :</p>
    <br></br>
    <p class="w3-justify">• Buka web www.godds.id</p>
    <p class="w3-justify">• Buat akun di web www.godds.id dan isi data diri kamu secara lengkap</p>
    <p class="w3-justify">• Pilih produk dan size yang kamu inginkan</p>
    <p class="w3-justify">• Pilih metode pengiriman dan pembayaran yang kamu inginkan</p>
    <p class="w3-justify">• Lakukan pembayaran ke rekening resmi GODDS, ( A/N Naufal Hunaif )</p>
    <p class="w3-justify">• Jangan lupa konfirmasi pembayaran di GODDS setelah melakukan pembayaran</p>
    <p class="w3-justify">• GODDS akan segera mengkonfirmasi dan memproses pesanan kamu</p>
    <p class="w3-justify">• ! Hubungi customer service kami di 083835525656 jika terdapat kendala dalam proses transaksi</p>
    
    </div>
  </div>

<!-- Footer -->
<footer class="w3-container w3-padding-64 w3-center w3-opacity w3-light-grey w3-xlarge">
</footer>

</body>
</html>

<?php 
$this->load->view('public/footer');
?>
	