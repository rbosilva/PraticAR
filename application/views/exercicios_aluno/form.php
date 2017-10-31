<form class="form-horizontal">
    <nav class="breadcrumb">
        <a href="<?php echo base_url(); ?>" title="Ir para o Início"><i class="fa fa-home"></i> Início</a>
        <a href="" title="Ir para Questionários" data-object="exercicios_aluno">Questionários</a>
        <span class="active">Responder</span>
    </nav>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo "Respondendo \"$lista\""; ?>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs hidden" role="tablist">
                        <?php
                        $sequencia = 1;
                        foreach ($exercicios as $exercicio) {
                        ?>
                            <li role="presentation" <?php echo $sequencia == 1 ? 'class="active"' : ''; ?>><a href="#tab-exercicio<?php echo $sequencia++; ?>" data-toggle="tab"></a></li>
                        <?php
                        }
                        ?>
                        <li role="presentation"><a href="#tab-finalizando" data-toggle="tab"></a></li>
                    </ul>
                    <div class="tab-content" style="margin-top: 15px;">
                        <?php
                        $sequencia = 1;
                        foreach ($exercicios as $exercicio) {
                            $exercicio['sequencia'] = $sequencia++;
                            $exercicio['ultimo'] = ($exercicio['sequencia'] == count($exercicios));
                            $exercicio['resposta'] = $respostas[$exercicio['id']]['resposta'];
                            $exercicio['tentativas'] = $respostas[$exercicio['id']]['tentativas'];
                            $this->load->view('exercicios_aluno/form_exercise', $exercicio);
                        }
                        ?>
                        <div role="tabpanel" class="tab-pane" id="tab-finalizando">
                            <div class="col-lg-12">
                                <label>Você concluiu este questionário!</label>
                                <br>
                                Caso queira revisar alguma questão, clique em "Voltar". Se achar que está tudo OK, clique em "Enviar respostas".
                            </div>
                            <div class="col-lg-12 form-buttons" style="margin-top: 5px;">
                                <button type="button" class="btn btn-outline btn-primary exercicio-anterior"><i class="fa fa-arrow-left"></i> <span>Voltar</span></button>
                                <button type="submit" class="btn btn-outline btn-primary"><i class="fa fa-save"></i> <span>Enviar respostas</span></button>
                                <button type="button" class="btn btn-outline btn-danger cancelar"><i class="fa fa-ban"></i> <span>Cancelar</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
