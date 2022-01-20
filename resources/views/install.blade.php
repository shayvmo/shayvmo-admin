<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>安装页面</title>

        <style>
            #app {
                width: 1000px;
                margin: 0 auto;
            }
        </style>
        <script src="/static/unpkg/vue@2.6.10/dist/vue.js"></script>
        <script src="/static/unpkg/element-ui@2.12.0/lib/index.js"></script>
        <script src="/js/axios.min.js"></script>
        <link rel="stylesheet" href="/static/unpkg/element-ui@2.12.0/lib/theme-chalk/index.css">
    </head>
    <body>
        <div id="app">
            <el-card>
                简易安装程序
            </el-card>
            <el-card>
                <el-row>
                    <el-col :span="24">
                        <div class="grid-content bg-purple-dark">
                            <el-form ref="form" :model="form" label-width="150px" :rules="rules">
                                <el-form-item label="数据库地址" prop="db_host">
                                    <el-input v-model="form.db_host" ></el-input>
                                </el-form-item>
                                <el-form-item label="端口号" prop="db_port">
                                    <el-input v-model="form.db_port"></el-input>
                                </el-form-item>
                                <el-form-item label="数据库名称" prop="db_database">
                                    <el-input v-model="form.db_database"></el-input>
                                </el-form-item>
                                <el-form-item label="用户名" prop="db_username">
                                    <el-input v-model="form.db_username"></el-input>
                                </el-form-item>
                                <el-form-item label="密码" prop="db_password">
                                    <el-input v-model="form.db_password"></el-input>
                                </el-form-item>
                                <el-form-item label="数据库表前缀" prop="db_prefix">
                                    <el-input v-model="form.db_prefix"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" @click="onSubmit" :loading="loading">立即安装</el-button>
                                    <el-button>取消</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                    </el-col>

                </el-row>
            </el-card>
        </div>
    </body>
    <script>
        const SAVE_URL = '{{ route('install.save') }}';
        const EXECUTE_URL = '{{ route('install.execute') }}';
        const LOGIN_PAGE_URL = '{{ route('admin.auth.page') }}';
        const app = new Vue({
            el: '#app',
            data: {
                form: {
                    db_host: '{{ $env_array['DB_HOST'] ?? ''}}',
                    db_port: '{{ $env_array['DB_PORT']  ?? '3306'}}',
                    db_database: '{{ $env_array['DB_DATABASE']  ?? ''}}',
                    db_username: '{{ $env_array['DB_USERNAME']  ?? ''}}',
                    db_password: '{{ $env_array['DB_PASSWORD']  ?? ''}}',
                    db_prefix: '{{ $env_array['DB_PREFIX']  ?? ''}}',
                },
                rules: {
                    db_host: [
                        { required: true, message: '请输入数据库地址', trigger: 'blur' }
                    ],
                    db_port: [
                        { required: true, message: '请输入端口', trigger: 'blur' }
                    ],
                    db_database: [
                        { required: true, message: '请输入数据库名称', trigger: 'blur' }
                    ],
                    db_username: [
                        { required: true, message: '请输入账号', trigger: 'blur' }
                    ],
                    db_password: [
                        { required: true, message: '请输入密码', trigger: 'blur' }
                    ],
                    db_prefix: [
                        { min: 0, max: 10, message: '长度不能超过 10 个字符', trigger: 'blur' }
                    ],
                },
                loading: false
            },
            methods: {
                onSubmit() {
                    this.$refs['form'].validate((valid) => {
                        if (valid) {
                            let that = this
                            that.loading = true
                            axios.post(SAVE_URL, this.form).then(function(response) {
                                that.loading = false
                                if (response.data.code === 0) {
                                    that.$notify.success({
                                        title: '成功',
                                        message: response.data.msg,
                                        duration: 1000,
                                        onClose: function() {
                                            that.$notify.info({
                                                title: '消息',
                                                message: '进入安装程序',
                                                duration: 1000,
                                                onClose: function () {
                                                    that.execute()
                                                }
                                            });

                                        },
                                    });
                                } else {
                                    that.$notify.error({
                                        title: '错误',
                                        message: response.data.msg
                                    });
                                }
                            })
                        } else {
                            console.log('error submit!!');
                            return false;
                        }
                    })
                },
                execute() {
                    let that = this
                    const loading = this.$loading({
                        lock: true,
                        text: '执行安装中',
                        spinner: 'el-icon-loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });
                    axios.post(EXECUTE_URL).then(function(response) {
                        loading.close();
                        if (response.data.code === 0) {
                            that.$notify.success({
                                title: '成功',
                                message: response.data.msg,
                                duration: 1000,
                                onClose: function() {
                                    location.href = LOGIN_PAGE_URL;
                                },
                            });
                        } else {
                            that.$notify.error({
                                title: '错误',
                                message: response.data.msg
                            });
                        }
                    })
                }
            }
        });
    </script>
</html>
