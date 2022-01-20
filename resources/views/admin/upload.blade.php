<template id="app-upload">
    <div class="app-upload" @click="handleClick">
        <slot></slot>
        <input ref="input" type="file" :accept="accept" :multiple="multiple" style="display: none"
               @change="handleChange">
    </div>
</template>
<style scoped>
    .app-upload {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<script>
    const UPLOAD_FILE_URL = '{{ route("api.attachment.upload_file") }}';
    Vue.component('app-upload', {
        template: '#app-upload',
        props: {
            disabled: Boolean,
            multiple: Boolean,
            max: Number,
            accept: String,
            params: Object,
            fields: Object,
        },
        data() {
            return {
                dialogVisible: false,
                loading: true,
                attachments: [],
                checkedAttachments: [],
                files: [],
            };
        },
        created() {
        },
        methods: {
            handleClick() {
                if (this.disabled) {
                    return;
                }
                this.$refs.input.value = null;
                this.$refs.input.click();
            },
            handleChange(e) {
                if (!e.target.files) return;
                this.uploadFiles(e.target.files);
            },
            uploadFiles(rawFiles) {
                if (this.max && rawFiles.length > this.max) {
                    this.$message.error('最多一次只能上传' + this.max + '个文件。')
                    return;
                }
                this.files = [];
                for (let i = 0; i < rawFiles.length; i++) {
                    const file = {
                        _complete: false,
                        response: null,
                        rawFile: rawFiles[i],
                    };
                    this.files.push(file);
                }
                this.$emit('start', this.files);
                for (let i in this.files) {
                    this.upload(this.files[i]);
                }
            },
            upload(file) {
                let formData = new FormData();
                for (let i in this.fields) {
                    formData.append(i, this.fields[i]);
                }
                formData.append('file', file.rawFile, file.rawFile.name);
                this.$request({
                    headers: {'Content-Type': 'multipart/form-data'},
                    url: UPLOAD_FILE_URL,
                    method: 'post',
                    params: this.params,
                    data: formData,
                }).then(e => {
                    console.log('eeeeeeeeeeeee',e)
                    if (e.data.code !== 200) {
                        this.$message.error(e.data.msg);
                    }
                    file.response = e;
                    file._complete = true;
                    this.onSuccess(file);
                    console.log(file)
                }).catch(e => {
                    file._complete = true;
                });
            },
            onSuccess(file) {
                this.$emit('success', file);
                let allComplete = true;
                for (let i in this.files) {
                    if (!this.files[i]._complete) {
                        allComplete = false;
                        break;
                    }
                }
                if (allComplete) {
                    this.$emit('complete', this.files);
                }
            },
        },
    });
</script>
