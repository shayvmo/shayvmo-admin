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
        <van-search
            v-model="keyword"
            shape="round"
            placeholder="请输入搜索关键词"
            @search="onSearch"
        ></van-search>
        <van-tabs
            :sticky="sticky"
            :before-change="beforeChange"
        >
            <van-tab v-for="cate in categories" :title="cate.title">
                <div class="swipe-div">
                    <van-swipe :autoplay="2000">
                        <van-swipe-item v-for="(image, index) in images" :key="index">
                            <img v-lazy="image" />
                        </van-swipe-item>
                    </van-swipe>
                </div>

            </van-tab>
        </van-tabs>
        【 @{{ nowTime }} 】
        <br>
        生活就像海洋，只有意志坚强的人才能到达彼岸。
    </div>
@endsection

@section('script')
    <script>
        const app = new Vue({
            el: '#app',
            data: {
                nowTime: '',
                keyword: '',
                sticky: true,
                images: [
                    'https://img01.yzcdn.cn/vant/apple-1.jpg',
                    'https://img01.yzcdn.cn/vant/apple-2.jpg',
                ],
                categories: [
                    {
                        id: 0,
                        title: '全部',
                    },
                    {
                        id: 1,
                        title: '自我',
                    },
                    {
                        id: 2,
                        title: '创业',
                    },
                    {
                        id: 3,
                        title: '经济',
                    },
                ],
                articles: [
                    {
                        title: '标题',
                    },
                ],
            },
            created() {
                // vant.Toast.success('成功文案');
                // vant.Dialog.alert({
                //     message: '生活就像海洋，只有意志坚强的人才能到达彼岸。',
                // }).then(() => {
                //     // on close
                // });
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
                onSearch() {
                    vant.Toast.success(this.keyword)
                },
                beforeChange(index) {
                    console.log(this.categories[index])
                    // 返回 Promise 来执行异步逻辑
                    return new Promise((resolve) => {
                        // 在 resolve 函数中返回 true 或 false
                        resolve(true);
                    });
                }
            },
        })

        Vue.use(vant.Lazyload);

    </script>
@endsection
