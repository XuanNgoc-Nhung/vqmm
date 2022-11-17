<template>
    <div v-loading.fullscreen.lock="loading.status" :element-loading-text="loading.text"
         element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)">
        <el-row :gutter="24">
            <el-col :span="12" class="text-left mt-5">
                <p>Danh sách phần thưởng thưởng</p>
            </el-col>
            <el-col :span="12" class="text-right mt-5">
                <el-button @click.prevent="hienThiThemMoi()" size="mini" type="primary">Thêm mới</el-button>
            </el-col>
            <el-col :span="24" class="mt-2">
                <div class="table-responsive">
                    <table class="datatable table-bordered table hover-table">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Tên</th>
                            <th class="text-center">Giá trị</th>
                            <th class="text-center">Hình nền</th>
                            <th class="text-center">Tỷ lệ</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">Đã sử dụng</th>
                            <th class="text-center">Còn lại</th>
                            <th class="text-center">Ngày tạo</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                        </thead>
                        <tbody v-if="list_data&&list_data.length">
                        <tr v-for="(item,index) in list_data" :key="index"
                        >
                            <td class="text-center">
                                <p>{{ (paging.currentPage - 1) * paging.limit + index + 1 }}</p>
                            </td>
                            <td>{{ item.name }}</td>
                            <td>{{ parseInt(item.value).toLocaleString() }} vnđ
                            </td>
                            <td class="text-center">
                                <img style="width: 120px; height: 60px" :src="item.image" alt="">
                            </td>
                            <td class="text-center">{{ item.ratio }}%</td>
                            <td class="text-center">{{ item.amount.toLocaleString() }}</td>
                            <td class="text-center">{{ item.used.toLocaleString() }}</td>
                            <td class="text-center">{{ (item.amount - item.used).toLocaleString() }}</td>
                            <td class="text-center">{{ item.created_at }}</td>
                            <td class="text-center">
                                <el-button :disabled="item.status==1" @click.prevent="showChinhSua(item)" size="mini"
                                           type="warning">Chỉnh sửa
                                </el-button>
                                <el-button :disabled="item.status==1" @click.prevent="xoaPhanThuong(item)" size="mini"
                                           type="danger">Xoá
                                </el-button>
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
        <el-row :gutter="24">
            <el-col :span="24">

                <el-dialog
                    append-to-body
                    top="10vh"
                    title="Thêm mới phần thưởng"
                    :close-on-click-modal="false" :close-on-press-escape="false"
                    custom-class="minWidth350"
                    :visible.sync="show_add"
                    width="40%"
                    :before-close="closeModal">
                    <el-row :gutter="24">
                        <el-col :span="24">
                            <el-form label-position="top" label-width="100px" :rules="rulesSearch"
                                     ref="formThemPhanThuong"
                                     :model="thongTinPhanThuong">
                                <el-form-item label="Tên phần thưởng" prop="name">
                                    <el-input v-model="thongTinPhanThuong.name" placeholder="Nhập tên"></el-input>
                                </el-form-item>
                                <el-form-item label="Giá trị" prop="value">
                                    <el-input v-model="thongTinPhanThuong.value" placeholder="Nhập giá trị"></el-input>
                                </el-form-item>
                                <el-form-item label="Tỷ lệ" prop="ratio">
                                    <el-input v-model="thongTinPhanThuong.ratio" placeholder="Nhập tỷ lệ"></el-input>
                                </el-form-item>
                                <el-form-item label="Số lượng" prop="amount">
                                    <el-input v-model="thongTinPhanThuong.amount" placeholder="Nhập tỷ lệ"></el-input>
                                </el-form-item>
                                <p>Hình nền phần thưởng:</p>
                                <div class="source d-flex">
                                    <ul class="el-upload-list el-upload-list--picture-card " style="display: contents">
                                        <li v-if="hinhAnhUpload&&hinhAnhUpload!=''" tabindex="0"
                                            class="el-upload-list__item is-ready" style="min-width: 250px">
                                            <img :src="hinhAnhUpload" alt=""
                                                 class="el-upload-list__item-thumbnail">
                                        </li>
                                        <li>
                                            <div class="">
                                                <el-upload ref="uploadMatTruoc" :show-file-list="false"
                                                           :on-change="uploadHinhAnhPhanTHuong"
                                                           accept=".jpg,.png,.jpeg,.jfif" action="/"
                                                           :auto-upload="false">
                                                    <div tabindex="0" class="el-upload el-upload--picture-card"><i
                                                        class="el-icon-plus"/>
                                                        <p></p></div>
                                                </el-upload>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                            </el-form>
                        </el-col>
                    </el-row>
                    <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="SubmitForm('formThemPhanThuong')">Thêm mới</el-button>
                <el-button type="danger" size="mini" @click="show_add = false">Đóng</el-button>
          </span>
                </el-dialog>

                <el-dialog
                    append-to-body
                    top="10vh"
                    title="Chỉnh sửa phần quà"
                    :close-on-click-modal="false" :close-on-press-escape="false"
                    custom-class="minWidth350"
                    :visible.sync="show_edit"
                    width="40%"
                    :before-close="closeModal">
                    <el-row :gutter="24">
                        <el-col :span="24">
                            <el-form label-position="top" label-width="100px" :rules="rulesSearch"
                                     ref="formChinhSua"
                                     :model="dataEdit">
                                <el-form-item label="Tên phần thưởng" prop="name">
                                    <el-input v-model="dataEdit.name" placeholder="Nhập tên"></el-input>
                                </el-form-item>
                                <el-form-item label="Giá trị" prop="value">
                                    <el-input v-model="dataEdit.value" type="number" placeholder="Nhập giá trị"></el-input>
                                </el-form-item>
                                <el-form-item label="Tỷ lệ" prop="ratio">
                                    <el-input v-model="dataEdit.ratio" type="number" placeholder="Nhập tỷ lệ"></el-input>
                                </el-form-item>
                                <el-form-item label="Số lượng" prop="amount">
                                    <el-input v-model="dataEdit.amount" type="number" placeholder="Nhập tỷ lệ"></el-input>
                                </el-form-item>
                                <el-form-item label="Đã trúng" prop="used">
                                    <el-input v-model="dataEdit.used"
                                              placeholder="Nhập số quà đã quay trúng"></el-input>
                                </el-form-item>
                            </el-form>
                        </el-col>
                    </el-row>
                    <span slot="footer" class="dialog-footer">
                <el-button type="warning" size="mini" @click="SubmitFormChinhSua('formChinhSua')">Chỉnh sửa</el-button>
                <el-button type="danger" size="mini" @click="show_edit = false">Đóng</el-button>
          </span>
                </el-dialog>
            </el-col>
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

Vue.use(ElementUI);
Vue.use(Icon);
export default {
    props: ['info'],
    components: {
        PhanTrang
    },
    data() {
        return {
            loading: {
                status: false,
                text: 'Loading...'
            },
            list_data: [],
            trangbatdau: false,
            paging: {
                total: 0,
                start: 0,
                limit: 10,
                currentPage: 1
            },
            dataSearch: {
                phone: ''
            },
            show_add: false,
            formDataHinhAnh: '',
            hinhAnhUpload: '',
            show_edit: false,
            dataEdit: {
                name: '',
                value: '',
                ratio: '',
                amount: '',
                used: 0,
            },
            thongTinPhanThuong: {
                name: '',
                value: '',
                ratio: '',
                amount: '',
                used: 0,
            },
            rulesSearch: {
                name: [{
                    required: true,
                    message: 'Chưa nhập',
                    trigger: ['change', 'blur']
                }],
                value: [{
                    required: true,
                    message: 'Chưa nhập',
                    trigger: ['change', 'blur']
                }],
                ratio: [{
                    required: true,
                    message: 'Chưa nhập',
                    trigger: ['change', 'blur']
                }],
                amount: [{
                    required: true,
                    message: 'Chưa nhập',
                    trigger: ['change', 'blur']
                }],
                used: [{
                    required: true,
                    message: 'Chưa nhập',
                    trigger: ['change', 'blur']
                }],
            }

        }
    },
    mounted() {
        this.layDanhSachQua()
    },
    methods: {
        SubmitFormChinhSua(formName){
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    this.$confirm('Xác nhận chỉnh sửa phần thưởng?', 'Thông báo', {
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy',
                    })
                        .then(_ => {
                            console.error('Tiến hành đăng ký')
                            this.chinhSuaQua();
                        })
                        .catch(_ => {
                        });

                } else {
                    this.thongBao('error', 'Vui lòng bổ sung các trường thông tin bắt buộc')
                    return false;
                }
            });
        },
        SubmitForm(formName) {
            if (!this.formDataHinhAnh || this.formDataHinhAnh == '') {
                this.thongBao('error', 'Vui lòng bổ sung hình ảnh sản phẩm')
                return;
            }
            console.error('SubmitForm')
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    this.$confirm('Xác nhận thêm mới phần thưởng?', 'Thông báo', {
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy',
                    })
                        .then(_ => {
                            console.error('Tiến hành đăng ký')
                            this.themMoiPhanThuong();
                        })
                        .catch(_ => {
                        });

                } else {
                    this.thongBao('error', 'Vui lòng bổ sung các trường thông tin bắt buộc')
                    return false;
                }
            });
        },
        uploadHinhAnhPhanTHuong(file, fileList) {
            this.hinhAnhUpload = '';
            this.formDataHinhAnh = '';
            fileList.forEach((item, x) => {
                const typeImage = /(png|jpg|jfif|jpeg)$/i
                if (item.raw) {
                    if (typeImage.exec(item.raw.type)) {
                        this.hinhAnhUpload = URL.createObjectURL(item.raw)
                        this.formDataHinhAnh = item.raw
                    }
                }
            })
            console.error("hình ảnh upload:")
            console.error(this.hinhAnhUpload)

            this.$refs.uploadMatTruoc.clearFiles()
            this.$refs.uploadMatTruoc.value = null
        },
        showChinhSua(qua) {
            this.dataEdit = JSON.parse(JSON.stringify(qua));
            this.show_edit = true;
        },
        chinhSuaQua(){
            console.error('Chỉnh sửa')
            console.error(this.dataEdit)

            this.loading.status = false;
            rest_api.post('/admin/chinh-sua-phan-thuong', this.dataEdit).then(
                response => {
                    console.error('Thông tin kết quả đăng ký khoản vay:')
                    console.error(response)
                    if (response && response.data.rc == 0) {
                        this.thongBao('success', response.data.rd)
                        this.closeModal();
                        this.layDanhSachQua()
                    } else {
                        this.thongBao('error', response.data.rd)
                    }
                    this.loading.status = false;
                }
            ).catch((e) => {
                console.error('rơi vào catch')
                this.thongBao('error', 'Vui lòng thử lại sau ít phút.')
                this.loading.status = false;
            })
        },
        xoaPhanThuong(qua) {
            let params = {
                id: qua.id
            }
            this.$confirm('Xác nhận xoá phần quà?', 'Thông báo', {
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy',
            })
                .then(_ => {
                    this.loading.status = false;
                    rest_api.post('/xoa-phan-thuong', params).then(
                        response => {
                            console.error('Thông tin kết quả đăng ký khoản vay:')
                            console.error(response)
                            if (response && response.data.rc == 0) {
                                this.thongBao('success', response.data.rd)
                                this.layDanhSachQua()
                            } else {
                                this.thongBao('error', response.data.rd)
                            }
                            this.loading.status = false;
                        }
                    ).catch((e) => {
                        console.error('rơi vào catch')
                        this.thongBao('error', 'Vui lòng thử lại sau ít phút.')
                        this.loading.status = false;
                    })
                })
                .catch(_ => {
                });
        },
        themMoiPhanThuong() {
            this.loading.text = 'Đang xử lý thông tin...'
            this.loading.status = true;
            var dataForm = new FormData()
            dataForm.append('name', this.thongTinPhanThuong.name)
            dataForm.append('value', this.thongTinPhanThuong.value)
            dataForm.append('ratio', this.thongTinPhanThuong.ratio)
            dataForm.append('amount', this.thongTinPhanThuong.amount)
            dataForm.append('image', this.formDataHinhAnh, this.formDataHinhAnh.name)
            console.error('Trước khi gửi đi')
            rest_api.post('/them-moi-phan-thuong', dataForm).then(
                response => {
                    console.error('Thông tin kết quả đăng ký khoản vay:')
                    console.error(response)
                    if (response && response.data.rc == 0) {
                        this.thongBao('success', response.data.rd)
                        this.closeModal();
                        this.layDanhSachQua()
                    } else {
                        this.thongBao('error', response.data.rd)
                    }
                    this.loading.status = false;
                }
            ).catch((e) => {
                console.error('rơi vào catch')
                this.thongBao('error', 'Vui lòng thử lại sau ít phút.')
                this.loading.status = false;
            })
        },
        closeModal() {
            this.show_add = false;
            this.show_edit = false;
        },
        hienThiThemMoi() {
            this.show_add = true;
        },
        layLai(e) {
            console.log('onPagingClick')
            this.paging.start = e.start;
            this.paging.limit = e.limit;
            this.paging.currentPage = e.currentPage;
            this.layDanhSachQua()
        },
        getDataPage(page) {
            console.error('getDataPage')
            this.trangbatdau = !this.trangbatdau;
        },
        layDanhSachQua() {
            let params = {
                start: this.paging.start,
                limit: this.paging.limit,
                phone: this.dataSearch.phone
            }
            rest_api.post('/admin/danh-sach-qua', params).then(
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
label {
    padding-bottom: 0 !important;
}

p {
    padding: 0;
    margin: 0
}
</style>
