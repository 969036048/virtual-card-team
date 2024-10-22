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
                    <div class="vue-main-title-content">解锁卡密列表</div>
                </div>

            </div>
            <div class="vue-main">
                <div>
                    <div class="vue-main-title" style="margin-bottom:20px">
                        <div class="vue-main-title-left"></div>
                        <div class="vue-main-title-content" style="flex:0 0 120px">解锁卡密列表</div>
                        <div class="vue-main-title-button">
                        </div>
                    </div>
                    <el-table :data="list" style="width: 100%">
                        <el-table-column label="ID" align="center" prop="id"></el-table-column>
                        <el-table-column label="时间" align="center" prop="created_at"></el-table-column>
                        <el-table-column label="卡密名称" align="center" prop="title"></el-table-column>
                        <el-table-column label="商品" align="center">
                            <template slot-scope="scope">
                                <div style="display: flex;align-items: center;justify-content: center" v-if="scope.row.goods && scope.row.goods != null && scope.row.goods.thumb">
                                    <div>
                                        <img :src="scope.row.goods.thumb" alt=""
                                             style="width:40px;height:40px;border-radius:50%">
                                    </div>
                                    <div @click="gotoGoods(scope.row.goods.id)"  style="color:#29BA9C;cursor: pointer;" class="vue-ellipsis">
                                        [[scope.row.goods.title]]
                                    </div>
                                </div>
                            </template>
                        </el-table-column>

                        <el-table-column label="活动时间" align="center">
                            <template slot-scope="scope">
                                [[scope.row.start_time]] - [[scope.row.end_time]]
                            </template>
                        </el-table-column>
                        <el-table-column label="活动人数" align="center">
                            <template slot-scope="scope">
                                [[scope.row.member_count]]
                            </template>
                        </el-table-column>
                        <el-table-column label="活动状态" align="center">
                            <template slot-scope="scope">
                                [[scope.row.status_name]]
                            </template>
                        </el-table-column>

                        <el-table-column prop="refund_time" label="操作" align="center" width="280">
                            <template slot-scope="scope">
                                <div>
                                    <el-link title="打卡记录" :underline="false" @click="addModal(scope.row.id)"
                                             style="text-align: center;width:60px">
                                        打卡记录
                                    </el-link>

                                    <el-link title="奖励记录" :underline="false" @click="deleteModal(scope.row.id)"
                                             style="text-align: center;width:60px">
                                        奖励记录
                                    </el-link>
                                    <el-link title="编辑" :underline="false" @click="addModal(scope.row.id)"
                                             style="text-align: center;width:60px">
                                        编辑
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
                    this.$http.post('{!! yzWebFullUrl('plugin.check-in-rebate.admin.activity.get-data') !!}', requestData).then(function (response) {
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
                gotoGoods(id) {
                    window.open(`{!! yzWebFullUrl('goods.goods.edit') !!}` + `&id=` + id);
                },

                search(val) {
                    this.getData(val);
                },

                changeList() {
                    this.list = [];
                    this.getData(1)
                },
                addModal(id='') {

                    let link = `{!! yzWebFullUrl('plugin.check-in-rebate.admin.activity.detail') !!}`;
                    if (id && id != '') {
                        link = `{!! yzWebFullUrl('plugin.check-in-rebate.admin.activity.detail') !!}` + `&id=` + id;
                    }

                    console.log(link);
                    window.open(link);
                },
                gotoDetail(item) {
                    let link = `{!! yzWebFullUrl('plugin.journal.admin.journal-list.edit') !!}` + `&journal_id=` + item.id;
                    window.location.href = link;
                },
                // 复制活动链接
                copyList(index) {
                    that = this;
                    console.log(that.$refs['list' + index])
                    let Url = that.$refs['list' + index];
                    console.log(Url)
                    Url.select(); // 选择对象
                    document.execCommand("Copy", false);
                    that.$message({message: "复制成功！", type: "success"});
                },
                copyList2(index) {
                    that = this;
                    console.log(that.$refs['small' + index])
                    let Url = that.$refs['small' + index];
                    console.log(Url)
                    Url.select(); // 选择对象
                    document.execCommand("Copy", false);
                    that.$message({message: "复制成功！", type: "success"});
                },
                del(id, index) {
                    console.log(id, index)
                    this.$confirm('确定删除吗', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        let loading = this.$loading({
                            target: document.querySelector(".content"),
                            background: 'rgba(0, 0, 0, 0)'
                        });
                        this.$http.post('{!! yzWebFullUrl('plugin.journal.admin.journal-list.del') !!}', {journal_id: id}).then(function (response) {
                                if (response.data.result) {
                                    this.$message({type: 'success', message: '删除成功!'});
                                } else {
                                    this.$message({type: 'error', message: response.data.msg});
                                }
                                loading.close();
                                this.search(this.current_page)
                            }, function (response) {
                                this.$message({type: 'error', message: response.data.msg});
                                loading.close();
                            }
                        );
                    }).catch(() => {
                        this.$message({type: 'info', message: '已取消删除'});
                    });
                },

            },
        })
    </script>
@endsection