<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Banco extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Banco_Model', 'banco');
    }
    
    public function form() {
        $tables = $this->banco->get_tables();
        $view = $this->load->view('banco/form', array('tables' => $tables), true);
        $this->response('success', array(
            'view' => $view
        ));
    }
    
    public function execute() {
        $sql = $this->input->post('sql', false);
        $query = $this->banco->query($sql);
        if ($query !== false) {
            $tables = $this->banco->get_tables();
            $view = $this->load->view('generic/tables', array('tables' => $tables, 'acoes' => true), true);
            if (is_array($query)) {
                if (count($query) > 0) {
                    $columns = array_keys(reset($query));
                    $resultados = $this->load->view('generic/table_template', array('columns' => $columns, 'data' => $query), true);
                } else {
                    $resultados = 'Comando executado com sucesso. Nenhum resultado retornado.';
                }
            } else {
                $resultados = 'Comando executado com sucesso.';
            }
            $this->response('success', array(
                'resultados' => $resultados,
                'view' => $view
            ));
        } else {
            $error = $this->db->error();
            $message = str_replace('aluno_praticar_exercicios.', '', $error['message']);
            if (trim($message) === '') {
                $message = 'Ocorreu um erro durante a execução do seu comando SQL.';
            }
            $this->response('error', $message);
        }
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
    
    public function drop_table($table = null) {
        if ($this->banco->drop_table($table)) {
            $this->response();
        } else {
            $error = $this->db->error();
            $message = str_replace('aluno_praticar_exercicios.', '', $error['message']);
            $this->response('error', $message);
        }
    }

}
