var Praticar = {
    parser: null,
    url: function (method, params) {
        method = typeof method === 'string' ? '/' + method : '';
        params = typeof params !== 'undefined' ? '/' + params : '';
        return System.base_url('praticar' + method + params);
    },
    init: function () {
        var self = this;
        $.post(self.url('form'), function (json) {
            $('#page-wrapper').html(json.view);
            self.parser = peg.generate(json.grammar);
            System.initializeComponents();
            $('.operacoes a').click(function (e) {
                e.preventDefault();
                $('#consulta').replaceSelectedText($(this).data('operacao')).keyup();
            });
            $('#consulta').keyup(function (e) {
                if ($('#consulta').val().trim()) {
                    try {
                        $('#sql').val(self.parser.parse($('#consulta').val()));
                    } catch (e) {
                        $('#sql').val(e);
                    }
                } else {
                    $('#sql').val('');
                }
                if (e.ctrlKey && e.which === 13) {
                    $('#executar').click();
                }
            });
            $('#executar').click(function () {
                if ($('#consulta').val().trim()) {
                    $.post(self.url('execute'), {
                        sql: self.parser.parse($('#consulta').val())
                    }, function (json) {
                        if (json.info === 1) {
                            $('#tab-resultados').html(json.results);
                        } else {
                            $('#tab-resultados').html('<b style="font-size: 14px; color: red;">Erro: ' + json.msg + '</b>');
                        }
                        $('.nav-tabs li:eq(2) a').tab('show');
                    }, 'json');
                }
            });
            $('#tab-tabelas table tbody').on('click', 'a.visualizar', function (e) {
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
        }, 'json');
    },
    save: function (form) {
        var self = this;
        $.post(self.url('save'), $(form).getFormData(), function (json) {
            System.alert({
                msg: json.msg,
                msg_type: json.info === 1 ? 'info' : 'error',
                callback: function () {
                    if (json.info === 1) {
                        document.location.replace(System.base_url());
                    }
                }
            });
        }, 'json');
    }
};
