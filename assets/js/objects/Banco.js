var Banco = {
    url: function (method, params) {
        method = typeof method === 'string' ? '/' + method : '';
        params = typeof params !== 'undefined' ? '/' + params : '';
        return System.base_url('banco' + method + params);
    },
    init: function () {
        var self = this;
        $.post(self.url('form'), function (json) {
            $('#page-wrapper').html(json.view);
            System.initializeComponents();
            $('#consulta').keyup(function (e) {
                if (e.ctrlKey && e.which === 13) {
                    $('#executar').click();
                }
            });
            $('#executar').click(function () {
                $('#resultados').removeClass('panel-primary panel-danger').html('');
                if ($('#consulta').val().trim()) {
                    $.post(self.url('execute'), {
                        sql: $('#consulta').val()
                    }, function (json) {
                        if (json.info === 1) {
                            $('#resultados').html(json.resultados).addClass('panel-primary');
                            $('#tab-tabelas').html(json.view);
                        } else {
                            $('#resultados').html('<b style="font-size: 14px; color: red;">' + json.msg + '</div>').addClass('panel-danger');
                        }
                    }, 'json');
                }
            });
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
            }).on('click', '.excluir', function (e) {
                e.preventDefault();
                var table = $(this).get_tr_data('table'),
                    tr = $(this).closest('tr');
                System.confirm({
                    title: 'Excluir Tabela',
                    msg: 'Deseja realmente excluir esta tabela?',
                    onConfirm: function () {
                        $.post(self.url('drop_table/' + table), function (json) {
                            if (json.info === 1) {
                                tr.remove();
                            } else {
                                System.alert({
                                    msg: json.msg,
                                    msg_type: 'error'
                                });
                            }
                        }, 'json');
                    }
                });
            });
        }, 'json');
    }
};
