@extends('admin.layouts.app')

@section('style')
    <style>

    </style>
@endsection

@section('content')
    <div id="app">
        <el-card class="box-card">
            【 @{{ nowTime }} 】 生活就像海洋，只有意志坚强的人才能到达彼岸。
        </el-card>
        <el-row :gutter="20" class="margin-top-bottom-10">
            <el-col :span="12">
                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>更新动态</span>
                    </div>
                    <el-timeline>
                        <el-timeline-item timestamp="2022-02-25" placement="top">
                            <el-card>
                                <h4>删除生成头像composer包；去掉消息组件等</h4>
                            </el-card>
                        </el-timeline-item>
                        <el-timeline-item timestamp="2022-01-25" placement="top">
                            <el-card>
                                <h4>仪表盘更新, 添加更新日志以及仓库地址</h4>
                            </el-card>
                        </el-timeline-item>
                    </el-timeline>
                </el-card>
            </el-col>
            <el-col :span="12">
                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>链接</span>
                    </div>
                    <el-row>
                        <el-link href="https://gitee.com/shayvmo/shayvmo-admin" target="_blank">Gitee 仓库：https://gitee.com/shayvmo/shayvmo-admin</el-link>
                    </el-row>
                    <el-row>
                        <el-link href="https://github.com/shayvmo/shayvmo-admin" target="_blank">Github 仓库： https://github.com/shayvmo/shayvmo-admin</el-link>
                    </el-row>
                </el-card>
            </el-col>
        </el-row>
    </div>
@endsection

@section('script')
    <script>
        const app = new Vue({
            el: '#app',
            data: {
                nowTime: ''
            },
            created() {

            },
            mounted() {
                const _this = this
                this.timeId = setInterval(function() {
                    _this.timeFormat(new Date())
                }, 10)
            },
            beforeDestroy: function() {
                if (this.timeId) {
                    clearInterval(this.timeId)
                }
            },
            methods: {
                timeFormat(timeStamp) {
                    const year = new Date(timeStamp).getFullYear()
                    const month =
                        new Date(timeStamp).getMonth() + 1 < 10
                            ? '0' + (new Date(timeStamp).getMonth() + 1)
                            : new Date(timeStamp).getMonth() + 1
                    const date =
                        new Date(timeStamp).getDate() < 10
                            ? '0' + new Date(timeStamp).getDate()
                            : new Date(timeStamp).getDate()
                    const hh =
                        new Date(timeStamp).getHours() < 10
                            ? '0' + new Date(timeStamp).getHours()
                            : new Date(timeStamp).getHours()
                    const mm =
                        new Date(timeStamp).getMinutes() < 10
                            ? '0' + new Date(timeStamp).getMinutes()
                            : new Date(timeStamp).getMinutes()
                    const ss =
                        new Date(timeStamp).getSeconds() < 10
                            ? '0' + new Date(timeStamp).getSeconds()
                            : new Date(timeStamp).getSeconds()
                    const week = new Date(timeStamp).getDay()
                    const weeks = ['日', '一', '二', '三', '四', '五', '六']
                    const getWeek = '星期' + weeks[week]
                    this.nowTime = year + '年' + month + '月' + date + '日 ' + hh + ':' + mm + ':' + ss + ' ' + getWeek
                }
            },
        })

    </script>
@endsection


