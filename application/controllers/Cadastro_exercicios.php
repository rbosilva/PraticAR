<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cadastro_exercicios extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Banco_Model', 'banco');
        $this->load->model('Turma_Model', 'turma');
        $this->load->model('Usuario_Model', 'aluno');
        $this->load->model('Lista_Model', 'lista');
        $this->load->model('Exercicio_Model', 'exercicio');
        $this->load->model('Resposta_Model', 'resposta');
    }
    
    public function index() {
        $datapost = $this->input->post(null, true);
        if (!empty($datapost)) {
            $where = 'u.id = ' . $this->session->user_info['id'];
            $params = formatParams($datapost);
            if (!empty($params['where'])) {
                $params['where'] = "$where and (" . $params['where'] . ')';
            } else {
                $params['where'] = $where;
            }
            $data = $this->turma->get_where($params['where'], $params['order_by'], $params['length'], $params['start']);
            $count_all = $this->turma->count($where);
            $count_filtered = $this->turma->count($params['where']);
            $results = formatVars($data);
            echo formatResults($results, $count_all, $count_filtered, $params['draw'], function (&$row) {
                $row['visualizar'] = '<a href="#" title="Visualizar questionários" class="visualizar"><i class="fa fa-list-ol"></i></a>';
            });
        } else {
            $this->load->view('cadastro_exercicios/list');
        }
    }
    
    public function list_lists() {
        $datapost = $this->input->post(null, true);
        if (!empty($datapost['draw'])) {
            $where = "turma = $datapost[turma]";
            $params = formatParams($datapost);
            if (!empty($params['where'])) {
                $params['where'] = "$where and (" . $params['where'] . ')';
            } else {
                $params['where'] = $where;
            }
            $data = $this->lista->get_where($params['where'], $params['order_by'], $params['length'], $params['start']);
            $count_all = $this->lista->count($params['where']);
            $count_filtered = $this->lista->count($params['where']);
            $results = formatVars($data);
            echo formatResults($results, $count_all, $count_filtered, $params['draw'], function (&$row) {
                $row['entregas'] = '<a href="#" title="Visualizar entregas" class="entregas"><i class="fa fa-eye"></i></a>';
            });
        } else {
            $id_turma = $datapost['id_turma'];
            $turma = $this->turma->get($id_turma);
            $descricao = $turma['descricao'];
            $this->load->view('cadastro_exercicios/list_lists', array('turma' => $descricao));
        }
    }
    
    public function list_deliveries() {
        $datapost = $this->input->post(null, true);
        if (!empty($datapost['draw'])) {
            $where = "ut.id_turma = $datapost[turma] and u.tipo = 2 and l.id = $datapost[lista]";
            $params = formatParams($datapost);
            if (!empty($params['where'])) {
                $params['where'] = "$where and (" . $params['where'] . ')';
            } else {
                $params['where'] = $where;
            }
            $data = $this->aluno->get_users_with_lists($params['where'], $params['order_by'], $params['length'], $params['start']);
            $count_all = $this->aluno->count_users_with_lists($where);
            $count_filtered = $this->aluno->count_users_with_lists($params['where']);
            $results = formatVars($data);
            echo formatResults($results, $count_all, $count_filtered, $params['draw'], function (&$row) {
                $row['detalhes'] = '<a href="#" title="Visualizar detalhes" class="detalhes"><i class="fa fa-eye"></i></a>';
            });
        } else {
            $id_lista = $datapost['id_lista'];
            $id_turma = $datapost['id_turma'];
            $lista = $this->lista->get($id_lista);
            $turma = $this->turma->get($id_turma);
            $this->load->view('cadastro_exercicios/list_deliveries', array('lista' => $lista, 'turma' => $turma));
        }
    }
    
    public function list_details() {
        $datapost = $this->input->post(null, true);
        $id_turma = $datapost['id_turma'];
        $id_lista = $datapost['id_lista'];
        $id_aluno = $datapost['id_aluno'];
        $lista = $this->lista->get($id_lista);
        $aluno = $this->aluno->get($id_aluno);
        $data = $this->resposta->get_answers_by_aluno($id_lista, $id_aluno);
        $this->load->view('cadastro_exercicios/list_details', array(
            'turma' => $id_turma,
            'lista' => $lista,
            'aluno' => $aluno,
            'data' => $data
        ));
    }
    
    public function form_list() {
        $datapost = $this->input->post(null, true);
        $id_lista = $datapost['lista'];
        $id_turma = $datapost['turma'];
        $turma = $this->turma->get($id_turma);
        $exercicios = array();
        if (!empty($id_lista)) {
            $lista = $this->lista->get($id_lista);
            $exercicios = $this->exercicio->get_where("lista = $id_lista", 'id asc');
        } else {
            $lista['titulo'] = 'Exercícios - ' . date('d/m/Y');
        }
        $data = formatVars(array(
            'turma' => $turma,
            'lista' => $lista,
            'exercicios' => $exercicios
        ), array('titulo'));
        $this->load->view('cadastro_exercicios/form_list', $data);
    }
    
    public function form_exercise() {
        $datapost = $this->input->post(null, true);
        $id_turma = $datapost['turma'];
        $lista = $datapost['lista'];
        $exercicio = $datapost['exercicio'];
        $turma = $this->turma->get($id_turma);
        $data = formatVars(array(
            'exercicio' => $exercicio,
            'lista' => $lista,
            'turma' => $turma,
            'tables' => $this->banco->get_tables()
        ), array('exercicio', 'lista'));
        $this->load->view('cadastro_exercicios/form_exercise', $data);
    }
    
    public function row_exercises() {
        $datapost = $this->input->post(null, true);
        $this->load->view('cadastro_exercicios/row_exercises', $datapost);
    }
    
    public function save() {
        $dados = $this->input->post(null, false);
        // Separa os dados de cada tabela
        $dados_lista = $dados['lista'];
        $dados_exercicios = $dados['exercicios'];
        // Separa as regras de validação
        $lista_rules = $this->lista->get_rules_from_db();
        $exercicio_rules = $this->exercicio->get_rules_from_db();
        // Faz as validações
        $this->form_validation->set_data($dados_lista);
        $this->form_validation->set_rules($lista_rules);
        // Cadastra os registros
        if ($this->form_validation->run()) {
            $this->db->trans_begin();
            if (($save = $this->lista->save($dados_lista)) !== false) {
                $this->form_validation->set_rules($exercicio_rules);
                foreach ($dados_exercicios as $exercicio) {
                    // Se o exercício foi excluído
                    if ($exercicio['excluido'] == 'true') {
                        $this->resposta->delete_where("exercicio = $exercicio[id]");
                        if (!$this->exercicio->delete($exercicio['id'])) {
                            $error = $this->db->error();
                            $this->db->trans_rollback();
                            $this->response('error', $error['message']);
                        }
                    } else {
                    // Caso contrário
                        $exercicio['lista'] = $save;
                        unset($exercicio['excluido']);
                        $this->form_validation->set_data($exercicio);
                        if ($this->form_validation->run()) {
                            if ($this->exercicio->save($exercicio) === false) {
                                $error = $this->db->error();
                                $this->db->trans_rollback();
                                $this->response('error', $error['message']);
                            }
                        } else {
                            $this->response('error', validation_errors(' ', '<br>'));
                        }
                    }
                }
                $this->db->trans_commit();
                $this->response('success', 'Registro salvo com sucesso.');
            }
            $error = $this->db->error();
            $this->db->trans_rollback();
            $this->response('error', $error['message']);
        } else {
            $this->db->trans_rollback();
            $this->response('error', validation_errors(' ', '<br>'));
        }
    }
    
    public function delete_list($id = null) {
        $this->db->trans_begin();
        if ($this->exercicio->delete_where("lista = $id")) {
            if ($this->lista->delete($id)) {
                $this->db->trans_commit();
                $this->response();
            }
        }
        $error = $this->db->error();
        $this->db->trans_rollback();
        if (stripos($error['message'], 'foreign key') !== false) {
            $this->response('error', 'Este questionário já foi respondido, não é possível excluí-lo.');
        } else {
            $this->response('error', $error['message']);
        }
    }
    
    public function execute() {
        $sql = $this->input->post('sql', false);
        if (strtolower(substr(trim($sql), 0, 6)) === 'select') {
            $query = $this->banco->query($sql);
            if ($query !== false) {
                $view = $this->load->view('generic/results', array('results' => $query), true);
                $this->response('success', array('results' => $view));
            } else {
                $error = $this->db->error();
                $message = str_replace("Table 'aluno_praticar_exercicios.", "Table '", $error['message']);
                $this->response('error', $message);
            }
        }
        $this->response('error', 'Consulta inválida.');
    }
    
    public function table_data() {
        $table = $this->input->post('table', true);
        $table_data = $this->banco->table_data($table);
        $this->load->view('generic/table_data', array(
            'table' => $table,
            'columns' => $table_data['columns'],
            'data' => $table_data['data']
        ));
    }
    
}
