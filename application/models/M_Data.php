<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Data extends CI_Model {

	public function save()
	{
        $tanggal = date('Y-m-d H:i:s');
        $suhu = $this->input->get('suhu');

        $data = [
            "waktu" => $tanggal,
            "suhu" => $suhu
        ];

        $this->db->insert('tbrekap', $data);
    }

    public function ambil_data_terakhir()
    {
        $this->db->select('*');
        $this->db->from('tbrekap');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);

        return $this->db->get()->result_array();
    }

    public function ambilKontrol()
    {
        return $this->db->get('tbkontrol')->result_array();
    }

    public function updateKontrol($tanggal, $keterangan)
    {
        $data = [
            "waktu" => $tanggal,
            "keterangan" => $keterangan
        ];

        $this->db->where('id', 1);
        $this->db->update('tbkontrol', $data);
    }

    public function addPemakaian()
    {
        $tanggal = date('Y-m-d H:i:s');
        $delay = $this->input->get('delay');

        $data = [
            "waktu" => $tanggal,
            "delay" => $delay,
            "keterangan" => "Dari alat"
        ];

        $this->db->insert('tbpemakaian', $data);
    }

}