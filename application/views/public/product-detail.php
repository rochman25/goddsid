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
<section id="content">
    <div id="product" data-product-nama="<?= $produk->nama_produk; ?>" data-product-id="<?= $produk->id_produk; ?>">
        <div class="container">
            <div class="row">
                <!--  -->
                <div class="col-lg-5 col-md-5 gallery-container" data-image-count="7">
                    <div id="containergallery" class="image-gallery">
                        <img id="elevate-zoom" class="img-responsive" src="<?= base_url() ?>assets/uploads/thumbnail_produk/<?= $thumbnail[0]; ?>" data-zoom-image="<?= base_url() ?>assets/uploads/thumbnail_produk/<?= $thumbnail[0]; ?>" />
                    </div>
                    <div id="containervideo" class="image-gallery">
                        <iframe src="<?= $produk->video_link; ?>" style="border:0;height:400px;width:400px">

                        </iframe>
                    </div>
                    <div class="image-thumbnail owl-carousel clearfix">
                        <?php foreach ($thumbnail as $p) { ?>
                            <div class="image-thumbnail--list">
                                <img src="<?= base_url() ?>assets/uploads/thumbnail_produk/<?= $p; ?>" data-zoom-image="<?= base_url() ?>assets/uploads/thumbnail_produk/<?= $p; ?>" />
                            </div>
                        <?php } ?>

                    </div>
                
                </div>

                <!--  -->

                <div id="containermgallery" class="image-gallery-mobile owl-carousel">

                    <?php foreach ($thumbnail as $p) { ?>
                        <img src="<?= base_url() ?>assets/uploads/thumbnail_produk/<?= $p; ?>" />
                    <?php } ?>

                </div>

                <!--  -->
                <div id="containermvideo" class="image-gallery-mobile owl-carousel">
                    <iframe src="<?= $produk->video_link; ?>" style="border:0;height:400px;width:100%;align-content: center;margin-left:50px;">

                    </iframe>

                </div>
                <div class="image-gallery-mobile owl-carousel">

                   
                </div>

                <!--  -->
                <div class="col-lg-7 col-md-7 col-xs-12">


                    <div class="product-ribbon clearfix">
                    </div>
                    <div class="product-info" style="margin-left:10px">
                        <div class="row">
                            <div class="column" style="width: 100%;margin-bottom: 15px;">
                                <div class="column" style="float: left;width: 100%;margin-top:0px;margin-right:10%;text-align: center;display: flex;align-items: center;">
                                    <?php $label_p = explode(",", $produk->label_produk);
                                    foreach ($label_p as $key_l => $val_l) {
                                    ?>
                                        <div class="badge_label_<?php echo str_replace(" ", "_", $val_l) ?>" <?php if ($key_l != 0) { ?> style="margin-top:auto;" <?php } ?>>
                                            <p style="margin-top:0px; font-size:xx-small; font-weight:bold"><?= strtoupper($val_l) ?></p>
                                        </div>
                                    <?php } ?>
                                
                                </div>
                            </div>
                            <div class="column" style="width: 100%;">
                                <h1 style="margin-top:0px;"><?= $produk->nama_produk; ?></h1>
                                <h2 style="font-size: 0;line-height: none;">
                                    <div class="row" style="margin-right: 0;margin-left: 0px;">
                                        <?php if ($produk->diskon_produk != 0) { ?>
                                            <div class="column" style="width: auto; margin-right:10px">
                                                <div class="price_before" style="font-size: 20px;color:#767171"> Rp <?= number_format($produk->harga_produk, 0); ?></div>
                                            </div>
                                            <div class="column" style="width: auto;">
                                                <div class="price_after">
                                                    <p class="value" style="font-size: 20px; margin-top: 0;">Rp <?= number_format($produk->harga_produk - (($produk->diskon_produk / 100) * $produk->harga_produk), 0); ?></p>
                                                    <!-- <span class="value" style="font-size: 16px; margin-top: 0;">Rp <?= number_format($produk->harga_produk - (($produk->diskon_produk / 100) * $produk->harga_produk), 0); ?></span> -->
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="column">
                                                <div class="price_after">
                                                    <span class="value">Rp <?= number_format($produk->harga_produk, 0); ?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="column" style="width: auto;">
                                            <div class="button-action" style="display: none;margin-top:0">
                                                <?php
                                                if (isset($this->session->userdata['user_data'])) {
                                                ?>
                                                    <button class="addToCart">
                                                        <i class="svg-icon svg_icon__pdp_cart"></i> Beli
                                                    </button>
                                                <?php
                                                } else {
                                                ?>
                                                    <a href="<?= base_url(); ?>login" style="padding:15px;" class="addToCart">
                                                        <i class="svg-icon svg_icon__pdp_cart"></i> Beli
                                                    </a>
                                                <?php
                                                } ?>
                                                <span class="out-of-stock" style="display:none">HABIS MEN</span>
                                            </div>
                                        </div>
                                    </div>
                                </h2>
                            </div>
                            
                        </div>
                        <br>
                        <div class="row">
                            <span>Ukuran :</span>
                            <div id="size" class="size-product">
                                <ul class="clearfix">
                                    <?php foreach ($size as $s) {
                                    ?>
                                        <li onclick="setUkuran('<?= $s; ?>');" id="produk<?= $s ?>" style="color:black !important;" class="size ">
                                            <span><?= $s; ?></span>
                                        </li>
                                    <?php
                                    } ?>

                                </ul>
                            </div>
                            <br>
                            <span>Jumlah :</span>
                            <div id="ukuran" class="size-product">
                                <ul class="clearfix">
                                    <li onclick="ubahjml(2);" class="size active ">
                                        <span>-</span>
                                    </li>
                                    <li class="size  ">
                                        <span style="color:black;" id="value">1</span>
                                    </li>
                                    <li onclick="ubahjml(1);" class="size active ">
                                        <span>+</span>
                                    </li>

                                </ul>
                            </div>

                            <div class="button-action">
                                <?php
                                if (isset($this->session->userdata['user_data'])) {
                                ?>
                                    <button class="addToCart">
                                        <i class="svg-icon svg_icon__pdp_cart"></i> Beli
                                    </button>

                                <?php
                                } else {

                                ?>
                                    <a href="<?= base_url(); ?>login" style="padding:15px;" class="addToCart">
                                        <i class="svg-icon svg_icon__pdp_cart"></i> BELi
                                    </a>

                                <?php
                                } ?>
                                <span class="out-of-stock" style="display:none">HABIS MEN</span>
                            </div>

                            <div class="product-order">
                            </div>
                            <ul class="nav nav-tabs">
                                <li class="active" data-target="product-deskripsi">
                                    <a style="color: #5a5a5a;background-color:none;" href="javascript:;">Deskripsi</a>
                                </li>
                                <li class="active" data-target="product-deskripsi">
                                    <a href="#" data-toggle="modal" data-target="#sizeModal" style="color: red;font-weight: bold;text-d;/* text-decoration: underline; */-top:10px;">Size Chart</a></a>
                                </li><!-- <li data-target="review"><a href="javascript:;">Rating dan Ulasan ( 5/5 )</a></li> -->
                            </ul>
                            <div class="tab-content">
                                <div class="product-deskripsi">
                                    <font color="5a5a5a">
                                    <?php if (empty($produk->deskripsi_produk)) {
                                        echo "<p>Belum ada deskripsi</p>";
                                    } else {
                                        echo $produk->deskripsi_produk;
                                    } ?>
                                </div>
                                <div class="review">
                                </div>
                            </div>
                            <?php
                            if (!empty($subProduk)) {
                                foreach ($subProduk as $key => $item) { ?>
                                    <div class="left-right">
                                        <div class="right-side">
                                            <div class="card" style="margin-right:10px">
                                                <div class="card-row">
                                                    <div class="row" style="margin-right: 0px;margin-left: 0px;">
                                                        <div class="column">
                                                            <input type="radio" name="addon" id="sub<?= $key ?>" value="<?= $item['id_sub_produk'] ?>"> <label for="sub<?= $key ?>" style="margin-left: 10px;"><?= $item['nama_sub'] ?></label>
                                                        </div>
                                                        <div class="column">
                                                            <b id="harga<?= $key ?>"><?= "+ Rp " . number_format($item['harga_sub'], 2) ?></b>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-right: 0px;margin-left: 0px;">
                                                        <div class="column">
                                                            <p><b>Ukuran</b></p>
                                                            <div id="size" class="size-product2">
                                                                <ul class="clearfix" style="font-size: 10px;">
                                                                    <?php
                                                                    $size = explode(",", $item['size_sub']);
                                                                    foreach ($size as $s) {
                                                                    ?>
                                                                        <li onclick="setUkuran2('<?= $s; ?>');" id="sub<?= $s ?>" style="color:black !important;" class="size ">
                                                                            <span><?= $s; ?></span>
                                                                        </li>
                                                                    <?php
                                                                    } ?>

                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="column">
                                                            <p><b>Jumlah</b></p>
                                                            <div id="ukuran" class="size-product">
                                                                <ul class="clearfix" style="font-size: 10px;">
                                                                    <li onclick="ubahjml2(2,<?=$item['stok_sub']?>,<?=$item['id_sub_produk']?>);" class="size2 active ">
                                                                        <span>-</span>
                                                                    </li>
                                                                    <li class="size  ">
                                                                        <span style="color:black;" id="valuesub<?=$item['id_sub_produk']?>">1</span>
                                                                    </li>
                                                                    <li onclick="ubahjml2(1,<?=$item['stok_sub']?>,<?=$item['id_sub_produk']?>);" class="size2 active ">
                                                                        <span>+</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="sizeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="color: black;">Size Chart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if ($produk->nama_kategori == "Celana") { ?>
                    <img src="<?= base_url() ?>assets/public/size_celana.jpg" width="100%">
                <?php } else if ($produk->nama_kategori == "Jas") { ?>
                    <img src="<?= base_url() ?>assets/public/size_jas.jpg" width="100%">
                <?php }else if($produk->nama_kategori == "Kemeja"){ ?>
                    <img src="<?= base_url() ?>assets/public/size_kemeja.jpg" width="100%">
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->load->view('public/footer');
?>


<script type="text/javascript">
    $(window).on('load', function() {
        $(".svg-container").fadeOut("slow");
    });
</script>
<script type="text/javascript">
    var base_url = '<?= base_url() ?>';
    var def_jml = 1;
    var def_jml2 = 1;
    var def_Size = "";
    var def_Size2 = "";
    var add_on = "";
    var stok = '<?= $produk->stok_produk; ?>';
    var harga = '<?= $produk->harga_produk ?>'
    // var harga = '<?= $produk->diskon_produk != 0 ? $produk->harga_produk - (($produk->diskon_produk / 100) * $produk->harga_produk) : $produk->harga_produk ?>';
    var diskon = '<?= $produk->diskon_produk ?>'

    var containervideo = document.getElementById('containervideo');
    var containergallery = document.getElementById('containergallery');
    var containermvideo = document.getElementById('containermvideo');
    var containermgallery = document.getElementById('containermgallery');
    containervideo.style.display = "none";
    containermvideo.style.display = "none";
    var ishide = true;
    var ismhide = true;

    function toggleVideo() {
        if (ishide) {
            containervideo.style.display = "none";
            containergallery.style.display = "block";


            ishide = false;
        } else {
            containervideo.style.display = "block";
            containergallery.style.display = "none";

            ishide = true;
        }
    }

    function toggleMVideo() {
        if (ismhide) {

            containermvideo.style.display = "none";
            containermgallery.style.display = "block";

            ismhide = false;
        } else {

            containermvideo.style.display = "block";
            containermgallery.style.display = "none";
            ismhide = true;
        }
    }

    function changeSizeS() {
        var S = document.getElementById('S');
        var M = document.getElementById('M');
        var L = document.getElementById('L');
        var XL = document.getElementById('XL');

        S.classList.add('active');
        M.classList.remove('active');
        L.classList.remove('active');
        XL.classList.remove('active');
        def_Size = "S";
    }

    function changeSizeM() {
        var S = document.getElementById('S');
        var M = document.getElementById('M');
        var L = document.getElementById('L');
        var XL = document.getElementById('XL');

        S.classList.remove('active');
        M.classList.add('active');
        L.classList.remove('active');
        XL.classList.remove('active');
        def_Size = "M";
    }

    function changeSizeL() {
        var S = document.getElementById('S');
        var M = document.getElementById('M');
        var L = document.getElementById('L');
        var XL = document.getElementById('XL');
        S.classList.remove('active');
        M.classList.remove('active');
        L.classList.add('active');
        XL.classList.remove('active');
        def_Size = "L";
    }

    function changeSizeXL() {
        var S = document.getElementById('S');
        var M = document.getElementById('M');
        var L = document.getElementById('L');
        var XL = document.getElementById('XL');
        XL.classList.add('active');
        S.classList.remove('active');
        M.classList.remove('active');
        L.classList.remove('active');
        XL.classList.add('active');
        def_Size = "XL";
    }

    function setUkuran(ukuran) {
        def_Size = ukuran;
        // var id = document.getElementById(id)
        // id.classList.add('active');
    }

    function setUkuran2(ukuran) {
        def_Size2 = ukuran;
        // var id = document.getElementById(id)
        // id.classList.add('active');
    }

    // function setUkuran(ukuran) {
    //     def_Size = ukuran;
    //     // var id = document.getElementById(id)
    //     // id.classList.add('active');
    // }

    // /var size_view = 
    function ubahjml(state) {
        console.log(state);
        if (state == 1) {
            if (def_jml < stok) {
                def_jml++
            } else {
                //  def_jml = stok;
            }
        } else {
            if (def_jml <= 1) {
                def_jml = 1;
            } else {
                def_jml--;
            }
        }
        document.getElementById('value').innerHTML = def_jml;
    }

    function ubahjml2(state,jml,id) {
        console.log(state);
        if (state == 1) {
            if (def_jml2 < jml) {
                def_jml2++
            } else {
                //  def_jml = stok;
            }
        } else {
            if (def_jml2 <= 1) {
                def_jml2 = 1;
            } else {
                def_jml2--;
            }
        }
        document.getElementById('valuesub'+id).innerHTML = def_jml2;
    }

    $(document).ready(function() {
        console.log(stok);
        
         $(".size-product2 ul li").on("click", function () {
			$(".size-product2 ul li").removeClass("active"),
				$(this).addClass("active");
        });

        $(".addToCart").on("click", function() {
            var id_cart = "";
            if (def_Size != "") {
                var id_prod = $("#product").data("product-id");
                var nama_barang = $("#product").data("product-nama");
                $.ajax({
                    url: base_url + "user/Home/add_cart",
                    type: "POST",
                    data: {
                        id_cart: '<?php if (!$id_cart == null || !$id_cart == "") {
                                        echo $id_cart->id_cart;
                                    } else {
                                        echo "";
                                    } ?>',
                        id_pengguna: '<?php if (empty($this->session->userdata['user_data']['id'])) {
                                            echo "";
                                        } else {
                                            echo $this->session->userdata['user_data']['id'];
                                        } ?>',
                        id_produk: '<?= $produk->id_produk; ?>',
                        qty: def_jml,
                        img: '<?= base_url() . 'assets/uploads/thumbnail_produk/'. $thumbnail[0]; ?>',
                        nama_barang: nama_barang,
                        harga: def_jml * harga,
                        size: def_Size,
                        diskon: diskon
                    }
                }).done(function(t) {
                    var res = JSON.parse(t);

                    if (true == res.success) {
                        id_cart = res.id_cart;
                        add_on = $('input[name="addon"]:checked').val();
                        if (add_on) {
                            // alert(add_on);
                            // id_cart = "Invoice-202003212308-002";
                            var idVal = $('input[name="addon"]:checked').attr("id");
                            var harga_sub = $('#harga' + idVal.replace("sub", "")).text().replace("+ Rp", "")
                            // alert(harga_sub);
                            var nama_sub = $("label[for='" + idVal + "']").text()
                            $.ajax({
                                url: base_url + "user/Home/add_cart",
                                type: "POST",
                                data: {
                                    id_cart: id_cart,
                                    id_pengguna: '<?php if (empty($this->session->userdata['user_data']['id'])) {
                                                        echo "";
                                                    } else {
                                                        echo $this->session->userdata['user_data']['id'];
                                                    } ?>',
                                    id_produk: '<?= $produk->id_produk; ?>',
                                    id_sub_produk: add_on,
                                    qty: def_jml2,
                                    img: '<?= base_url()."assets/images/add_on.png" ?>',
                                    nama_barang: nama_sub,
                                    harga: def_jml2 * parseFloat(harga_sub),
                                    size: def_Size2,
                                }
                            }).done(function(t) {
                                var res = JSON.parse(t);

                                if (true == res.success) {
                                    id_cart = res.id_cart;
                                    $(".cart-wrapper").append(res.element);
                                    var i = parseInt($(".icon-cart").find(".notif").html());
                                    $(".menu-cart").addClass("open"),
                                        $(".overlay-desktop").addClass("active")

                                }
                            }).fail(function(t) {
                                console.log(t)
                                location.reload()
                            });
                        }
                        $(".cart-wrapper").append(res.element);
                        var i = parseInt($(".icon-cart").find(".notif").html());
                        $(".menu-cart").addClass("open"),
                            $(".overlay-desktop").addClass("active")

                    }


                }).fail(function(t) {
                    console.log(t)
                    location.reload()
                });
            } else {
                alert("Silahkan Piilih Ukuran Terlebih Dahulu!")
            }
        })
    })
</script>
<script>
    function callZoom() {
        $('#elevate-zoom').elevateZoom({
            zoomType: "inner",
            cursor: "crosshair"
        });
    }

    $(document).ready(function() {

        callZoom();

        $('.image-thumbnail .owl-item').on('click', function() {

            $('.image-thumbnail--list').removeClass('active');
            $(this).find('.image-thumbnail--list').addClass('active');

            var imgSrc = $(this).find('img').attr('src');

            $('#elevate-zoom').attr('src', imgSrc);
            $('#elevate-zoom').data('zoom-image', imgSrc);
            $('#elevate-zoom').remove('.zoomContainer');
            $('#elevate-zoom').removeData('elevateZoom');

            callZoom();
        });

        if ($('.size-product li:first-child').hasClass('empty')) {
            $('.addToCart').hide();
            $('.out-of-stock').show();
        } else {
            $('.addToCart').show();
            $('.out-of-stock').hide();
        }

        $('.size').on('click', function() {
            var val = $(this).children('span').attr('data-stock');
            if (val == 0) {
                $('.addToCart').css({
                    'display': 'none'
                });
                $('.out-of-stock').css({
                    'display': 'inline-block'
                });
                $('.label-size .text-label').text(val + ' stok tersisa');
                $('.label-size').hide();
            } else {
                if (val >= 1 && val <= 3) {
                    $('.label-size').css('display', 'inline-block');
                    $('.label-size .text-label').text(val + ' stok tersisa');
                } else {
                    $('.label-size').hide();
                    $('.label-size .text-label').text('');
                }
                $('.out-of-stock').css({
                    'display': 'none'
                });
                $('.addToCart').css({
                    'display': 'inline-block'
                });
            }
        });

        $(".option-0").on("click", ".init", function() {
            $(this).closest(".option-0").children('.child-0:not(.init)').toggle();
            if ($(this).closest(".option-0").children('.child-0:not(.init)').is(":hidden")) {
                $(this).closest(".option-0").removeClass("show-option");
            } else {
                $(this).closest(".option-0").addClass("show-option");
            }
        });

        var allOptions = $(".option-0").children('.child-0:not(.init)');
        $(".option-0").on("click", ".child-0:not(.init)", function() {
            allOptions.removeClass('selected');
            $(this).addClass('selected');
            $(".option-0").children('.init').html($(this).html());
            allOptions.toggle();
            $(this).closest(".option-0").removeClass("show-option");

            generateSize(1, $(".option-0").find(".selected").data("value"));
        });

        $(".option-1").on("click", ".init", function() {
            $(this).closest(".option-1").children('.child-1:not(.init)').toggle();
            if ($(this).closest(".option-1").children('.child-1:not(.init)').is(":hidden")) {
                $(this).closest(".option-1").removeClass("show-option");
            } else {
                $(this).closest(".option-1").addClass("show-option");
            }
        });

        var allOptions2 = $(".option-1").children('.child-1:not(.init)');
        $(".option-1").on("click", ".child-1:not(.init)", function() {
            allOptions2.removeClass('selected');
            $(this).addClass('selected');
            $(".option-1").children('.init').html($(this).html());
            allOptions2.toggle();
            $(this).closest(".option-1").removeClass("show-option");

            generateSize(2, $(".option-1").find(".selected").data("value"));
        });

        $(".option-2").on("click", ".init", function() {
            $(this).closest(".option-2").children('.child-2:not(.init)').toggle();
            if ($(this).closest(".option-2").children('.child-2:not(.init)').is(":hidden")) {
                $(this).closest(".option-2").removeClass("show-option");
            } else {
                $(this).closest(".option-2").addClass("show-option");
            }
        });

        var allOptions3 = $(".option-2").children('.child-2:not(.init)');
        $(".option-2").on("click", ".child-2:not(.init)", function() {
            allOptions3.removeClass('selected');
            $(this).addClass('selected');
            $(".option-2").children('.init').html($(this).html());
            allOptions3.toggle();
            $(this).closest(".option-2").removeClass("show-option");

            generateSize(3, $(".option-2").find(".selected").data("value"));
        });

        var radios = document.getElementsByName('addon');
        for (i = 0; i < radios.length; i++) {
            radios[i].onclick = function(e) {
                if (e.ctrlKey || e.metaKey) {
                    this.checked = false;
                }
            }
        }


    });
</script>
</body>

</html>