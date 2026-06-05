<?php
/** Local CKEditor — same bundle as Grocery CRUD (settings → test_sections description). */
$ckeditor_base = base_url('assets/grocery_crud/texteditor/ckeditor/');
?>
<script src="<?php echo $ckeditor_base; ?>ckeditor.js"></script>
<script src="<?php echo $ckeditor_base; ?>adapters/jquery.js"></script>
<script>
(function(window, $) {
    'use strict';

    window.destroyPanelSectionEditors = function() {
        if (typeof window.CKEDITOR === 'undefined') {
            return;
        }
        $('#test_configuration').find('textarea.panel-section-desc').each(function() {
            var id = this.id;
            if (id && window.CKEDITOR.instances[id]) {
                window.CKEDITOR.instances[id].destroy(true);
            }
            $(this).removeData('ckeditor-init');
        });
    };

    window.initPanelSectionEditors = function() {
        if (typeof window.CKEDITOR === 'undefined' || !$.fn.ckeditor) {
            return;
        }

        // Brief delay so AJAX-injected markup is painted before replace().
        window.setTimeout(function() {
            $('#test_configuration').find('textarea.panel-section-desc').each(function() {
                var $ta = $(this);
                if ($ta.data('ckeditor-init')) {
                    return;
                }
                if (!this.id) {
                    this.id = 'panel_section_desc_' + ($ta.attr('data-section-id') || $.now());
                }
                var id = this.id;
                if (window.CKEDITOR.instances[id]) {
                    window.CKEDITOR.instances[id].destroy(true);
                }
                $ta.ckeditor({
                    toolbar: 'Full',
                    height: 160,
                    removePlugins: 'elementspath',
                    resize_enabled: true
                });
                $ta.data('ckeditor-init', 1);
            });
        }, 50);
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
})(window, jQuery);
</script>
