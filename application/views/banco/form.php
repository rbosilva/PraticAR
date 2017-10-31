<nav class="breadcrumb">
    <a href="<?php echo base_url(); ?>" title="Ir para o Início"><i class="fa fa-home"></i> Início</a>
    <span class="active">Banco de dados</span>
</nav>
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-sql" data-toggle="tab">SQL</a></li>
                <li role="presentation"><a href="#tab-tabelas" data-toggle="tab">Tabelas</a></li>
            </ul>
            <div class="tab-content" style="margin-top: 15px;">
                <div role="tabpanel" class="tab-pane active" id="tab-sql">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <textarea id="consulta" class="form-control" rows="5" style="resize: none;" spellcheck="false"
                                placeholder="Digite aqui seu comando SQL, por exemplo: create table pessoa (id integer, nome text)"></textarea>
                            <label style="color: grey;">Dica: Evite criar chaves e índices nas tabelas, assim fica mais fácil manipulá-las.</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <h5>Resultados:</h5>
                            <div id="resultados" class="form-control" style="height: 200px; overflow: auto;"></div>
                        </div>
                    </div>
                    <div class="form-buttons">
                        <button id="executar" type="button" class="btn btn-outline btn-primary">
                            <i class="fa fa-play"></i> <span>Executar</span>
                        </button>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab-tabelas" style="height: 450px; overflow: auto;">
                    <?php
                    $this->load->view('generic/tables', array('tables' => $tables, 'acoes' => true));
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>
