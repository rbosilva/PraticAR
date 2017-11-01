var Exercicios_aluno = {
    url: function (method, params) {
        method = typeof method === 'string' ? '/' + method : '';
        params = typeof params !== 'undefined' ? '/' + params : '';
        return System.base_url('exercicios_aluno' + method + params);
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
                        {data: 'titulo'},
                        {data: 'data_prazo'},
                        {data: 'hora_prazo'},
                        {data: 'respondido_em', targets: "no-sort", orderable: false, searchable: false},
                        {data: 'responder', class: 'text-center'}
                    ],
                    order: [1, 'asc'],
                    createdRow: function(row, data, dataIndex) {
                        if (data.respondido_em != null) {
                            $(row).data('respondido', 1);
                        }
                    }
                }
            });
            $('table.table').on('click', '.responder', function (e) {
                e.preventDefault();
                var element = $(this);
                if ($(this).get_tr_data('respondido') == 1) {
                    System.confirm({
                        msg: 'Este questionário já foi respondido, deseja respondê-lo novamente?',
                        onConfirm: function () {
                            self.form(element.get_tr_data('id'));
                        }
                    });
                } else {
                    self.form(element.get_tr_data('id'));
                }
            });
        });
    },
    form: function (id) {
        var self = this;
        if (typeof id === 'undefined') {
            id = 0;
        }
        $.post(self.url('form'), {
            id_lista: id
        }, function (json) {
            if (json.info == 1) {
                $('#page-wrapper').html(json.view);
                System.initializeComponents();
                $('.resposta').focus();
                // Funções dos botões de operação
                $('.operacoes a').click(function (e) {
                    e.preventDefault();
                    $('.resposta:visible').replaceSelectedText($(this).data('operacao')).keyup();
                });
                // Conversão da álgebra para SQL
                $('.resposta').keyup(function () {
                    var resposta = $(this),
                        sql = resposta.closest('.tab-pane').find('.sql');
                    if (resposta.val().trim()) {
                        try {
                            sql.val(Parser.parse(resposta.val()));
                        } catch (e) {
                            sql.val(e);
                        }
                    } else {
                        sql.val('');
                    }
                }).keyup();
                // Execução da consulta em SQL
                $('.visualizar-consulta').click(function () {
                    var sql = '';
                    try {
                        $('.sql:visible').removeClass('error');
                        sql = Parser.parse($('.resposta:visible').val());
                        System.modal({
                            url: self.url('execute'),
                            data: {
                                sql: sql
                            },
                            onShow: function (modal) {
                                modal.find('.btn').focus();
                            }
                        });
                    } catch (e) {
                        $('.sql:visible').addClass('error');
                        $('.sql:visible').val(e);
                    }
                });
                // Visualização das tabelas
                $('.visualizar-tabelas').click(function () {
                    System.modal({
                        url: self.url('get_tables'),
                        data: {
                            title: 'Usar tabela'
                        },
                        onShow: function (modal) {
                            modal.find('table tbody a').click(function () {
                                $('.resposta:visible').replaceSelectedText($(this).text()).keyup();
                                modal.modal('hide');
                            });
                            modal.find('.btn').focus();
                        }
                    });
                });
                // Voltar
                $('.exercicio-anterior').click(function () {
                    var abas = $('.nav-tabs li'),
                        aba_atual = abas.filter('.active').index();
                    abas.filter(':eq(' + (aba_atual - 1) + ')').find('a').tab('show');
                });
                // Avançar
                $('.proximo-exercicio').click(function () {
                    var abas = $('.nav-tabs li'),
                        aba_atual = abas.filter('.active').index(),
                        resposta = $('.resposta:visible'),
                        tentativas = $('.resposta:visible').closest('.tab-pane').find('.tentativas');
                    if (resposta.val().trim() != '') {
                        $.post(self.url('compare_queries'), {
                            exercicio: resposta.data('exercicio'),
                            sql: Parser.parse(resposta.val()),
                            tentativas: tentativas.val()
                        }, function (json) {
                            if (json.info == 1) {
                                System.alert({
                                    msg: '<b>Resposta correta!</b><br>' +
                                         'Sua resposta: ' + resposta.val() + '<br>' +
                                         'Resposta do professor: ' + json.resposta_professor,
                                    callback: function () {
                                        abas.filter(':eq(' + (aba_atual + 1) + ')').find('a').tab('show');
                                    }
                                });
                            } else {
                                System.alert({
                                    msg_type: 'error',
                                    msg: '<b>Resposta incorreta!</b><br>' + json.msg
                                });
                                tentativas.val(json.tentativas);
                            }
                        }, 'json');
                    } else {
                        System.alert({
                            msg: 'É necessário responder esta questão para prosseguir.',
                            callback: function () {
                                var aba_atual = resposta.closest('.tab-pane').attr('id');
                                aba_atual = $('.nav-tabs li[href=#' + aba_atual + '] a').tab('show');
                                resposta.focus();
                            }
                        });
                    }
                });
                // Enviar respostas
                $('.form-horizontal').submit(function (e) {
                    var form = $(this);
                    e.preventDefault();
                    e.stopPropagation();
                    $('.resposta').keyup();
                    self.save(form);
                });
            } else {
                System.alert({
                    msg_type: 'error',
                    msg: json.msg
                });
            }
        }, 'json');
    },
    save: function (form) {
        var self = this,
            data = $(form).getFormData();
        $.post(self.url('save'), data, function (json) {
            System.alert({
                msg: json.msg,
                msg_type: json.info === 1 ? 'info' : 'error',
                callback: function () {
                    $('input, select, textarea').filter(':tabbable').first().focus();
                }
            });
            if (json.info === 1) {
                self.init();
            }
        }, 'json');
    }
};
