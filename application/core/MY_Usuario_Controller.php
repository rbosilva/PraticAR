<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Usuario_Controller extends MY_Controller {
    
    protected $tipo;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_Model', 'usuario');
    }
    
    public function index() {
        $datapost = $this->input->post(null, true);
        if (!empty($datapost)) {
            $params = formatParams($datapost);
            if (!empty($params['where'])) {
                $params['where'] = "tipo = $this->tipo and (" . $params['where'] . ')';
            } else {
                $params['where'] = "tipo = $this->tipo";
            }
            $data = $this->usuario->get_where($params['where'], $params['order_by'], $params['length'], $params['start']);
            $count_all = $this->usuario->count("tipo = $this->tipo");
            $count_filtered = $this->usuario->count($params['where']);
            $results = formatVars($data);
            echo formatResults($results, $count_all, $count_filtered, $params['draw']);
        } else {
            $this->load->view(strtolower(get_class($this)) . '/list');
        }
    }
    
    public function form($id = null) {
        $dados = array();
        if (!empty($id)) {
            $dados = $this->usuario->get($id);
        }
        $results = formatVars($dados);
        $this->load->view(strtolower(get_class($this)) . '/form', $results);
    }
    
    public function save() {
        $dados = $this->input->post(null, true);
        $dados['tipo'] = $this->tipo;
        if (trim($dados['senha']) !== '') {
            $dados['senha'] = Bcrypt::hash($dados['senha']);
        } else {
            unset($dados['senha']);
        }
        $this->form_validation->set_rules($this->usuario->get_rules());
        if ($this->form_validation->run()) {
            if ($this->usuario->save($dados) !== false) {
                $this->response('success', 'Registro salvo com sucesso.');
            } else {
                $error = $this->db->error();
                $this->response('error', $error['message']);
            }
        } else {
            $this->response('error', validation_errors(' ', '<br>'));
        }
    }
    
    public function delete($id = null) {
        if ($this->usuario->delete($id)) {
            $this->response();
        } else {
            $error = $this->db->error();
            if (stripos($error['message'], 'foreign key')) {
                $this->response('error', 'Este ' . ucfirst(get_class($this)) . ' já foi cadastrado em uma Turma, ' .
                                         'é necessário excluir seu vínculo antes.');
            }
            $this->response('error', $error['message']);
        }
    }

}
