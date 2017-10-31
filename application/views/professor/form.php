<nav class="breadcrumb">
    <a href="<?php echo base_url(); ?>" title="Ir para o Início"><i class="fa fa-home"></i> Início</a>
    <a href="" title="Ir para Professores" data-object="professor"> Professores</a>
    <span class="active"><?php echo !isset($id) ? 'Novo' : 'Editar'; ?></span>
</nav>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo !isset($id) ? '<i class="fa fa-plus"></i> Novo Professor' : '<i class="fa fa-pencil"></i> Editando Professor'; ?>
            </div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <input type="hidden" id="id" name="id" value="<?php echo evaluate($id); ?>">
                    <div class="form-group">
                        <label for="nome" class="control-label col-lg-2">Nome</label>
                        <div class="col-lg-10">
                            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo evaluate($nome); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="login" class="control-label col-lg-2">Login</label>
                        <div class="col-lg-10">
                            <input type="text" id="login" name="login" class="form-control" value="<?php echo evaluate($login); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="senha" class="control-label col-lg-2"><?php echo isset($id) ? 'Alterar s' : 'S' ?>enha</label>
                        <div class="col-lg-10">
                            <input type="password" id="senha" name="senha" class="form-control">
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
