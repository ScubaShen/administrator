//Vue.component('index-result-refresh', {
//    // 定义组件的属性
//    props: {
//        // 用来初始化省市区的值，在编辑时会用到
//        initValue: {
//            type: Array, // 格式是数组
//            default: () => ([]), // 默认是个空数组
//        }
//    },
//    // 组件的数据
//    data() {
//    return {
//        items: [
//            {
//                id: '',
//                name: '',
//                supervision_name: '',
//                start_at: '',
//                finish_at: ''
//            }
//        ]
//
//    }
//},
//methods: {
//    // 把参数 val 中的值保存到组件的数据中
//    onDistrictChanged(val) {
//        if (val.length === 3) {
//            this.province = val[0];
//            this.city = val[1];
//            this.district = val[2];
//        }
//    }
//}
//});