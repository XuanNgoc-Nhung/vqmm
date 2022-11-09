<template>
    <div v-loading.fullscreen.lock="loading.status" :element-loading-text="loading.text"
         element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)">
        <el-row :gutter="24">
<!--            <el-col :xs="15" :sm="12" :md="8" :lg="6">-->
<!--                <label>Số điện thoại</label>-->
<!--                <el-input style="width:100%"  placeholder="Nhập tên số điện thoại quay" v-model="dataSearch.phone"></el-input>-->
<!--            </el-col>-->
<!--            <el-col :xs="9" :sm="12" :md="8" :lg="6">-->
<!--                <el-button style="margin-top:31px" @click.prevent="getDataPage(1)" type="primary">Tìm-->
<!--                    kiếm-->
<!--                </el-button>-->
<!--                <el-button style="margin-top:31px" @click.prevent="getDataPage(1)" type="success">Xuất Excel-->
<!--                </el-button>-->
<!--            </el-col>-->
            <el-col :span="12" class="text-left">
                <p>Danh sách tài khoản trúng thưởng</p>
            </el-col>
            <el-col :span="12" class="text-right">
                <el-button :disabled="list_data.length==0" @click.prevent="downloadFile" size="mini" type="primary">Xuất excel</el-button>
            </el-col>
            <el-col :span="24" class="mt-1">
                <div class="table-responsive">
                    <table class="datatable table-bordered table hover-table">
                        <thead class="thead-light">
                        <tr>
                            <th>STT</th>
                            <th>SĐT</th>
                            <th>Phần thưởng</th>
                            <th>Hình ảnh phần thưởng</th>
                            <th>Thời gian trúng</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody v-if="list_data&&list_data.length">
                        <tr v-for="(item,index) in list_data" :key="index"
                        >
                            <td class="text-center">
                                <p>{{ (paging.currentPage - 1) * paging.limit + index + 1 }}</p>
                            </td>
                            <td>{{ item.phone }}</td>
                            <td>{{ item.name }}
                                <br>
                                Trị giá: {{item.value}} vnđ
                            </td>
                            <td class="text-center">
                                <img style="width: 120px" :src="item.image" alt="">
                            </td>
                            <td class="text-center">{{ item.created_at }}</td>
                            <td class="text-center">
                                <el-button v-if="item.status==1" size="mini" type="success">Đã trao giải</el-button>
                                <el-button v-else size="mini" type="warning">Chưa xử lý</el-button>
                            </td>
                            <td class="text-center">
                                <el-button :disabled="item.status==1" size="mini" type="success">Trao giải</el-button>
                            </td>
                        </tr>
                        </tbody>
                        <tbody v-else>
                        <tr>
                            <td colspan="9" class="text-center">
                                <p>Không có dữ liệu</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </el-col>
            <PhanTrang v-show="paging.total>0" :tongbanghi="paging.total"
                       :batdau="trangbatdau"
                       @pageChange="layLai($event)">
            </PhanTrang>
        </el-row>
    </div>
</template>
<script>
import rest_api from "../../api/rest_api";
import Vue from 'vue';
import ElementUI from 'element-ui';
import PhanTrang from '../Ui/paginate'
import {Icon} from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import exportFromJSON from "export-from-json";

Vue.use(ElementUI);
Vue.use(Icon);
export default {
    props: ['info'],
    components: {
        PhanTrang
    },
    data() {
        return {
            investorsList: [
                {
                    id: 0,
                    name: "Gautam",
                    email: "gautam@example.com",
                    investment: "Stocks",
                },
                {
                    id: 1,
                    name: "Sam",
                    email: "sam@example.com",
                    investment: "Bonds",
                },
                {
                    id: 2,
                    name: "Tim",
                    email: "tim@example.com",
                    investment: "Options",
                },
                {
                    id: 3,
                    name: "Kim",
                    email: "kim@example.com",
                    investment: "Stocks",
                },
                {
                    id: 4,
                    name: "John",
                    email: "john@example.com",
                    investment: "Options",
                },
                {
                    id: 5,
                    name: "Lee",
                    email: "lee@example.com",
                    investment: "Stocks",
                },
                {
                    id: 6,
                    name: "Charlotte",
                    email: "charlotte@example.com",
                    investment: "Options",
                },
                {
                    id: 7,
                    name: "Amy",
                    email: "amy@example.com",
                    investment: "Stocks",
                },
                {
                    id: 8,
                    name: "Mark",
                    email: "mark@example.com",
                    investment: "Bonds",
                },
                {
                    id: 9,
                    name: "Rose",
                    email: "rose@example.com",
                    investment: "Stocks",
                },
            ],
            loading: {
                status: false,
                text: 'Loading...'
            },
            list_data: [],
            trangbatdau:false,
            paging: {
                total: 0,
                start: 0,
                limit: 10,
                currentPage: 1
            },
            dataSearch: {
                phone: ''
            }

        }
    },
    mounted() {
        this.getHistory()
    },
    methods: {
        downloadFile(){
            let params = {
                start: 0,
                limit: 9999999999999,
                phone: ''
            }
            rest_api.post('/admin/danh-sach-trung-qua', params).then(
                response => {
                    if (response && response.data.rc == 0) {
                        let data_export = response.data.history;
                        let du_lieu = [];
                        for (let i = 0; i < data_export.length; i++) {
                            du_lieu.push({
                                'STT':i+1,
                                'SDT':''+data_export[i].phone.toString(),
                                'Phần thưởng':data_export[i].name,
                                'Thời gian':data_export[i].created_at,
                            })
                        }
                        // const data = this.investorsList;
                        const data = du_lieu
                        const fileName = "danh-sach-trung-giai";
                        const exportType = exportFromJSON.types.csv;

                        if (data) exportFromJSON({
                            data:data,
                            fileName:fileName,
                            exportType:exportType,
                            withBOM:true,
                            space:'150'
                        });
                    } else {
                        this.thongBao('error', response.data.rd)
                    }
                    this.loading.status = false;
                }
            ).catch((e) => {
            })

        },
        layLai(e) {
            console.log('onPagingClick')
            this.paging.start = e.start;
            this.paging.limit = e.limit;
            this.paging.currentPage = e.currentPage;
            this.getHistory()
        },
        getDataPage(page){
            console.error('getDataPage')
            this.trangbatdau = !this.trangbatdau;
        },
        getHistory() {
            let params = {
                start: this.paging.start,
                limit: this.paging.limit,
                phone:this.dataSearch.phone
            }
            rest_api.post('/admin/danh-sach-trung-qua', params).then(
                response => {
                    if (response && response.data.rc == 0) {
                        this.thongBao('success', response.data.rd)
                        this.list_data = response.data.history;
                        this.paging.total = response.data.total;
                    } else {
                        this.thongBao('error', response.data.rd)
                    }
                    this.loading.status = false;
                }
            ).catch((e) => {
            })
        },
        thongBao(typeNoty, msgNoty) {
            let msg = "";
            let cl = "";
            if (msgNoty) {
                msg = msgNoty;
            }
            let type = "success";
            if (typeNoty) {
                type = typeNoty
            }
            if (type == "success") {
                cl = "dts-noty-success"
            }
            if (type == "warning") {
                cl = "dts-noty-warning"
            }
            if (type == "error") {
                cl = "dts-noty-error"
            }
            if (type == "info") {
                cl = "dts-noty-info"
            }
            this.$message({
                customClass: cl,
                showClose: true,
                message: msg,
                type: type,
                duration: 3000
            });
        },
    }
}
</script>
<style scoped="scoped">
.el-radio {
    width: 100%;
    text-align: center !important;
}

th {
    font-weight: 400;
    text-align: center
}

.thumbnailBill {
    width: 150px;
    height: 75px;

}

.table > :not(:first-child) {
    border-top: none !important;
}

.el-input.is-disabled .el-input__inner {
    color: black !important;
}

.mauNenXanh {
    background: aquamarine;
}
</style>
