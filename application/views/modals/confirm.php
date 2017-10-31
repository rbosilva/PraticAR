<div class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo htmlentities($title) ?></h4>
            </div>
            <div class="modal-body">
                <div class="container row-fluid">
                    <?php echo $msg ?>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"><i class=""></i> Sim</button>
                <button class="btn btn-danger" data-dismiss="modal"><i class=""></i> Não</button>
            </div>
        </div>
    </div>
</div>
