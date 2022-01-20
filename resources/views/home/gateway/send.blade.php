@extends('admin.layouts.app')

@section('style')
    <style>
        .van-swipe-item img {
            height: 100%;
            width: 100%;
            border-radius: 5%;

        }

        .swipe-div {
            width: 95%;
            margin: 10px;
        }
    </style>
@endsection

@section('content')
    <div id="app">
        <el-card class="margin-top-bottom-10">
            【 @{{ nowTime }} 】 生活就像海洋，只有意志坚强的人才能到达彼岸。
        </el-card>

        <el-card class="margin-top-bottom-10">
            <div slot="header">
                <span>【发送方】</span>
            </div>
            <el-form :model="form" label-width="80px">
                <el-form-item label="消息">
                    <el-input type="textarea" v-model="form.content"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="onSubmit">发送</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
@endsection

@section('script')
    <script>
        const app = new Vue({
            el: '#app',
            data: {
                nowTime: '',
                form: {
                    content: ''
                }
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
                },
                onSubmit() {
                    this.$request({
                        url: '{{route('gateway.send')}}',
                        method: 'post',
                        data: this.form
                    }).then(res => {
                        this.form.content = ''
                        console.log(res.data)
                    })
                }
            },
        })

    </script>
@endsection


