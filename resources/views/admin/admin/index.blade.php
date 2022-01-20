@extends('admin.layouts.app')

@section('style')
    <style>
        .color-red {
            color:red
        }
        .scope-el-select {
            width: 100%;
        }
        .role-tag {
            margin-right: 5px;
        }
    </style>
@endsection

@section('content')
    <div id="app">
        <div class="box-card margin-top-bottom-10">
            <el-card class="box-card">
                管理员列表
            </el-card>
        </div>

        <div class="box-card margin-top-bottom-10">
            <el-card class="box-card">
                <el-form :inline="true" :model="searchData" class="demo-form-inline">
                    <el-form-item label="关键词">
                        <el-input
                            placeholder="请输入ID, 账号, 呢称"
                            v-model="searchData.keyword"
                            clearable
                            @input="getList"
                        ></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="getList" >查询</el-button>
                    </el-form-item>
                </el-form>
            </el-card>
        </div>

        <div class="box-card margin-top-bottom-10">
            <el-card >
                <div slot="header" class="clearfix">
                    <span>数据列表</span>
                    <div style="float: right">
                        <el-button size="small" @click="dialogShow()">添加</el-button>
                        <el-button size="small" type="danger" @click="handleBatchDelete()">批量删除</el-button>
                    </div>
                </div>
                <el-table
                    v-loading="listLoading"
                    ref="multipleTable"
                    :data="tableData"
                    tooltip-effect="dark"
                    style="width: 100%"
                    @selection-change="handleSelectionChange">
                    <el-table-column
                        type="selection"
                        width="55">
                    </el-table-column>
                    <el-table-column
                        prop="id"
                        label="ID"
                        width="50"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="username"
                        label="账号">
                    </el-table-column>
                    <el-table-column
                        prop="nickname"
                        label="呢称">
                    </el-table-column>
                    <el-table-column
                        label="角色">
                        <template slot-scope="scope">
                            <el-tag class="role-tag" v-for="role_name in scope.row.role_names">
                                @{{ role_name }}
                            </el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="last_login_at"
                        label="最近登录时间">
                    </el-table-column>
                    <el-table-column
                        prop="last_login_ip"
                        label="最近登录IP">
                    </el-table-column>
                    <el-table-column
                        prop="status"
                        label="状态">
                        <template slot-scope="scope">
                            <el-switch
                                v-if="scope.row.id > 1"
                                v-model="scope.row.status"
                                :active-value="1"
                                :inactive-value="0"
                                @change="handleStatusChange(scope.$index, scope.row)"
                            ></el-switch>
                            <el-tag type="success" v-else>正常</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column
                        label="操作"
                        width="300"
                    >
                        <template slot-scope="scope">
                            <div v-if="scope.row.id > 1">
                                <el-button
                                    size="mini"
                                    @click="handleReset(scope.$index, scope.row)"
                                >重置密码</el-button>
                                <el-button
                                    size="mini"
                                    @click="dialogShow(scope.row)"
                                >编辑</el-button>
                                <el-button
                                    size="mini"
                                    type="danger"
                                    @click="handleDelete(scope.$index, scope.row)"
                                >删除</el-button>
                            </div>
                        </template>

                    </el-table-column>
                </el-table>

                <div style="display: flex; justify-content: flex-end; margin: 10px 0">
                    <el-pagination
                        background
                        layout="prev, pager, next"
                        @current-change="pagination"
                        :total="total"
                        :page-size="pageSize"
                        :page-count="pageCount"
                        :current-page="searchData.page"
                    >
                    </el-pagination>
                </div>
            </el-card>

        </div>

        <el-dialog title="编辑" :visible.sync="dialogFormVisible" :before-close="handleCloseDialog">
            <el-form ref="formDialog" :model="formData" :rules="rules">
                <el-input v-model="formData.id" type="hidden" name="id"></el-input>
                <el-form-item label="登录账号" :label-width="formLabelWidth" prop="username">
                    <el-input v-model="formData.username" name="username" autocomplete="off" />
                </el-form-item>
                <el-form-item label="呢称" :label-width="formLabelWidth" prop="nickname">
                    <el-input v-model="formData.nickname" name="nickname" autocomplete="off" />
                </el-form-item>
                <el-form-item label="邮箱" :label-width="formLabelWidth" prop="email">
                    <el-input v-model="formData.email" name="email" autocomplete="off" />
                </el-form-item>
                <el-form-item label="手机号" :label-width="formLabelWidth" prop="mobile">
                    <el-input v-model="formData.mobile" name="mobile" autocomplete="off" />
                </el-form-item>
                <el-form-item label="角色" :label-width="formLabelWidth" prop="roles">
                    <el-select v-model="formData.roles" class="scope-el-select" multiple name="roles" placeholder="请选择">
                        <el-option
                            v-for="item in roles"
                            :key="item.name"
                            :label="item.title"
                            :value="item.name"
                        />
                    </el-select>
                </el-form-item>
                <el-form-item label="状态" :label-width="formLabelWidth" required prop="status">
                    <el-radio-group v-model="formData.status">
                        <el-radio :label="1">正常</el-radio>
                        <el-radio :label="0">禁用</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item :label-width="formLabelWidth" class="color-red">
                    <span v-show="this.formData.id === 0 ? true : false ">新增账号时，默认密码：123456</span>
                </el-form-item>

            </el-form>

            <div slot="footer" class="dialog-footer">
                <el-button @click="handleCloseDialog">取 消</el-button>
                <el-button type="primary" :loading="formSubmitLoading" @click="handleSubmitDialog">确 定</el-button>
            </div>
        </el-dialog>
    </div>
@endsection

@section('script')
    <script>

        const DATA_URL = '{{ route('admin.admin.index') }}';
        const CREATE_URL = '{{ route('admin.admin.store') }}';
        const EDIT_URL = '{{ route('admin.admin.update', 0)}}';

        const DESTROY_URL = '{{ route('admin.admin.destroy', 0) }}';
        const RESET_PWD_URL = '{{ route('admin.admin.resetPwd', 0) }}';
        const QUICK_FORBIDDEN_URL = '{{ route('admin.admin.quickForbidden', 0) }}';
        const ROLES = @json($roles);

        const defaultData = {
            id: 0,
            username: '',
            nickname: '',
            roles: [],
            email: '',
            status: 1,
            mobile: ''
        }

        const app = new Vue({
            el: '#app',
            data: {
                listLoading: false,
                total: 0,
                pageCount: 0,
                pageSize: 10,
                searchData: {
                    keyword: "",
                    page: 1,
                },
                tableData: [],
                multipleSelection: [],


                dialogFormVisible: false,
                formData: defaultData,
                formLabelWidth: '120px',
                roles: ROLES,
                rules: {
                    username: [
                        { required: true, message: '请输入登录账号', trigger: 'blur' },
                        { min: 5, max: 30, message: '长度在 5 到 30 个字符', trigger: 'blur' }
                    ],
                    nickname: [
                        { required: true, message: '请输入呢称', trigger: 'blur' },
                        { min: 1, max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
                    ],
                    email: [
                        { type: 'email', message: '请输入正确的邮箱', trigger: 'blur' }
                    ],
                    mobile: [
                        { pattern: /^1[345678]\d{9}$/, message: '请输入正确的手机号', trigger: 'blur' }
                    ]
                },
                formSubmitLoading: false
            },
            created() {
                this.getList()
            },
            methods: {
                pagination(currentPage) {
                    this.searchData.page = currentPage;
                    this.getList();
                },
                handleSelectionChange(val) {
                    this.multipleSelection = val;
                },
                refreshList() {
                    this.searchData.page = 1;
                    this.getList();
                },
                // 获取列表
                getList() {
                    this.listLoading = true
                    let that = this
                    this.$request({
                        url: DATA_URL,
                        method: 'get',
                        params: this.searchData,
                        // data: formData,
                    }).then(response => {
                        that.listLoading = false
                        response = response.data
                        if (response.code !== 200) {
                            that.$message({
                                type: 'error',
                                message: response.msg
                            })
                            return;
                        }
                        that.tableData = response.data.data
                        that.total = response.data.total
                    }).catch(e => {
                    });
                },
                dialogShow(row) {
                    this.formData = Object.assign({}, defaultData);
                    if (row) {
                        this.formData = {
                            id: row.id,
                            username: row.username,
                            nickname: row.nickname,
                            roles: row.roles,
                            email: row.email,
                            status: row.status,
                            mobile: row.mobile
                        }
                    }
                    this.dialogFormVisible = true;
                },
                handleAdd() {
                    console.log('添加管理员', this.formData)
                    this.formSubmitLoading = true
                    let that = this
                    this.$request({
                        url: CREATE_URL,
                        method: "post",
                        data: this.formData
                    }).then(response => {
                        that.formSubmitLoading = false
                        response = response.data
                        if (response.code !== 200) {
                            that.$message.error(response.msg)
                            return;
                        }
                        that.$message({
                            type: 'success',
                            message: response.msg,
                            onClose: function () {
                                that.dialogFormVisible = false;
                                that.refreshList()
                            },
                            duration: 500
                        })
                    }).catch(()=>{
                        that.formSubmitLoading = false
                    })
                },
                handleEdit(id) {
                    console.log('编辑管理员：' + id)
                    this.formSubmitLoading = true
                    let that = this;
                    myRequest({
                        url: EDIT_URL.replace('0', id) ,
                        type: "PUT",
                        data: this.formData
                    }).then(response => {
                        this.formSubmitLoading = false
                        if (response.code === 1) {
                            this.$message.error(response.msg)
                            return
                        }
                        this.$message({
                            type: 'success',
                            message: response.msg,
                            onClose: function () {
                                that.dialogFormVisible = false;
                                that.refreshList()
                            },
                            duration: 500
                        })
                    }).catch(() => {
                        this.formSubmitLoading = false
                    })
                },
                handleBatchDelete() {
                    let ids = this.multipleSelection.map(function (item, index) {
                        return item.id
                    })
                    console.log('批量删除：' + ids)
                    this.$notify({
                        title: '注意',
                        message: '功能开发中',
                        type: 'warning'
                    });
                },
                // 删除
                handleDelete(index, row) {
                    this.$confirm('确定删除?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        this.listLoading = true
                        myRequest({
                            url: DESTROY_URL.replace('0', row.id) ,
                            type: "delete"
                        }).then(response => {

                            this.listLoading = false
                            if (response.code === 1) {
                                this.$message.error(response.msg)
                                return
                            }
                            this.$message({
                                type: 'success',
                                message: response.msg
                            })
                            this.tableData.splice(index, 1)
                        }).catch(() => {
                            this.listLoading = false
                        })
                    }).catch(() => {
                    })
                },
                // 重置密码
                handleReset(index, row) {
                    this.$confirm('确认重置登录密码?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        this.listLoading = true
                        myRequest({
                            url: RESET_PWD_URL.replace('0', row.id) ,
                            type: "put"
                        }).then(response => {
                            this.listLoading = false
                            if (response.code === 1) {
                                this.$message.error(response.msg)
                                return
                            }
                            this.$message({
                                type: 'success',
                                message: response.msg
                            })
                        }).catch(() => {
                            this.listLoading = false
                        })
                    }).catch(() => {
                    })
                },
                // 启用禁用
                handleStatusChange(index, row) {
                    this.$confirm('是否修改该状态?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        this.listLoading = true
                        myRequest({
                            url: QUICK_FORBIDDEN_URL.replace('0', row.id) ,
                            type: "put"
                        }).then(response => {
                            this.listLoading = false
                            if (response.code === 1) {
                                this.$message.error(response.msg)
                                return
                            }
                            this.$message({
                                type: 'success',
                                message: response.msg
                            })
                            this.refreshList()
                        }).catch(() => {
                            this.listLoading = false
                        })
                    }).catch(() => {
                        row.status = row.status ? 0 : 1;
                    })
                },


                // 关闭对话框
                handleCloseDialog() {
                    this.dialogFormVisible = false
                },
                // 处理对话框
                handleSubmitDialog() {
                    this.$refs.formDialog.validate((valid) => {
                        if (!valid) {
                            this.$message({
                                type: 'error',
                                message: '请检查数据格式'
                            })
                            return false
                        } else {
                            console.log()
                            if (this.formData.id === 0) {
                                this.handleAdd()
                            } else {
                                this.handleEdit(this.formData.id)
                            }
                        }
                    })
                },

            },
        })

    </script>
@endsection


