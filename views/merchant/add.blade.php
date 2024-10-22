@extends('layouts.base')
@section('title', trans('基础设置'))
@section('content')
    @include('public.admin.uploadMultimediaImg')

    <link rel="stylesheet" href="{{resource_get('plugins/lakala_pay/views/css/index.css')}}">
    <div class="all">
        <div id="app" v-cloak>
            <el-form ref="form" :model="form" label-width="15%" v-loading="table_loading">
                <div class="vue-main" style="min-height: 500px;">
                    <div class="vue-main-title">
                        <div class="vue-main-title-left"></div>
                        <div class="vue-main-title-content">新增进件</div>
                    </div>

                    <div class="vue-main-form">
                        <el-form-item label="进件POS类型">
                            <el-select style="width:20%;" v-model="form.posType" clearable >
                                <el-option label="传统POS" value="GENERAL_POS"></el-option>
                                <el-option label="智能POS" value="SUPER_POS"></el-option>
                                <el-option label="蓝精灵" value="BLUE_WIZARD"></el-option>
                                <el-option label="专业化扫码" value="WECHAT_PAY"></el-option>
                                <el-option label="收钱吧扫码" value="SQB_SCAN_CODE"></el-option>
                                <el-option label="收钱吧码牌" value="SQB_PAPER_CODE"></el-option>
                                <el-option label="收钱吧桌码" value="SQB_DESK_CODE"></el-option>
                                <el-option label="收钱吧POS" value="SQB_POS"></el-option>
                                <el-option label="新云小店" value="CLOUD_STORE_NEW"></el-option>
                                <el-option label="云分销" value="CLOUD_DISTRIBUTION"></el-option>
                                <el-option label="云分销线上" value="CLOUD_DISTRIBUTION_CB"></el-option>
                                <el-option label="云小店线上" value="CLOUD_STORE_CB"></el-option>
                                <el-option label="云小店线下" value="CLOUD_STORE_BC"></el-option>
                                <el-option label="云小店非收银机" value="CLOUD_STORE_BC_NOTLKL"></el-option>
                                <el-option label="惠码" value="HM"></el-option>
                                <el-option label="惠码线上" value="HM_CB"></el-option>
                                <el-option label="惠码线下" value="HM_BC"></el-option>
                                <el-option label="扫码点餐" value="SCAN_CODE_ORDER"></el-option>
                                <el-option label="B2B收银台" value="B2B_CASHIER_DESK"></el-option>
                                <el-option label="B2B收款码" value="B2B_QR_CODE"></el-option>
                                <el-option label="手机POS" value="MOBILE_POS"></el-option>
                                <el-option label="御风云码" value="YF_YM"></el-option>
                                <el-option label="御风云码线上" value="YF_YM_CB"></el-option>
                                <el-option label="大额理财" value="TRANSFER_ACCOUNT"></el-option>
                                <el-option label="超级收款宝" value="SUPER_MPOS"></el-option>
                                <el-option label="收钱宝盒" value="W_BOX"></el-option>
                                <el-option label="Q码精灵" value="SCANNING_GUN_PAY"></el-option>
                                <el-option label="智能POS PRO" value="WIDE_SUPER_POS"></el-option>
                                <el-option label="云超科技线上" value="YUNCHAO_TECH_CB"></el-option>
                                <el-option label="云超科技线下" value="YUNCHAO_TECH_BC"></el-option>
                            </el-select>
                        </el-form-item>


                        <el-form-item label="机构代码">
                            <el-input v-model="form.orgCode" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                合作方在拉卡拉的标识，请联系业务员
                            </div>
                        </el-form-item>
                        <el-form-item
                                label="商户注册名称">
                            <el-input v-model="form.merRegName" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                长度不小于7个汉字；营业执照商户可填营业执照名称，小微商户入网不得含有“有限公司”。
                            </div>
                        </el-form-item>

                        <el-form-item label="商户地区代码">
                            <el-input v-model="form.merRegDistCode" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                <a href="http://106.14.188.26:8182/attach_files/openplatform/120" target="_blank" title="地区码表NEW.xlsx" style="box-sizing: border-box; background: transparent; color: rgb(65, 131, 196); text-decoration-line: none;">地区码表NEW.xlsx</a>
                            </div>
                        </el-form-item>

                        <el-form-item label="商户详细地址">
                            <el-input v-model="form.merRegAddr" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                去除省，市，区后的详细地址
                            </div>
                        </el-form-item>

                        <el-form-item label="商户MCC编号">
                            <el-input v-model="form.mccCode" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                <a href="http://open.lakala.com/#/home/document/detail?title=MCC%E5%AF%B9%E7%85%A7%E8%A1%A8&id=311" target="_blank" title="MCC对照表" style="box-sizing: border-box; background: transparent; color: rgb(65, 131, 196); text-decoration-line: none;">MCC对照表</a>
                            </div>
                        </el-form-item>

                        <el-form-item label="营业执照名称">
                            <el-input v-model="form.merBlisName" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                小微商户可不填，其它必填
                            </div>
                        </el-form-item>

                        <el-form-item label="营业执照号">
                            <el-input v-model="form.merBlis" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                小微商户可不填，其它必填
                            </div>
                        </el-form-item>

                        <el-form-item label="营业执照开始日期">
                            <el-input v-model="form.merBlisStDt" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                格式（yyyy-MM-dd）有营业执照时必传，否则微信实名认证会失败
                            </div>
                        </el-form-item>

                        <el-form-item label="营业执照有效期">
                            <el-input v-model="form.merBlisExpDt" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                格式（yyyy-MM-dd）有营业执照时必传，否则微信实名认证会失败
                            </div>
                        </el-form-item>


                        <el-form-item label="商户经营内容">
                            <el-select style="width:20%;" v-model="form.merBusiContent" clearable >
                                <el-option label="百货、中介、培训、景区门票等" value="642"></el-option>
                                <el-option label="交通运输售票" value="645"></el-option>
                                <el-option label="电气缴费" value="646"></el-option>
                                <el-option label="政府类" value="647"></el-option>
                                <el-option label="便民类" value="648"></el-option>
                                <el-option label="公立医院、公立学校、慈善" value="649"></el-option>
                                <el-option label="宾馆餐饮娱乐类" value="650"></el-option>
                                <el-option label="房产汽车类" value="651"></el-option>
                                <el-option label="批发类" value="652"></el-option>
                                <el-option label="超市加油类" value="653"></el-option>
                                <el-option label="一般类商户" value="654"></el-option>
                                <el-option label="三农商户" value="655"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="商户法人姓名">
                            <el-input v-model="form.larName" style="width: 35%"></el-input>
                        </el-form-item>

                        <el-form-item label="法人证件类型">
                            <el-select style="width:20%;" v-model="form.larIdType" clearable >
                                <el-option label="身份证" value="01"></el-option>
                                <el-option label="护照" value="02"></el-option>
                                <el-option label="港澳通行证" value="03"></el-option>
                                <el-option label="台胞证" value="04"></el-option>
                                <el-option label="其它证件" value="99"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="法人身份证号码">
                            <el-input v-model="form.larIdcard" style="width: 35%"></el-input>
                        </el-form-item>

                        <el-form-item label="法人身份证开始日期">
                            <el-input v-model="form.larIdcardStDt" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                yyyy-MM-dd
                            </div>
                        </el-form-item>

                        <el-form-item label="法人身份证有效期">
                            <el-input v-model="form.larIdcardExpDt" style="width: 35%"></el-input>
                            <div style="font-size:12px;">
                                yyyy-MM-dd
                            </div>
                        </el-form-item>

                        <el-form-item label="商户联系人手机号码">
                            <el-input v-model="form.merContactMobile" style="width: 35%"></el-input>
                        </el-form-item>

                        <el-form-item label="商户联系人">
                            <el-input v-model="form.merContactName" style="width: 35%"></el-input>
                        </el-form-item>

                        <el-form-item label="结算账户性质">
                            <el-select style="width:20%;" v-model="form.acctTypeCode" clearable >
                                <el-option label="对公" value="57"></el-option>
                                <el-option label="对私" value="58"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="结算账户账号">
                            <el-input v-model="form.acctNo" style="width: 35%"></el-input>
                        </el-form-item>

                        <el-form-item label="结算账户名称">
                            <el-input v-model="form.acctName" style="width: 35%"></el-input>
                        </el-form-item>

                        <el-form-item v-if="form.acctTypeCode == 57" label="结算账户开户行号">
                            <el-input v-model="form.openningBankCode" style="width: 35%"></el-input>
                        </el-form-item>

                        <el-form-item v-if="form.acctTypeCode == 57" label="结算账户开户行名称">
                            <el-input v-model="form.openningBankName" style="width: 35%"></el-input>
                        </el-form-item>

                        <el-form-item v-if="form.acctTypeCode == 57" label="结算账户清算行号">
                            <el-input v-model="form.clearingBankCode" style="width: 35%"></el-input>
                        </el-form-item>

                        <el-form-item  label="结算人证件类型">
                            <el-select style="width:20%;" v-model="form.acctIdType" clearable >
                                <el-option label="身份证" value="01"></el-option>
                                <el-option label="护照" value="02"></el-option>
                                <el-option label="港澳通行证" value="03"></el-option>
                                <el-option label="台胞证" value="04"></el-option>
                                <el-option label="其它证件" value="99"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item  label="结算人证件号码">
                            <el-input v-model="form.acctIdcard" style="width: 35%"></el-input>
                        </el-form-item>
                        <el-form-item  label="结算人证件有效期">
                            <el-input v-model="form.acctIdDt" style="width: 35%"></el-input>
                        </el-form-item>

                        <el-form-item label="手续费率">
                            <el-input v-model="form.feeRatePct" style="width: 35%">
                                <template slot="append">%</template>
                            </el-input>
                        </el-form-item>

                        <div class="vue-main-title">
                            <div class="vue-main-title-content">图片格式jpg、图像大小5M内</div>
                        </div>

                        <el-form-item label="法人身份证正面">
                                    <el-upload
                                            class="avatar-uploader"
                                            action="{{ yzWebFullUrl('upload.uploadV2.upload',['upload_type'=>'image'])}}"
                                            :show-file-list="false"
                                            accept="image/jpg"
                                            :on-success="handleSuccess1"
                                            :before-upload="beforeAvatarUpload">

                                        <img v-if="form.FR_ID_CARD_FRONT" :src="form.FR_ID_CARD_FRONT"   class="avatar">
                                        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                    </el-upload>
                        </el-form-item>
                        <el-form-item label="法人身份证反面">
                            <el-upload
                                    class="avatar-uploader"
                                    action="{{ yzWebFullUrl('upload.uploadV2.upload',['upload_type'=>'image'])}}"
                                    :show-file-list="false"
                                    accept="image/jpg"
                                    :on-success="handleSuccess2"
                                    :before-upload="beforeAvatarUpload">

                                <img v-if="form.FR_ID_CARD_BEHIND" :src="form.FR_ID_CARD_BEHIND"   class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-form-item>
                        <el-form-item label="身份证正面">
                            <el-upload
                                    class="avatar-uploader"
                                    action="{{ yzWebFullUrl('upload.uploadV2.upload',['upload_type'=>'image'])}}"
                                    :show-file-list="false"
                                    accept="image/jpg"
                                    :on-success="handleSuccess3"
                                    :before-upload="beforeAvatarUpload">

                                <img v-if="form.ID_CARD_FRONT" :src="form.ID_CARD_FRONT"   class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-form-item>
                        <el-form-item label="身份证反面">
                            <el-upload
                                    class="avatar-uploader"
                                    action="{{ yzWebFullUrl('upload.uploadV2.upload',['upload_type'=>'image'])}}"
                                    :show-file-list="false"
                                    accept="image/jpg"
                                    :on-success="handleSuccess4"
                                    :before-upload="beforeAvatarUpload">

                                <img v-if="form.ID_CARD_BEHIND" :src="form.ID_CARD_BEHIND"   class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-form-item>
                        <el-form-item label="银行卡">
                            <el-upload
                                    class="avatar-uploader"
                                    action="{{ yzWebFullUrl('upload.uploadV2.upload',['upload_type'=>'image'])}}"
                                    :show-file-list="false"
                                    accept="image/jpg"
                                    :on-success="handleSuccess5"
                                    :before-upload="beforeAvatarUpload">

                                <img v-if="form.BANK_CARD" :src="form.BANK_CARD"   class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-form-item>
                        <el-form-item label="营业执照">
                            <el-upload
                                    class="avatar-uploader"
                                    action="{{ yzWebFullUrl('upload.uploadV2.upload',['upload_type'=>'image'])}}"
                                    :show-file-list="false"
                                    accept="image/jpg"
                                    :on-success="handleSuccess6"
                                    :before-upload="beforeAvatarUpload">

                                <img v-if="form.BUSINESS_LICENCE" :src="form.BUSINESS_LICENCE"   class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                            <div style="color: red">企业商户必传，小微商户可不传</div>
                        </el-form-item>
                        <el-form-item label="商户门头照">
                            <el-upload
                                    class="avatar-uploader"
                                    action="{{ yzWebFullUrl('upload.uploadV2.upload',['upload_type'=>'image'])}}"
                                    :show-file-list="false"
                                    accept="image/jpg"
                                    :on-success="handleSuccess7"
                                    :before-upload="beforeAvatarUpload">

                                <img v-if="form.MERCHANT_PHOTO" :src="form.MERCHANT_PHOTO"   class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-form-item>
                        <el-form-item label="商铺内部照片">
                            <el-upload
                                    class="avatar-uploader"
                                    action="{{ yzWebFullUrl('upload.uploadV2.upload',['upload_type'=>'image'])}}"
                                    :show-file-list="false"
                                    accept="image/jpg"
                                    :on-success="handleSuccess8"
                                    :before-upload="beforeAvatarUpload">

                                <img v-if="form.SHOPINNER" :src="form.SHOPINNER"   class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                            </el-upload>
                        </el-form-item>



                        {{--                        <el-form-item label="结算周期">--}}
{{--                            <el-input v-model="form.settlePeriod" style="width: 35%"></el-input>--}}
{{--                        </el-form-item>--}}
                    </div>

                </div>

                <div class="vue-page">
                    <div class="vue-center">
                        <el-button type="primary" @click="submitForm">保存设置</el-button>
                    </div>
                </div>
            </el-form>
            <upload-multimedia-img :upload-show="uploadShow" :type="typeStatus" :name="chooseImgName" :sel-Num="selNum"
                                   @replace="changeProp" @sure="sureImg"></upload-multimedia-img>
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
                        FR_ID_CARD_FRONT:"",
                        FR_ID_CARD_BEHIND:"",
                        ID_CARD_FRONT:"",
                        ID_CARD_BEHIND:"",
                        BANK_CARD:"",
                        BUSINESS_LICENCE:"",
                        MERCHANT_PHOTO:"",
                        SHOPINNER:"",

                    },
                    private_key_file: '',
                    public_key_file: '',
                    uploadShow: false,
                    typeStatus: '',
                    selNum: '',
                    chooseImgName: '',
                    submit_url: '',
                    showVisible: false,
                    dialogFormVisible: false,
                    table_loading: false,
                }
            },
            mounted() {
                this.getData()
            },
            methods: {
                submitForm() {
                    this.form.save = true
                    this.table_loading = true;

                    this.getData();

                },
                getData() {
                    this.$http.post(`{!! yzWebFullUrl('plugin.lkl-pay.admin.merchant.set') !!}`, this.form).then(function (response) {
                        if (response.data.result) {
                            if (response.data.data) {
                                if (this.form.save == true) {
                                    this.$message({
                                        message: '申请成功',
                                        type: 'success'
                                    });
                                    this.table_loading = false;
                                    window.location.href = "{{ yzWebUrl('plugin.lkl-pay.admin.merchant.index') }}";
                                }
                                this.form = response.data.data

                            }
                        } else {
                            this.$message({
                                message: response.data.msg,
                                type: 'error'
                            });
                            if (this.form.save == true) {
                                this.table_loading = false;
                            }
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
                beforeAvatarUpload(file) {
                    const isJPG = file.type === 'image/jpeg';
                    const isLt2M = file.size / 1024 / 1024 < 2;

                    console.log("isJPG"+isJPG)
                    console.log("isLt2M"+isLt2M)

                    return isJPG && isLt2M;
                },
                handleSuccess1:function (res, file) {
                    this.form.FR_ID_CARD_FRONT = res.data.url;
                },
                handleSuccess2:function (res, file) {
                    this.form.FR_ID_CARD_BEHIND = res.data.url;
                },
                handleSuccess3:function (res, file) {
                    this.form.ID_CARD_FRONT = res.data.url;
                },
                handleSuccess4:function (res, file) {
                    this.form.ID_CARD_BEHIND = res.data.url;
                },
                handleSuccess5:function (res, file) {
                    this.form.BANK_CARD = res.data.url;
                },
                handleSuccess6:function (res, file) {
                    this.form.BUSINESS_LICENCE = res.data.url;
                },
                handleSuccess7:function (res, file) {
                    this.form.MERCHANT_PHOTO = res.data.url;
                },
                handleSuccess8:function (res, file) {
                    this.form.SHOPINNER = res.data.url;
                },

                handleExceed(files, fileList) {
                    this.$message.warning(`当前限制选择 1 个文件，本次选择了 ${files.length} 个文件，共选择了 ${files.length + fileList.length} 个文件`);
                },
                handleOnChangeCert(file, fileList) {
                    // 检查文件类型是不是pem
                    let FileExt = file.name.replace(/.+\./, "");
                    if (['pem'].indexOf(FileExt.toLowerCase()) === -1) {
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
                clearImg(img) {
                    this.form[img] = '';
                    this.form[img + '_url'] = '';
                    this.$forceUpdate();
                },
                openUpload(str, type, sel) {
                    this.chooseImgName = str;
                    this.uploadShow = true;
                    this.typeStatus = String(type)
                    this.selNum = sel
                },
                sureImg(name, uploadShow, fileList) {
                    debugger
                    if (fileList.length <= 0) {
                        return
                    }
                    this.form[name] = fileList[0].attachment
                    this.form[name + '_url'] = fileList[0].url
                },
                changeProp(val) {
                    if (val == true) {
                        this.uploadShow = false;
                    } else {
                        this.uploadShow = true;
                    }
                },
                handleOnChangeKey(file, fileList) {
                    let FileExt = file.name.replace(/.+\./, "");
                    if (['cer'].indexOf(FileExt.toLowerCase()) === -1) {
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
    <style>
        .avatar{
            width: 200px;
            height: 200px;
        }
    </style>
@endsection