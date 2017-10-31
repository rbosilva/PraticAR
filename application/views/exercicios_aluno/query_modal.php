<div class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Visualizando consulta</h4>
            </div>
            <div class="modal-body">
                <div class="container row-fluid" style="height: 400px; overflow: auto;">
                    <?php
                    echo $view;
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal"><i class=""></i> Fechar</button>
            </div>
        </div>
    </div>
</div>
