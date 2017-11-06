<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Exercicios_aluno extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Banco_Model', 'banco');
        $this->load->model('Turma_Model', 'turma');
        $this->load->model('Usuario_Turma_Model', 'alunos_turma');
        $this->load->model('Usuario_Model', 'aluno');
        $this->load->model('Lista_Model', 'lista');
        $this->load->model('Exercicio_Model', 'exercicio');
        $this->load->model('Resposta_Model', 'resposta');
    }
    
    public function index() {
        $datapost = $this->input->post(null, true);
        if (!empty($datapost)) {
            $user_id = $this->session->user_info['id'];
            $where = "ut.id_usuario = $user_id and t.ativa is true";
            $params = formatParams($datapost);
            if (!empty($params['where'])) {
                $params['where'] = "$where and (" . $params['where'] . ')';
            } else {
                $params['where'] = $where;
            }
            $data = $this->lista->get_listas_by_aluno($params['where'], $params['order_by'], $params['length'], $params['start']);
            $count_all = $this->lista->count_by_aluno($where);
            $count_filtered = $this->lista->count_by_aluno($params['where']);
            $results = formatVars($data);
            echo formatResults($results, $count_all, $count_filtered, $params['draw'], function (&$row) {
                $icon = (empty($row['respondido_em']) ? 'fa-pencil-square-o' : 'fa-check');
                $title = (empty($row['respondido_em']) ? 'Responder Questionário' : 'Responder novamente');
                $row['responder'] = '<a href="#" class="responder" title="' . $title . '"><i class="fa ' . $icon . '"></i></a>';
            });
        } else {
            $this->load->view('exercicios_aluno/list');
        }
    }
    
    public function form() {
        $id_lista = $this->input->post('id_lista', true);
        $user_id = $this->session->user_info['id'];
        $exercicios = $respostas = array();
        if (!empty($id_lista)) {
            $lista = $this->lista->get($id_lista);
            $data_prazo = new DateTime($lista['data_prazo']);
            $data_atual = new DateTime("now");
            $hora_prazo = strtotime($lista['hora_prazo']);
            $hora_atual = time();
            if ($data_atual < $data_prazo || ($data_atual == $data_prazo && ($hora_atual <= $hora_prazo))) {
                $exercicios = $this->exercicio->get_where("lista = $id_lista", 'id asc');
                foreach ($exercicios as $exercicio) {
                    $resposta = $this->resposta->get_first_where("exercicio = $exercicio[id] and aluno = $user_id");
                    if (!empty($resposta)) {
                        $respostas[$exercicio['id']] = array(
                            'resposta' => $resposta['resposta'],
                            'resposta_sql' => $resposta['resposta_sql'],
                            'tentativas' => $resposta['tentativas']
                        );
                    }
                }
                $data = array(
                    'exercicios' => $exercicios,
                    'lista' => $lista['titulo'],
                    'respostas' => $respostas
                );
                $results = formatVars($data, array('descricao'));
                $view = $this->load->view('exercicios_aluno/form', $results, true);
                $this->response('success', array('view' => $view));
            }
        }
        $this->response('error', 'O prazo deste questionário já expirou.');
    }
    
    public function save() {
        $respostas = $this->input->post('resposta', true);
        $user_id = $this->session->user_info['id'];
        $this->form_validation->set_rules($this->resposta->get_rules_from_db());
        $this->db->trans_begin();
        foreach ($respostas as $exercicio => $resposta) {
            $this->resposta->delete_where("exercicio = $exercicio and aluno = $user_id");
            $data = array(
                'exercicio' => $exercicio,
                'aluno' => $user_id,
                'resposta' => $resposta['ra'],
                'resposta_sql' => $resposta['sql'],
                'data' => date('Y-m-d'),
                'hora' => date('H:i'),
                'tentativas' => empty($resposta['tentativas']) ? 1 : $resposta['tentativas']
            );
            $this->form_validation->set_data($data);
            if ($this->form_validation->run()) {
                if (!$this->resposta->save($data)) {
                    $error = $this->db->error();
                    $this->db->trans_rollback();
                    $this->response('error', $error['message']);
                }
            } else {
                $this->db->trans_rollback();
                $this->response('error', validation_errors(' ', '<br>'));
            }
        }
        $this->db->trans_commit();
        $this->response('success', 'Registro salvo com sucesso.');
    }
    
    public function execute() {
        $sql = $this->input->post('sql', false);
        if (strtolower(substr(trim($sql), 0, 6)) === 'select') {
            $query = $this->banco->query($sql);
            if ($query !== false) {
                $view = $this->load->view('generic/results', array('results' => $query), true);
                $this->load->view('exercicios_aluno/query_modal', array('view' => $view));
            } else {
                $error = $this->db->error();
                $message = str_replace('aluno_praticar_exercicios.', '', $error['message']);
                if (trim($message) === '') {
                    $message = 'Ocorreu um erro durante a execução do seu comando SQL.';
                }
                $this->load->view('exercicios_aluno/query_modal', array('view' => '<b>' . $message . '</b>'));
            }
        } else {
            $this->load->view('exercicios_aluno/query_modal', array('view' => '<b>Consulta inválida.</b>'));
        }
    }
    
    public function get_tables() {
        $title = $this->input->post('title', true);
        $tables = $this->banco->get_tables();
        $this->load->view('exercicios_aluno/tables_modal', array('tables' => $tables, 'title' => $title));
    }
    
    public function compare_queries() {
        $datapost = $this->input->post(null, true);
        $id_exercicio = $datapost['exercicio'];
        $sql_aluno = html_entity_decode($datapost['sql']);
        $tentativas = (integer) $datapost['tentativas'];
        
        $exercicio = $this->exercicio->get($id_exercicio);
        $ra_professor = $exercicio['resposta'];
        $sql_professor = $exercicio['resposta_sql'];
        $resultado_professor = $this->banco->query($sql_professor);
        
        $resultado_aluno = $this->banco->query($sql_aluno);
        
        if (is_array($resultado_aluno)) {
            $count_resultado_aluno = count($resultado_aluno);
            $count_resultado_professor = count($resultado_professor);
            if ($count_resultado_aluno == $count_resultado_professor) {
                foreach ($resultado_aluno as $key => $value) {
                    $diferenças_valores = array_diff_assoc($resultado_professor[$key], $value);
                    if (count($diferenças_valores) > 0) {
                        $tentativas++;
                        $this->response('error', array(
                            'msg' => 'Sua consulta está retornando resultados diferentes da resposta dada pelo professor.',
                            'tentativas' => $tentativas
                        ));
                    }
                }
            } else {
                $tentativas++;
                $this->response('error', array(
                    'msg' => "Sua consulta retorna $count_resultado_aluno linhas, a resposta do professor está retornando $count_resultado_professor.",
                    'tentativas' => $tentativas
                ));
            }
        } else {
            $error = $this->db->error();
            $message = str_replace('aluno_praticar_exercicios.', '', $error['message']);
            if (trim($message) === '') {
                $message = 'Ocorreu um erro durante a execução do seu comando SQL.';
            }
            $this->response('error', array(
                'msg' => $message,
                'tentativas' => $tentativas
            ));
        }
        $this->response('success', array('resposta_professor' => $ra_professor));
    }
    
}
