@extends('layouts.base')
@section('title', trans('基础设置'))
@section('content')
    <link rel="stylesheet" href="{{resource_get('plugins/paypal/views/css/index.css')}}">
    <div class="all">
        <div id="app" v-cloak>
            <el-form ref="form" :model="form" label-width="15%">
                <div class="vue-main" style="min-height: 500px;">
                    <div class="vue-main-title">
                        <div class="vue-main-title-left"></div>
                        <div class="vue-main-title-content">基础设置</div>
                    </div>

                    <div class="vue-main-form" width="50%">
                        <el-form-item label="插件开关">
                            <el-switch v-model="form.is_open" :active-value=1
                                       :inactive-value=0>
                            </el-switch>
                        </el-form-item>

                        <el-form-item label="请选择赠送的卡密类型">
                            <el-select v-model="form.virtual_card_type_id" style="width:70%" placeholder="请选择类型"
                                       clearable filterable allow-create default-first-option>
                                <el-option v-for="item in virtual_type" :key="item.id" :label="item.field_name"
                                           :value="item.id">[[item.field_name]]
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="商品">
                            <div style="display:flex;flex-wrap: wrap;">
                                <div class="good" v-for="(item,index,key) in form.goods_list"
                                     style="width:150px;display:flex;margin-right:20px;flex-direction: column">
                                    <div class="img" style="position:relative;">
                                        <a style="color:#333;">
                                            <div style="width: 16px;height: 16px;background-color: #dde2ee;display:flex;align-items:center;justify-content:center;position:absolute;right:-10px;top:-10px;border-radius:50%;"
                                                 @click="delPeople(item)">X
                                            </div>
                                        </a>
                                        <img :src="item.thumb" style="width:150px;height:150px;">
                                    </div>
                                    <div style="display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;overflow: hidden;font-size:12px;">
                                        [[item.title]]
                                    </div>
                                </div>
                                <div class="upload-box" @click="openPeople(0)">
                                    <a style="font-size:32px;color: #3F4857;"><i class="el-icon-plus"></i></a>
                                </div>
                            </div>
                            <div class="tip">
                                成为经销商时购买的商品为设置的商品中的任意一个即可获得预计虚拟卡密，一个会员只生效一次
                            </div>
                        </el-form-item>
                        <el-form-item label="赠送预计虚拟卡密数量">
                            <el-input style="width: 100px" v-model="form.after_send"
                                      placeholder="请输入数量"></el-input>
                        </el-form-item>

                        <el-form-item label="解冻商品">
                            <div style="display:flex;flex-wrap: wrap;">
                                <div class="good" v-for="(item,index,key) in form.member_goods_list"
                                     style="width:150px;display:flex;margin-right:20px;flex-direction: column">
                                    <div class="img" style="position:relative;">
                                        <a style="color:#333;">
                                            <div style="width: 16px;height: 16px;background-color: #dde2ee;display:flex;align-items:center;justify-content:center;position:absolute;right:-10px;top:-10px;border-radius:50%;"
                                                 @click="delPeople(item)">X
                                            </div>
                                        </a>
                                        <img :src="item.thumb" style="width:150px;height:150px;">
                                    </div>
                                    <div style="display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 2;overflow: hidden;font-size:12px;">
                                        [[item.title]]
                                    </div>
                                </div>
                                <div class="upload-box" @click="openPeople(1)">
                                    <a style="font-size:32px;color: #3F4857;"><i class="el-icon-plus"></i></a>
                                </div>
                            </div>
                            <div class="tip">
                                经销商下级以及经销商自己购买商品后，经销商的预计虚拟卡密转换为实际卡密，解冻商品为设置的商品中的任意一个即可获得实际卡密
                            </div>
                        </el-form-item>
                        <el-form-item label="购买一个解冻商品转换实际卡密数量">
                            <el-input style="width: 100px" v-model="form.after_send_goods"
                                      placeholder="请输入数量"></el-input>
                        </el-form-item>
                    </div>

                </div>

                <div class="vue-page">
                    <div class="vue-center" style="text-align: center">
                        <el-button type="primary" @click="submitForm">保存设置</el-button>
                    </div>
                </div>
            </el-form>
            <el-dialog :visible.sync="peopleShow" width="60%" center title="选择会员">
                <div style="text-align:center;">
                    <el-input style="width:80%" v-model="keyword"></el-input>
                    <el-button @click="getGoods" style="margin-left:10px;" type="primary">搜索</el-button>
                </div>
                <el-table :data="goods_list" style="width: 100%;height:500px;overflow:auto">
                    <el-table-column label="商品信息" align="center">
                        <template slot-scope="scope">
                            <div v-if="scope.row" style="display:flex;align-items: center;">
                                <img v-if="scope.row.thumb" :src="scope.row.thumb" style="width:50px;height:50px"/>
                                <div style="margin-left:10px">[[scope.row.title]]</div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="商品ID" prop="id" align="center"></el-table-column>
                    <el-table-column prop="refund_time" label="操作" align="center">
                        <template slot-scope="scope">
                            <el-button @click="surePeople(scope.row)">
                                选择
                            </el-button>

                        </template>
                    </el-table-column>
                </el-table>
            </el-dialog>
            <upload-img :upload-show="uploadShow" :name="chooseImgName" @replace="changeProp"
                        @sure="sureImg"></upload-img>
        </div>
    </div>
    @include('public.admin.uploadImg')
    <script>
        let app = new Vue({
            el: "#app",
            delimiters: ['[[', ']]'],
            data() {
                return {
                    form: {
                        is_open: 0,
                        goods_list: [],
                        member_goods_list: [],
                    },
                    virtual_type: [],
                    goods_list: [],
                    peopleShow: false,
                    keyword: '',
                    uploadShow: false,
                    chooseImgName: '',
                    after_send_goods: 0,
                    type: 0,
                }
            },
            mounted() {
                this.getData()
            },
            methods: {
                submitForm() {
                    let json = {
                        form_data: {
                            ...this.form
                        }
                    }
                    this.$http.post(`{!! yzWebFullUrl('plugin.virtual-card-team.admin.basic.set') !!}`, json).then(function (response) {
                        if (response.data.result) {
                            if (response.data.data) {
                                this.form = {...response.data.data}
                                this.$message({
                                    message: response.data.msg,
                                    type: 'success'
                                });
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
                getData() {
                    this.$http.post(`{!! yzWebFullUrl('plugin.virtual-card-team.admin.basic.get-data') !!}`)
                        .then(function (response) {
                            if (response.data.result) {
                                if (response.data.data) {
                                    this.form = {
                                        ...this.form,
                                        ...response.data.data.set
                                    }
                                    this.virtual_type = response.data.data.virtual_type
                                }
                            }
                        });
                },

                delPeople(item) {
                    if (this.type === 1) {
                        this.form.member_goods_list.forEach((list, index) => {
                            if (list.id == item.id) {
                                this.form.member_goods_list.splice(index, 1)
                            }
                        })
                    } else {
                        this.form.goods_list.forEach((list, index) => {
                            if (list.id == item.id) {
                                this.form.goods_list.splice(index, 1)
                            }
                        })
                    }
                },
                openPeople(type) {
                    this.type = type
                    this.peopleShow = true;
                },
                surePeople(item) {
                    var status = 0;
                    if (this.type === 1) {
                        console.log(this.form.member_goods_list)
                        if (this.form.member_goods_list.length > 0) {
                            this.form.member_goods_list.some((list, index, key) => {
                                if (list.id == item.id) {
                                    status = 1
                                    this.$message({message: '商品已被选中', type: 'error'});
                                    return true
                                }
                            })
                        }
                        if (status == 1) {
                            return false
                        }
                        this.form.member_goods_list.push(item)

                    } else {
                        if (this.form.goods_list.length > 0) {
                            this.form.goods_list.some((list, index, key) => {
                                if (list.id == item.id) {
                                    status = 1
                                    this.$message({message: '商品已被选中', type: 'error'});
                                    return true
                                }
                            })
                        }
                        if (status == 1) {
                            return false
                        }
                        this.form.goods_list.push(item)
                    }

                },
                getGoods() {
                    this.$http.post("{!! yzWebUrl('plugin.virtual-card-team.admin.basic.get-goods-list') !!}", {keyword: this.keyword}).then(response => {
                        if (response.data.result) {
                            this.goods_list = response.data.data.data
                        } else {
                            this.$message({type: 'error', message: response.data.msg});
                        }
                    }, response => {
                        this.$message({type: 'error', message: response.data.msg});
                        console.log(response);
                    });
                },
                changeProp(val) {
                    if (val == true) {
                        this.uploadShow = false;
                    } else {
                        this.uploadShow = true;
                    }
                },
                sureImg(name, image, image_url) {
                    this.form.top_thumb = image;
                    this.form.top_thumb_url = image_url;
                },
            }
        })
    </script>
    <style>
    </style>
@endsection