@extends('admin.layouts.app')

@section('style')
    <style>
        .tree {
            margin-top: 10px;
        }

        .custom-tree-node {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            padding-right: 8px;
        }

        .span-el-tag {
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')
    <div id="app">
        <div class="margin-top-bottom-10">
            <el-card>
                权限列表 <span style="color: red">【建议仅限开发者操作】</span>
            </el-card>
        </div>
        <div class="custom-tree-container">
            <el-card class="filter-container" shadow="never">
                <el-row>
                    <el-col :span="8">
                        <el-row :gutter="10">
                            <el-col :span="21">
                                <el-input
                                    v-model="filterText"
                                    clearable
                                    placeholder="输入关键字进行过滤"
                                />
                            </el-col>
                            <el-col :span="2">
                                <el-button
                                    icon="el-icon-refresh-right"
                                    @click="refresh"
                                />
                            </el-col>
                        </el-row>

                        <el-tree
                            ref="tree"
                            v-loading="treeLoading"
                            class="tree"
                            :data="data"
                            node-key="id"
                            :props="defaultProps"
                            :filter-node-method="filterNode"
                            :expand-on-click-node="false"
                            @node-click="nodeEdit"
                        >
                            <span slot-scope="{ node,data }" class="custom-tree-node">
                              <span>@{{ node.label }}</span>
                              <span>
                                <el-tag class="span-el-tag" type="info" size="small">@{{ data.sort }} <i class="el-icon-bottom"></i></el-tag>
                                <el-tag v-if="data.type === 1" class="span-el-tag" size="small">页面</el-tag>
                                <el-tag v-if="data.type === 2" class="span-el-tag" type="info" size="small">接口</el-tag>
                                <el-button
                                    type="text"
                                    size="mini"
                                    @click.stop.prevent
                                    @click="() => append(data)"
                                >
                                    <i class="el-icon-circle-plus"></i>
                                </el-button>
                                <el-button
                                    type="text"
                                    size="mini"
                                    @click.stop.prevent
                                    @click="() => remove(node, data)"
                                >
                                    <i class="el-icon-delete color-danger"></i>
                                </el-button>
                              </span>
                            </span>
                        </el-tree>
                    </el-col>
                    <el-col :span="12">

                        <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="demo-ruleForm">
                            <el-form-item>
                                <el-button type="primary" @click="addRight">添加权限</el-button>
                            </el-form-item>
                            <el-form-item label="父级权限" prop="pid">
                                <el-select v-model="form.pid" filterable placeholder="请选择">
                                    <el-option
                                        v-for="item in parentMenus"
                                        :key="item.id"
                                        :label="item.title"
                                        :value="item.id"
                                    >
                                        <span style="float: left">@{{ item.title }}</span>
                                        <span style="float: right; color: #8492a6; font-size: 13px">
                                            <el-tag v-if="item.type === 1" class="span-el-tag" size="small">页面</el-tag>
                                            <el-tag v-if="item.type === 2" class="span-el-tag" type="info" size="small">接口</el-tag>
                                          </span>
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="名称" prop="title">
                                <el-input ref="name" v-model="form.title"/>
                            </el-form-item>
                            <el-form-item label="权限类型" prop="type" required>
                                <el-select v-model="form.type" placeholder="请选择权限类型" @change="changePermissionType">
                                    <el-option
                                        v-for="item in rightTypes"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id"
                                    />
                                </el-select>
                            </el-form-item>
                            <el-form-item label="是否菜单" prop="is_menu" required>
                                <el-radio-group v-model="form.is_menu">
                                    <el-radio :label="1">是</el-radio>
                                    <el-radio :label="0">否</el-radio>
                                </el-radio-group>
                            </el-form-item>
                            <el-form-item prop="name">
                                <template slot="label">
                                    权限值
                                    <el-tooltip class="item" effect="dark" content="权限判断name值"
                                                placement="top">
                                        <i class="el-icon-warning"></i>
                                    </el-tooltip>
                                </template>
                                <el-input v-model="form.name"/>
                            </el-form-item>

                            <el-form-item prop="route">
                                <template slot="label">
                                    路由
                                    <el-tooltip class="item" effect="dark" content="路由" placement="top">
                                        <i class="el-icon-warning"></i>
                                    </el-tooltip>
                                </template>
                                <el-input v-model="form.route"/>
                            </el-form-item>
                            <el-form-item v-if="form.type === 1" prop="icon">
                                <template slot="label">
                                    图标
                                    <el-tooltip class="item" effect="dark" content="默认系统图标" placement="top">
                                        <i class="el-icon-warning"></i>
                                    </el-tooltip>
                                </template>
                                <i :class="'layui-icon ' + form.icon"></i>
                                <el-link href="https://layui.itze.cn/doc/element/icon.html" target="_blank"
                                         type="primary">查看图标(暂无法预览)
                                </el-link>
                                <el-input v-model="form.icon" placeholder="菜单图标"></el-input>
                            </el-form-item>
                            <el-form-item v-if="form.type === 1" prop="sort">
                                <template slot="label">
                                    排序
                                    <el-tooltip class="item" effect="dark" content="倒序" placement="top">
                                        <i class="el-icon-warning"></i>
                                    </el-tooltip>
                                </template>
                                <el-input v-model="form.sort" type="number" min="0" step="1"/>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="submitForm('form')">保存</el-button>
                                <el-button @click="resetForm('form')">重置</el-button>
                            </el-form-item>
                        </el-form>
                    </el-col>
                </el-row>
            </el-card>
        </div>

    </div>
@endsection

@section('script')
    <script>

        const DATA_URL = '{{ route('admin.permission.index') }}';
        const CREATE_URL = '{{ route('admin.permission.store') }}';
        const EDIT_URL = '{{ route('admin.permission.update', 0)}}';
        const DESTROY_URL = '{{ route('admin.permission.destroy', 0) }}';

        const parentMenus = [
            {
                id: 0,
                title: '顶级权限'
            }
        ]

        const DEFAULT_DATA = {
            id: 0,
            pid: 0,
            name: '',
            title: '',
            icon: '',
            route: '',
            sort: 100,
            type: 1,
            is_menu: 0
        }

        const app = new Vue({
            el: '#app',
            data: {
                defaultProps: {
                    children: 'children',
                    label: 'title'
                },
                treeLoading: false,
                parentMenus: [],
                rightTypes: [
                    {
                        id: 1,
                        name: '页面'
                    },
                    {
                        id: 2,
                        name: '接口'
                    }
                ],
                form: DEFAULT_DATA,
                rules: {
                    pid: [
                        {required: true, message: '请输入选择父级权限', trigger: 'change'}
                    ],
                    title: [
                        {required: true, message: '请输入名称', trigger: 'blur'},
                        {min: 1, max: 30, message: '长度在 1 到 30 个字符', trigger: 'blur'}
                    ],
                    name: [
                        {required: true, message: '请输入权限值', trigger: 'blur'},
                        {min: 1, max: 50, message: '长度在 1 到 50 个字符', trigger: 'blur'}
                    ],
                    type: [
                        {required: true, message: '请选择权限类型', trigger: 'change'}
                    ]
                },
                data: [],
                filterText: ''
            },
            watch: {
                filterText(val) {
                    this.$refs.tree.filter(val)
                }
            },
            created() {
                this.getList()
            },
            methods: {
                refresh() {
                    this.getList()
                    this.resetForm()
                },
                submitForm(formName) {
                    this.$refs[formName].validate((valid) => {
                        if (valid) {
                            this.form.id > 0 ? this.handleEdit() : this.handleAdd();
                        } else {
                            this.$message({
                                type: 'error',
                                message: '请正确输入信息',
                                duration: 1000
                            })
                            return false
                        }
                    })
                },
                handleAdd() {
                    this.treeLoading = true
                    let that = this
                    myRequest({
                        url: CREATE_URL,
                        type: "POST",
                        data: this.form
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
                            message: response.msg,
                            onClose: function () {
                                that.treeLoading = false
                                that.refresh()
                            },
                            duration: 500
                        })

                    })
                },
                handleEdit() {
                    this.treeLoading = true
                    let that = this
                    myRequest({
                        url: EDIT_URL.replace('0', this.form.id),
                        type: "PUT",
                        data: this.form
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
                            message: response.msg,
                            onClose: function () {
                                that.treeLoading = false
                                that.resetForm()
                                that.refresh()
                            },
                            duration: 500
                        })
                    })
                },
                resetForm() {
                    this.form = DEFAULT_DATA
                    this.$refs.form.clearValidate()
                },
                nodeEdit(data, node, tree) {
                    this.form = data
                },
                append(data) {
                    this.$confirm('确定添加【 ' + data.title + ' 】下级节点?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        this.resetForm()
                        this.form.pid = data.id
                        this.$refs['name'].focus()
                    }).catch(() => {
                    })
                },

                remove(node, data) {
                    let that = this
                    this.$confirm('确定删除【 ' + data.title + ' 】节点?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        myRequest({
                            url: DESTROY_URL.replace('0', data.id),
                            type: "DELETE",
                            data: this.form
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
                                message: response.msg,
                                onClose: function () {
                                    that.refresh()
                                },
                                duration: 500
                            })
                        })
                    }).catch(() => {
                    })
                },
                filterNode(value, data) {
                    if (!value) return true
                    return data.name.indexOf(value) !== -1
                },
                getList() {
                    this.treeLoading = true
                    let that = this
                    myRequest({
                        url: DATA_URL,
                        type: "get",
                    }).then(response => {
                        that.listLoading = false
                        if (response.code !== 200) {
                            this.$message({
                                type: 'error',
                                message: response.msg
                            })
                            return;
                        }
                        that.treeLoading = false
                        that.data = response.data.permissions_tree
                        that.parentMenus = parentMenus.concat(response.data.permissions)
                    })

                },
                addRight() {
                    this.resetForm()
                },
                changePermissionType(data) {
                    if (data === 1) {
                        this.resetForm()
                    }
                    if (data === 2) {
                        this.form.icon = ''
                    }
                    console.log(this.form)
                },
            },
        })

    </script>
@endsection


