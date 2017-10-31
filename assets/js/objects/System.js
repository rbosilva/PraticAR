var System = {
    /**
     * Retorna a url do site. Opcionalmente adiciona mais algum trecho à url
     * @param {String} url [optional]
     * @returns {String}
     */
    base_url: function (url) {
        var base_url = !location.origin ? location.protocol + "//" + location.host : location.origin;
        return base_url + '/' + location.pathname.split('/')[1] + '/' + (typeof url !== 'undefined' ? url : '');
    },
    /**
     * Exibe uma modal
     * @param {Json} data Informações para configuração da modal
     */
    modal: function (data) {
        var defaults = {
            url: '',
            data: false,
            onShow: false,
            onHide: false
        };
        var config = $.extend(defaults, data);
        $.post(config.url, config.data, function (html) {
            $(html).modal('show').on('shown.bs.modal', function () {
                if ($.isFunction(config.onShow)) {
                    config.onShow($(this));
                }
            }).on('hidden.bs.modal', function () {
                if ($.isFunction(config.onHide)) {
                    config.onHide($(this));
                }
            });
        });
    },
    /**
     * Exibe uma modal de alerta
     * @param {Json} data Informações para configuração da modal
     */
    alert: function (data) {
        var defaults = {
            msg: '',
            msg_type: 'info',
            callback: false
        };
        var config = $.extend(defaults, data);
        this.modal({
            url: this.base_url('modals/alert'),
            data: {
                msg: config.msg,
                msg_type: config.msg_type
            },
            onShow: function (modal) {
                modal.find('.btn').focus();
            },
            onHide: function () {
                if ($.isFunction(config.callback)) {
                    config.callback();
                }
            }
        });
    },
    /**
     * Exibe uma modal de confirmação
     * @param {Json} data Informações para configuração da modal
     */
    confirm: function (data) {
        var defaults = {
            title: 'Confirmação',
            msg: '',
            onConfirm: false,
            onCancel: false
        };
        var config = $.extend(defaults, data);
        this.modal({
            url: this.base_url('modals/confirm'),
            data: {
                title: config.title,
                msg: config.msg
            },
            onShow: function (modal) {
                modal.find('.btn-primary').focus();
                if ($.isFunction(config.onConfirm)) {
                    modal.find('.btn-primary').click(function (e) {
                        config.onConfirm(e);
                    });
                }
                if ($.isFunction(config.onCancel)) {
                    modal.find('.btn-danger').click(function (e) {
                        config.onCancel(e);
                    });
                }
            }
        });
    },
    /**
     * Inicializa e configura diversos componentes na view
     * @param {Json} settings Parâmetros para configuração dos componentes
     */
    initializeComponents: function (settings) {
        var defaults = {
            content: $('#page-wrapper'),
            datatables: {},
            datepicker: {},
            uniform: {
                checkboxClass: 'checkbox checker'
            },
            select2: {},
            clockpicker: {
                autoclose: "true"
            },
            popover: {
                trigger: "hover",
                placement: "bottom",
                html: true,
                container: "body"
            }
        },
        configs = $.extend(defaults, settings);

        $.applyDataMask();
        configs.content.find('table:not(.no-datatables)').DataTable(configs.datatables).on('draw', function () {
            $(this).find('.editar, .excluir').closest('td').addClass('text-center');
        });
        configs.content.find('input[data-date-format]').datepicker(configs.datepicker);
        configs.content.find('input[type=checkbox]:not(.no-uniform), input[type=radio]:not(.no-uniform)').uniform(configs.uniform);
        configs.content.find('select:not(.no-select2)').select2(configs.select2).on('select2:close', function () {
            $(this).focus();
        });
        configs.content.find('.clockpicker').clockpicker(configs.clockpicker).on('change', function () {
            if (!isTime24h($(this).val())) {
                $(this).val('00:00');
            }
        });
        $('[data-toggle="popover"]').popover(configs.popover);
    }
};
