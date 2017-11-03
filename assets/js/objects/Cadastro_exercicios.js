var Cadastro_exercicios = {
    rules: {
        'lista[titulo]': 'required',
        'lista[data_prazo]': {
            required: true,
            dateBR: true
        },
        'lista[hora_prazo]': {
            required: true,
            time24h: true
        },
        'exercicio[descricao]': 'required',
        'exercicio[resposta]': 'required'
    },
    url: function (method, params) {
        method = typeof method === 'string' ? '/' + method : '';
        params = typeof params !== 'undefined' ? '/' + params : '';
        return System.base_url('cadastro_exercicios' + method + params);
    },
    init: function () {
        var self = this;
        $('#page-wrapper').load(self.url('index'), function () {
            System.initializeComponents({
                datatables: {
                    ajax: {
                        url: self.url('index'),
                        type: 'POST'
                    },
                    columns: [
                        {data: 'descricao'},
                        {data: 'visualizar', class: 'text-center'}
                    ]
                }
            });
            $('table.table').on('click', '.visualizar', function (e) {
                e.preventDefault();
                self.list_lists($(this).get_tr_data('id'));
            });
        });
    },
    list_lists: function (id_turma) {
        var self = this;
        $('#page-wrapper').load(self.url('list_lists'), {
            id_turma: id_turma
        }, function () {
            System.initializeComponents({
                datatables: {
                    ajax: {
                        url: self.url('list_lists'),
                        type: 'POST',
                        data: {
                            turma: id_turma
                        }
                    },
                    columns: [
                        {data: 'titulo'},
                        {data: 'data_prazo'},
                        {data: 'hora_prazo'},
                        {data: 'editar'},
                        {data: 'excluir'},
                        {data: 'entregas', class: 'text-center'}
                    ]
                }
            });
            $('.novo').click(function () {
                self.form_list(id_turma);
            });
            $('table.table').on('click', '.editar', function (e) {
                e.preventDefault();
                self.form_list(id_turma, $(this).get_tr_data('id'));
            }).on('click', '.excluir', function (e) {
                e.preventDefault();
                self.delete_list($(this).get_tr_data('id'), id_turma);
            }).on('click', '.entregas', function (e) {
                e.preventDefault();
                self.list_deliveries(id_turma, $(this).get_tr_data('id'));
            });
        });
    },
    list_deliveries: function (id_turma, id_lista) {
        var self = this;
        $('#page-wrapper').load(self.url('list_deliveries'), {
            id_lista: id_lista,
            id_turma: id_turma
        }, function () {
            System.initializeComponents({
                datatables: {
                    ajax: {
                        url: self.url('list_deliveries'),
                        type: 'POST',
                        data: {
                            lista: id_lista,
                            turma: id_turma
                        }
                    },
                    columns: [
                        {data: 'nome'},
                        {data: 'data', searchable: false},
                        {data: 'hora', searchable: false},
                        {data: 'detalhes', class: 'text-center'}
                    ]
                }
            });
            $('table.table').on('click', '.detalhes', function (e) {
                e.preventDefault();
                self.list_details(id_turma, id_lista, $(this).get_tr_data('id'));
            });
        });
    },
    list_details: function (id_turma, id_lista, id_aluno) {
        var self = this;
        $('#page-wrapper').load(self.url('list_details'), {
            id_turma: id_turma,
            id_lista: id_lista,
            id_aluno: id_aluno
        });
    },
    form_list: function (id_turma, id_lista) {
        var self = this;
        if (typeof id_lista === 'undefined') {
            id_lista = 0;
        }
        $('#page-wrapper').load(self.url('form_list'), {
            lista: id_lista,
            turma: id_turma
        }, function () {
            System.initializeComponents();
            $('#titulo').focus();
            $('.adicionar-exercicio').click(function () {
                self.form_exercise(id_turma);
            });
            $('table.table').on('click', '.editar', function (e) {
                e.preventDefault();
                self.form_exercise(id_turma, $(this).get_tr_data());
            }).on('click', '.excluir', function (e) {
                e.preventDefault();
                var tr = $(this).closest('tr');
                if (tr.data('id') != 0) {
                    tr.data('excluido', !tr.data('excluido'));
                    tr.toggleClass('danger');
                    tr.find('.editar').toggle();
                    $(this).find('i').toggleClass('fa-pencil fa-undo');
                    if ($(this).attr('title') === 'Excluir Exercício') {
                        $(this).attr('title', 'Desfazer exclusão');
                        tr.find('td:eq(1), td:eq(2)').removeAttr('title');
                        tr.attr('title', 'Registro marcado para exclusão');
                    } else {
                        $(this).attr('title', 'Excluir Exercício');
                        tr.find('td:eq(1)').attr('title', tr.find('td:eq(1)').text());
                        tr.find('td:eq(2)').attr('title', tr.find('td:eq(1)').text());
                        tr.removeAttr('title');
                    }
                } else {
                    tr.remove();
                }
            });
            $('.form-horizontal').validate({
                submitHandler: function (form) {
                    if ($('#informacoes-lista').is(':visible')) {
                        self.save(form);
                    } else {
                        $('#resposta').keyup();
                        var id_exercicio = $('#id-exercicio').val().trim();
                        $.post(self.url('row_exercises'), {
                            exercicio: {
                                id: id_exercicio,
                                sequencia: $('#sequencia').val(),
                                lista: $('#id-lista').val(),
                                descricao: escapeHtml($('#descricao').val()),
                                resposta: escapeHtml($('#resposta').val()),
                                resposta_sql: escapeHtml($('#sql').val())
                            }
                        }, function (html) {
                            if (id_exercicio != 0) {
                                $('#informacoes-lista table.table tbody tr[data-id=' + id_exercicio + ']').after(html).remove();
                            } else {
                                $('#informacoes-lista table.table tbody').append(html);
                            }
                            $('.cancelar').click();
                        }, 'html');
                    }
                },
                rules: self.rules,
                onfocusout: false
            });
        });
    },
    form_exercise: function (id_turma, tr_data) {
        var self = this,
            id = (typeof tr_data !== 'undefined' ? tr_data.id : 0),
            sequencia = typeof tr_data !== 'undefined' ? tr_data.sequencia :
                            ($('#informacoes-lista table.table tbody tr').length > 0 ?
                                parseInt($('#informacoes-lista table.table tbody tr:last td:eq(0)').text()) + 1 : 1),
            descricao = (typeof tr_data !== 'undefined' ? tr_data.descricao : ''),
            resposta = (typeof tr_data !== 'undefined' ? tr_data.resposta : ''),
            resposta_sql = (typeof tr_data !== 'undefined' ? tr_data.resposta_sql : '');
        $('#informacoes-exercicio').load(self.url('form_exercise'), {
            exercicio: {
                id: id,
                sequencia: sequencia,
                descricao: descricao,
                resposta: resposta,
                lista: $('#id-lista').val(),
                resposta_sql: resposta_sql
            },
            turma: id_turma,
            lista: {
                id: $('#id-lista').val(),
                titulo: $('#titulo').val()
            }
        }, function () {
            // Carrega as informações do exercício
            $('#informacoes-lista, #informacoes-exercicio').toggle();
            // Inicializa os componentes da tela
            System.initializeComponents();
            // Configura o comportamento dos "breadcrumbs" e foca no primeiro campo
            $('.breadcrumb a').last().click(function (e) {
                e.preventDefault();
                $('#informacoes-exercicio').html('');
                $('#informacoes-lista, #informacoes-exercicio').toggle();
            });
            $('#descricao').focus();
            // Seta as funções dos botões de operação
            $('.operacoes a').click(function (e) {
                e.preventDefault();
                $('#resposta').replaceSelectedText($(this).data('operacao')).keyup();
            });
            // Trata da parte de conversão da álgebra para SQL
            $('#resposta').keyup(function () {
                if ($(this).val().trim()) {
                    try {
                        $('#sql').val(Parser.parse($(this).val()));
                    } catch (e) {
                        $('#sql').val(e);
                    }
                } else {
                    $('#sql').val('');
                }
            });
            // Trata da parte de execução da consulta em SQL
            $('.visualizar-consulta').click(function () {
                var sql = '';
                try {
                    $('#sql').removeClass('error');
                    sql = Parser.parse($('#resposta').val());
                    $.post(self.url('execute'), {
                        sql: sql
                    }, function (json) {
                        if (json.info === 1) {
                            $('#tab-resultados').html(json.results);
                        } else {
                            $('#tab-resultados').html('<b style="font-size: 14px; color: red;">Erro: ' + json.msg + '</b>');
                        }
                        $('.nav-tabs li:eq(2) a').tab('show');
                    }, 'json');
                } catch (e) {
                    $('#sql').addClass('error');
                }
            });
            // Visualização das tabelas
            $('#tab-tabelas').on('click', 'table tbody a.visualizar', function (e) {
                e.preventDefault();
                var table = $(this).get_tr_data('table');
                
                System.modal({
                    url: self.url('table_data'),
                    data: {
                        table: table
                    },
                    onShow: function (modal) {
                        modal.find('.btn').focus();
                    }
                });
            });
        });
    },
    save: function (form) {
        var self = this,
            data = $(form).getFormData(),
            exercicios = [],
            excluidos = 0;
        $('table.table tbody tr').each(function () {
            var tr_data = $(this).data();
            exercicios.push({
                id: tr_data.id,
                descricao: tr_data.descricao,
                resposta: tr_data.resposta,
                lista: tr_data.lista,
                resposta_sql: tr_data.resposta_sql,
                excluido: tr_data.excluido
            });
            if (tr_data.excluido === true) {
                excluidos++;
            }
        });
        if (exercicios.length == excluidos || exercicios.length == 0) {
            System.alert({
                msg: 'É necessário cadastrar ao menos um Exercício neste Questionário para prosseguir.',
                callback: function () {
                    $('.adicionar-exercicio').focus();
                }
            });
            return false;
        }
        data.exercicios = exercicios;
        $.post(self.url('save'), data, function (json) {
            System.alert({
                msg: json.msg,
                msg_type: json.info === 1 ? 'info' : 'error',
                callback: function () {
                    $('input, select, textarea').filter(':tabbable').first().focus();
                }
            });
            if (json.info === 1) {
                $('.cancelar').click();
            }
        }, 'json');
    },
    delete_list: function (id, id_turma) {
        var self = this;
        System.confirm({
            title: 'Excluir Lista de exercícios',
            msg: 'Deseja realmente excluir este registro?',
            onConfirm: function () {
                $.post(self.url('delete_list/' + id), function (json) {
                    if (json.info === 1) {
                        self.list_lists(id_turma);
                    } else {
                        System.alert({
                            msg: json.msg,
                            msg_type: 'error'
                        });
                    }
                }, 'json');
            }
        });
    }
};
