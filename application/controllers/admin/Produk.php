<?php


class Produk extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model', 'produk');
        $this->load->model('Kategori_model', 'kategori');
        $this->load->model('SubProduk_model', 'subproduk');
        $this->load->library('upload');
    }

    public function index()
    {
        if ($this->adminIsLoggedIn()) {
            $thumbnail = array();
            $data['produk'] = $this->produk->order_by("kode_produk", "ASC");
            $data['produk'] = $this->produk->getData()->result_array();
            foreach ($data['produk'] as $row) {
                $foto = explode(',', $row['thumbnail_produk']);
                // foreach($foto as $rowf){
                //     $thumbnail[$row['id_produk']][] = $rowf;
                // }
                $thumbnail[$row['id_produk']] = $foto[0];
            }
            $data['thumbnail'] = $thumbnail;
            // die(json_encode($thumbnail));
            $this->load->view('admin/pages/produk/list', $data);
        } else {
            redirect('admin/home/login');
        }
    }

    public function tambah()
    {
        try{
        if ($this->adminIsLoggedIn()) {
            $diskon_percent = 0;
            $data['kat_p'] = $this->kategori->getData()->result_array();
            if ($this->input->post('kirim')) {
                $nama_p = $this->input->post('nama_p');
                $desc_p = $this->input->post('desc_p');
                $stok_p = $this->input->post('stok_p');
                $harga_p = $this->input->post('harga_p');
                $label_p = $this->input->post('label_p');
                $berat_p = $this->input->post('berat_p');
                $diskon_p = $this->input->post('diskon_p');
                $kat_p = $this->input->post('kat_p');
                $size_p = $this->input->post('size_p');
                $link = $this->input->post('link');
                $namaFile = "";
                $label_p = implode(",", $label_p);
                // $thumbnail = $_FILES['file']['name'];
                if ($this->session->userdata('produk_data') != null) {
                    $kode_p = $this->session->userdata['produk_data']['kode'];
                } else {
                    $kode_p = $this->produk->generateKode($nama_p);
                }

                $namaFile = implode(",", $this->session->userdata['produk_data']['thumbnail']);
                // die(json_encode())
                $diskon_percent = 100 * ($harga_p - $diskon_p) / $harga_p;
                $i = 0;
                $data = array(
                    "kode_produk" => $kode_p,
                    "nama_produk" => $nama_p,
                    "deskripsi_produk" => $desc_p,
                    "stok_produk" => $stok_p,
                    "harga_produk" => $harga_p,
                    "id_kategori" => $kat_p,
                    "size_produk" => $size_p,
                    "label_produk" => $label_p,
                    "berat_produk" => $berat_p,
                    "diskon_produk" => $diskon_percent,
                    "thumbnail_produk" => $namaFile,
                    "video_link" => $link,
                    "created_at" => date("Y-m-d H:i:s")
                );
                //die(json_encode($data));
                if ($this->produk->tambah_produk($data)) {


                    $data_sub_insert = [];
                    $dataproduk = $this->produk->getById($kode_p);
                    $id_sub = $this->input->post('id_sub');
                    $nama_sub = $this->input->post('nama_sub');
                    $size_sub = $this->input->post('size_sub');
                    $harga_sub = $this->input->post('harga_sub');
                    $berat_sub = $this->input->post('berat_sub');
                    $diskon_sub = $this->input->post('diskon_sub');
                    $stok_sub = $this->input->post('stok_sub');
                    if (!in_array("",$nama_sub)) {
                        foreach ($nama_sub as $key => $item) {
                            $data_sub_insert[] =
                                [
                                    "produk_id" => $dataproduk->id_produk,
                                    "nama_sub" => $item,
                                    "size_sub" => $size_sub[$key],
                                    "harga_sub" => $harga_sub[$key],
                                    "berat_sub" => $berat_sub[$key],
                                    "diskon_sub" => $diskon_sub[$key],
                                    "active" => true,
                                    "stok_sub" => $stok_sub[$key],
                                    "created_at" => date("Y-m-d H:i:s"),
                                    "updated_at" => date("Y-m-d H:i:s")
                                ];
                        }
                        if (!empty($data_sub_insert)) {
                            $subProduk = $this->subproduk->insert_multiple($data_sub_insert);
                        } else {
                            $subProduk = true;
                        }
                    }else{
                        $subProduk = true;
                    }

                    if ($subProduk) {
                        $this->session->unset_userdata('produk_data');
                        $this->session->set_flashdata(
                            'pesan',
                            '<div class="alert alert-success mr-auto alert-dismissible">Data Berhasil ditambahkan</div>'
                        );
                        redirect('admin/produk');
                    } else {
                        $this->session->set_flashdata(
                            'pesan',
                            '<div class="alert alert-danger mr-auto alert-dismissible">Ada masalah</div>'
                        );
                        redirect('admin/produk');
                    }
                } else {
                    $this->session->set_flashdata(
                        'pesan',
                        '<div class="alert alert-danger mr-auto alert-dismissible">Ada masalah</div>'
                    );
                    redirect('admin/produk');
                }
            } else {
                $this->load->view('admin/pages/produk/form_produk', $data);
            }
        } else {
            redirect('admin/home/login');
        }
        }catch(Exception $e){
            $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-danger mr-auto alert-dismissible">'.$e->getMessage().'</div>'
            );
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function ubah()
    {
        if ($this->adminIsLoggedIn()) {
            $id = $this->input->get('id');
            $diskon_percent = 0;
            // die(json_encode($this->input->post('nama_sub')));
            $data['kat_p'] = $this->kategori->getData()->result_array();
            $data['produk'] = $this->produk->getById($id);
            $data['subProduk'] = $this->subproduk->getByIdProduk($data['produk']->id_produk);
            if ($this->input->post('kirim')) {
                $nama_p = $this->input->post('nama_p');
                $desc_p = $this->input->post('desc_p');
                $stok_p = $this->input->post('stok_p');
                $harga_p = $this->input->post('harga_p');
                $berat_p = $this->input->post('berat_p');
                $diskon_p = $this->input->post('diskon_p');
                $label_p = $this->input->post('label_p');
                $kat_p = $this->input->post('kat_p');
                $size_p = $this->input->post('size_p');
                $link = $this->input->post('link');

                $label_p = implode(",", $label_p);
                $diskon_percent = 100 * ($harga_p - $diskon_p) / $harga_p;

                if ($this->session->userdata('produk_data') != null) {
                    $thumbnail = implode(",", $this->session->userdata['produk_data']['thumbnail']);
                    $datas = array(
                        "nama_produk" => $nama_p,
                        "deskripsi_produk" => $desc_p,
                        "stok_produk" => $stok_p,
                        "harga_produk" => $harga_p,
                        "id_kategori" => $kat_p,
                        "berat_produk" => $berat_p,
                        "label_produk" => $label_p,
                        "diskon_produk" => $diskon_percent,
                        "thumbnail_produk" => $thumbnail,
                        "size_produk" => $size_p,
                        "video_link" => $link,
                        "updated_at" => date("Y-m-d H:i:s")
                    );
                    $this->session->unset_userdata('produk_data');
                } else {
                    $datas = array(
                        "nama_produk" => $nama_p,
                        "deskripsi_produk" => $desc_p,
                        "stok_produk" => $stok_p,
                        "harga_produk" => $harga_p,
                        "id_kategori" => $kat_p,
                        "diskon_produk" => $diskon_percent,
                        "label_produk" => $label_p,
                        "berat_produk" => $berat_p,
                        "size_produk" => $size_p,
                        "video_link" => $link,
                        "updated_at" => date("Y-m-d H:i:s")
                    );
                    // die(json_encode($datas));
                }

                // die(json_encode($datas));
                if ($this->produk->updateData($datas, $id)) {
                    //sub produk
                    $data_sub_insert = [];
                    $data_sub_update = [];
                    $id_sub = $this->input->post('id_sub');
                    $nama_sub = $this->input->post('nama_sub');
                    $size_sub = $this->input->post('size_sub');
                    $harga_sub = $this->input->post('harga_sub');
                    $berat_sub = $this->input->post('berat_sub');
                    $diskon_sub = $this->input->post('diskon_sub');
                    $stok_sub = $this->input->post('stok_sub');
                    // die(json_encode(!empty($nama_sub)));
                    if (!in_array("",$nama_sub)) {
                        foreach ($nama_sub as $key => $item) {
                            if ($id_sub[$key] != null) {
                                $data_sub_update[] =
                                    [
                                        "id_sub_produk" => $id_sub[$key],
                                        "produk_id" => $data['produk']->id_produk,
                                        "nama_sub" => $item,
                                        "size_sub" => $size_sub[$key],
                                        "harga_sub" => $harga_sub[$key],
                                        "berat_sub" => $berat_sub[$key],
                                        "diskon_sub" => $diskon_sub[$key],
                                        "stok_sub" => $stok_sub[$key],
                                        "active" => true,
                                        "created_at" => date("Y-m-d H:i:s"),
                                        "updated_at" => date("Y-m-d H:i:s")
                                    ];
                            } else {
                                $data_sub_insert[] =
                                    [
                                        "produk_id" => $data['produk']->id_produk,
                                        "nama_sub" => $item,
                                        "size_sub" => $size_sub[$key],
                                        "harga_sub" => $harga_sub[$key],
                                        "berat_sub" => $berat_sub[$key],
                                        "diskon_sub" => $diskon_sub[$key],
                                        "stok_sub" => $stok_sub[$key],
                                        "active" => true,
                                        "created_at" => date("Y-m-d H:i:s"),
                                        "updated_at" => date("Y-m-d H:i:s")
                                    ];
                            }
                        }
                    }
                    // die(json_encode($data_sub));
                    if (!empty($data_sub_update)) {
                        $subProduk = $this->subproduk->update_multiple($data_sub_update, "id_sub_produk");
                    }

                    if (!empty($data_sub_insert)) {
                        $subProduk = $this->subproduk->insert_multiple($data_sub_insert);
                    }
                    $subProduk = true;
                    if ($subProduk) {
                        $this->session->set_flashdata(
                            'pesan',
                            '<div class="alert alert-success mr-auto alert-dismissible">Data Berhasil diubah</div>'
                        );
                        redirect('admin/produk');
                    } else {
                        $this->session->set_flashdata(
                            'pesan',
                            '<div class="alert alert-danger mr-auto alert-dismissible">Data Gagal diubah</div>'
                        );
                        redirect('admin/produk');
                    }
                } else {
                    $this->session->set_flashdata(
                        'pesan',
                        '<div class="alert alert-danger mr-auto alert-dismissible">Ada masalah</div>'
                    );
                    redirect('admin/produk');
                }
            } else {
                $this->load->view('admin/pages/produk/form_produk', $data);
            }
        } else {
            redirect('admin/home/login');
        }
    }

    public function stok()
    {
        if ($this->adminIsLoggedIn()) {
            if ($this->input->post('kirim')) {
                $id = $this->input->post('id');
                $stok = $this->input->post('stok');

                $data = array(
                    "stok_produk" => $stok
                );
                // die(json_encode($data));
                if ($this->produk->updateData($data, $id)) {
                    $this->session->set_flashdata(
                        'pesan',
                        '<div class="alert alert-success mr-auto alert-dismissible">Data Berhasil diubah</div>'
                    );
                    redirect('admin/produk');
                } else {
                    $this->session->set_flashdata(
                        'pesan',
                        '<div class="alert alert-danger mr-auto alert-dismissible">Ada masalah</div>'
                    );
                    redirect('admin/produk');
                }
            }
        } else {
            redirect('admin/home/login');
        }
    }

    public function uploadFile()
    {
        if ($this->adminIsLoggedIn()) {
            if ($this->input->post('status') != "remove") {
                $data = [];
                $foto = null;
                $i = 0;
                $index = 0;
                $kode_p = $this->input->post('kode_p');
                if ($kode_p == "") {
                    $kode_p = $this->produk->generateKode($this->input->post('file_name'));
                } else {
                    $data_f = $this->produk->getById($kode_p);
                    $foto = explode(',', $data_f->thumbnail_produk);
                }
                if ($this->session->userdata('produk_data') != null) {
                    // $index = 0;
                    foreach ($this->session->userdata['produk_data']['thumbnail'] as $row) {
                        $index++;
                    }
                } else if ($foto != null) {
                    foreach ($foto as $row) {
                        $index++;
                    }
                }


                foreach ($_FILES['thumbnail']['name'] as $row) {
                    if (!empty($_FILES['thumbnail']['name'][$i])) {
                        $eks = substr(strrchr($_FILES['thumbnail']['name'][$i], '.'), 1);
                        $name = $kode_p . "_" . $index . "." . $eks;
                        $_FILES['file']['name'] = $_FILES['thumbnail']['name'][$i];
                        $_FILES['file']['type'] = $_FILES['thumbnail']['type'][$i];
                        $_FILES['file']['tmp_name'] = $_FILES['thumbnail']['tmp_name'][$i];
                        $_FILES['file']['error'] = $_FILES['thumbnail']['error'][$i];
                        $_FILES['file']['size'] = $_FILES['thumbnail']['size'][$i];
                        $this->uploadFoto($name);
                        if ($this->upload->do_upload("file")) {
                            $data[$i] = $name;
                        } else {
                            $this->session->set_flashdata(
                                'pesan',
                                '<div class="alert alert-danger mr-auto alert-dismissible">Ada masalah error: ' . $this->upload->display_errors() . '</div>'
                            );
                            redirect('admin/produk');
                        }
                    }
                    $i++;
                    $index++;
                }
                if ($this->session->userdata('produk_data') != null) {
                    $data_t = $this->session->userdata['produk_data']['thumbnail'];
                    foreach ($data_t as $row) {
                        array_push($data, $row);
                    }
                    $this->session->set_userdata('produk_data', array(
                        "thumbnail" => $data,
                        "kode" => $kode_p
                    ));
                } else if ($foto != null) {
                    foreach ($foto as $row) {
                        array_push($data, $row);
                    }
                    $this->session->set_userdata('produk_data', array(
                        "thumbnail" => $data,
                        "kode" => $kode_p
                    ));
                } else {
                    $this->session->set_userdata('produk_data', array(
                        "thumbnail" => $data,
                        "kode" => $kode_p
                    ));
                }
            } else {
            }
        } else {
            redirect('admin/home/login');
        }
    }

    public function deleteFile()
    {
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $tp = [];
        $data = $this->produk->getById($id);
        $thumbnail = explode(',', $data->thumbnail_produk);
        foreach ($thumbnail as $row) {
            if ($nama != $row) {
                $tp[] = $row;
            } else {
                unlink("assets/uploads/thumbnail_produk/" . $row);
            }
        }
        $thumbnail = implode(",", $tp);
        $data = array(
            "thumbnail_produk" => $thumbnail
        );
        $query = $this->produk->updateData($data, $id);
        if ($query) {
            echo json_encode(['message' => "success"]);
        } else {
            echo json_encode(['message' => "error"]);
        }
    }

    public function deleteSubProduk()
    {
        $id = $this->input->post('id');
        // $query = $this->subproduk->delete("id_sub_produk", $id);
        $query = $this->subproduk->getWhere("id_sub_produk",$id);
        $query = $this->subproduk->update(["active" => false]);

        if ($query) {
            echo json_encode(['status' => true, 'message' => "success"]);
        } else {
            echo json_encode(['status' => false, 'message' => "error"]);
        }
    }

    public function getThumbnail($id = null)
    {
        if ($this->adminIsLoggedIn()) {
            // if(isset($id))
            $data = $this->produk->getById($id);
            $thumbnail = explode(',', $data->thumbnail_produk);
            echo json_encode($thumbnail);
        } else {
            redirect('admin/home/login');
        }
    }

    public function test()
    {
        // $this->session->unset_userdata('produk_data');
        die(json_encode($this->session->userdata()));
    }

    public function hapus()
    {
        if ($this->adminIsLoggedIn()) {
            $id = $this->input->post('id');
            if ($this->produk->delete("id_produk", $id)) {
                $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-success mr-auto alert-dismissible">Data Berhasil dihapus</div>'
                );
                redirect('admin/produk');
            } else {
                $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-danger mr-auto alert-dismissible">Ada masalah</div>'
                );
                redirect('admin/produk');
            }
        } else {
            redirect('admin/home/login');
        }
    }
}
