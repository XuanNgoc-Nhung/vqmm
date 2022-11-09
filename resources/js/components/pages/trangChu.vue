<template>
    <div v-loading.fullscreen.lock="loading.status" :element-loading-text="loading.text"
         element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)">
        <el-row :gutter="24">
            <el-col :sm="24" :xs="24" :md="12" style="text-align: center; margin-bottom:30px">
                <el-input placeholder="Nhập số điện thoại để nhận lượt quay" style="max-width: 60%"
                          v-model="sdtQuay">
                    <el-button slot="append" @click.prevent="checkSdt(num)"
                               style="background-color: #409EFF;color: white" type="success">
                        Nhận lượt quay
                    </el-button>
                </el-input>
                <img class="emBeCamHoa" src="http://luckywheel.snbshop.vn/images/themes/content.png" alt="">
            </el-col>
            <el-col :sm="24" :xs="24" :md="12">
                <!--                <div style="text-align:center">-->
                <!--                    <el-input placeholder="Nhập số điện thoại để nhận lượt quay" style="max-width: 80vw"-->
                <!--                              v-model="sdtQuay">-->
                <!--                        <el-button slot="append" @click.prevent="checkSdt(num)"-->
                <!--                                   style="background-color: #409EFF;color: white" type="success">-->
                <!--                            Lấy lượt quay-->
                <!--                        </el-button>-->
                <!--                    </el-input>-->
                <!--                </div>-->
                <div class="lucky-wheel" style="margin: 0 auto; padding-top:50px;">
                    <div class="pointer-container">
                        <div class="pointer" ref="pointer" id="pointer"
                             :style="{'transform':rotate_deg,'transition': prize_transition}"
                             @click="layKetQua(num)">
                        </div>
                    </div>
                    <!-- lucky wheel -->
                    <div :class="containerClass">
                        <div v-for="(item,index) in prizes" :key="item.name" ref="item" :class="itemClass"  :style="{ background: 'url('+item.img+')' }">
                            <div :class="contentClass">
                               <span style="font-size: 18px;color:red;" class="tenVatPham">{{ item.name }}
            </span>
                            </div>
                        </div>
                    </div>

                    <!-- display results -->
                    <transition name="slide-fade">
                        <div class="prize" v-if="isShow==isClicked">
                            <div class="prize-container">
                                <div class="prize-title">WELL<br/> DONE!</div>
                                <div class="prize-title">YOU GET A FREE...<br/>
                                    <span class="prize-item">{{ prize_name }}</span>
                                </div>
                                <div class="prize-background">
                                    <template v-for="(prizeIcon,index) in 9">
                                        <i class="material-icons">
                                            {{ prize_icon }}
                                        </i>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </el-col>
            <el-col :span="24">

                <el-dialog
                    title="Thông báo"
                    :visible.sync="noti.status"
                    width="30%"
                    custom-class="minWidth375"
                    center>
                    <span>{{ noti.mess }}</span>
                    <span slot="footer" class="dialog-footer">
    <el-button size="mini" type="danger" @click="noti.status = false">Đóng</el-button>
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
import {Icon} from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

Vue.use(ElementUI);
Vue.use(Icon);
export default {
    props: [],
    data() {
        return {
            noti: {
                status: false,
                mess: ''
            },
            loading: {
                status: false,
                text: 'Loading...'
            },

            prizes: [],
            prizes_2017: [
                {
                    "name": "SP  1",
                    "icon": "cake",
                    "count": 5
                },
                {
                    "name": "SP  2",
                    "icon": "stars",
                    "count": 5
                },
                {
                    "name": "SP  3",
                    "icon": "child_care",
                    "count": 4
                },
                {
                    "name": "SP  4",
                    "icon": " flight",
                    "count": 1
                },
                {
                    "name": "SP  5",
                    "icon": "wifi",
                    "count": 5
                },
                {
                    "name": "SP  6",
                    "icon": "movie_filter",
                    "count": 0
                },
                {
                    "name": "SP 7",
                    "icon": "movie_filter",
                    "count": 0
                },
                {
                    "name": "SP 8",
                    "icon": "movie_filter",
                    "count": 0
                },
            ],
            prizes_2018: [],
            prize_name: '',
            prize_icon: '',
            prize_rotate: [],
            prize_transition: '',
            each_deg: 0,
            rotate_deg: 0,
            start_deg: 0,
            current_deg: 0,
            index: 0,
            current_year: 2017,
            duration: 6000,
            time_remaining: 20,
            num: 0,
            numbers: [],//紀錄還有獎品的編號
            isToggle: false,//顯示隱藏按鈕
            isClicked: false,//轉動中禁止觸發
            isShow: true,
            idTrungThuong: '',
            sdtQuay: '',
            duocPhepQuay: false,
        }
    },
    computed: {
        // 判斷轉盤 class
        containerClass() {
            let vm = this
            return vm.current_year === 2017 ? 'container' : 'container container-large'
        },
        itemClass() {
            let vm = this
            return vm.current_year === 2017 ? 'item item-skew' : 'item item-skew-large'
        },
        contentClass() {
            let vm = this
            return vm.current_year === 2017 ? 'item-content' : 'item-content item-content-large'
        },
        countClass() {
            let vm = this
            return vm.current_year === 2017 ? 'count' : 'count count-large'
        }
    },
    mounted() {
        this.getAllItem();
    },
    methods: {
        showNoti(mess) {
            this.noti.mess = mess
            this.noti.status = true;
        },
        checkSdt(num) {
            console.error('Check SĐT')
            if (!this.sdtQuay || this.sdtQuay == '') {
                this.showNoti('Vui lòng nhập số điện thoại.')
                return
            }
            let phonePattern = new RegExp('(84|0[3|5|7|8|9])+([0-9]{8})');
            let checkPattern = phonePattern.test(this.sdtQuay);
            if(!checkPattern){
                this.showNoti('Định dạng số điện thoại không hợp lệ. Vui lòng kiểm tra lại.')
                return
            }
            if (this.sdtQuay.length>10) {
                this.showNoti('Định dạng số điện thoại không hợp lệ. Vui lòng kiểm tra lại.')
                return
            }
            console.error('checkSdt')
            let params = {}
            let url = 'get-spin/' + this.sdtQuay
            this.duocPhepQuay = false;
            rest_api.post(url, params).then(
                response => {
                    console.error('Thông tin check sđt:')
                    console.error(response.data)
                    if (response.data.status == true) {
                        // this.thongBao('success', 'Chúc mừng bạn nhận được 1 lượt quay từ chương trình.')

                        this.showNoti('Chúc mừng bạn nhận được 1 lượt quay từ chương trình.')
                        this.duocPhepQuay = true;
                    } else {
                        // this.thongBao('error', 'Số điện thoại này đã quay trong ngày')
                        this.showNoti('Số điện thoại này đã quay trong ngày')
                    }
                }
            ).catch((e) => {
                console.error('rơi vào catch')
                // this.thongBao('error', 'Vui lòng thử lại sau ít phút.')
                this.showNoti('Vui lòng thử lại sau ít phút')
                this.loading.status = false;
            })
        },
        layKetQua() {
            if (!this.duocPhepQuay || this.duocPhepQuay == false) {
                // this.thongBao('error', 'Vui lòng nhập số điện thoại rồi nhận lượt quay')
                this.showNoti('Vui lòng nhập số điện thoại rồi nhận lượt quay')
                return;
            }
            console.error('checkSdt')
            let params = {}
            let url = '/result/' + this.sdtQuay
            this.duocPhepQuay = false;
            rest_api.post(url, params).then(
                response => {
                    console.error('Thông tin check sđt:')
                    console.error(response.data)
                    if (response.data.id) {
                        let qua = response.data.id
                        this.idTrungThuong = qua;
                        this.rotateHandler(qua)
                    } else {
                        // this.thongBao('error', 'Số điện thoại này đã quay trong ngày')
                        this.showNoti('Số điện thoại này đã quay trong ngày')
                    }
                }
            ).catch((e) => {
                console.error('rơi vào catch')
                // this.thongBao('error', 'Vui lòng thử lại sau ít phút.')
                this.showNoti('Vui lòng thử lại sau ít phút')
                this.loading.status = false;
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
        getAllItem() {
            console.error('lấy toàn bộ item')
            let params = {
                start: 0,
                limit: 100
            }
            rest_api.post('/admin/danh-sach-qua', params).then(
                response => {
                    console.error('Thông tin vật phẩm:')
                    let data = response.data.history;
                    let list_qua = [];
                    for (let i = 0; i < data.length; i++) {
                        list_qua.push({name: data[i].name, id: data[i].id,img:data[i].image})
                    }
                    console.error(list_qua)
                    this.prizes_2017 = list_qua;
                    this.initPrize();
                }
            ).catch((e) => {
                console.error('rơi vào catch')
                // this.thongBao('error', 'Vui lòng thử lại sau ít phút.')
                this.showNoti('Vui lòng thử lại sau ít phút')
                this.loading.status = false;
            })
        },
        prizeActive() {
            console.error('prizeActive')
            // 抽到獎品後變更 item 的 css
            let vm = this
            setTimeout(() => {
                vm.$refs.item[vm.index].classList.value = `${vm.itemClass} active`
            }, vm.duration);
            console.log('item active')
        },
        setCurrentYear(year) {
            console.error('setCurrentYear')
            let vm = this
            if (vm.isClicked) return
            vm.current_year = year
        },
        restart() {
            console.error('restart')
            let vm = this
            vm.$refs.item[vm.index].classList.value = vm.itemClass
            if (vm.current_year === 2017) {
                console.error('case 2017')
                vm.time_remaining = 20
                vm.reset()
                vm.initPrize()
            } else if (vm.current_year === 2018) {
                console.error('case 2018')
                vm.time_remaining = 120
                vm.reset()
                vm.initPrize_2018()
            }
        },
        reset() {
            console.error('reset')
            let vm = this
            vm.isShow = true
            vm.index = 0
            vm.prize_name = ''
            vm.prize_icon = ''
            vm.prize_rotate = []
            vm.numbers = []
            vm.start_deg = 0
            vm.rotate_deg = `rotate(0deg)`
            vm.current_deg = 0
            vm.isClicked = false
            vm.prize_transition = `none`;
            console.log('RESET')
        },
        initPrize() {
            console.error('initPrize')
            let vm = this
            // axios.get('./prize20.json')
            //   .then(function (response) {
            // vm.prizes_2017 = JSON.parse(response.request.responseText)

            vm.num = vm.prizes_2017.length
            console.error('Độ dài:')
            console.error(vm.num)
            vm.degree(vm.num)
            vm.prizes = vm.prizes_2017
            vm.numberArray()
            // })
            // .catch(function (error) {
            //   console.log(error);
            // });
        },
        initPrize_2018() {
            console.error('initPrize_2018')
            let vm = this
            vm.prizes_2018 = []
            for (let i = 1; i <= 120; i++) {
                let item = {}
                if (i === 1) {
                    item.name = 1
                    item.count = 1
                    vm.prizes_2018.push(item)
                } else if (i > 1 && i <= 16) {
                    item.name = i
                    item.count = 1
                    vm.prizes_2018.push(item)
                } else if (i === 17) {
                    item.name = i
                    item.count = 5
                    vm.prizes_2018.push(item)
                } else if (i === 18) {
                    item.name = i
                    item.count = 10
                    vm.prizes_2018.push(item)
                } else if (i === 19) {
                    item.name = i
                    item.count = 20
                    vm.prizes_2018.push(item)
                } else if (i === 20) {
                    item.name = i
                    item.count = 69
                    vm.prizes_2018.push(item)
                }
            }
            vm.num = vm.prizes_2018.length
            vm.prizes = vm.prizes_2018
            vm.degree(vm.num)
            vm.numberArray()
        },
        degree(num) {
            console.error('degree')
            console.error(num)
            // 計算每個轉盤角度
            let vm = this
            for (let i = 1; i <= num; i++) {
                let deg = 360 / num
                vm.each_deg = deg
                let eachDeg
                eachDeg = i * deg
                vm.prize_rotate.push(eachDeg)
            }
        },
        numberArray() {
            console.error('numberArray')
            // 產生獎品 index 編號 => [0,1,2,3,4,5]
            let vm = this
            console.error(vm.prizes)
            vm.numbers = vm.prizes.map((prize, index) => {
                return index
            })
        },
        rotateHandler(num) {
            console.error('rotateHandler')
            let vm = this
            // 刪去沒有獎品的 index
            vm.prizes.filter((prize, index) => {
                let filterArray
                if (prize.count <= 0) {
                    let filterArray = vm.numbers.filter((num) => {
                        return num !== index
                    })
                    vm.numbers = filterArray
                }
            })

            if (vm.time_remaining > 0) {
                vm.$refs.item[vm.index].classList.value = vm.itemClass
                // 執行旋轉
                vm.prize_draw(num)
            } else if (vm.time_remaining <= 0) {
                vm.$refs.item[vm.index].classList.value = vm.itemClass
                vm.restart()
            }
        },
        prize_draw(num) {
            console.error('prize_draw')
            console.error(num)
            // 執行抽獎
            let vm = this
            if (vm.isClicked) return
            vm.isShow = vm.isClicked

            // 移除抽到獎品 active 狀態
            vm.$refs.item[vm.index].classList.value = vm.itemClass

            // 取出 0-5之間隨機整數
            console.error(JSON.stringify(vm.prizes_2017))
            console.error('Giờ là tìm id trong này')

            // vm.index = vm.numbers[Math.floor(Math.random() * vm.numbers.length)]
            let viTriTrungQua = 0;
            for (let i = 0; i < vm.prizes_2017.length; i++) {
                console.error('So sánh ' + num + 'và ' + vm.prizes_2017[i].id)
                if (num == vm.prizes_2017[i].id) {
                    viTriTrungQua = i;
                }
            }
            console.error("viTriTrungQua:" + viTriTrungQua)
            vm.index = viTriTrungQua
            console.log('1.剩餘牌號', vm.numbers)

            // 預先旋轉四圈
            let circle = 4
            let degree
            //degree=初始角度 + 旋轉4圈 + 獎品旋轉角度[隨機數] - 餘數
            degree = vm.start_deg + circle * 360 + vm.prize_rotate[vm.index] - vm.start_deg % 360 + -22.5

            // 將初始角度 start_deg:0度 = 旋轉後的角度 degree，下次執行從當下角度開始
            vm.start_deg = degree
            console.error('rotate_deg' + vm.rotate_deg)
            //綁定旋轉角度到指針
            vm.current_year === 2017 ? vm.rotate_deg = `rotate(${degree}deg)` : vm.rotate_deg = `rotate(${degree - vm.each_deg / 2}deg)`

            vm.prize_transition = `all ${vm.duration / 1000}s cubic-bezier(0.42, 0, 0.2, 0.91)`
            vm.time_remaining--
            vm.isClicked = true

            // 取當下開始角度的餘數，與輪盤角度比對(除錯用)
            let remainder = vm.start_deg % 360
            if (remainder <= 0) {
                // 為了不產生負數或0，加360
                vm.current_year === 2017 ? vm.current_deg = remainder + 360 : vm.current_deg = remainder + 360 - vm.each_deg / 2

            } else if (remainder > 0) {
                vm.current_year === 2017 ? vm.current_deg = remainder : vm.current_deg = remainder - vm.each_deg / 2
            }
            console.log('2.執行旋轉', degree, 'index', vm.index)

            // 將vm.index設為抽中獎品索引數，獎品抽完的索引數將不再出現，直到獎品全數抽完，重新 RESET
            let prize = vm.prizes[vm.index]
            vm.prize_name = prize.name
            vm.prize_icon = prize.icon
            console.error("Quay được:")
            console.error(vm.prize_name)
            setTimeout(() => {
                // this.thongBao('success', 'Chúc mừng bạn quay được: ' + vm.prize_name)
                this.showNoti('Chúc mừng bạn đã quay được: ' + vm.prize_name)
            }, 6500)
            if (vm.current_year === 2018) {
                vm.prize_icon = "card_giftcard"
            }
            vm.prizeActive()
            setTimeout(() => {
                prize.count--
                console.log('3.旋轉角度:', vm.current_deg, '獎品:', prize.name, '剩餘數量:', prize.count, ' index', vm.index)
            }, vm.duration);

            // 點選動畫結束後，將"已點選"改回"未點選"
            setTimeout(() => {
                if (vm.isClicked === true) {
                    vm.isClicked = false
                }
            }, vm.duration);
        }
    },
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
