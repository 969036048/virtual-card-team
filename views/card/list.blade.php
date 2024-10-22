@extends('layouts.base')
@section('title', "预计卡密列表")
@section('content')
    <link rel="stylesheet" type="text/css" href="{{static_url('yunshop/goods/vue-goods1.css')}}"/>
    <style>
        /* 导航 */
        .el-radio-button .el-radio-button__inner, .el-radio-button:first-child .el-radio-button__inner {
            border-radius: 4px 4px 4px 4px;
            border-left: 0px;
        }

        .el-radio-button__inner {
            border: 0;
        }

        .el-radio-button:last-child .el-radio-button__inner {
            border-radius: 4px 4px 4px 4px;
        }
    </style>
    <div class="all">
        <div id="app" v-cloak>
            <div class="vue-head">
                <div class="vue-main-title" style="margin-bottom:20px">
                    <div class="vue-main-title-left"></div>
                    <div class="vue-main-title-content">预计卡密列表</div>
                </div>
                <div class="vue-search">
                    <el-form :inline="true" :model="search_form" class="demo-form-inline">

                        <el-form-item label="">
                            <el-input v-model="search_form.member" placeholder="会员昵称/Id/手机号"></el-input>
                        </el-form-item>

                        <el-form-item>
                            <el-date-picker
                                    v-model="search_form.time"
                                    type="datetimerange"
                                    value-format="timestamp"
                                    range-separator="至"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期"
                                    align="right">
                            </el-date-picker>
                        </el-form-item>
                        <el-form-item>
                            <el-button-group>
                                <el-button :type="buttonType(1)" @click="changeBtn(1)">今</el-button>
                                <el-button :type="buttonType(2)" @click="changeBtn(2)">昨</el-button>
                                <el-button :type="buttonType(3)" @click="changeBtn(3)">近7天</el-button>
                                <el-button :type="buttonType(4)" @click="changeBtn(4)">近30天</el-button>
                                <el-button :type="buttonType(5)" @click="changeBtn(5)">近1年</el-button>
                            </el-button-group>

                        </el-form-item>

                        <el-form-item label="">
                            <el-button type="primary" @click="search(1)">搜索</el-button>
                            {{--<el-button @click="export1()">导出</el-button>--}}
                        </el-form-item>
                    </el-form>

                </div>
            </div>
            <div class="vue-main">
                <div>
                    <div class="vue-main-title" style="margin-bottom:20px">
                        <div class="vue-main-title-left"></div>
                        <div class="vue-main-title-content" style="flex:0 0 120px">预计卡密列表</div>
                        <div class="vue-main-title-button">
                        </div>
                    </div>
                    <el-table :data="list" style="width: 100%">
                        <el-table-column label="ID" align="center" prop="id"></el-table-column>
                        <el-table-column label="时间" align="center" prop="created_at"></el-table-column>
                        <el-table-column label="卡密名称" align="center">
                            <template slot-scope="scope">
                                [[scope.row.has_virtual_card.name]]
                            </template>
                        </el-table-column>
                        <el-table-column label="会员" align="center">
                            <template slot-scope="scope">
                                <div style="display: flex;align-items: center;justify-content: center" v-if="scope.row.member && scope.row.member != null && scope.row.member.avatar">
                                    <div>
                                        <img :src="scope.row.member.avatar" alt=""
                                             style="width:40px;height:40px;border-radius:50%">
                                    </div>
                                    <div @click="gotoMember(scope.row.member.uid)"  style="color:#29BA9C;cursor: pointer;" class="vue-ellipsis">
                                        [[scope.row.member.nickname]]
                                    </div>
                                </div>
                            </template>
                        </el-table-column>


                        <el-table-column label="订单号" align="center">
                            <template slot-scope="scope">
                                [[scope.row.order.order_sn]]
                            </template>
                        </el-table-column>
                        <el-table-column label="预计卡密数量" align="center">
                            <template slot-scope="scope">
                                [[scope.row.card_num]]
                            </template>
                        </el-table-column>
                        <el-table-column label="已发放卡密数量" align="center">
                            <template slot-scope="scope">
                                [[scope.row.card_num_give]]
                            </template>
                        </el-table-column>

                        <el-table-column prop="refund_time" label="操作" align="center" width="280">
                            <template slot-scope="scope">
                                <div>


                                    <el-link title="发放记录" :underline="false" @click="addModal(scope.row.id)"
                                             style="text-align: center;width:60px">
                                        发放记录
                                    </el-link>
                                </div>
                            </template>
                        </el-table-column>

                    </el-table>
                </div>
            </div>
            <!-- 分页 -->
            <div class="vue-page" v-if="total>0">
                <el-row>
                    <el-col align="right">
                        <el-pagination layout="prev, pager, next,jumper" @current-change="search" :total="total"
                                       :page-size="per_page" :current-page="current_page" background
                        ></el-pagination>
                    </el-col>
                </el-row>
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: "#app",
            delimiters: ['[[', ']]'],
            name: 'test',
            data() {
                return {
                    list: [],
                    search_form: {
                        goods_id: '',
                        goods_kwd: '',
                        title: '',
                        status: '',
                        time: [],
                    },
                    times: [],

                    rules: {},
                    current_page: 1,
                    total: 1,
                    per_page: 1,
                    showSelectTimeRange: false,
                    options: [
                        {
                            value: 0,
                            label: '未开赛'
                        },
                        {
                            value: 1,
                            label: '进行中'
                        },
                        {
                            value: 2,
                            label: '已结束'
                        }
                    ],
                }
            },
            created() {

            },
            mounted() {
                this.getData(1);
            },
            methods: {
                buttonType(val) {
                    if (this.time_btn == val) {
                        return "primary"
                    } else {
                        return "default"
                    }
                },
                changeBtn(num) {
                    let end
                    let start
                    num = num.toString()
                    if (this.time_btn == num) {
                        this.time_btn = ''
                        this.$set(this.search_form, "time", [])
                    } else {
                        this.time_btn = num
                        switch (num) {
                            case "1":
                                start = new Date(new Date().setHours(0, 0, 0, 0)); //获取当天零点的时间
                                end = new Date(new Date().setHours(0, 0, 0, 0) + 24 * 60 * 60 * 1000 - 1); //获取当天23:59:59的时间
                                break
                            case "2":
                                start = new Date(new Date().setHours(0, 0, 0, 0) - 86400 * 1000); //获取当天零点的时间
                                end = new Date(new Date().setHours(0, 0, 0, 0) + 24 * 60 * 60 * 1000 - 86401 * 1000); //获取当天23:59:59的时间
                                break
                            case "3":
                                end = new Date(new Date().setHours(0, 0, 0, 0) + 24 * 60 * 60 * 1000 - 1);
                                start = new Date(end.getTime() - 3600 * 24 * 7 * 1000 + 1000)
                                break
                            case "4":
                                end = new Date(new Date().setHours(0, 0, 0, 0) + 24 * 60 * 60 * 1000 - 1);
                                start = new Date(end.getTime() - 3600 * 24 * 30 * 1000 + 1000)
                                break
                            case "5":
                                end = new Date(new Date().setHours(0, 0, 0, 0) + 24 * 60 * 60 * 1000 - 1);
                                start = new Date(end.getTime() - 3600 * 24 * 365 * 1000 + 1000)
                                break
                        }
                        console.log(212121)
                        let timestart = parseInt(start.getTime() / 1000) * 1000
                        let timeend = parseInt(end.getTime() / 1000) * 1000

                        this.$set(this.search_form, "time", [timestart, timeend])
                        this.$set(this.search_form, "start_time", start)
                        this.$set(this.search_form, "end_time", end)
                    }
                    // 点击时去执行
                    let target = event.target;
                    if (target.nodeName == "SPAN") {
                        target = event.target.parentNode;
                    }
                    target.blur();
                },
                getData(page) {
                    let requestData = {
                        goods_id: this.goods_id,
                        uid: this.uid,
                        page: page,
                        code: this.code,
                        search: JSON.parse(JSON.stringify(this.search_form)),
                    };
                    if (this.times && this.times.length > 0) {
                        requestData.search.start_time = this.times[0];
                        requestData.search.end_time = this.times[1];
                    }
                    requestData.search.sid = this.sid;

                    console.log(requestData);
                    let loading = this.$loading({
                        target: document.querySelector(".content"),
                        background: 'rgba(0, 0, 0, 0)'
                    });
                    this.$http.post('{!! yzWebFullUrl('plugin.virtual-card-team.admin.card.get-data') !!}', requestData).then(function (response) {
                        if (response.data.result) {
                            this.list = response.data.data.data;
                            this.current_page = response.data.data.current_page;
                            this.total = response.data.data.total;
                            this.per_page = response.data.data.per_page;
                            loading.close();
                        } else {
                            this.$message({
                                message: response.data.msg,
                                type: 'error'
                            });
                        }
                        loading.close();
                    }, function (response) {
                        this.$message({
                            message: response.data.msg,
                            type: 'error'
                        });
                        loading.close();
                    });
                },
                gotoMember(id) {
                    window.location.href = `{!! yzWebFullUrl('member.member.detail') !!}` + `&id=` + id;
                },

                search(val) {
                    this.getData(val);
                },

                changeList() {
                    this.list = [];
                    this.getData(1)
                },
                addModal(id = '') {

                    link = `{!! yzWebFullUrl('plugin.check-in-rebate.admin.activity.detail') !!}` + `&id=` + id;
                    window.open(link);
                },
            },
        })
    </script>
@endsection