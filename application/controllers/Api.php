<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Api extends RestController
{

    private $secret = "R4h4514123";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    public function login_post()
    {
        $username = $this->post('username');
        $password = $this->post('password');
        $invalidLogin = ['invalid' => $username];
        if (!$username || !$password) $this->response($invalidLogin, RestController::HTTP_NOT_FOUND);
        $id = $this->Auth_model->is_valid($username, $password);
        if ($id) {

            $exp = time() + 3600;
            $token = array(
                "iat" => time(),
                "nbf" => time() + 10,
                "exp" => $exp,
                "data" => array(
                    "id" => $id,
                    "username" => $username
                )
            );
            $output['access_token'] = JWT::encode($token, $this->secret, 'HS256');
            $this->set_response($output, RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No users were found'
            ], 404);
        }
    }

    public function datana_get()
    {
        if ($this->check_token()) {
            $this->db->select('*');
            $this->db->from('user');
            $query = $this->db->get();
            $this->set_response( $query->result(), RestController::HTTP_OK);
        }
    }

    public function check_token()
    {

        $token = null;
        $authHeader = $this->input->request_headers()['Authorization'];
        $arr = explode(" ", $authHeader);
        $token = $arr[1];
        if ($token) {
            try {
                //decode token with HS256 method
                $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
                if ($decoded) {
                    return true;
                }
            } catch (\Exception $e) {
                $this->response([
                    'status' => false,
                    'message' => 'Invalid Token'
                ], 401);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Invalid Token'
            ], 401);
        }
    }
}
