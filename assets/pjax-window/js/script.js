// noinspection JSUnresolvedFunction,JSJQueryEfficiency,JSUnresolvedVariable,JSCheckFunctionSignatures

/**
 * Вызов ajax окна
 *
 обязательно элемент должен обладать селектором .ajax-submit
 'data' => [
 'pjax' => 0|1,
 'pjax-id' => 'pjax-id-container',
 ['confirm-message' => 'Подтвержедение',]
 'href' => Url::to([ajax-url-path]),
 ],

 'data' => [
 'pjax' => 0,
 'pjax-id' => 'pjax-id-container',
 'bs-toggle' => 'modal',
 'bs-target' => '#grid-modal',
 'href' => Url::to([ajax-url-path]),
 ],
 */
!function ($) {
    $(function () {

        document.getElementById('grid-modal')?.addEventListener('show.bs.modal', function (e) {

            const link = $(e.relatedTarget),
                el = $(this);

            if (link.data('bs-target') === '#grid-modal') {

                el.find("h3.modal-title").text(link.data('title'));

                el.find(".modal-body").html('').load(link.data("href"), function () {

                    el.find(".modal-body form").attr('data-pjax-container', link.data('pjax-id'));
                });
            }
        });

        $(document).on('submit', '#grid-modal form', function (e) {

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

                        const modal = bootstrap.Modal.getInstance(document.getElementById('grid-modal'));
                        if (modal) modal.toggle();

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
        });

        $(document).on('click', '#grid-modal a.cancel-button', function (e) {

            e.preventDefault();
            const modal = bootstrap.Modal.getInstance(document.getElementById('grid-modal'));
            if (modal) modal.toggle();
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