var Alterar_senha = {
    rules: {
        senha_antiga: {
            required: true,
            minlength: 3,
            nowhitespace: true
        },
        senha: {
            required: true,
            minlength: 3,
            nowhitespace: true
        }
    },
    url: function (method, params) {
        method = typeof method === 'string' ? '/' + method : '';
        params = typeof params !== 'undefined' ? '/' + params : '';
        return System.base_url('alterar_senha' + method + params);
    },
    init: function () {
        var self = this;
        $('#page-wrapper').load(self.url('form'), function () {
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
            $('#senha_antiga').focus();
            $('.form-horizontal').validate({
                submitHandler: function (form) {
                    self.save(form);
                },
                rules: self.rules
            });
        });
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
