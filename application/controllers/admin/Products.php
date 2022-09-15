<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Products extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("product_model");
        $this->load->library('form_validation');
        $this->load->model('auth_model');
		if(!$this->auth_model->current_user()){
			redirect('auth/login');
		}
        
    }

    public function index()
    {
        $data['current_user'] = $this->auth_model->current_user();
        $data["products"] = $this->product_model->getAll();
        $this->load->view("admin/product/list", $data);
    }

    public function add()
    {
        $data['current_user'] = $this->auth_model->current_user();
        $product = $this->product_model;
        $validation = $this->form_validation;
        $validation->set_rules($product->rules());

        if ($validation->run()) {
            $product->save();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
        }

        $this->load->view("admin/product/new_form",$data);
    }

    public function edit($id = null)
    {
        $data['current_user'] = $this->auth_model->current_user();
        if (!isset($id)) redirect('admin/products');

        $product = $this->product_model;
        $validation = $this->form_validation;
        $validation->set_rules($product->rules());

        if ($validation->run()) {
            $product->update();
            $this->session->set_flashdata('success', 'Berhasil disimpan');
        }

        $data["product"] = $product->getById($id);
        if (!$data["product"]) show_404();

        $this->load->view("admin/product/edit_form", $data);
    }

    public function delete($id = null)
    {
        
        if (!isset($id)) show_404();

        if ($this->product_model->delete($id)) {
            redirect(site_url('admin/products'));
        }
    }

    public function upload_avatar($id)
    {
        $data['current_user'] = $this->auth_model->current_user();

        if ($this->input->method() === 'post') {
            // the user id contain dot, so we must remove it
            $file_name = str_replace('.', '', $id);
            $config['upload_path']          = FCPATH . '/upload/avatar/';
            $config['allowed_types']        = 'gif|jpg|jpeg|png';
            $config['file_name']            = $file_name;
            $config['overwrite']            = true;
            $config['max_size']             = 1024; // 1MB
            $config['max_width']            = 1080;
            $config['max_height']           = 1080;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('avatar')) {
                $data['error'] = $this->upload->display_errors();
            } else {
                $uploaded_data = $this->upload->data();

                $new_data = [
                    'id' => $id,
                    'image' => $uploaded_data['file_name'],
                ];

                if ($this->product_model->upload_image($new_data)) {
                    $this->session->set_flashdata('message', 'Avatar updated!');
                    redirect(site_url('admin/products'));
                }
            }
        }

        $this->load->view('admin/product/upload_avatar',$data);
    }
    public function remove_avatar()
    {
        $current_user = $this->auth_model->current_user();
        $this->load->model('profile_model');

        // hapus file
        unlink(FCPATH . "/upload/avatar/" . $current_user->avatar);

        // set avatar menjadi null
        $new_data = [
            'id' => $current_user->id,
            'avatar' => null,
        ];

        if ($this->profile_model->update($new_data)) {
            $this->session->set_flashdata('message', 'Avatar dihapus!');
            redirect(site_url('admin/setting'));
        }
    }
}
