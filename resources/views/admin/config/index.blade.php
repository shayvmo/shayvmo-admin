@extends('admin.layouts.app')

@section('style')
    <style>
        .tips {
            color: #b1b1b1;
        }
    </style>
@endsection

@section('content')
    <div id="app">
        <div class="box-card margin-top-bottom-10">
            <el-card class="box-card">
                配置项管理
            </el-card>
        </div>

        <div class="box-card margin-top-bottom-10">
            <el-card class="box-card">
                <el-tabs v-model="activeName">
                    <el-tab-pane v-for="group in groups" :key="group.id" :label="group.name" :name="group.group_key">
                        <el-form :ref="group.group_key" :label-width="labelWidth">
                            <el-form-item v-for="config in group.configs" :key="config.id" :label="config.label">
                                <el-input v-if="config.type === 'text'" v-model="config.val"></el-input>
                                <el-input v-if="config.type === 'textarea'" type="textarea" v-model="config.val"></el-input>
                                <el-radio-group v-if="config.type === 'switch'" v-model="config.val">
                                    <el-radio :label="1">是</el-radio>
                                    <el-radio :label="0">否</el-radio>
                                </el-radio-group>
                                <span class="tips">@{{ config.tips }}</span>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" :loading="saveLoading" @click="handleEdit(group.group_key)">保存</el-button>
                                <el-button @click="dialogShow(group.id)">新增</el-button>
                            </el-form-item>
                        </el-form>
                    </el-tab-pane>
                </el-tabs>
            </el-card>
        </div>

        <el-dialog title="新增配置项" :visible.sync="dialogFormVisible" :before-close="handleCloseDialog">
            <el-form ref="formDialog" :model="formData" :rules="rules">
                <el-form-item label="类型" :label-width="formLabelWidth" required prop="type">
                    <el-select v-model="formData.type" placeholder="请选择">
                        <el-option
                            v-for="type in configType"
                            :key="type.id"
                            :label="type.name"
                            :value="type.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="名称" :label-width="formLabelWidth" prop="label">
                    <el-input v-model="formData.label" name="label" autocomplete="off" />
                </el-form-item>
                <el-form-item label="key" :label-width="formLabelWidth" prop="key">
                    <el-input v-model="formData.key" name="key" autocomplete="off" />
                </el-form-item>
                <el-form-item label="配置文件key" :label-width="formLabelWidth" prop="config_file_key">
                    <el-input v-model="formData.config_file_key" name="config_file_key" autocomplete="off" />
                </el-form-item>
                <el-form-item label="值" :label-width="formLabelWidth" prop="val">
                    <el-input v-model="formData.val" name="val" autocomplete="off" />
                </el-form-item>
                <el-form-item label="提示" :label-width="formLabelWidth" prop="tips">
                    <el-input v-model="formData.tips" name="tips" autocomplete="off" />
                </el-form-item>
                <el-form-item label="排序" :label-width="formLabelWidth" prop="sort">
                    <el-input v-model="formData.sort" name="sort" autocomplete="off" />
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="handleCloseDialog">取 消</el-button>
                <el-button type="primary" :loading="formSubmitLoading" @click="handleAdd">确 定</el-button>
            </div>
        </el-dialog>
    </div>
@endsection

@section('script')
    <script>
        const DATA_URL = '{{ route('admin.config.data') }}';
        const CREATE_URL = '{{ route('admin.config.store') }}';
        const EDIT_URL = '{{ route('admin.config.update')}}';

        const defaultData = {
            group_id: '',
            label: '',
            key: '',
            config_file_key: '',
            val: '',
            type: '',
            tips: '',
            sort: 100,
        }

        const app = new Vue({
            el: '#app',
            data: {
                activeName: '',
                labelWidth: '120px',
                saveLoading: false,
                groups: [],

                configType: [
                    {
                        id: 2,
                        name: '文本',
                    },
                    {
                        id: 3,
                        name: '多行文本',
                    },
                    {
                        id: 1,
                        name: '开关',
                    },
                ],
                dialogFormVisible: false,
                formData: defaultData,
                formLabelWidth: '120px',
                rules: {
                    label: [
                        { required: true, message: '请输入名称', trigger: 'blur' },
                        { min: 1, max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
                    ],
                    key: [
                        { required: true, message: '请输入key值', trigger: 'blur' },
                        { min: 1, max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
                    ],
                    val: [
                        { required: true, message: '请输入value值', trigger: 'blur' },
                    ],
                    type: [
                        { required: true, message: '请选择类型', trigger: 'blur' },
                    ]
                },
                formSubmitLoading: false
            },
            created() {
                this.getData()
            },
            methods: {
                getData() {
                    myRequest({
                        url: DATA_URL,
                        type: "get",
                    }).then(response => {
                        if (response.code !== 200) {
                            this.$message.error(response.msg)
                            return;
                        }
                        this.groups = response.data.groups
                        if (this.activeName === '' || this.activeName === '0') {
                            this.activeName = this.groups.length > 0 ? this.groups[0].group_key : ''
                        }
                    }).catch(()=>{
                    })
                },
                handleEdit(group_key) {
                    // 拿到当前激活的配置页
                    var data = this.groups.filter(function (item) {
                        return item.group_key === group_key
                    })[0] || {}
                    var that = this
                    that.saveLoading = true
                    myRequest({
                        url: EDIT_URL,
                        type: "PUT",
                        data: {
                            data: data.configs
                        }
                    }).then(response => {
                        that.saveLoading = false
                        if (response.code !== 200) {
                            that.$message.error(response.msg)
                            return;
                        }
                        that.$message({
                            type: 'success',
                            message: response.msg,
                        })
                    }).catch(()=>{
                        that.saveLoading = false
                    })
                },

                dialogShow(group_id) {
                    this.formData = Object.assign({}, defaultData);
                    this.formData.group_id = group_id
                    this.dialogFormVisible = true;
                },
                handleAdd() {
                    this.formSubmitLoading = true
                    let that = this
                    myRequest({
                        url: CREATE_URL,
                        type: "post",
                        data: this.formData
                    }).then(response => {
                        that.formSubmitLoading = false
                        if (response.code !== 200) {
                            that.$message.error(response.msg)
                            return;
                        }
                        that.$message({
                            type: 'success',
                            message: response.msg,
                            onClose: function () {
                                that.dialogFormVisible = false;
                                that.getData()
                            },
                            duration: 500
                        })
                    }).catch(()=>{
                        that.formSubmitLoading = false
                    })
                },
                // 关闭对话框
                handleCloseDialog() {
                    this.dialogFormVisible = false
                },

            },
        })

    </script>
@endsection


