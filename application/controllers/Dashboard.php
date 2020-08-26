<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('data_login'))) {
			$this->session->set_flashdata('flash-error', 'Anda Belum Login');
			redirect('Auth','refresh');
		}

		$this->load->model('M_Rekap', 'rekap');
		$this->load->model('M_Data', 'data');
		$this->load->model('M_Pemakaian', 'pemakaian');
	}

	public function index()
	{
		$data['title'] = 'Dashboard';
		$data['page'] = 'backend/dashboard';

		$this->load->view('backend/index', $data);
	}

	public function dashboard_realtime()
	{
		$data = [
			"suhu" => $this->rekap->getLast(),
			"total" => count($this->pemakaian->getAll()),
			"hari_ini" => count($this->pemakaian->getHariIni())
		];

		echo json_encode($data);
	}

	public function profile()
	{
		$data['title'] = 'Profile';
		$data['page'] = 'backend/profile';
		$this->load->view('backend/index', $data);	
	}

	public function editProfile()
	{
		if ($this->input->post('password', true))
		{
			$data = [
	            "nama" => $this->input->post('nama', true),
	            "password" => password_hash($this->input->post('password', true), PASSWORD_DEFAULT)
	        ];
		}
		else
		{
			$data = [
	            "nama" => $this->input->post('nama', true)
	        ];
		}
		
        $this->db->where('id', $this->input->post('id', true));
        $this->db->update('tbuser', $data);

		$this->session->set_flashdata('flash-sukses', 'Profile berhasil diedit');
		redirect('Dashboard/profile');
	}

	function get_realtime(){
		$data_tabel = $this->rekap->getGrafik();
		echo json_encode($data_tabel);
	}

	public function grafik()
	{
		$data['title'] = 'Rekap Suhu';
		$data['page'] = 'backend/grafik';
		$this->load->view('backend/index', $data);
	}

	public function rekap()
	{
		$data['title'] = 'Rekap Suhu';
		$data['page'] = 'backend/rekap';
		$data['suhu'] = $this->rekap->getAll();
		$this->load->view('backend/index', $data);
	}

	public function pemakaian()
	{
		$data['title'] = 'Rekap Pemakaian';
		$data['page'] = 'backend/pemakaian';
		$data['pemakaian'] = $this->pemakaian->getAll();
		$this->load->view('backend/index', $data);
	}

	public function hapusRekap($id)
	{
		$this->rekap->hapusRekap($id);
		$this->session->set_flashdata('flash-sukses', 'data berhasil dihapus');
		redirect('Dashboard/rekap');
	}

	public function hapusPemakaian($id)
	{
		$this->pemakaian->hapus($id);
		$this->session->set_flashdata('flash-sukses', 'data berhasil dihapus');
		redirect('Dashboard/pemakaian');
	}

	public function kontrol()
	{
		$data['title'] = 'Kontrol Alat';
		$data['page'] = 'backend/kontrol';
		$this->load->view('backend/index', $data);
	}

	public function kontrolAlat()
	{
		$delay = $this->input->post('delay');

		$this->pemakaian->kontrol($delay);
		
		$this->session->set_flashdata('flash-sukses', 'Sukses, alat sedang berjalan');
		redirect('Dashboard/kontrol', 'refresh');
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */