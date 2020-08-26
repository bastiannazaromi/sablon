<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {
    
    public function save()
	{
        $this->load->model('M_Data', 'data');

		$tanggal = date('Y-m-d H:i:s');
        $jam_sekarang = date('H');
        $hari_sekarang = date('d');
        $bulan_sekarang = date('m');
        $tahun_sekarang = date('Y');

        $suhu = $this->input->get('suhu');

        // data dari M_Simpan.php
        $rekap = $this->data->ambil_data_terakhir();
        
        if ($rekap)
        {
            $suhu_sebelumnya = $rekap[0]["suhu"];
            
            $awal  = date_create($rekap[0]['waktu']);
            $akhir = date_create(); // waktu sekarang
            $diff  = date_diff( $awal, $akhir );
            
            $hari = $diff->d;
            $jam = $diff->h;

            if ($suhu_sebelumnya == $suhu)
            {
                if ($hari >= 1 || $jam >= 1)
                {
                    // Simoan ke database
                    $this->data->save();
                    $this->kontrol();
                }
                else
                {
                    $this->kontrol();
                }
            }
            else
            {
                // Simpan ke database
                $this->data->save();
                $this->kontrol();
            }
        }
        
    }

    public function kontrol()
    {
        $this->load->model('M_Data', 'data');

        $kontrol = $this->data->ambilKontrol();

        $keterangan = $kontrol[0]['keterangan'];
        $delay = $kontrol[0]['delay'];

        if ($keterangan == "Belum selesai")
        {
            echo "1" . $delay;
        }
        else
        {
            echo "0" . $delay;
        }

    }

    public function updateKontrol()
    {
        $this->load->model('M_Data', 'data');

        $tanggal = date('Y-m-d H:i:s');
        
        $keterangan = $this->input->get('keterangan');

        $this->data->updateKontrol($tanggal, $keterangan);

        echo "Sukses, kontrol pemakaian telah selesai";
    }

    public function addPemakaian()
    {
        $this->load->model('M_Data', 'data');

        $this->data->addPemakaian();

        echo "Sukses, pemakaian berhasil ditambahkan ke database";
    }

}