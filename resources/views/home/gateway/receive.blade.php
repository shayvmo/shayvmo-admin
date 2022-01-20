@extends('home.layout.app')

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
        【 @{{ nowTime }} 】
        <br>
        生活就像海洋，只有意志坚强的人才能到达彼岸。
        <van-list
        >
            <van-cell v-for="item,index in list" :key="index" :title="item.message" />
        </van-list>
    </div>
@endsection

@section('script')
    <script>
        const app = new Vue({
            el: '#app',
            data: {
                nowTime: '',
                list: [
                    {
                        message: '初始化连接...'
                    }
                ],
            },
            created() {
                // vant.Toast.success('成功文案');
                // vant.Dialog.alert({
                //     message: '生活就像海洋，只有意志坚强的人才能到达彼岸。',
                // }).then(() => {
                //     // on close
                // });
                var ws = new WebSocket("ws://127.0.0.1:7272")
                ws.onmessage = this.onMessage
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
                onMessage(e) {
                    var data = JSON.parse(e.data);
                    var type = data.type || '';
                    switch(type){
                        case 'init':
                            this.$request({
                                url: '{{route('gateway.bind')}}',
                                method: 'POST',
                                data: {
                                    client_id: data.client_id
                                }
                            }).then(res => {
                                console.log('绑定结果', res.data)
                                this.list.push({
                                    message: '初始化完成！'
                                })
                            })
                            break;
                        default :
                            this.list.push(data)
                            console.log(data);
                    }
                }
            },
        })

        Vue.use(vant.Lazyload);

    </script>
@endsection


