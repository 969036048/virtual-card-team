@extends('layouts.base') @section('title', '学院编辑') @section('content')
    <link rel="stylesheet" href="{{ static_url('css/public-number.css') }}"/>
    <link
            rel="stylesheet"
            type="text/css"
            href="{{ static_url('yunshop/goods/vue-goods1.css') }}"
    />
    <style>
        .main-panel {
            margin-top: 50px;
        }

        .panel {
            margin-bottom: 10px !important;
            padding-left: 20px;
            border-radius: 10px;
        }

        .panel .active a {
            background-color: #29ba9c !important;
            border-radius: 18px !important;
            color: #fff;
        }

        .panel a {
            border: none !important;
            background-color: #fff !important;
        }

        .content {
            background: #eff3f6;
            padding: 10px !important;
        }

        .con {
            padding: 10px;
            position: relative;
            border-radius: 8px;
            min-height: 100vh;

        }

        .con .setting .block {
            background-color: #fff;
            border-radius: 8px;
            min-height: 800px;
        }

        .con .setting .block .title {
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            padding: 30px;
        }

        .confirm-btn {
            width: calc(100% - 116px);
            position: fixed;
            bottom: 0;
            right: 0;
            margin-right: 10px;
            line-height: 63px;
            background-color: #ffffff;
            box-shadow: 0px 8px 23px 1px rgba(51, 51, 51, 0.3);
            background-color: #fff;
            text-align: center;
        }

        b {
            font-size: 14px;
        }

        .add-goods {
            width: 120px;
            height: 120px;
            border: dashed 1px #dde2ee;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* 表格样式 */
        .el-table td,
        .el-table th.is-leaf {
            border: none;
        }

        .article .el-table th {
            background-color: #f5f7fa;
        }

        .el-table__body tr.hover-row > td {
            background-color: #fff;
        }

        .post {
            display: flex;
        }

        .post .el-button {
            border: none;
            margin-left: 30px;
        }

        .post [class^="el-icon-"] {
            font-size: 22px;
        }

        .post .el-upload--picture-card {
            width: 120px;
        }

        .choose {
            width: 80%;
            border: 1px dashed #e1e1e1;
        }

        .tableHead {
            display: flex;
            flex-direction: column;
        }

        .tableHead .size {
            font-size: 12px;
            color: #999;
            font-weight: 500;
            line-height: 24px;
        }

        .upload-boxed-text-clear {
            position: absolute;
            bottom: 0;
            right: -70px;
            cursor: pointer;
            line-height: 32px;
            color: #ee3939;
        }
    </style>
    <div id="re_content">
        <div class="con">
            <div class="setting">
                <el-form ref="form" :model="form" :rules="rules" label-width="15%">
                    <div class="block">
                        <div class="title">
            <span
                    style="
                width: 4px;
                height: 18px;
                background-color: #29ba9c;
                margin-right: 15px;
                display: inline-block;
              "
            ></span
            ><b>添加学员</b>
                        </div>
                        <el-form-item label="学员姓名">
                            <el-input
                                    v-model="form.relation_name"
                                    style="width: 70%"
                            ></el-input>
                        </el-form-item>

                        <el-form-item label="出生日期">
                            <el-input
                                    v-model="form.birthday"
                                    style="width: 70%"
                            ></el-input>
                        </el-form-item>

                        <el-form-item label="性别">
                            <el-select v-model="form.sex" placeholder="请选择">
                                <el-option
                                        v-for="item in options"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value"
                                ></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="选择会员">
                            <div class="upload-box" @click="openMember" v-if="!form.member_id">
                                <div class="upload-box-member">
                                    <i class="el-icon-plus" style="font-size:32px"></i><br>
                                    选 择
                                </div>
                            </div>
                            <div class="upload-boxed" v-if="form.member">
                                <img @click="openMember" :src="form.member.avatar" alt=""
                                     style="width:150px;height:150px;border-radius: 5px;cursor: pointer;"/>
                                <div class="upload-boxed-text">重新选择</div>
                                <div class="upload-boxed-text-clear" @click="clearMember">清除选择</div>
                                <div style="text-align:center;line-height: 20px;">[[form.member.nickname]]</div>
                            </div>
                        </el-form-item>

                        <el-form-item label="就读学校">
                            <el-input
                                    v-model="form.school"
                                    style="width: 70%"
                            ></el-input>
                        </el-form-item>

                        <el-form-item label="班级">
                            <el-input
                                    v-model="form.school_class"
                                    style="width: 70%"
                            ></el-input>
                        </el-form-item>
                        <el-form-item label="地区" style="margin-top: 40px">
                            <el-select v-model="form.province_id" placeholder="请选择省" clearable
                                       @change="changeProvince" style="width:17.5%">
                                <el-option v-for="item in province_list" :key="item.id" :label="item.areaname"
                                           :value="item.id"></el-option>
                            </el-select>
                            <el-select v-model="form.city_id" placeholder="请选择市" clearable @change="changeCity"
                                       style="width:17.5%">
                                <el-option v-for="item in city_list" :key="item.id" :label="item.areaname"
                                           :value="item.id"></el-option>
                            </el-select>
                            <el-select v-model="form.district_id" placeholder="请选择区" clearable
                                       @change="changeDistrict" style="width:17.5%">
                                <el-option v-for="item in district_list" :key="item.id" :label="item.areaname"
                                           :value="item.id"></el-option>
                            </el-select>
                            <el-select v-model="form.street_id" placeholder="请选择街道" clearable style="width:17.5%">
                                <el-option v-for="item in street_list" :key="item.id" :label="item.areaname"
                                           :value="item.id"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="居住地">
                            <el-input style="width: 20%" v-model="form.address" placeholder="请输入详细地址"></el-input>
                        </el-form-item>
                    </div>
                    <div class="confirm-btn">
                        <el-button type="primary" @click="submit">提交</el-button>
                        <el-button @click="goBack">返回</el-button>
                    </div>
                </el-form>
            </div>
        </div>
        <el-dialog title="选择会员" :visible.sync="member_show" width="60%">
            <div>
                <el-input v-model="member_keyword" style="width:60%;" placeholder="会员信息"></el-input>
                <el-button @click="getMember">搜索</el-button>
            </div>
            <el-table :data="member_list" style="width: 100%;height:500px;overflow:auto">
                <el-table-column label="ID" prop="uid" align="center" width="100px"></el-table-column>
                <el-table-column label="会员信息">
                    <template slot-scope="scope">
                        <div style="display:flex;align-items: center;">
                            <div v-if="scope.row.avatar_image" style="width:40px;">
                                <el-image :src="scope.row.avatar_image" alt=""
                                          style="width:40px;height:40px;border-radius:50%"></el-image>
                            </div>
                            <div style="flex:1;">【id:[[scope.row.uid]]】[[scope.row.nickname]]</div>
                        </div>

                    </template>
                </el-table-column>

                <el-table-column prop="refund_time" label="操作" align="center" width="320">
                    <template slot-scope="scope">
                        <el-button @click="chooseMember(scope.row)">
                            选择
                        </el-button>

                    </template>
                </el-table-column>
            </el-table>
            <span slot="footer" class="dialog-footer">
                    <el-button @click="member_show = false">取 消</el-button>
                </span>

        </el-dialog>
    </div>
    @include('public.admin.uploadImg') @include('public.admin.tinymceee')
    <script src="{{
    resource_get('static/yunshop/tinymce4.7.5/tinymce.min.js')
  }}"></script>
    <script>
        let data = {!! json_encode($student)?:'[]' !!};
        let set = {!! json_encode($set)?:'[]' !!};
        console.log(data)
        var vm = new Vue({
            el: "#re_content",
            delimiters: ['[[', ']]'],
            data() {
                return {
                    form: {
                        member: '',
                        member_id: '',
                        province_id: '',
                        city_id: '',
                        district_id: '',
                        street_id: '',
                        address: '',
                        school: '',
                        school_class: '',
                        ...data,
                    },
                    set: set,
                    province_list: [],
                    city_list: [],
                    district_list: [],
                    street_list: [],
                    member_show: false,
                    member_keyword: '',
                    member_list: [],
                    options: [
                        {
                            value: 1,
                            label: '男'
                        },
                        {
                            value: 2,
                            label: '女'
                        }
                    ],
                    rules: {
                        name: [
                            {
                                required: true,
                                message: "请输入厂家名称",
                                trigger: "blur"
                            }
                        ],
                    }

                }
            },
            mounted() {
                this.initProvince();
                if (this.form.province_id) {
                    this.initCity(this.form.province_id);
                }
                if (this.form.city_id) {
                    this.initDistrict(this.form.city_id);
                }
                if (this.form.district_id) {
                    this.initStreet(this.form.district_id);
                }
            },
            methods: {
                goBack() {
                    history.go(-1)
                },
                openMember() {
                    this.member_show = true;
                },
                clearMember() {
                    this.form.member_id = null;
                    this.form.member = null;
                },
                getMember() {
                    this.$http.post("{!! yzWebUrl('plugin.event-registration.admin.student.member-query') !!}", {keyword: this.member_keyword}).then(response => {
                        if (response.data.result) {
                            this.member_list = response.data.data
                        } else {
                            this.$message({type: 'error', message: response.data.msg});
                        }
                    }, response => {
                        this.$message({type: 'error', message: response.data.msg});
                        console.log(response);
                    });
                },
                chooseMember(row) {
                    this.form.member_id = row.uid;
                    this.form.member = row;
                    this.member_show = false;
                },
                submit() {

                    let json = {
                        ...this.form
                    }
                    let loading = this.$loading({
                        target: document.querySelector(".content"),
                        background: 'rgba(0, 0, 0, 0)'
                    });
                    this.$http.post('{!! yzWebFullUrl('plugin.event-registration.admin.student.edit-post') !!}', json).then(function (response) {

                        if (response.data.result) {
                            this.$message({message: response.data.msg, type: 'success'});
                            window.location.href = '{!! yzWebFullUrl('plugin.event-registration.admin.student.index') !!}'
                        } else {
                            this.$message({message: response.data.msg, type: 'error'});
                        }
                        loading.close();
                    }, function (response) {
                        this.$message({message: response.data.msg, type: 'error'});
                        loading.close();
                    })
                },
                // 初始化省
                initProvince(val) {
                    this.areaLoading = true;
                    this.$http.get(this.set.area_url_init + '&parent_id=' + val).then(response => {
                        this.province_list = response.data.data;
                        this.areaLoading = false;
                    }, response => {
                        this.areaLoading = false;
                    });
                },
                initCity(val) {
                    this.areaLoading = true;
                    this.$http.get(this.set.area_url + '&parent_id=' + val).then(response => {
                        this.city_list = response.data.data;
                        this.areaLoading = false;
                    }, response => {
                        this.areaLoading = false;
                    });
                },
                initDistrict(val) {
                    this.areaLoading = true;
                    this.$http.get(this.set.area_url + '&parent_id=' + val).then(response => {
                        this.district_list = response.data.data;
                        this.areaLoading = false;
                    }, response => {
                        this.areaLoading = false;
                    });
                },
                initStreet(val) {
                    this.areaLoading = true;
                    this.$http.get(this.set.area_url + '&parent_id=' + val).then(response => {
                        this.street_list = response.data.data;
                        this.areaLoading = false;
                    }, response => {
                        this.areaLoading = false;
                    });
                },
                changeProvince(val) {
                    this.city_list = [];
                    this.district_list = [];
                    this.street_list = [];
                    this.form.city_id = "";
                    this.form.district_id = "";
                    this.form.street_id = "";
                    this.areaLoading = true;
                    let url = this.set.area_url + '&parent_id=' + val;
                    this.$http.get(url).then(response => {
                        if (response.data.data.length) {
                            this.city_list = response.data.data;
                        } else {
                            this.city_list = null;
                        }
                        this.areaLoading = false;
                    }, response => {
                        this.areaLoading = false;
                    });
                }, // 市改变
                changeCity(val) {
                    this.district_list = [];
                    this.street_list = [];
                    this.form.district_id = "";
                    this.form.street_id = "";
                    this.areaLoading = true;
                    console.log(val)
                    let url = this.set.area_url + '&parent_id=' + val;
                    this.$http.get(url).then(response => {
                        if (response.data.data.length) {
                            this.district_list = response.data.data;
                        } else {
                            this.district_list = null;
                        }
                        this.areaLoading = false;
                    }, response => {
                        this.areaLoading = false;
                    });
                }, // 区改变
                changeDistrict(val) {
                    console.log(val)
                    this.street_list = [];
                    this.form.street_id = "";
                    this.areaLoading = true;
                    let url = this.set.area_url + '&parent_id=' + val;
                    this.$http.get(url).then(response => {
                        if (response.data.data.length) {
                            this.street_list = response.data.data;
                        } else {
                            this.street_list = null;
                        }
                        this.areaLoading = false;
                    }, response => {
                        this.areaLoading = false;
                    });
                }, // 新增层级
            }
        });
    </script>
@endsection
