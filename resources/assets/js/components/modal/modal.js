
Vue.component('modal', {
    template: '<transition name="modal">\n' +
    '        <div class="modal-mask">\n' +
    '            <div class="modal-wrapper" @click="$emit(\'close\')">\n' +
    '                <div class="modal-container" @click.stop>\n' +
    '\n' +
    '                    <div class="modal-header">\n' +
    '                        <slot name="header">\n' +
    '                            default header\n' +
    '                        </slot>\n' +
    '                    </div>\n' +
    '\n' +
    '                    <div class="modal-body">\n' +
    '                        <slot name="body">\n' +
    '                            default body\n' +
    '                        </slot>\n' +
    '                    </div>\n' +
    '\n' +
    '                    <div class="modal-footer">\n' +
    '                        <slot name="footer">\n' +
    '                            default footer\n' +
    '                            <button class="modal-default-button" @click="$emit(\'close\')">\n' +
    '                                OK\n' +
    '                            </button>\n' +
    '                        </slot>\n' +
    '                    </div>\n' +
    '                </div>\n' +
    '            </div>\n' +
    '        </div>\n' +
    '    </transition>',

    created: function created() {
        $('.wrap-modal').show();
    }
});