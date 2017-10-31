<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Turma extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Turma_Model', 'turma');
        $this->load->model('Usuario_Model', 'usuario');
        $this->load->model('Usuario_Turma_Model', 'usuario_turma');
    }
    
    public function index() {
        $datapost = $this->input->post(null, true);
        if (!empty($datapost)) {
            $where = "u.tipo = 1";
            $params = formatParams($datapost);
            if (!empty($params['where'])) {
                $params['where'] = "$where and (" . $params['where'] . ')';
            } else {
                $params['where'] = $where;
            }
            $data = $this->turma->get_where($params['where'], $params['order_by'], $params['length'], $params['start']);
            $count_all = $this->turma->count_all();
            $count_filtered = $this->turma->count($params['where']);
            $results = formatVars($data);
            echo formatResults($results, $count_all, $count_filtered, $params['draw']);
        } else {
            $this->load->view('turma/list');
        }
    }
    
    public function form($id = null) {
        $dados = array();
        if (!empty($id)) {
            $dados = $this->turma->get($id);
            $usuarios = $this->usuario_turma->get_where(array('id_turma' => $id));
            foreach ($usuarios as $usuario) {
                if ($usuario['tipo'] == '1') {
                    $dados['id_professor'] = $usuario['usuario'];
                    $dados['professor'] = $usuario['nome'];
                } else {
                    $dados['alunos'][] = array(
                        'id' => $usuario['usuario'],
                        'nome' => $usuario['nome']
                    );
                }
            }
        }
        $results = formatVars($dados);
        $this->load->view('turma/form', $results);
    }
    
    public function save() {
        $dados = $this->input->post(null, true);
        if (is_array($dados['usuarios']['alunos']) && count($dados['usuarios']['alunos']) == 0) {
            $this->db->trans_rollback();
            $this->response('error', 'É necessário selecionar ao menos um Aluno para a turma.');
        }
        if (is_array($dados['usuarios']['professor']) && count($dados['usuarios']['professor']) == 0) {
            $this->db->trans_rollback();
            $this->response('error', 'É necessário selecionar um Professor para a turma.');
        }
        // Separa os dados de cada tabela
        $turma = $dados['turma'];
        $usuarios = $dados['usuarios']['alunos'];
        $usuarios[] = $dados['usuarios']['professor'];
        // Separa as regras de validação
        $turma_rules = $this->turma->get_rules_from_db();
        $turma['descricao'] = trim($turma['descricao']);
        // Faz as validações
        $this->form_validation->set_data($turma);
        $this->form_validation->set_rules($turma_rules);
        if ($this->form_validation->run()) {
            // Inicia as alterações no banco
            $this->db->trans_begin();
            if (($save = $this->turma->save($turma))) {
                $this->usuario_turma->delete_where("id_turma = $save");
                foreach ($usuarios as $usuario) {
                    if ($this->usuario_turma->save(array('id_turma' => $save, 'id_usuario' => $usuario)) === false) {
                        $error = $this->db->error();
                        $this->db->trans_rollback();
                        $this->response('error', $error['message']);
                    }
                }
                $this->db->trans_commit();
                $this->response('success', 'Registro salvo com sucesso.');
            }
            $error = $this->db->error();
            if (stripos($error['message'], 'Duplicate') !== false) {
                $this->response('error', 'Esta descrição já foi cadastrada em outra Turma, ' .
                                         'favor informar outra.');
            }
            $this->db->trans_rollback();
            $this->response('error', $error['message']);
        } else {
            $this->db->trans_rollback();
            $this->response('error', validation_errors(' ', '<br>'));
        }
    }
    
    public function delete($id = null) {
        $this->db->trans_begin();
        if (!$this->usuario_turma->delete_where("id_turma = $id")) {
            $error = $this->db->error();
            $this->db->trans_rollback();
            $this->response('error', $error['message']);
        }
        if ($this->turma->delete($id)) {
            $this->db->trans_commit();
            $this->response();
        } else {
            $error = $this->db->error();
            $this->db->trans_rollback();
            $this->response('error', $error['message']);
        }
    }
    
    public function teachers() {
        $datapost = $this->input->post();
        $where = 'tipo = 1';
        if (!empty($datapost['term'])) {
            $search = filter_var($datapost['term'], FILTER_SANITIZE_STRING);
            $where .= " and nome like '%$search%'";
        }
        $page = 0;
        if (!empty($datapost['page'])) {
            $page = (int) filter_var($datapost['page'], FILTER_SANITIZE_NUMBER_INT);
        }
        $dados = $this->usuario->get_where($where, 'nome asc', 30, 30 * $page);
        $total_count = $this->usuario->count($where);
        $result = array();
        foreach ($dados as $professor) {
            $result[] = array(
                'id' => $professor['id'],
                'text' => $professor['nome']
            );
        }
        echo json_encode(array('dados' => $result, 'total_count' => $total_count));
    }
    
    public function students() {
        $datapost = $this->input->post();
        $where = 'tipo = 2';
        if (!empty($datapost['term'])) {
            $search = filter_var($datapost['term'], FILTER_SANITIZE_STRING);
            $where .= " and nome like '%$search%'";
        }
        $page = 0;
        if (!empty($datapost['page'])) {
            $page = (int) filter_var($datapost['page'], FILTER_SANITIZE_NUMBER_INT);
        }
        $dados = $this->usuario->get_where($where, 'nome asc', 30, 30 * $page);
        $total_count = $this->usuario->count($where);
        $result = array();
        foreach ($dados as $professor) {
            $result[] = array(
                'id' => $professor['id'],
                'text' => $professor['nome']
            );
        }
        $alunos_disponíveis = array(
            'text' => count($result) > 0 ? 'Alunos disponíveis:' : 'Não há alunos disponíveis.',
            'children' => $result
        );
        echo json_encode(array('dados' => array($alunos_disponíveis), 'total_count' => $total_count));
    }
    
}
