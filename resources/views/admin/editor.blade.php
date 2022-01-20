<template id="wang-editor">
    <div>
        <div id="div1"></div>
    </div>
</template>
<script type="text/javascript" src="/static/unpkg/wangEditor/wangEditor.min.js"></script>
<script>
    const UPLOAD_URL = '{{ route("api.attachment.upload_file") }}';
    Vue.component('wang-editor', {
        template: '#wang-editor',
        props: {
            value: null
        },
        data: function () {
            return {
                tempContent:this.value,
                wangEditor: null,
                isChange: false,
            }
        },
        watch: {
            tempContent: function (newVal, oldVal) {
                if (!this.isChange && newVal) {
                    if (this.wangEditor) {
                        this.wangEditor.txt.html(newVal)
                    } else {
                        this.tempContent = newVal;
                    }
                }
                if (this.isChange) {
                    this.isChange = false
                }
            }
        },
        mounted() {
            this.loadEditor()
        },
        methods: {
            loadEditor() {
                const that = this
                const E = window.wangEditor
                const editor = new E("#div1")
                editor.config.onchange = function (newHtml) {
                    this.isChange = true;
                    that.changeContent(newHtml)
                }
                editor.config.onblur = function (newHtml) {
                    this.isChange = true;
                    that.changeContent(newHtml)
                }
                editor.config.onfocus = function (newHtml) {
                    this.isChange = true;
                    that.changeContent(newHtml)
                }
                editor.config.zIndex = 1
                editor.config.onchangeTimeout = 500; // 修改为 500ms
                editor.config.uploadImgMaxLength = 5
                editor.config.uploadImgServer = UPLOAD_URL
                editor.config.uploadFileName = 'file'
                editor.config.uploadImgParams = {
                    from: 'wangEditor',
                }
                editor.create()
                editor.txt.html(this.tempContent)
                that.wangEditor = editor
            },
            changeContent(newHtml) {
                this.$emit('input', newHtml)
            }
        }
    })
</script>
