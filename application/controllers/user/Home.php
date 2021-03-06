<?php

class Home extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model', 'produk');
        $this->load->model('SubProduk_model', 'subproduk');
        $this->load->model('Pengguna_model', 'user');
        $this->load->model('Banner_model', 'banner');
        $this->load->model('Kategori_model', 'kategori');
        $this->load->model('Cart_model', 'cart');
        $this->load->model('Alamat_model', 'alamat');
        $this->load->model('Detailcart_model', 'cart_item');
        $this->load->model('Transaksi_model', 'transaksi');
        $this->load->library('bcrypt');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $thumbnail = array();
        $data['produk'] = $this->produk->order_by("kode_produk", "ASC");
        $data['produk'] = $this->produk->getJoin("kategori", "kategori.id_kategori=produk.id_kategori", "inner");
        $data['produk'] = $this->produk->getWhere('produk.stok_produk > ', '0');
        $data['produk'] = $this->produk->getData()->result_array();
        $data['kategori'] = $this->kategori->getData()->result_array();
        $data['banner'] = $this->banner->order_by("order", "ASC");
        $data['banner'] = $this->banner->getWhere("active", "1");
        $data['banner'] = $this->banner->getData()->result_array();
        foreach ($data['produk'] as $row) {
            $foto = explode(',', $row['thumbnail_produk']);
            $thumbnail[$row['id_produk']] = $foto[0];
        }
        $data['thumbnail'] = $thumbnail;
        if ($this->userIsLoggedIn()) {
            $data['id_cart'] = $this->cart->getWhere("id_pengguna", $this->session->userdata['user_data']['id']);
            $data['id_cart'] = $this->cart->getData()->row();
        } else {
            $data['id_cart'] = "";
        }
        //   die(json_encode($data));
        $this->load->view('public/home', $data);
    }
    public function search()
    {
        $cari = $this->input->post('search');
        $data['produk'] = $this->produk->search($cari, "produk")->result_array();
        // die(json_encode($data));
        $thumbnail = array();
        if ($data['produk']) {
            foreach ($data['produk'] as $row) {
                $foto = explode(',', $row['thumbnail_produk']);
                $thumbnail[$row['id_produk']] = $foto[0];
            }
        }
        $data['bg'] = base_url('assets/images/Kemeja/Kemeja-BG.png');
        $data['section'] = "Hasil pencarian," . $cari;
        $data['thumbnail'] = $thumbnail;
        $this->load->view('public/product', $data);
    }
    public function produk($kategori = "")
    {

        if ($kategori == "") {
            $thumbnail = array();
            $data['produk'] = $this->produk->order_by("kode_produk", "ASC");
            $data['produk'] = $this->produk->getJoin("kategori", "kategori.id_kategori=produk.id_kategori", "inner");
            $data['produk'] = $this->produk->getWhere("produk.stok >", "0");
            $data['produk'] = $this->produk->getData()->result_array();
            $data['kategori'] = $this->kategori->getData()->result_array();
            foreach ($data['produk'] as $row) {
                $foto = explode(',', $row['thumbnail_produk']);
                $thumbnail[$row['id_produk']] = $foto[0];
            }
            $data['thumbnail'] = $thumbnail;
        } else {
            $datakategori =  $this->kategori->getLike('nama_kategori', $kategori);
            $datakategori = $this->kategori->getData()->row();
            //   die(json_encode($kategori));
            if ($datakategori == "" || empty($datakategori) || $datakategori == null) {
                $thumbnail = array();

                $data['produk'] = null;
                $data['thumbnail'] = null;
            } else {
                $thumbnail = array();
                $data['produk'] = $this->produk->order_by("kode_produk", "ASC");
                $data['produk'] = $this->produk->getWhere('produk.id_kategori', $datakategori->id_kategori);
                $data['produk'] = $this->produk->getJoin("kategori", "kategori.id_kategori=produk.id_kategori", "inner");
                $data['produk'] = $this->produk->getWhere("produk.stok_produk >", "0");
                $data['produk'] = $this->produk->getData()->result_array();
                $data['kategori'] = $this->kategori->getData()->result_array();
                foreach ($data['produk'] as $row) {
                    $foto = explode(',', $row['thumbnail_produk']);
                    $thumbnail[$row['id_produk']] = $foto[0];
                }
                $data['thumbnail'] = $thumbnail;
            }
        }
        $data['thumbnail'] = $thumbnail;
        if ($this->userIsLoggedIn()) {
            $data['id_cart'] = $this->cart->getWhere("id_pengguna", $this->session->userdata['user_data']['id']);
            $data['id_cart'] = $this->cart->getData()->row();
        } else {
            $data['id_cart'] = "";
        }
        $data['section'] = $kategori;
        if ($kategori == "celana") {
            $data['bg'] = base_url('assets/images/Celana/Celana-BG.png');
        } else if ($kategori == "kemeja") {
            $data['bg'] = base_url('assets/images/Kemeja/Kemeja-BG.png');
        } else if ($kategori == "jas") {
            $data['bg'] = base_url('assets/images/Jas/Jas-BG.png');
        } else {
            $data['bg'] = base_url('assets/images/Celana/Celana-BG.png');
        }
        $this->load->view('public/product', $data);
    }
    public function promo()
    {
        $this->load->view('public/product-promo');
    }
    public function produk_detail()
    {
        //die(json_encode($this->session->userdata("c72e6711-4ea1-11ea-9a04-e03f4931b17e")));
        $id_produk = $this->input->get('produk');

        $thumbnail = array();
        $ukuran = array();
        $data['produk'] = $this->produk->getWhere("id_produk", $id_produk);
        $data['produk'] = $this->produk->getJoin("kategori", "kategori.id_kategori=produk.id_kategori", "inner");
        $data['produk'] = $this->produk->getData()->row();
        $data['subProduk'] = $this->subproduk->getByIdProduk($id_produk);
        $data['kategori'] = $this->kategori->getData()->result_array();

        $foto = explode(',', $data['produk']->thumbnail_produk);
        $size = explode(',', $data['produk']->size_produk);
        foreach ($foto as $f) {
            $thumbnail[] = $f;
        }
        foreach ($size as $u) {
            $ukuran[] = $u;
        }

        $data['thumbnail'] = $thumbnail;
        $data['size'] = $ukuran;
        if ($this->userIsLoggedIn()) {
            $data['id_cart'] = $this->cart->getWhere("id_pengguna", $this->session->userdata['user_data']['id']);
            $data['id_cart'] = $this->cart->getData()->row();
        } else {
            $data['id_cart'] = "";
        }
        //    / die(json_encode($data));
        $this->load->view('public/product-detail', $data);
    }
    public function hapus_item()
    {
        $id_item = $this->input->post('id_item');
        $hapus = $this->cart_item->delete("id_detail_item_cart", $id_item);
        if ($hapus) {
            echo json_encode(array(
                "status" => "success",
                "success" => true,
                "id_item" => $id_item,
                "message" => "Berhasil",
                "element" => '',
            ));
        } else {
            echo json_encode(array(
                "status" => "unsuccess",
                "success" => false,
                "id_item" => $id_item,
                "message" => "gagal",
                "element" => '',
            ));
        }
    }
    public function login()
    {
        if ($this->userIsLoggedIn()) {
            redirect(base_url());
        } else {
            if ($this->input->post('kirim')) {
                $email = $this->input->post('email');
                $pass = $this->input->post('password');
                $where = array(
                    'email' => $email
                );
                $cek = $this->user->login($where)->row();
                if ($cek != null) {
                    if ($cek->status == true) {
                        //   die(json_encode($this->bcrypt->hash_password($pass)));
                        //    die(json_encode(array("datacek"=>$cek,"datapass"=>$this->bcrypt->hash_password($pass))));
                        // die(json_encode($this->bcrypt->check_password($pass, $cek->password)));
                        if ($this->bcrypt->check_password($pass, $cek->password)) {
                            //  if ($cek->password == $pass) {
                            $datas = array(
                                "updated_at" => date("Y-m-d H:i:s")
                            );
                            $this->user->updateData($datas, $cek->id_pengguna);
                            $user = array(
                                "id" => $cek->id_pengguna,
                                "username" => $cek->username,
                                "email" => $cek->email,
                                "status" => $cek->status,
                                "login" => true,
                            );
                            $this->session->set_userdata('user_data', $user);
                            redirect(base_url());
                        } else {
                            $this->session->set_flashdata(
                                'pesan',
                                '<div class="alert alert-danger mr-auto">Password atau Username salah</div>'
                            );
                            $this->load->view('public/login');
                        }
                    } else {
                        $this->session->set_flashdata(
                            'pesan',
                            '<div class="alert alert-danger mr-auto">Akun belum diverifikasi silahkan cek email untuk verfikasi akun.</div>'
                        );
                        $this->load->view('public/login');
                    }
                } else {
                    $this->session->set_flashdata(
                        'pesan',
                        '<div class="alert alert-danger mr-auto">Akun tidak ditemukan</div>'
                    );
                    $this->load->view('public/login');
                }
            } else {
                $this->load->view('public/login');
            }
        }
    }
    public function get_cart()
    {
        if (!$this->userIsLoggedIn()) {
            echo json_encode(array(
                "status" => "unsuccess",
                "success" => false,
                "id_cart" => "",
                "message" => "Kamu belum masuk",
                "element" => '',
            ));
        } else {

            $datafull = array();
            $thumbnail = array();
            $harga = 0;
            $datacart = $this->cart_item->order_by("id_detail_item_cart", "ASC");
            $datacart = $this->cart_item->getJoin("cart_item", "cart_item.id_cart=detail_cart_item.id_cart", "inner");
            $datacart = $this->cart_item->getJoin("produk", "produk.id_produk=detail_cart_item.id_produk", "inner");
            $datacart = $this->cart_item->getJoin("sub_produk", "sub_produk.id_sub_produk = detail_cart_item.id_sub_produk", "left");
            $datacart = $this->cart_item->getJoin("kategori", "kategori.id_kategori=produk.id_kategori", "inner");
            $datacart = $this->cart_item->getWhere("cart_item.id_cart", $this->input->get('id'));
            $datacart = $this->cart_item->getData()->result();
            foreach ($datacart as $d) {
                $foto = explode(',', $d->thumbnail_produk);
                foreach ($foto as $f) {
                    $thumbnail[] = $f;
                }
                if ($d->id_sub_produk != null) {
                    $realHarga = $d->harga_sub;
                } else {
                    if ($d->diskon_produk != 0) {
                        $realHarga = $d->harga_produk - (($d->diskon_produk / 100) * $d->harga_produk);
                    } else {
                        $realHarga = $d->harga_produk;
                    }
                }

                $harga = $harga + $realHarga;

                if ($d->id_sub_produk == null) {
                    $dsize = $d->size;
                    $dHarga = $d->diskon_produk != 0 ? "<p style='text-decoration:line-through;font-size:10px'> Rp $d->harga_produk </p>" : "";
                }else{
                    $dHarga = "";
                    $dsize = $d->size;
                }

                if ($d->id_sub_produk != null) {
                    $nama_produk = $d->nama_sub;
                } else {
                    $nama_produk = $d->nama_produk;
                }

                if($d->id_sub_produk != null){
                    $d = array(
                        "id_cart" => $d->id_cart,
                        "id_item" => $d->id_detail_item_cart,
                        "nama_produk" => $nama_produk,
                        "berat_produk" => $d->berat_produk,
                        "qty" => $d->quantity,
                        "harga" => $realHarga,
                        "kategori" => $d->nama_kategori,
                        "thumb" => $thumbnail,
                        "element" => '<div class="cart-list" >
                        <a href="#">
                            <img src="' . base_url() . 'assets/images/add_on.png">
                            <div class="content">
                                <div class="name">' . $nama_produk . '</div>
                                <div class="real">' .
                            $dHarga
                            . 'Rp ' . number_format($realHarga, 2) . '</div>
                                    <div class="content-detail">
                                        Jumlah : <strong class="cart-quantity">' . $d->quantity . '/ Ukuran :' . $dsize . '</strong> 
                                    
                                    </div>
                            </div>
                        </a>
                        <a class="delete-cart" onclick="deleteitem(' . "'" . $d->id_detail_item_cart . "'" . ');" >
                            <i class="svg_icon__header_garbage svg-icon"></i>
                        </a>
                    </div>'
                    );
                }else{
                    $d = array(
                        "id_cart" => $d->id_cart,
                        "id_item" => $d->id_detail_item_cart,
                        "nama_produk" => $nama_produk,
                        "berat_produk" => $d->berat_produk,
                        "qty" => $d->quantity,
                        "harga" => $realHarga,
                        "kategori" => $d->nama_kategori,
                        "thumb" => $thumbnail,
                        "element" => '<div class="cart-list" >
                        <a href="#">
                            <img src="' . base_url() . 'assets/uploads/thumbnail_produk/' . $thumbnail[0] . '">
                            <div class="content">
                                <div class="name">' . $nama_produk . '</div>
                                <div class="real">' .
                            $dHarga
                            . 'Rp ' . number_format($realHarga, 2) . '</div>
                                    <div class="content-detail">
                                        Jumlah : <strong class="cart-quantity">' . $d->quantity . '/ Ukuran :' . $dsize . '</strong> 
                                    
                                    </div>
                            </div>
                        </a>
                        <a class="delete-cart" onclick="deleteitem(' . "'" . $d->id_detail_item_cart . "'" . ');" >
                            <i class="svg_icon__header_garbage svg-icon"></i>
                        </a>
                    </div>'
                    );
                }

                
                array_push($datafull, $d);
                $thumbnail = [];
            }

            echo json_encode(array("data" => $datafull, "total" => $harga));
        }
    }
    public function update_cart()
    {
        if (!$this->userIsLoggedIn()) {
            echo json_encode(array(
                "status" => "unsuccess",
                "success" => false,
                "id_cart" => "",
                "message" => "Kamu belum masuk",
                "element" => '',
            ));
        } else {
        }
    }
    public function add_cart()
    {
        if (!$this->userIsLoggedIn()) {
            echo json_encode(array(
                "status" => "unsuccess",
                "success" => false,
                "id_cart" => "",
                "message" => "Kamu belum masuk",
                "element" => '',
            ));
        } else {
            $idc = $this->input->post('id_cart');
            //$this->input->post('id_cart');
            $idp = $this->input->post('id_pengguna');
            $id_produk = $this->input->post('id_produk');
            $qty = $this->input->post('qty');
            $size = $this->input->post('size');
            $harga = $this->input->post('harga');
            $nama_barang = $this->input->post('nama_barang');
            $img = $this->input->post('img');
            $diskon = (!empty($this->input->post('diskon')) ? $this->input->post('diskon') : 0);
            if ($idc == "" || empty($idc) || $idc == null) {
                $idc = $this->cart->generateKode();
                $data = array(
                    "id_cart" => $idc,
                    "id_pengguna" => $idp,
                    "created_at" => date("Y-m-d H:i:s"),
                );
                $data_item = array(
                    "id_cart" => $idc,
                    "id_produk" => $id_produk,
                    "quantity" => $qty,
                    "size" => $size
                );
                $cek = $this->cart->cekCart();
                if ($cek != null) {
                    $simpan = true;
                    $data_item['id_cart'] = $cek->id_cart;
                } else {
                    $simpan = $this->cart->insert($data);
                }

                if ($simpan) {
                    $simpan_item = $this->cart_item->tambahDetailCart($data_item);
                    if ($diskon != 0) {
                        $realHarga = $harga - (($diskon / 100) * $harga);
                    } else {
                        $realHarga = $harga;
                    }

                    // $harga = $harga + $realHarga;

                    $dHarga = $diskon != 0 ? "<p style='text-decoration:line-through;font-size:10px'> Rp $harga </p>" : "";

                    if ($simpan_item) {
                        $session_cart = array(
                            "current_cart" => $idc,
                            "created_at" => date("Y-m-d H:i:s")
                        );
                        $this->session->set_userdata($idp, $session_cart);
                        echo json_encode(array(
                            "status" => "success",
                            "success" => true,
                            "id_cart" => $idc,
                            "element" => '<div class="cart-list" id="cart_list_39223">
                    <a href="#">
                        <img src="'.$img.'">
                        <div class="content">
                            <div class="name">' . $nama_barang . '</div>
                            <div class="real">' . $dHarga
                                . 'Rp ' . number_format($realHarga, 2) . '</div>
                                <div class="content-detail">
                                    Jumlah : <strong class="cart-quantity">' . $qty . '/Ukuran : ' . $size . ' </strong> 
                                </div>
                        </div>
                    </a>
                    <a class="delete-cart" data-id="39223" onclick="deleteitem(' . "'" . $idc . "'" . ');" href="#">
                        <i class="svg_icon__header_garbage svg-icon"></i>
                    </a>
                </div>',
                        ));
                    } else {
                        echo json_encode(array(
                            "status" => "unsuccess",
                            "success" => false,
                            "id_cart" => $idc,
                            "message" => "berhasil diinput tapi gagal input item",
                            "element" => '',
                        ));
                    }
                } else {
                    echo json_encode(array(
                        "status" => "unsuccess",
                        "success" => false,
                        "id_cart" => "",
                        "message" => "berhasil diinput tapi gagal input item",
                        "element" => '',
                    ));
                }
            } else {

                $idp = $this->input->post('id_pengguna');
                $id_produk = $this->input->post('id_produk');
                $id_sub_produk = $this->input->post('id_sub_produk');
                $qty = $this->input->post('qty');
                $img = $this->input->post('img');
                $size = $this->input->post('size');
                $harga = $this->input->post('harga');
                $diskon = (!empty($this->input->post('diskon')) ? $this->input->post('diskon') : 0);
                $nama_barang = $this->input->post('nama_barang');
                $data_item = array(
                    "id_cart" => $idc,
                    "id_produk" => $id_produk,
                    "id_sub_produk" => $id_sub_produk,
                    "quantity" => $qty,
                    "size" => $size
                );

                $simpan_item = $this->cart_item->tambahDetailCart($data_item);

                if ($diskon != 0) {
                    $realHarga = $harga - (($diskon / 100) * $harga);
                } else {
                    $realHarga = $harga;
                }

                // $harga = $harga + $realHarga;

                $dHarga = $diskon != 0 ? "<p style='text-decoration:line-through;font-size:10px'> Rp $harga </p>" : "";
                if ($simpan_item) {
                    $session_cart = array(
                        "current_cart" => $idc,
                        "created_at" => date("Y-m-d H:i:s")
                    );
                    $this->session->set_userdata($idp, $session_cart);
                    echo json_encode(array(
                        "status" => "success",
                        "success" => true,
                        "id_cart" => $idc,
                        "element" => '<div class="cart-list" ">
    			<a href="#">
        			<img src="' .$img. '">
        			<div class="content">
            			<div class="name">' . $nama_barang . '</div>
                    
                        <div class="real">' . $dHarga
                            . 'Rp ' . number_format($realHarga, 2) . '</div>
                            <div class="content-detail">
                    			Jumlah : <strong class="cart-quantity">' . $qty . ' / Ukuran : ' . $size . '</strong> 
                			</div>
        			</div>
    			</a>
    			<a class="delete-cart" data-id="39223" href="#">
        			<i class="svg_icon__header_garbage svg-icon"></i>
    			</a>
			</div>',
                    ));
                } else {
                    echo json_encode(array(
                        "status" => "unsuccess",
                        "success" => false,
                        "id_cart" => "",
                        "message" => "berhasil diinput tapi gagall input item",
                        "element" => '',
                    ));
                }
            }
        }
    }
    public function register()
    {
        if ($this->userIsLoggedIn()) {
            redirect(base_url());
        } else {
            if ($this->input->post('kirim')) {
                $email = $this->input->post('email');
                $pass = $this->input->post('password');
                $uname = $this->input->post('username');

                $cek = $this->user->getWhere('email', $email);
                $cek = $this->user->getData()->row();
                //  die(json_encode($cek));
                if ($cek != null) {
                    $this->session->set_flashdata("pesan", "Email yang anda masukkan sudah terdaftar ");
                    //sudah ada
                    $this->load->view('public/register');
                    // die(json_encode("ada"));
                } else {
                    $data = array(
                        "email" => $email,
                        "username" => $uname,
                        "password" => $this->bcrypt->hash_password($pass),
                        "status" => 1,
                        "token" => base64_encode($email),
                        "created_at" => date("Y-m-d H:i:s"),
                    );
                    $register = $this->user->set_data('id_pengguna', 'UUID()');
                    $register = $this->user->insert($data);
                    // die(json_encode($register));
                    if ($register) {
                        // if($this->send_verification($email,base64_encode($email))){
                        $this->session->set_flashdata("pesan", "Anda berhasil registrasi, silahkan login untuk melanjutkan.");
                        // }else{
                        // $this->session->set_flashdata("pesan","ada masalah ");
                        // }
                        $this->load->view('public/login');
                    } else {
                        $this->load->view('public/login');
                    }
                }
            } else if ($this->input->post('email')) {
                $email = $this->input->post('email');
                $cek = $this->user->getWhere('email', $email);
                $cek = $this->user->getData()->row();
                $data['email'] = "";
                if (empty($email)) {
                    $data['email'] = "";
                } else {
                    $data['email'] = $email;
                }

                if ($cek != null) {
                    $this->session->set_flashdata("pesan", "Email yang anda masukkan sudah terdaftar ");
                    //sudah ada
                    $this->load->view('public/login');
                    // die(json_encode("ada"));
                } else {
                    $this->load->view('public/register', $data);
                }
                // die(json_encode($data));
            } else {
                $data['email'] = "";
                $this->load->view('public/login', $data);
            }
        }
    }

    public function verifikasi()
    {
        $code = $this->input->get('code');
        // die(json_encode(base64_decode($code)));
        $cek = $this->user->getWhere('email', base64_decode($code));
        $cek = $this->user->getData('user')->row();
        if ($cek != null) {
            $data = array(
                "status" => true
            );
            if ($this->user->updateData($data, $cek->id_pengguna)) {
                echo "Selamat akun anda sudah aktif!. Silahkan klik <a href='" . base_url('login') . "'>login</a>";
            } else {
                echo "ada masalah";
            }
        } else {
            echo "verifikasi kode ilegal.";
        }
    }

    public function profil()
    {
        if ($this->userIsLoggedIn()) {
            $idp = $this->session->userdata['user_data']['id'];
            $data['profil'] = $this->user->getWhere("id_pengguna", $idp);
            $data['profil'] = $this->user->getData()->row();
            $data['kategori'] = $this->kategori->getData()->result_array();

            // $data['transaksi'] = $this->transaksi->select('*');
            $data['transaksi'] = $this->transaksi->distinctWithNoCol();
            $data['transaksi'] = $this->transaksi->order_by("transaksi.waktu_transaksi", "DESC");
            $data['transaksi'] = $this->transaksi->getJoin("pengguna", "pengguna.id_pengguna=transaksi.id_pengguna", "inner");
            $data['transaksi'] = $this->transaksi->getJoin("alamat_pengguna", "alamat_pengguna.id_alamat=transaksi.id_alamat", "left");
            // $data['transaksi'] = $this->transaksi->getJoin("detail_transaksi", "detail_transaksi.id_transaksi=transaksi.id_transaksi", "left");
            // $data['transaksi'] = $this->transaksi->getJoin("produk", "produk.id_produk=detail_transaksi.id_produk", "inner");
            $data['transaksi'] = $this->transaksi->getWhere("transaksi.id_pengguna", $idp);
            $data['transaksi'] = $this->transaksi->limit(10);
            // $data['transaksi'] = $this->transaksi->getWhere("transaksi.bukti_transfer =","");
            $data['transaksi'] = $this->transaksi->getData()->result_array();

            $data['alamat'] = $this->alamat->getWhere("id_pengguna", $idp);
            $data['alamat'] = $this->alamat->getData()->result();
            //    die(json_encode($data['transaksi']));
            $data['id_cart'] = $this->cart->getWhere("id_pengguna", $this->session->userdata['user_data']['id']);
            $data['id_cart'] = $this->cart->getData()->row();
            $this->load->view('public/profil', $data);
        } else {
            redirect(base_url('login'));
        }
    }

    public function ubah_profile()
    {
        if ($this->userIsLoggedIn()) {
            $idp = $this->session->userdata['user_data']['id'];
            $data['profil'] = $this->user->getJoin("alamat_pengguna", "alamat_pengguna.id_pengguna=pengguna.id_pengguna", "left");
            $data['profil'] = $this->user->getWhere("pengguna.id_pengguna", $idp);
            $data['profil'] = $this->user->getData()->row();
            $data['kategori'] = $this->kategori->getData()->result_array();
            // $data['alamat'] = $this->alamat->getWhere("id_pengguna", "a");
            // $data['alamat'] = $this->alamat->getData()->row();
            $provinsi = json_decode($this->get_provinsi());
            $data['id_cart'] = $this->cart->getWhere("id_pengguna", $this->session->userdata['user_data']['id']);
            $data['id_cart'] = $this->cart->getData()->row();
            $data['list_provinsi'] = $provinsi->rajaongkir->results;
            if ($this->input->post('kirim')) {
                $nama_lengkap = $this->input->post('nama_lengkap');
                $no_telp = $this->input->post('no_telp');
                $alamat_1 = $this->input->post('alamat_1');
                $alamat_2 = $this->input->post('alamat_2');
                $provinsi_id = explode(",", $this->input->post('provinsi_id'));
                $kecamatan_id = explode(",", $this->input->post('kecamatan_id'));
                $kabupaten_id = explode(",", $this->input->post('kabupaten_id'));
                $idA = $this->input->post('id_alamat');
                $kode_pos = $this->input->post('kode_pos');
                $data_alamat = array(
                    "nama_lengkap" => $nama_lengkap,
                    "no_telp" => $no_telp,
                    "alamat_1" => $alamat_1,
                    "alamat_2" => $alamat_2,
                    "provinsi_id" => $provinsi_id[0],
                    "provinsi" => $provinsi_id[1],
                    "kecamatan_id" => $kecamatan_id[0],
                    "kecamatan" => $kecamatan_id[1],
                    "kabupaten_id" => $kabupaten_id[0],
                    "kabupaten" => $kabupaten_id[1],
                    "kode_pos" => $kode_pos,
                    "id_pengguna" => $idp
                );

                if ($idA == "") {
                    $query = $this->alamat->insertData($data_alamat);
                } else {
                    $query = $this->alamat->updateData($data_alamat, $idA);
                }
                // die(json_encode($update_alamat));
                if ($query) {
                    $this->session->set_flashdata("pesan", "Data berhasil diperbarui ");
                    redirect(base_url('ubah_profile'));
                } else {
                    $this->session->set_flashdata("pesan", "Data gagal diperbarui ");
                    redirect(base_url('ubah_profile'));
                }
            } else {
                // die(json_encode($data));
                $this->load->view('public/ubah_profile', $data);
            }
            // die(json_encode($data));
        } else {
            redirect(base_url('login'));
        }
    }
    public function panduan_ukuran()
    {
        //
        $this->load->view('public/panduan_ukuran');
    }
    public function panduan_return()
    {
        //
        $this->load->view('public/panduan_return');
    }
    public function panduan_pemesanan()
    {
        //
        $this->load->view('public/panduan_pemesanan');
    }
    public function logout()
    {
        if ($this->userIsLoggedIn()) {
            $this->session->unset_userdata('user_data');
            redirect(base_url('login'), 'refresh');
            //redirect('/user_view/user_login', 'refresh');
            exit();
        } else {
            redirect(base_url('login'));
        }
        // die(json_encode($this->session->userdata('user_data')));
    }

    private function send_verification($email, $code)
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => '465',
            'smtp_user' => 'mailgodds@gmail.com',
            'smtp_pass' => 'Exztoo.123!', // informasi rahasia ini jangan di gunakan sembarangan
            'smtp_crypto' => 'ssl',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );

        $message =     "
                  <html>
                  <head>
                      <title>Verifikasi Akun anda</title>
                  </head>
                  <body>
                      <h2>Terima kasih sudah Mendaftar.</h2>
                      <p>Akun anda:</p>
                      <p>Email: " . $email . "</p>
                      <p>Silahkan klik link berikut untuk memverifikasi akun anda.</p>
                      <h4><a href='" . base_url() . "verifikasi?code=" . $code . "'>Verifikasi Akun Saya</a></h4>
                  </body>
                  </html>
                  ";

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($config['smtp_user']);
        $this->email->to($email);
        $this->email->subject('Verifikasi akun');
        $this->email->message($message);

        return $this->email->send();
    }

    private function send_forgetPass($email, $code)
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => '465',
            'smtp_user' => 'mailgodds@gmail.com',
            'smtp_pass' => 'Exztoo.123!', // informasi rahasia ini jangan di gunakan sembarangan
            'smtp_crypto' => 'ssl',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );

        $message =     "
                  <html>
                  <head>
                      <title>Reset Password Akun</title>
                  </head>
                  <body>
                      <p>Hallo Godds!</p>
                      <p>Ada permintaan untuk Lupa Password, dengan akun :</p>
                      <p>Email: " . $email . "</p>
                      <p>Silahkan klik link berikut untuk mereset password akun anda.</p>
                      <a href='" . base_url() . "user/home/lupa_password?code=" . $code . "'>Reset Password Akun Saya</a>
                      <br></br>
                      <br></br>
                      <br></br>
                      <br></br>
                      <br></br>
                      <p>Best Regards,</p>
                      <p>Godds.id</p>
                  </body>
                  </html>
                  ";

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($config['smtp_user']);
        $this->email->to($email);
        $this->email->subject('Reset Password Akun');
        $this->email->message($message);

        return $this->email->send();
    }

    public function konfirmasi()
    {
        if (!$this->userIsLoggedIn()) {
            redirect(base_url());
        } else {
            $id = $this->input->post('idtransaksi');

            $data['data'] = $this->transaksi->getWhere("id_transaksi", $id);
            $data['data'] = $this->transaksi->getData()->row();
            $data['kategori'] = $this->kategori->getData()->result_array();
            //die(json_encode($data));
            $this->load->view('public/konfirmasi-pembayaran', $data);
        }
    }

    public function konfirmasi_proses()
    {
        if (!$this->userIsLoggedIn()) {
            redirect(base_url());
        } else {

            $id = $this->input->post("idtransaksi");

            $getIdTransaksi = $this->transaksi->getWhere('id_transaksi', $id);
            $getIdTransaksi = $this->transaksi->getData()->row();

            $config['upload_path']          = 'assets/uploads/transaksi';
            $config['allowed_types']        = 'gif|jpg|png|jpeg';
            $config['max_size']             = 1024;
            $config['file_name']            = $getIdTransaksi->kode_transaksi;


            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('bukti')) {
                // $this->session->set_flashdata(
                //     'pesan',
                //     '<div class="alert alert-danger mr-auto">Password atau Username salah</div>'
                // );
                echo $this->upload->display_errors();
                // $error = array('error' => $this->upload->display_errors());

            } else {
                $data = $this->upload->data("file_name");

                $data = array(
                    "bukti_transfer" => $data
                );
                $update = $this->transaksi->updateData($data, $id);
                // $update = $this->transaksi->getWhere("id_transaksi", $id);
                if ($update) {
                    $this->session->set_flashdata(
                        'pesan',
                        'Selamat bukti transaksi anda sudah terkirim, silahkan menunggu untuk validasi oleh admin 1*24 Jam'
                    );
                    redirect("profil");
                } else {
                    $this->session->set_flashdata(
                        'pesan',
                        'ada masalah di server silahkan coba beberapa saat lagi.'
                    );
                    redirect("profil");
                }
            }
        }
    }


    public function lupa_password()
    {
        if ($this->userIsLoggedIn()) {
            redirect('user/home');
        } else {
            $code = $this->input->get('code');
            if ($code) {
                if ($this->input->post('kirim')) {
                    $nPass = $this->input->post('newPassword');
                    $cPass = $this->input->post('confPassword');
                    if ($nPass == $cPass) {
                        $data = $this->user->getDataByEmail(base64_decode($code));
                        if ($data) {

                            $user = array(
                                "password" => $this->bcrypt->hash_password($nPass),
                                "updated_at" => date("Y-m-d H:i:s")
                            );
                            // die(json_encode($this->bcrypt->check_password($nPass,$data->password)));
                            $query = $this->user->updateData($user, $data->id_pengguna);
                            // die(json_encode($query));
                            if ($query) {
                                $this->session->set_flashdata("pesan", "Password berhasil diperbarui, Silahkan coba login.");
                                redirect('login');
                            } else {
                                $this->session->set_flashdata("pesan", "Ada masalah coba lagi nanti!.");
                                $this->load->view('public/form-lupa-password');
                            }
                        } else {
                            $this->session->set_flashdata("pesan", "Kode reset password tidak valid!.");
                            $this->load->view('public/form-lupa-password');
                        }
                    } else {
                        $this->session->set_flashdata("pesan", "Password yang anda masukkan tidak sama!.");
                        $this->load->view('public/form-lupa-password');
                    }
                } else {
                    $data['code'] = $code;
                    $this->load->view('public/form-lupa-password', $data);
                }
            } else {
                if ($this->input->post('kirim')) {
                    $email = $this->input->post('email');
                    $data = $this->user->getDataByEmail($email);
                    if ($data != null) {
                        // var_dump($this->send_forgetPass($email, $data->token));
                        // die();
                        if ($this->send_forgetPass($email, $data->token)) {
                            $this->session->set_flashdata("pesan", "Silahkan cek email anda untuk reset password.");
                            $this->load->view('public/lupa-password');
                        } else {
                            $this->session->set_flashdata("pesan", $this->email->print_debugger(array('headers')));
                            $this->load->view('public/lupa-password');
                        }
                    } else {
                        $this->session->set_flashdata("pesan", "Email yang anda masukkan tidak terdaftar ");
                        //sudah ada
                        $this->load->view('public/lupa-password');
                    }
                } else {
                    $this->load->view('public/lupa-password');
                }
            }
        }
    }
}
