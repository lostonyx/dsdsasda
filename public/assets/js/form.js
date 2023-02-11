$(function () {
    var a = !1;
    var notyf = new Notyf({position: {x: 'right', y: 'top'} });
    $('form').submit(function (b) {
        if ((b.preventDefault(), b.stopPropagation(), a)) {
            notyf.error('Necessário aguardar a finalização da ação.');
        } else {
            var c = $(this),
            d = c.find('button[name="loaderbutton"]'),
            e = d.html();
            if (c.attr('id') !== undefined && c.attr('id').includes('reseltlement')) return;
            var f = c.serialize();
            $.ajax({ url: c.attr('action'), type: 'POST', data: f })
            .done(function (a) {
                //console.log(a);
                var zz = JSON.parse(a);
                if(typeof zz.message !== 'undefined') {
                    var message = zz.message;
                } else {
                    var message = 'Sem mensagem de retorno.';
                }
                if(typeof zz.status !== 'undefined' && zz.status === 'success') {
                    notyf.success(message);
                } else {
                    notyf.error(message);
                }
                if(typeof zz.reload !== 'undefined') {
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            })
            .fail(function (a, b, c) {
                //console.log(a, b, c),
                notyf.error('Erro no envio da ação.');
            })
            .always(function () {
                d.html(e), (a = !1);
            });
        }
        return !1;
    });
});