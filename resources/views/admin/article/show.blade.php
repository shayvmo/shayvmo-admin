@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="/static/unpkg/editor-md/css/editormd.preview.css" />
    <style>

    </style>
@endsection

@section('content')
    <div id="app">

        <div class="box-card margin-top-bottom-10">
            <div style="height: 600px">
{{--                <div id="test-editor">--}}
{{--                    <textarea style="display:none;">{{$markdown}}</textarea>--}}
{{--                </div>--}}
                {!! $content !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="/static/unpkg/editor-md/lib/marked.min.js"></script>
    <script src="/static/unpkg/editor-md/lib/prettify.min.js"></script>
    <script src="/static/unpkg/editor-md/lib/raphael.min.js"></script>
    <script src="/static/unpkg/editor-md/lib/underscore.min.js"></script>
    <script src="/static/unpkg/editor-md/lib/sequence-diagram.min.js"></script>
    <script src="/static/unpkg/editor-md/lib/flowchart.min.js"></script>
    <script src="/static/unpkg/editor-md/lib/jquery.flowchart.min.js"></script>
    <script src="/static/unpkg/editor-md/editormd.js"></script>
    <script type="text/javascript">
        $(function() {
            var testEditormdView2;
            testEditormdView2 = editormd.markdownToHTML("test-editor", {
                htmlDecode      : "style,script,iframe",  // you can filter tags decode
                emoji           : true,
                taskList        : true,
                tex             : true,  // 默认不解析
                flowChart       : true,  // 默认不解析
                sequenceDiagram : true,  // 默认不解析
            });
        });
    </script>
@endsection


