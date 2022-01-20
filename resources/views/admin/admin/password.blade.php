@extends('admin.layouts.app')

@section('content')
<div id="app">
    <div class="margin-top-bottom-10">
        <el-card class="box-card">
            修改密码
        </el-card>
    </div>
    <div class="margin-top-bottom-10">
        <el-card>
            <el-form ref="formData" :model="formData" :rules="rules">
                <el-form-item label="原密码" :label-width="formLabelWidth" prop="old_password">
                    <el-input v-model="formData.old_password" name="old_password" autocomplete="off" />
                </el-form-item>
                <el-form-item label="新密码" :label-width="formLabelWidth" prop="new_password">
                    <el-input v-model="formData.new_password" name="new_password" autocomplete="off" />
                </el-form-item>
                <el-form-item label="确认密码" :label-width="formLabelWidth" prop="new_password_confirmation">
                    <el-input v-model="formData.new_password_confirmation" name="new_password_confirmation" autocomplete="off" />
                </el-form-item>
                <el-form-item :label-width="formLabelWidth">
                    <el-button type="primary" :loading="formSubmitLoading" @click="handleSubmit">确 定</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </div>
</div>
@endsection

@section('script')
<script>
    const UPDATE_PWD_URL = '{{ route('admin.password.update') }}';
    const DEFAULT_DATA = {
        old_password: '',
        new_password: '',
        new_password_confirmation: '',
    };
    var validatePass = (rule, value, callback) => {
        if (value === '') {
            callback(new Error('请输入密码'));
        } else {
            if (this.formData.new_password_confirmation !== '') {
                this.$refs.formData.validateField('checkPass');
            }
            callback();
        }
    };
    var validatePass2 = (rule, value, callback) => {
        if (value === '') {
            callback(new Error('请再次输入密码'));
        } else if (value !== this.formData.new_password) {
            callback(new Error('两次输入密码不一致!'));
        } else {
            callback();
        }
    };
    const app = new Vue({
        el: '#app',
        data() {
            var validatePass = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请输入密码'));
                } else {
                    if (this.formData.new_password_confirmation !== '') {
                        this.$refs.formData.validateField('checkPass');
                    }
                    callback();
                }
            };
            var validatePass2 = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请再次输入密码'));
                } else if (value !== this.formData.new_password) {
                    callback(new Error('两次输入密码不一致!'));
                } else {
                    callback();
                }
            };
            return {
                formSubmitLoading: false,
                formLabelWidth: '120px',
                formData: DEFAULT_DATA,
                rules: {
                    old_password: [
                        { required: true, message: '请输入原密码', trigger: 'blur' },
                        { min: 5, max: 30, message: '长度在 5 到 30 个字符', trigger: 'blur' }
                    ],
                    new_password: [
                        { required: true, message: '请输入新密码', trigger: 'blur' },
                        { min: 5, max: 30, message: '长度在 5 到 30 个字符', trigger: 'blur' },
                        { validator: validatePass, trigger: 'blur' }
                    ],
                    new_password_confirmation: [
                        { required: true, message: '请输入确认密码', trigger: 'blur' },
                        { min: 5, max: 30, message: '长度在 5 到 30 个字符', trigger: 'blur' },
                        { validator: validatePass2, trigger: 'blur' }
                    ],
                }
            }
        },
        methods: {
            handleSubmit() {
                this.$refs.formData.validate((valid) => {
                    if (!valid) {
                        this.$message({
                            type: 'error',
                            message: '请检查数据格式'
                        })
                        return false
                    } else {
                        this.formSubmitLoading = true
                        myRequest({
                            url: UPDATE_PWD_URL,
                            type: "POST",
                            data: this.formData
                        }).then(response => {
                            console.log(response)
                            this.formSubmitLoading = false
                            if (response.code === 1) {
                                this.$message.error(response.msg)
                                return
                            }
                            this.$message({
                                type: 'success',
                                message: response.msg,
                            })
                            this.$refs.formData.resetFields();
                        }).catch(() => {
                            this.formSubmitLoading = false
                        })
                    }

                })
            }
        },
    })
</script>
@endsection

