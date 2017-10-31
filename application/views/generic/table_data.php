<div class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Visualizando tabela "<?php echo $table; ?>"</h4>
            </div>
            <div class="modal-body">
                <div class="container row-fluid" style="height: 400px; overflow: auto;">
                    <?php
                    $this->load->view('generic/table_template', array('columns' => $columns, 'data' => $data));
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
