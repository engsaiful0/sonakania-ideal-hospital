<script>
(function(window) {
    'use strict';

    var CKEDITOR_CDN = 'https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js';

    function loadScriptOnce(src, cb) {
        var existing = document.querySelector('script[data-panel-ckeditor="1"]');
        if (existing) {
            if (typeof cb === 'function') {
                if (typeof window.CKEDITOR !== 'undefined') {
                    cb();
                } else {
                    existing.addEventListener('load', cb, { once: true });
                }
            }
            return;
        }
        var s = document.createElement('script');
        s.src = src;
        s.setAttribute('data-panel-ckeditor', '1');
        s.onload = function() {
            if (typeof cb === 'function') {
                cb();
            }
        };
        document.head.appendChild(s);
    }

    function editorContainer() {
        return document.getElementById('test_configuration') || document.body;
    }

    window.destroyPanelSectionEditors = function() {
        if (typeof window.CKEDITOR === 'undefined') {
            return;
        }
        var container = editorContainer();
        var nodes = container.querySelectorAll('.panel-section-desc');
        for (var i = 0; i < nodes.length; i++) {
            var id = nodes[i].id;
            if (id && window.CKEDITOR.instances[id]) {
                window.CKEDITOR.instances[id].destroy(true);
            }
        }
    };

    window.initPanelSectionEditors = function() {
        loadScriptOnce(CKEDITOR_CDN, function() {
            var container = editorContainer();
            var nodes = container.querySelectorAll('.panel-section-desc');
            for (var i = 0; i < nodes.length; i++) {
                var ta = nodes[i];
                if (!ta.id) {
                    var sid = ta.getAttribute('data-section-id') || i;
                    ta.id = 'panel_section_desc_' + sid;
                }
                if (window.CKEDITOR.instances[ta.id]) {
                    continue;
                }
                window.CKEDITOR.replace(ta.id, {
                    height: 140,
                    removePlugins: 'elementspath',
                    resize_enabled: true,
                    toolbar: [
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                        { name: 'links', items: ['Link', 'Unlink'] },
                        { name: 'insert', items: ['Table', 'HorizontalRule'] },
                        { name: 'styles', items: ['Format'] },
                        { name: 'tools', items: ['Maximize', 'Source'] }
                    ]
                });
            }
        });
    };

    window.syncPanelSectionEditors = function() {
        if (typeof window.CKEDITOR === 'undefined') {
            return;
        }
        for (var name in window.CKEDITOR.instances) {
            if (!window.CKEDITOR.instances.hasOwnProperty(name)) {
                continue;
            }
            window.CKEDITOR.instances[name].updateElement();
        }
    };
})(window);
</script>
