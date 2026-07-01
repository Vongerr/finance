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

(function () {

    console.log(321314);

    function getModal() {
        return bootstrap.Modal.getOrCreateInstance(document.getElementById('grid-modal'));
    }

    function findModal() {
        return document.getElementById('grid-modal');
    }

    function pjaxReload(containerId) {
        let container = document.querySelector('#' + containerId);
        if (!container) { location.reload(); return; }
        fetch(location.href)
            .then(function (r) { return r.text(); })
            .then(function (html) {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                let newContent = doc.querySelector('#' + containerId);
                if (newContent) {
                    container.replaceWith(newContent);
                } else {
                    location.reload();
                }
            })
            .catch(function () { location.reload(); });
    }

    function pjaxLoad(url, containerId) {
        let container = document.querySelector('#' + containerId);
        if (!container) { location.href = url; return; }
        fetch(url)
            .then(function (r) { return r.text(); })
            .then(function (html) {
                container.innerHTML = html;
            })
            .catch(function () { location.href = url; });
    }

    document.addEventListener('click', function (e) {

        let btn = e.target.closest('[data-bs-modal="#grid-modal"]');

        if (!btn) return;
        e.preventDefault();

        let href = btn.dataset.href;
        let pjaxId = btn.dataset.pjaxId;

        let modalEl = findModal();
        if (!modalEl) return;


        let body = modalEl.querySelector('.modal-body');
        if (href) {
            body.innerHTML = '';
            fetch(href)
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    body.innerHTML = html;
                    let form = body.querySelector('form');
                    if (form && pjaxId) {
                        form.dataset.pjaxContainer = pjaxId;
                    }
                    getModal().show();
                });
        } else {
            getModal().show();
        }
    });

    document.addEventListener('submit', function (e) {
        let form = e.target.closest('#grid-modal form');
        if (!form) return;
        e.preventDefault();

        let pjaxContainer = form.dataset.pjaxContainer;
        let formData = new FormData(form);

        if (form.getAttribute('method')?.toLowerCase() !== 'post') return;

        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
            .then(function (r) {
                let ct = r.headers.get('content-type') || '';
                if (ct.indexOf('json') !== -1) {
                    return r.json().then(function (data) { return { json: data }; });
                }
                return r.text().then(function (html) { return { html: html }; });
            })
            .then(function (res) {
                if (res.json && res.json.success) {
                    getModal().toggle();

                    if (typeof krajeeDialog !== 'undefined') {
                        krajeeDialog.alert('\u0414\u0430\u043D\u043D\u044B\u0435 \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u0441\u043E\u0445\u0440\u0430\u043D\u0435\u043D\u044B!');
                    }

                    if (pjaxContainer) {
                        if (res.json.url) {
                            pjaxLoad(res.json.url, pjaxContainer);
                        } else {
                            pjaxReload(pjaxContainer);
                        }
                    } else {
                        if (res.json.url) {
                            location.href = res.json.url;
                        } else {
                            location.reload();
                        }
                    }
                } else {
                    let body = findModal().querySelector('.modal-body');
                    body.innerHTML = res.html || res.json;
                    let newForm = body.querySelector('form');
                    if (newForm && pjaxContainer) {
                        newForm.dataset.pjaxContainer = pjaxContainer;
                    }
                }
            })
            .catch(function () {
                console.log('internal server error');
            });
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('#grid-modal a.cancel-button')) {
            e.preventDefault();
            getModal().toggle();
        }
    });

    function ajaxSubmit(href, pjaxContainer) {
        fetch(href, { method: 'POST' })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                console.log(res);
                if (res.success) {
                    if (pjaxContainer) {
                        if (res.url) {
                            pjaxLoad(res.url, pjaxContainer);
                        } else {
                            pjaxReload(pjaxContainer);
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
                        if (typeof krajeeDialog !== 'undefined') {
                            krajeeDialog.alert(res.message);
                        } else {
                            alert(res.message);
                        }
                    }
                }
            });
    }

    document.addEventListener('click', function (e) {
        let link = e.target.closest('a.ajax-submit');
        if (!link) return;
        e.preventDefault();

        let href = link.dataset.href;
        let confirmMsg = link.dataset.confirmMessage;
        let pjaxContainer = link.dataset.pjaxId;

        if (confirmMsg && typeof krajeeDialog !== 'undefined') {
            krajeeDialog.confirm(confirmMsg, function (result) {
                if (result) ajaxSubmit(href, pjaxContainer);
            });
        } else {
            ajaxSubmit(href, pjaxContainer);
        }
    });

})();
