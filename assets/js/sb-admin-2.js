/*!
 * Start Bootstrap - SB Admin 2 v3.3.7+1 (http://startbootstrap.com/template-overviews/sb-admin-2)
 * Copyright 2013-2016 Start Bootstrap
 * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap/blob/gh-pages/LICENSE)
 */

$(function() {
    /**
     * Loads the correct sidebar on window load,
     * collapses the sidebar on window resize.
     * Sets the min-height of #page-wrapper to window size
     */
    $(window).bind("load resize", function() {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }
        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });
    
    /**
     * Trata das animações de carregamento e checagem de sessão
     */
    var element, classes;
    $(document).ajaxStart(function () {
        element = $(this.activeElement);
        if (element.is('button, a')) {
            // Guarda as classes do elemento
            classes = element.find('i').attr('class');
            // Animação de "loading"
            element.find('i').attr('class', 'fa fa-spinner fa-spin');
        } else {
            element = null;
        }
    }).ajaxComplete(function () {
        // Caso a sessão expire, retorna para a página de login
        if (arguments[1].responseText == 'session_expired') {
            document.location.replace(System.base_url());
        }
        // Fim da animação de "loading"
        if (element !== null && element.find('i').length > 0) {
            // Retorna as classes trocadas anteriormente
            element.find('i').attr('class', classes);
        }
    });

    /**
     * Ações (Sidebar, Quick actions e Breadcrumb)
     */
    $('body').on('click', '.sidebar a[data-object], .quick-actions button, .breadcrumb a[data-object], ' +
                          '.navbar-top-links .dropdown-menu li a[data-object]', function (e) {
        e.preventDefault();
        var data_objeto = $(this).data('object').split('/'),
            objeto = data_objeto[0],
            ucObjeto = objeto.charAt(0).toUpperCase() + objeto.substr(1);
        $('.sidebar li').removeClass('active');
        $('.sidebar li a[data-object=' + objeto + ']').parent().addClass('active');
        requirejs(['assets/js/objects/' + ucObjeto], function () {
            if (data_objeto.length === 1) {
                window[ucObjeto].init();
            } else {
                var args = null;
                if (data_objeto.length > 2) {
                    args = data_objeto.slice(2);
                }
                window[ucObjeto][data_objeto[1]].apply(window[ucObjeto], args);
            }
        });
    }).on('click', '.cancelar, .voltar', function (e) {
        e.preventDefault();
        $('.breadcrumb a').last().get(0).click();
    });
});
