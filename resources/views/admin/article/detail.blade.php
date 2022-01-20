@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="/static/unpkg/editor-md/css/editormd.css">
    <style>

    </style>
@endsection

@section('content')
    <div id="app">

        <div class="box-card margin-top-bottom-10">
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span>文章详情</span>
                    <div style="float: right">
                        <el-button type="primary" size="small" @click="handleSubmit">保存</el-button>
                        <el-button size="small" @click="backToList()">返回</el-button>
                    </div>
                </div>
                <div style="height: 600px">
                    <div id="test-editor">
                        <textarea style="display:none;">
                        </textarea>
                    </div>
                </div>
            </el-card>
        </div>
    </div>
@endsection

@section('script')
    <script src="/static/unpkg/editor-md/editormd.min.js"></script>
    <script>

        const CREATE_URL = '{{ route('article.store') }}';

        const app = new Vue({
            el: '#app',
            data: {
                formData: {
                    markdown: ''
                }
            },
            created() {

            },
            methods: {
                backToList() {

                },
                handleSubmit() {
                    console.log(editor.getMarkdown())
                    this.formData.markdown = editor.getMarkdown()
                    let that = this
                    myRequest({
                        url: CREATE_URL,
                        type: "post",
                        data: this.formData
                    }).then(response => {
                        if (response.code !== 200) {
                            that.$message.error(response.msg)
                            return;
                        }
                        that.$message({
                            type: 'success',
                            message: response.msg,
                            onClose: function () {

                            },
                            duration: 500
                        })
                    }).catch(()=>{

                    })
                }
            },
        })

        var editor = editormd("test-editor", {
            // width  : "100%",
            // height : "100%",
            path   : "/static/unpkg/editor-md/lib/",
            emoji           : true,
            searchReplace : true,
            tocm            : true,
            taskList        : true,
            tex             : true,  // 默认不解析
            flowChart       : true,  // 默认不解析
            sequenceDiagram : true,  // 默认不解析
        });

    </script>
@endsection


