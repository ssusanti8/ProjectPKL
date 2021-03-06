<?php
session_start();
require 'backend/koneksi.php';
date_default_timezone_set("Asia/Bangkok");
$date_now = date("Y-m-d", strtotime("-16 years"));
$getid = $_GET['id'];
$getdata = mysqli_query($conn,"select * from job where id='$getid'");
$data = mysqli_fetch_array($getdata);
                    $idjob = $data['id'];
                    $namajob = $data['jobname'];
                    $descjob = $data['jobdesc'];
                    $mulai = date_format(date_create($data['jobstart']),"d M Y");
                    $selesai = date_format(date_create($data['jobend']),"d M Y");
                    $periode = $mulai." - ".$selesai;
                    $deadline = date_format(date_create($data['registerend']),"d M Y");
                    $jobloc = $data['jobloc'];
                    $workingtype = $data['workingtype'];
                    if(isset($_POST['apply'])){
						$fullname = $_POST['fullname'];
						$gender = $_POST['gender'];
						$dob = $_POST['dob'];
						$alamat = $_POST['alamat'];
						$nik = $_POST['nik'];
						$telepon = $_POST['telepon'];
						$motivational = $_POST['motivational'];
						extract($_POST);
						$nama_file   = $_FILES['foto']['name'];
						if(!empty($nama_file)){
						   // Baca lokasi file sementar dan nama file dari form (fupload)
						   $lokasi_file = $_FILES['foto']['tmp_name'];
						   $tipe_file = pathinfo($nama_file, PATHINFO_EXTENSION);
						   $file_foto = date('ymdhis').".".$tipe_file;					   
						   // Tentukan folder untuk menyimpan file
						   $folder = "img/foto/$file_foto";
						   // Apabila file berhasil di upload
						   move_uploaded_file($lokasi_file,"$folder");
						}
						else
						$file_foto="-";
						$nama_ktp   = $_FILES['ktp']['name'];
						if(!empty($nama_ktp)){
							// Baca lokasi file sementar dan nama file dari form (fupload)
							$lokasi_file = $_FILES['ktp']['tmp_name'];
							$tipe_file = pathinfo($nama_file, PATHINFO_EXTENSION);
							$file_ktp = date('ymdhis').".".$tipe_file;					   
							// Tentukan folder untuk menyimpan file
							$folder = "img/ktp/$file_ktp";
							// Apabila file berhasil di upload
								  move_uploaded_file($lokasi_file,"$folder");
						}
						else
							$file_ktp="-";
						$getdatanik = mysqli_query($conn, "select * from registrant where nik = '.$nik.' && where idjob == '.$getid'");
						if($getdatanik == NULL){
							$insertdata = mysqli_query($conn,"insert into registrant (idjob,name,gender,dob,alamat,nik,telepon,motivational,foto,ktp,status) 
							values('$idjob','$fullname','$gender','$dob','$alamat','$nik','$telepon','$motivational','$file_foto','$file_ktp', 'belum dicek')");
							
							if($insertdata){
								header('location:thanks.php');
							} else {
								echo 'Gagal
								<meta http-equiv="refresh" content="3;url=submit.php" />';
							}
						}
						else {
							echo 'Gagal
                            <meta http-equiv="refresh" content="3;url=submit.php" />';
						}
                    };

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$namajob;?></title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lobster">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="assets/css/main.css" />
	<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>


<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<!-- <header id="header" class="alt">
					<h1>DINAS TENAGA KERJA<br> Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Malang </h1>
						<p>Program pelatihan kerja ini memberikan kamu kesempatan untuk mengasah keterampilan agar siap bekerja <br> Terdapat 5 bidang keterampilan yang bisa kamu pilih.</p>
					</header> -->

				<!-- Main -->
					<div id="main">

						<!-- Introduction -->
							<section id="intro" class="main">
								<div class="spotlight">
									<div class="content">
										<header class="major">
											<h2><?=$namajob;?></h2>
										</header>
                                        <p>(<?=$periode;?>), <?=$workingtype;?></p>
                                        <p>Location: <?=$jobloc;?></p>
                                        <p><?=$descjob;?></p>
									</div>
									<img src="img/pelatihan.jpeg" class="landing" />
								</div>
							</section>

						<!-- First Section -->
							<section id="first" class="main special">
								<header class="major">
									<h2>Formulir Pelatihan Kerja</h2>
								</header>
								

                            <form method="post" enctype="multipart/form-data">
                            <div class="row gtr-uniform">
												<div class="col-6 col-12-xsmall">
                                                    Nama
													<input type="text" name="fullname" placeholder="Name" />
												</div>
												<div class="col-6 col-12-xsmall">
                                                    NIK
													<input type="text" name="nik" placeholder="NIK" />
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    Tanggal Lahir
													<input type="date" class="form-control" name="dob" min="1970-01-02" max="<?=$date_now;?>" required>
												</div>
												<div class="col-6 col-12-xsmall">
                                                    Jenis Kelamin
													<select name="gender"><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option></select>
                                                </div>
                                                <div class="col-6 col-12-xsmall">
                                                    Alamat
													<input type="text" name="alamat" placeholder="Alamat" />
												</div>
												<div class="col-6 col-12-xsmall">
                                                    Telepon (Whatsapp) dimulai dengan 62
													<input type="text" name="telepon" min="1" value="62"required>
                                                </div>
												<div class="col-12">
                                                    Motivasi
													<input type="text" name="motivational" placeholder="Motivasi dan Pengalaman Kerja" />
													<!-- <textarea name="demo-message" id="demo-message" name="motivational" placeholder="Enter your message" rows="6"></textarea> -->
												</div>
                                                <div class="col-6 col-12-xsmall">
                                                    Foto
													<input type="file" name="foto" required>
												</div>
												<div class="col-6 col-12-xsmall">
                                                    KTP
													<input type="file" name="ktp" required>
                                                </div>
                                                <div class="col-12"> 
													<ul class="actions">
														<li><input type="submit" value="Selesai" class="primary" name="apply" /></li>
														<li><a href="index.php" class="button">Kembali</a></li>
													</ul>
                                                </div>
                                            </div>
                                    </form>
							</section>

						
					</div>

				<!-- Footer -->
					<footer id="footer">
						<p class="copyright">DISNAKER PMPTSP 2022</p>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>