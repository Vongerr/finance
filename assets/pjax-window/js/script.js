// noinspection JSUnresolvedFunction,JSJQueryEfficiency,JSUnresolvedVariable,JSCheckFunctionSignatures

/**
 * Вызов ajax окна
 *
 обязаьельно элемент должен обладать селектором .ajax-submit
 'data' => [
 'pjax' => 0|1,
 'pjax-id' => 'pjax-id-container',
 ['confirm-message' => 'Подтвержедение',]
 'href' => Url::to([ajax-url-path]),
 ],

 'data' => [
 'pjax' => 0,
 'pjax-id' => 'pjax-id-container',
 'toggle' => 'modal',
 'target' => '#grid-modal',
 'href' => Url::to([ajax-url-path]),
 ],
 */
!function ($) {
    $(function () {

        $.fn.modal.Constructor.prototype.enforceFocus = function () {
        };

        $("#grid-modal")
            .on("show.bs.modal", function (e) {

                const link = $(e.relatedTarget),
                    el = $(this);

                if (link.data('target') === '#grid-modal') {

                    el.find("h3.modal-title").text(link.data('title'));

                    el.find(".modal-body").html('').load(link.data("href"), function () {

                        el.find(".modal-body form").attr('data-pjax-container', link.data('pjax-id'));
                    });
                }
            })
            .on('submit', 'form', function (e) {

                const form = $(this),
                    pjaxContainer = form.data('pjax-container'),
                    formData = new FormData(form[0]);

                if (form.attr('method') !== 'post') {

                    return true;
                }

                e.preventDefault();

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    'success': function (res) {

                        if (res.success) {

                            $('#grid-modal').modal("toggle");

                            if (krajeeDialog) {
                                krajeeDialog.alert("Данные успешно сохранены!");
                            }

                            if (pjaxContainer) {

                                if (res.url) {

                                    $.pjax({url: res.url, container: '#' + pjaxContainer});

                                } else {

                                    $.pjax.reload({container: "#" + pjaxContainer});
                                }

                            } else {

                                if (res.url) {

                                    location.href = res.url;

                                } else {

                                    location.reload();
                                }
                            }

                        } else {

                            $('#grid-modal').find(".modal-body").html(res);
                            $('#grid-modal').find(".modal-body form").attr('data-pjax-container', pjaxContainer);
                        }
                    },
                    'error': function () {

                        console.log('internal server error');
                    }
                });

                return false;
            })
            .on('click', 'a.cancel-button', function (e) {

                e.preventDefault();
                $('#grid-modal').modal("toggle");
            });

        $('body').on("hidden.bs.modal", ".modal", function () { //fire on closing modal box

            if ($('.modal:visible').length) { // check whether parent modal is opend after child modal close
                $('body').addClass('modal-open'); // if open mean length is 1 then add a bootstrap css class to body of the page
            }
        });

        function ajaxSubmit(href, pjaxContainer) {
            $.ajax({
                url: href,
                type: 'post',
                'success': function (res) {

                    console.log(res);

                    if (res.success) {

                        if (pjaxContainer) {

                            if (res.url) {

                                $.pjax({url: res.url, container: '#' + pjaxContainer});

                            } else {

                                $.pjax.reload({container: "#" + pjaxContainer});
                            }
                        } else {

                            if (res.url) {

                                location.href = res.url;

                            } else {

                                location.reload();
                            }
                        }
                    } else {

                        if (res.message) {

                            if (krajeeDialog) {
                                krajeeDialog.alert(res.message);
                            } else {

                                alert(res.message);
                            }
                        }

                    }
                }
            });
        }

        $('body').on('click', 'a.ajax-submit', function (e) {

            e.preventDefault();

            const href = $(this).data('href'),
                confirm = $(this).data('confirm-message'),
                pjaxContainer = $(this).data('pjax-id');

            if (confirm && krajeeDialog) {
                krajeeDialog.confirm(confirm, function (result) {

                    if (result) {

                        ajaxSubmit(href, pjaxContainer);
                    }
                });
            } else {

                ajaxSubmit(href, pjaxContainer);
            }
        })
    });
}(window.jQuery)