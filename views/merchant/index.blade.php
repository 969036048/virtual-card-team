@extends('layouts.base')
@section('title', trans('基础设置'))
@section('content')
    <link rel="stylesheet" href="{{resource_get('plugins/lakala_pay/views/css/index.css')}}">
    <div class="all">
        <div id="app" v-cloak>
            <el-form ref="form" :model="form" label-width="15%" v-loading="table_loading">
                <div class="vue-main" style="min-height: 500px;">
                    <div class="vue-main-title">
                        <div class="vue-main-title-left"></div>
                        <div class="vue-main-title-content">基础设置</div>
                    </div>

                    <div class="vue-main-form">
                        <el-form-item label="审核状态：">
                                <span style="margin-left: 10px">[[form.set.status_name]]</span>
                        </el-form-item>

                        <el-form-item label="商户号：">
                            <span style="margin-left: 10px">[[form.set.merCupNo]]</span>
                        </el-form-item>

                        <el-form-item label="内部商户号：">
                            <span style="margin-left: 10px">[[form.set.merInnerNo]]</span>
                        </el-form-item>

                        <el-form-item label="进件描述：">
                            <span style="margin-left: 10px">[[form.set.contractMemo]]</span>
                        </el-form-item>
                        <el-form-item v-if="form.set.is_re == 1" label="">
                            <el-button type="primary" @click="editForm">重新申请</el-button>
                        </el-form-item>

                    </div>
                </div>
            </el-form>

        </div>
    </div>

    <script>
        let app = new Vue({
            el: "#app",
            delimiters: ['[[', ']]'],
            data() {
                return {
                    form: {
                        save: false,
                        set: {
                            appid: '',
                            serial_no: '',
                            merchant_no: '',
                            private_key_path: '',
                            public_key_path: '',
                            is_open: 0,
                        }
                    },
                    private_key_file:'',
                    public_key_file:'',
                }
            },
            mounted() {
                this.getData()
            },
            methods: {
                editForm() {
                    window.location.href = "{{ yzWebUrl('plugin.lkl-pay.admin.merchant.edit') }}";
                },
                submitForm() {
                    this.form.save = true

                    this.getData();
                    this.$message({
                        message: '成功',
                        type: 'success'
                    });
                },
                getData() {
                    const form_data = new FormData();
                    if (this.form.save) {
                        if (this.private_key_file) {
                            form_data.append("private_key_file", this.private_key_file.raw)
                        }
                        if (this.public_key_file) {
                            form_data.append("public_key_file", this.public_key_file.raw)
                        }

                        // 把this.form对象转换成FormData对象
                        for (let key in this.form) {
                            if (key == 'save') {
                                form_data.append('save', this.form[key])
                            }
                            if (key == 'set') {
                                for (let k in this.form[key]) {
                                    form_data.append('set[' + k + ']', this.form[key][k])
                                }
                            }
                        }
                    }
                    this.$http.post(`{!! yzWebFullUrl('plugin.lkl-pay.admin.merchant.getData') !!}`, form_data).then(function (response) {
                        if (response.data.result) {
                            if (response.data.data) {
                                this.form.set = response.data.data
                            }
                        } else {
                            this.$message({
                                message: response.data.msg,
                                type: 'error'
                            });
                        }
                    }, function (response) {
                        this.$message({
                            message: response.data.msg,
                            type: 'error'
                        });
                    });
                },
                resPrivate() {
                    this.form.set.credential_code = ''
                    this.form.set.set_credential_code = 0
                },
                handleExceed(files, fileList) {
                    this.$message.warning(`当前限制选择 1 个文件，本次选择了 ${files.length} 个文件，共选择了 ${files.length + fileList.length} 个文件`);
                },
                handleOnChangeCert(file, fileList) {
                    // 检查文件类型是不是pem
                    let FileExt = file.name.replace(/.+\./, "");
                    if (['pem'].indexOf(FileExt.toLowerCase()) === -1){
                        this.$message.error('请上传pem格式的证书文件');
                        return
                    }

                    if (file.raw.size > 5 * 1024 * 1024) {
                        this.$message.error('文件大小不能超过5M');
                        return
                    }

                    this.private_key_file = file

                    this.$message.success('添加成功');
                },
                handleOnChangeKey(file, fileList) {
                    let FileExt = file.name.replace(/.+\./, "");
                    if (['cer'].indexOf(FileExt.toLowerCase()) === -1){
                        this.$message.error('请上传cer格式的证书文件');
                        return
                    }

                    if (file.raw.size > 5 * 1024 * 1024) {
                        this.$message.error('文件大小不能超过5M');
                        return
                    }

                    this.public_key_file = file
                    // this.$message.success('添加成功');
                },
            }
        })
    </script>
    <style>
    </style>
@endsection