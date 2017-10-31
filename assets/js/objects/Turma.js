var Turma = {
    rules: {
        'turma[descricao]': 'required',
        'usuarios[professor]': {
            required: true,
            digits: true
        },
        'usuarios[alunos]': 'required'
    },
    url: function (method, params) {
        method = typeof method === 'string' ? '/' + method : '';
        params = typeof params !== 'undefined' ? '/' + params : '';
        return System.base_url('turma' + method + params);
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
                        {data: 'usuario', "name": "nome"},
                        {data: 'ativa'},
                        {data: 'editar'},
                        {data: 'excluir'}
                    ]
                }
            });
            $('.novo').click(function () {
                self.form();
            });
            $('table.table').on('click', '.editar', function (e) {
                e.preventDefault();
                self.form($(this).get_tr_data('id'));
            }).on('click', '.excluir', function (e) {
                e.preventDefault();
                self.delete($(this).get_tr_data('id'));
            });
        });
    },
    form: function (id) {
        var self = this;
        if (typeof id === 'undefined') {
            id = 0;
        }
        $('#page-wrapper').load(self.url('form', id), function () {
            System.initializeComponents();
            $('#professor').select2({
                selectOnClose: false,
                ajax: {
                    url: self.url('teachers'),
                    dataType: 'json',
                    type: 'post',
                    delay: 250,
                    cache: true,
                    data: function (params) {
                        return params;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.dados,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                }
            });
            $('#alunos').select2({
                selectOnClose: false,
                closeOnSelect: false,
                ajax: {
                    url: self.url('students'),
                    dataType: 'json',
                    type: 'post',
                    delay: 250,
                    cache: true,
                    data: function (params) {
                        var data = $.extend(params, {
                            turma: $('#id').val()
                        });
                        return data;
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.dados,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    }
                }
            });
            $('#descricao').focus();
            $('.form-horizontal').validate({
                submitHandler: function (form) {
                    self.save(form);
                },
                rules: self.rules
            });
        });
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
                if ($.isNumeric(data['turma[id]'])) {
                    self.init();
                } else {
                    $(form).clearForm();
                }
            }
        }, 'json');
    },
    delete: function (id) {
        var self = this;
        System.confirm({
            title: 'Excluir Turma',
            msg: 'Deseja realmente excluir este registro?',
            onConfirm: function () {
                $.post(self.url('delete/' + id), function (json) {
                    if (json.info === 1) {
                        self.init();
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
