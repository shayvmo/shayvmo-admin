@extends('admin.layouts.app')

@section('style')
    <style>
        .tips {
            color: #b1b1b1;
        }
        .add-image-btn {
            width: 100px;
            height: 100px;
            color: #419EFB;
            border: 1px solid #e2e2e2;
            cursor: pointer;
            line-height: 100px;
            text-align: center;
        }
        .draggable-div {
            margin-right: 20px;
            position: relative;
            cursor: move;
            width: 100px;
            display: inline-block;
        }

        .del-btn {
            position: absolute;
            top: -10px;
            left: 85px;
        }
    </style>
@endsection



@section('content')
    <div id="app">
        <div class="box-card margin-top-bottom-10">
            <el-card class="box-card">
                Demo
            </el-card>
        </div>
        <div class="box-card margin-top-bottom-10">
            <el-card class="box-card">
                <el-form ref="formData" :model="formData" label-width="100px">
                    <el-form-item label="活动名称">
                        <el-input v-model="formData.name"></el-input>
                    </el-form-item>
                    <el-form-item label="活动轮播图">
                        <div style="display: flex">
                            <draggable v-model="formData.pic_url">
                                <div class="draggable-div" v-for="(item,index) in formData.pic_url" :key="index">
                                    <app-attachment
                                        @selected="updatePicUrl"
                                        :params="{'currentIndex': index}"
                                        :simple="simple"
                                    >
                                        <app-image mode="aspectFill" width="100px"
                                                   height='100px' :src="item.pic_url">
                                        </app-image>
                                    </app-attachment>
                                    <el-button class="del-btn" size="mini" type="danger"
                                               icon="el-icon-close" circle
                                               @click="delPic(index)"></el-button>
                                </div>
                            </draggable>
                            <app-attachment style="margin-bottom: 10px; display: inline-block" :multiple="true"
                                            :max="9" @selected="picUrl">
                                <el-tooltip class="item" effect="dark" content="建议尺寸:750 * 750"
                                            placement="top">
                                    <div flex="main:center cross:center" class="add-image-btn">
                                        + 添加图片
                                    </div>
                                </el-tooltip>
                            </app-attachment>
                        </div>
                    </el-form-item>
                    <el-form-item label="活动规则">
                        <wang-editor
                            :id="editor"
                            v-model="formData.content"
                        ></wang-editor>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onSubmit">立即创建</el-button>
                        <el-button>取消</el-button>
                    </el-form-item>
                </el-form>
            </el-card>
        </div>
        <div class="box-card margin-top-bottom-10">
            <el-card class="box-card">
                @{{ formData.content }}
            </el-card>
        </div>
    </div>
@endsection

@section('script')
    @component('admin.editor')
    @endcomponent
    @component('admin.upload')
    @endcomponent
    @component('admin.image')
    @endcomponent
    @component('admin.attachment')
    @endcomponent


    <script src="{{ asset('/js/Sortable.min.js') }}"></script>
    <script src="{{ asset('/static/unpkg/vuedraggable@2.18.1/dist/vuedraggable.umd.min.js') }}"></script>

    <script>
        const app = new Vue({
            el: '#app',
            data: {
                simple: false,
                editor: 'div1',
                formData: {
                    name: '活动名称',
                    content: '<p>组件参数传递2</p>',
                    pic_url: [
                        {
                            id: "1",
                            pic_url: 'https://fuss10.elemecdn.com/e/5d/4a731a90594a4af544c0c25941171jpeg.jpeg',
                        },
                        {
                            id: "2",
                            pic_url: 'https://cube.elemecdn.com/6/94/4d3ea53c084bad6931a56d5158a48jpeg.jpeg',
                        }
                    ]
                }
            },
            created() {

            },
            methods: {
                onSubmit() {
                    console.log('提交表单', this.formData)
                },
                // 商品轮播图
                picUrl(e) {
                    if (e.length) {
                        let self = this;
                        e.forEach(function (item, index) {
                            if (self.formData.pic_url.length >= 9) {
                                return;
                            }
                            self.formData.pic_url.push({
                                id: item.id,
                                pic_url: item.path
                            });
                        });
                    }
                },
                updatePicUrl(e, params) {
                    this.formData.pic_url[params.currentIndex].id = e[0].id;
                    this.formData.pic_url[params.currentIndex].pic_url = e[0].path;
                },
                delPic(index) {
                    this.formData.pic_url.splice(index, 1)
                },
            },
        })

    </script>
@endsection


