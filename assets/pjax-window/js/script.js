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

    function execScripts(root) {
        const scripts = Array.prototype.slice.call(root.querySelectorAll('script'));
        let i = 0;
        return new Promise(function (resolve) {
            (function next() {
                if (i >= scripts.length) { resolve(); return; }
                const oldScript = scripts[i++];
                const newScript = document.createElement('script');
                Array.prototype.forEach.call(oldScript.attributes, function (attr) {
                    try { newScript.setAttribute(attr.name, attr.value); } catch (e) {}
                });
                newScript.textContent = oldScript.textContent || '';
                oldScript.parentNode.replaceChild(newScript, oldScript);
                if (newScript.src) {
                    newScript.onload = function () { next(); };
                    newScript.onerror = function () { next(); };
                } else {
                    next();
                }
            })();
        });
    }

    function prepareModalHtml(html) {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const existing = {};
        Array.prototype.forEach.call(document.querySelectorAll('link[rel="stylesheet"]'), function (link) {
            if (link.href) existing[link.href] = true;
        });
        Array.prototype.forEach.call(doc.querySelectorAll('link[rel="stylesheet"]'), function (link) {
            if (!link.href) {
                link.parentNode && link.parentNode.removeChild(link);
                return;
            }
            if (existing[link.href]) {
                link.parentNode && link.parentNode.removeChild(link);
                return;
            }
            existing[link.href] = true;
            const copy = document.createElement('link');
            copy.rel = 'stylesheet';
            copy.href = link.href;
            document.head.appendChild(copy);
            link.parentNode && link.parentNode.removeChild(link);
        });
        return doc.documentElement.outerHTML;
    }

    function getModal() {
        return bootstrap.Modal.getOrCreateInstance(document.getElementById('grid-modal'));
    }

    function getModalBody() {
        let el = document.getElementById('grid-modal');
        return el && el.querySelector('.modal-body');
    }

    function pjax(url, containerId, replace) {
        let container = containerId && document.querySelector('#' + containerId);
        if (!container) { location.href = url; return; }
        fetch(url)
            .then(function (r) { return r.text(); })
            .then(function (html) {
                if (replace) {
                    let doc = new DOMParser().parseFromString(html, 'text/html');
                    let newContent = doc.querySelector('#' + containerId);
                    if (newContent) { container.replaceWith(newContent); return; }
                } else {
                    container.innerHTML = html;
                    return;
                }
                location.reload();
            })
            .catch(function () { location.href = url; });
    }

    function dialog(msg, cb) {
        if (typeof krajeeDialog !== 'undefined') {
            krajeeDialog.confirm(msg, cb);
        } else if (confirm(msg)) cb(true);
    }

    function navigate(data, pjaxContainer) {
        let url = data.url;
        if (url) {
            location.href = url;
        } else if (pjaxContainer) {
            pjax(location.href, pjaxContainer, true);
        } else {
            location.reload();
        }
    }

    function alertSuccess() {
        if (typeof krajeeDialog !== 'undefined') {
            krajeeDialog.alert('\u0414\u0430\u043D\u043D\u044B\u0435 \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u0441\u043E\u0445\u0440\u0430\u043D\u0435\u043D\u044B!');
        }
    }

    document.addEventListener('click', function (e) {
        let btn = e.target.closest('[data-bs-modal="#grid-modal"]');
        if (!btn) return;
        e.preventDefault();

        let href = btn.dataset.href;
        let pjaxId = btn.dataset.pjaxId;
        if (!getModalBody()) return;

        if (href) {
            getModalBody().innerHTML = '';
            fetch(href)
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    getModalBody().innerHTML = prepareModalHtml(html);
                    return execScripts(getModalBody()).then(function () {
                        let form = getModalBody().querySelector('form');
                        if (form && pjaxId) form.dataset.pjaxContainer = pjaxId;
                        getModal().show();
                    });
                });
        } else {
            getModal().show();
        }
    });

    document.addEventListener('submit', function (e) {
        let form = e.target.closest('#grid-modal form');
        if (!form) return;
        e.preventDefault();
        if (form.getAttribute('method')?.toLowerCase() !== 'post') return;

        let pjaxContainer = form.dataset.pjaxContainer;
        let formData = new FormData(form);

        fetch(form.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) {
                let ct = r.headers.get('content-type') || '';
                return ct.indexOf('json') !== -1
                    ? r.json().then(function (d) { return {json: d}; })
                    : r.text().then(function (h) { return {html: h}; });
            })
            .then(function (res) {
                if (res.json && res.json.success) {
                    getModal().toggle();
                    alertSuccess();
                    navigate(res.json, pjaxContainer);
                } else {
                    let mb = getModalBody();
                    if (!mb) return;
                    mb.innerHTML = res.html || res.json;
                    execScripts(mb).then(function () {
                        let newForm = mb.querySelector('form');
                        if (newForm && pjaxContainer) newForm.dataset.pjaxContainer = pjaxContainer;
                    });
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

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function getCsrfParam() {
        const meta = document.querySelector('meta[name="csrf-param"]');
        return meta ? meta.getAttribute('content') : '_csrf';
    }

    function ajaxSubmit(href, pjaxContainer) {
        const body = new URLSearchParams();
        body.set(getCsrfParam(), getCsrfToken());
        fetch(href, { method: 'POST', body: body, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success) { navigate(res, pjaxContainer); }
                else if (res.message) { dialog(res.message, function () {}); }
            });
    }

    document.addEventListener('click', function (e) {
        let link = e.target.closest('a.ajax-submit');
        if (!link) return;
        e.preventDefault();

        let href = link.dataset.href;
        let msg = link.dataset.confirmMessage;
        let pjaxContainer = link.dataset.pjaxId;

        if (msg && typeof krajeeDialog !== 'undefined') {
            krajeeDialog.confirm(msg, function (result) {
                if (result) ajaxSubmit(href, pjaxContainer);
            });
        } else {
            ajaxSubmit(href, pjaxContainer);
        }
    });

})();
