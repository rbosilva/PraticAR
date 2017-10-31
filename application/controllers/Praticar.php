<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Praticar extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Banco_Model', 'banco');
    }
    
    public function form() {
        $tables = $this->banco->get_tables();
        $view = $this->load->view('praticar/form', array('tables' => $tables), true);
        $this->response('success', array(
            'view' => $view,
            'grammar' => file_get_contents(base_url('assets/js/libs/pegjs/grammar.js'))
        ));
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
                $this->response('error', $error['message']);
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
