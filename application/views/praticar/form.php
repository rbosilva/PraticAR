<nav class="breadcrumb">
    <a href="<?php echo base_url(); ?>" title="Ir para o Início"><i class="fa fa-home"></i> Início</a>
    <span class="active">Praticando Álgebra Relacional</span>
</nav>
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-algebra-relacional" data-toggle="tab">Álgebra Relacional</a></li>
                <li role="presentation"><a href="#tab-tabelas" data-toggle="tab">Tabelas</a></li>
                <li role="presentation"><a href="#tab-resultados" data-toggle="tab">Resultados</a></li>
            </ul>
            <div class="tab-content" style="margin-top: 15px;">
                <div role="tabpanel" class="tab-pane active" id="tab-algebra-relacional">
                    <div class="form-group">
                        <div class="col-lg-12 operacoes">
                            <div class="pull-left">
                                <a aria-role="button" type="button" class="btn btn-default" title="Projeção" data-operacao="π"
                                   data-toggle="popover" data-content="<b style='color: red;'>π</b> colA, colB, ... (rel)">π</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Seleção" data-operacao="σ"
                                   data-toggle="popover" data-content='<b style="color: red;">σ</b> col = <b style="color: blue;">"</b>val<b 
                                   style="color: blue;">"</b> (rel)'>σ</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Junção (NATURAL / <b style='color: blue;'>INNER</b>)"
                                   data-operacao="⨝" data-toggle="popover" data-content="relA <b style='color: red;'>⨝</b> relB<br>relA
                                   <b style='color: red;'>⨝</b> <b style='color: blue;'>(relA.col1 = relB.col1)</b> relB">⨝</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Divisão" data-operacao="÷"
                                   data-toggle="popover" data-content="relA <b style='color: red;'>÷</b> relB">÷</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="União" data-operacao="∪"
                                   data-toggle="popover" data-content="relA <b style='color: red;'>∪</b> relB">∪</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Intersecção" data-operacao="∩"
                                   data-toggle="popover" data-content="relA <b style='color: red;'>∩</b> relB">∩</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Diferença" data-operacao="-"
                                   data-toggle="popover" data-content="relA <b style='color: red;'>-</b> relB">-</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Produto Cartesiano" data-operacao="X"
                                   data-toggle="popover" data-content="relA <b style='color: red;'>X</b> relB">X</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Renomear (TABLE / <b style='color: blue;'>COLUMN</b>)"
                                   data-operacao="ρ" data-toggle="popover" data-content="<b style='color: red;'>ρ</b> alias (rel)<br>
                                   <b style='color: red;'>ρ</b> <b style='color: blue;'>[</b>alias1<b style='color: blue;'>, alias2]</b> (π col1, col2 (rel))">ρ</a>
                            </div>
                            <div class="pull-right">
                                <a aria-role="button" type="button" class="btn btn-default" title='"E" condicional' data-operacao="^"
                                   data-toggle="popover" data-content='σ col1 = "val1" <b style="color: red;">^</b> col2 = "val2" (rel)'>^</a>
                                <a aria-role="button" type="button" class="btn btn-default" title='"OU" condicional' data-operacao="v"
                                   data-toggle="popover" data-content='σ col1 = "val1" <b style="color: red;">v</b> col2 = "val2" (rel)'>v</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Maior que" data-operacao=">"
                                   data-toggle="popover" data-content='σ col <b style="color: red;">></b> "val" (rel)'>></a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Menor que" data-operacao="<"
                                   data-toggle="popover" data-content='σ col <b style="color: red;"><</b> "val" (rel)'><</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Maior ou igual" data-operacao=">="
                                   data-toggle="popover" data-content='σ col <b style="color: red;">>=</b> "val" (rel)'>>=</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Menor ou igual" data-operacao="<="
                                   data-toggle="popover" data-content='σ col <b style="color: red;"><=</b> "val" (rel)'><=</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Igual" data-operacao="="
                                   data-toggle="popover" data-content='σ col <b style="color: red;">=</b> "val" (rel)'>=</a>
                                <a aria-role="button" type="button" class="btn btn-default" title="Diferente" data-operacao="<>"
                                   data-toggle="popover" data-content='σ col <b style="color: red;"><></b> "val" (rel)'><></a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <textarea id="consulta" class="form-control" rows="5" style="resize: none;" spellcheck="false"
                                placeholder="Digite aqui sua consulta, por exemplo: σ carros.id_carro = compras.id_carro (carros X compras)">ρ [cliente, carro] (
    π clientes.nome , carros.nome (
        σ clientes.id_cliente = compras.id_cliente ^ carros.id_carro = compras.id_carro (
            clientes X compras X carros
        )
    )
)</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12">
                            <h5>SQL:</h5>
                            <textarea id="sql" class="form-control" rows="5" style="resize: none;" spellcheck="false" readonly>SELECT `clientes`.`nome` AS `cliente`,`carros`.`nome` AS `carro`
FROM `clientes`,`compras`,`carros`
WHERE `clientes`.`id_cliente` = `compras`.`id_cliente`
               AND `carros`.`id_carro` = `compras`.`id_carro`
ORDER BY `cliente`</textarea>
                        </div>
                    </div>
                    <div class="form-buttons">
                        <button id="executar" type="button" class="btn btn-outline btn-primary">
                            <i class="fa fa-play"></i> <span>Executar</span>
                        </button>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab-tabelas" style="height: 350px; overflow: auto;">
                    <?php
                    $this->load->view('generic/tables', array('tables' => $tables));
                    ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="tab-resultados" style="height: 450px; overflow: auto;">
                    Execute uma consulta para checar os resultados!
                </div>
            </div>
        </form>
    </div>
</div>
