<nav class="breadcrumb">
    <a href="<?php echo base_url(); ?>" title="Ir para o Início"><i class="fa fa-home"></i> Início</a>
    <span class="active">Alteração de Senha</span>
</nav>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                Alteração de Senha
            </div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label for="senha_antiga" class="control-label col-lg-2">Senha antiga</label>
                        <div class="col-lg-10">
                            <input type="password" id="senha_antiga" name="senha_antiga" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="senha" class="control-label col-lg-2">Nova senha</label>
                        <div class="col-lg-10">
                            <input type="password" id="senha" name="senha" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-lg-offset-2 col-lg-10 form-buttons">
                        <button type="submit" class="btn btn-outline btn-primary"><i class="fa fa-save"></i> <span>Salvar</span></button>
                        <button type="button" class="btn btn-outline btn-danger cancelar"><i class="fa fa-ban"></i> <span>Cancelar</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
