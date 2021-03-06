	<!-- header desktop -->
	<header class="desktop">
	    <div class="header-fixed">
	        <div class="top-bar">
	            
	        </div>
	        <div class="subheader">
	            <a href="<?= base_url() ?>" class="slogan">
	                #TheBasic
	            </a>
	            <ul>

	                <li><a href="<?= base_url() ?>panduan/retur">Garansi</a></li>
            <li><a href=" <?= base_url() ?>panduan/pemesanan">Cara Pemesanan</a> </li> <li><a href="<?= base_url() ?>panduan/ukuran">Panduan Ukuran</a></li>
	            </ul>
	        </div>
	        <div class="mainheader">
	            <a href="<?= base_url() ?>" class="logo">
	                <img src="<?= base_url() ?>assets/images/logo.png">
	            </a>
	            <ul>
	                <li>
	                    <a href="#">koleksi</a>
	                    <div class="dropdown dropdown-collection">
	                        <div class="dropdown-wrapper">
								<?php foreach($kategori as $key => $item){ ?>
	                            <div class="collections">
	                                <div class="">
	                                    <a href="<?= base_url(); ?>produk/<?=$item['nama_kategori']?>">
	                                        <!-- <div class="gradient"></div> -->
	                                        <!-- <img src="<?= base_url() ?>assets/images/Celana/Celana-BG.png" alt="Sepatu"> -->
	                                        <h2 style="font-size: 20px;"><?=$item['nama_kategori']?></h2>
	                                    </a>
	                                </div>
	                                <ul>
	                                </ul>
								</div>
								<?php } ?>
	                            <!-- <div class="collections">
	                                <div class="">
	                                    <a href="<?= base_url(); ?>produk/jas">
	                                        <div class="gradient"></div>
	                                        <img src="<?= base_url() ?>assets/images/Jas/Jas-BG.png" alt="Tas">
	                                        <h2>Jas</h2>
	                                    </a>
	                                </div>
	                                <ul>
	                                </ul>
	                            </div>
	                            <div class="collections">
	                                <div class="">
	                                    <a href="<?= base_url(); ?>produk/kemeja">
	                                        <div class="gradient"></div>
	                                        <img src="<?= base_url() ?>assets/images/Kemeja/Kemeja-BG.png" alt="Apparel">
	                                        <h2>Kemeja</h2>
	                                    </a>
	                                </div>
	                                <ul>
	                                </ul>
	                            </div>
	                            <div class="right-collection">
	                                <ul>
	                                </ul>
	                            </div> -->
	                        </div>
	                    </div>
	                </li>
	                <li>
	                    <a href="<?= base_url()?>profil">
	                        <span>konfirmasi pembayaran</span>
	                    </a>
	                </li>
	            </ul>
	            <div class="header-right">
	                <div class="icon-right icon-login">
	                    <a href="<?= base_url() ?>keluar">
	                        <i class="svg_icon__header_login svg-icon"></i>
	                    </a>
	                </div>
	                <div class="icon-right icon-cart">
	                    <a href="javascript:;">
	                        <i class="svg_icon__header_cart svg-icon"><span class="notif">0</span></i>
	                    </a>
	                </div>
	                <div class="icon-right icon-wishlist">
	                    <a href="<?= base_url() ?>profil">
	                        <i class="svg_icon__wishlist svg-icon"></i>
	                    </a>
	                </div>
	                <div class="search">
	                    <form action="<?= base_url()?>search" method="get">
	                        <button type="submit"><i class="svg_icon__header_search svg-icon"></i></button>
	                        <input type="text" name="search" class="autocomplete" placeholder="Masukan Kata Kunci">
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</header>