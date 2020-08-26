<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Pemakaian extends CI_Model {

	public function getAll()
	{
        $this->db->select('*');
        $this->db->from('tbpemakaian');
        $this->db->order_by('id', 'desc');

        return $this->db->get()->result_array();
    }

    public function getHariIni()
    {
        $tanggal = date('Y-m-d');

        $this->db->select('*');
        $this->db->from('tbpemakaian');
        $this->db->where('DATE(waktu)', $tanggal);

        return $this->db->get()->result_array();
    }

    public function hapus($id)
    {
        // $this->db->where('id', $id);
        $this->db->delete('tbpemakaian', ['id' => $id]);
    }

    public function kontrol($delay)
    {
        $data = [
            "waktu" => date('Y-m-d H:i:s'),
            "delay" => $delay,
            "keterangan" => "Belum selesai"
        ];

        $this->db->where('id', 1);
        $this->db->update('tbkontrol', $data);

        $data2 = [
            "waktu" => date('Y-m-d H:i:s'),
            "delay" => $delay,
            "keterangan" => "Dari web"
        ];

        $this->db->insert('tbpemakaian', $data2);
    }

}