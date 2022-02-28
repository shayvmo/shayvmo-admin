@extends('admin.layouts.app')

@section('style')
    <style>

    </style>
@endsection

@section('content')
    <div id="app">
        <div class="box-card margin-top-bottom-10">
            <el-card class="box-card">
                角色列表
            </el-card>
        </div>

        <div class="box-card margin-top-bottom-10">
            <el-card >
                <el-form :inline="true" :model="searchData" class="demo-form-inline">
                    <el-form-item label="关键词">
                        <el-input
                            placeholder="请输入ID, 名称"
                            v-model="searchData.keyword"
                            clearable
                            @input="getList"
                        ></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="getList" >查询</el-button>
                        <el-button @click="dialogShow()">添加</el-button>
                    </el-form-item>
                </el-form>
                <el-table
                    v-loading="listLoading"
                    :data="tableData"
                    tooltip-effect="dark"
                    style="width: 100%"
                >
                    <el-table-column
                        prop="id"
                        label="ID"
                        width="50"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="name"
                        label="角色标识">
                    </el-table-column>
                    <el-table-column
                        prop="title"
                        label="名称">
                    </el-table-column>
                    <el-table-column
                        prop="desc"
                        label="描述">
                    </el-table-column>
                    <el-table-column
                        label="操作"
                        width="300"
                    >
                        <template slot-scope="scope">
                            <div v-if="scope.row.id > 1">

                                @can('system.role.permission')
                                    <el-button
                                        type="primary"
                                        size="mini"
                                        @click="permissionsDialogShow(scope.row)"
                                    >菜单权限</el-button>
                                @endcan

                                @can('system.role.edit')
                                    <el-button
                                        size="mini"
                                        @click="dialogShow(scope.row)"
                                    >编辑</el-button>
                                @endcan

                                @can('system.role.destroy')
                                    <el-button
                                        size="mini"
                                        type="danger"
                                        @click="handleDelete(scope.$index, scope.row)"
                                    >删除</el-button>
                                @endcan

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
                <el-form-item label="角色标识" :label-width="formLabelWidth" prop="name">
                    <el-input v-model="formData.name" name="name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="名称" :label-width="formLabelWidth" prop="title">
                    <el-input v-model="formData.title" name="title" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="描述" :label-width="formLabelWidth" prop="desc">
                    <el-input
                        type="textarea"
                        placeholder="请输入内容"
                        v-model="formData.desc"
                        maxlength="30"
                        show-word-limit
                    >
                    </el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="handleCloseDialog">取 消</el-button>
                <el-button type="primary" :loading="formSubmitLoading" @click="handleSubmitDialog">确 定</el-button>
            </div>
        </el-dialog>

        <el-dialog :title="permissionDialog.title" :visible.sync="dialogPermissionsVisible" :before-close="handleCloseDialog">
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <el-button size="small" :loading="permissionDialog.submitLoading" type="primary" @click="handleEditPermissions">保存</el-button>
                    <el-button size="small" @click="selectAll">全选</el-button>
                    <el-button size="small" type="danger" @click="resetRules">清空</el-button>
                </div>
                <div>
                    <el-input
                        v-model="filterText"
                        placeholder="输入关键字进行过滤"
                        clearable
                        class="input-margin"
                    ></el-input>
                    <el-tree
                        ref="tree"
                        class="filter-tree"
                        :data="permissionDialog.data"
                        show-checkbox
                        node-key="name"
                        :props="permissionDialog.defaultProps"
                        default-expand-all
                        :default-checked-keys="permissionDialog.hasKeys"
                        :filter-node-method="filterNode"
                    >
                      <span slot-scope="{ node,data }" class="custom-tree-node">
                        <span>@{{ node.label }}</span>
                        <span>
                          <el-tag v-if="data.type === 1" class="span-el-tag" size="small">菜单</el-tag>
                          <el-tag v-if="data.type === 2" class="span-el-tag" type="info" size="small">接口</el-tag>
                        </span>
                      </span>
                    </el-tree>
                </div>
            </el-card>
        </el-dialog>
    </div>
@endsection

@section('script')
    <script>

        const DATA_URL = '{{ route('admin.role.index') }}';
        const CREATE_URL = '{{ route('admin.role.store') }}';
        const EDIT_URL = '{{ route('admin.role.update', 0)}}';

        const DESTROY_URL = '{{ route('admin.role.destroy', 0) }}';

        const GET_PERMISSIONS_URL = '{{ route('admin.role.permission', 0) }}';
        const ASSIGN_PERMISSIONS_URL = '{{ route('admin.role.assignPermission', 0) }}';

        const defaultData = {
            id: 0,
            name: '',
            title: '',
            desc: '',
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


                dialogFormVisible: false,
                formData: defaultData,
                formLabelWidth: '120px',
                rules: {
                    name: [
                        { required: true, message: '请输入角色标识', trigger: 'blur' },
                        { min: 1, max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
                    ],
                    title: [
                        { required: true, message: '请输入名称', trigger: 'blur' },
                        { min: 1, max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur' }
                    ],
                    desc: [
                        { min: 0, max: 30, message: '长度不能大于 30 个字符', trigger: 'blur' }
                    ],
                },
                formSubmitLoading: false,

                dialogPermissionsVisible: false,
                filterText: '',
                permissionDialog: {
                    row: {},
                    dialogPermissionsVisible: false,
                    data: [],
                    submitLoading: false,
                    defaultProps: {
                        children: 'children',
                        label: 'title'
                    },
                    title: '编辑【角色】数据权限 ',
                    hasKeys: []
                }
            },
            watch: {
                dialogPermissionsVisible: function(newVal, oldVal) {
                    if (newVal === true) {
                        this.permissionDialog.title = '编辑【' + this.permissionDialog.row.name + '】权限'
                        this.getPermissions()
                    }
                },
                filterText(val) {
                    this.$refs.tree.filter(val)
                }
            },
            created() {
                this.getList()
            },
            methods: {
                pagination(currentPage) {
                    this.searchData.page = currentPage;
                    this.getList();
                },
                refreshList() {
                    this.searchData.page = 1;
                    this.getList();
                },
                // 获取列表
                getList() {
                    this.listLoading = true
                    let that = this
                    myRequest({
                        url: DATA_URL,
                        type: "get",
                        params: this.searchData
                    }).then(response => {
                        that.listLoading = false
                        if (response.code !== 200) {
                            that.$message({
                                type: 'error',
                                message: response.msg
                            })
                            return;
                        }
                        that.tableData = response.data.data
                        that.total = response.data.total
                    })
                },
                dialogShow(row) {
                    this.formData = Object.assign({}, defaultData);
                    if (row) {
                        this.formData = {
                            id: row.id,
                            name: row.name,
                            title: row.title,
                            desc: row.desc,
                        }
                    }
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
                                that.refreshList()
                            },
                            duration: 500
                        })
                    }).catch(()=>{
                        that.formSubmitLoading = false
                    })
                },
                handleEdit(id) {
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


                // 关闭对话框
                handleCloseDialog() {
                    this.dialogFormVisible = false
                    this.dialogPermissionsVisible = false
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


                permissionsDialogShow(row) {
                    this.permissionDialog.row = row
                    this.dialogPermissionsVisible = true;
                },
                getPermissions() {
                    myRequest({
                        url: GET_PERMISSIONS_URL.replace('0', this.permissionDialog.row.id),
                        type: "get"
                    }).then(response => {
                        if (response.code !== 200) {
                            this.$message({
                                type: 'error',
                                message: response.msg
                            })
                            return;
                        }
                        this.permissionDialog.data = response.data.lists
                        this.permissionDialog.hasKeys = response.data.checkedKeys
                    })
                },
                handleEditPermissions() {
                    const checkedKeys = this.$refs.tree.getCheckedKeys()
                    const halfCheckedKeys = this.$refs.tree.getHalfCheckedKeys()
                    const concatArray = checkedKeys.concat(halfCheckedKeys)
                    const data = {
                        rules: concatArray
                    }
                    myRequest({
                        url: ASSIGN_PERMISSIONS_URL.replace('0', this.permissionDialog.row.id),
                        type: "PUT",
                        data: data
                    }).then(response => {
                        if (response.code !== 200) {
                            this.$message({
                                type: 'error',
                                message: response.msg
                            })
                            return;
                        }
                        this.$message({
                            type: 'success',
                            message: response.msg
                        })
                    })
                },
                // 清空权限
                resetRules() {
                    this.$refs.tree.setCheckedKeys([])
                },
                // 全选
                selectAll() {
                    this.$refs.tree.setCheckedNodes(this.permissionDialog.data)
                },
                // 过滤节点
                filterNode(value, data) {
                    if (!value) return true
                    return data.title.indexOf(value) !== -1
                },

            },
        })

    </script>
@endsection


