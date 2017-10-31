<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alterar_senha extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_Model', 'usuario');
    }
    
    public function form() {
        $this->load->view('alterar_senha/form', $this->session->user_info);
    }
    
    public function save() {
        $datapost = $this->input->post(null, true);
        if (!empty($datapost['senha_antiga']) && !empty($datapost['senha']) &&
            trim($datapost['senha_antiga']) !== '' && trim($datapost['senha']) !== '') {
            $userdata = $this->usuario->get($this->session->user_info['id']);
            if (Bcrypt::check($datapost['senha_antiga'], $userdata['senha'])) {
                $this->usuario->save(array('id' => $this->session->user_info['id'], 'senha' => Bcrypt::hash($datapost['senha'])));
                $this->response('success', 'Senha alterada com sucesso.');
            } else {
                $this->response('error', 'O campo <b>Senha antiga</b> está incorreto.');
            }
        } else {
            $this->response('error', 'Favor informar os campos corretamente.');
        }
    }

}
