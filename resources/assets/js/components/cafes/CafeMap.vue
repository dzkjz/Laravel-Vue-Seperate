<template>
    <div id="cafe-map-container">
        <div id="cafe-map">

        </div>
        <cafe-map-filter></cafe-map-filter>
    </div>
</template>

<script>
    import {ROAST_CONFIG} from "../../config.js";
    import CafeMapFilter from "./CafeMapFilter";
    import {EventBus} from "../../event-bus";
    import {CafeBrewMethodsFilter} from "../../mixins/filters/CafeBrewMethodsFilter";
    import {CafeTextFilter} from "../../mixins/filters/CafeTextFilter";
    import {CafeIsRoasterFilter} from "../../mixins/filters/CafeIsRoasterFilter";

    export default {
        name: "CafeMap.vue",
        components: {
            CafeMapFilter,
        },
        mixins: [
            CafeTextFilter,
            CafeIsRoasterFilter,
            CafeBrewMethodsFilter,
        ],
        props: {
            'latitude': { //经度
                type: Number,
                default: function () {
                    return 104.09425900
                }
            },
            'longitude': { //纬度
                type: Number,
                default: function () {
                    return 30.62142600
                }
            },
            'zoom': { //缩放级别
                type: Number,
                default: function () {
                    return 4;
                }
            }
        },
        data() {
            return {
                markers: [],
                infoWindows: [],
            }
        },
        computed: {
            cafes() {
                return this.$store.getters.getCafes;
            },
        },
        methods: {
            // 为所有咖啡店创建点标记
            buildMarkers() {
                // 清空点标记数组
                this.markers = [];
                // 自定义点标记图标
                var image = ROAST_CONFIG.APP_URL + '/storage/img/coffee-marker.png';

                var icon = new AMap.Icon({
                    image: image,// 图像 URL
                    imageSize: new AMap.Size(19, 33), // 设置图标尺寸
                });

                // 遍历所有咖啡店并为每个咖啡店创建点标记
                var infoWindow = new AMap.InfoWindow();

                for (var i = 0; i < this.cafes.length; i++) {
                    // 通过高德地图 API 为每个咖啡店创建点标记并设置经纬度
                    var marker = new AMap.Marker({
                        position: new AMap.LngLat(parseFloat(this.cafes[i].latitude), parseFloat(this.cafes[i].longitude)),
                        title: this.cafes[i].location_name,
                        icon: icon,// 通过 icon 对象设置自定义点标记图标来替代默认蓝色图标
                        // map: this.map,
                        extData: {
                            'cafe': this.cafes[i],
                        }
                    });

                    // //为每个咖啡店创建信息窗体
                    // var infoWindow = new AMap.InfoWindow({
                    //     content: this.cafes[i].name,
                    // });

                    //自定义信息窗体
                    var contentString = '<div class="cafe-info-window">' +
                        '<div class="cafe-name">' + this.cafes[i].name + this.cafes[i].location_name + '</div>' +
                        '<div class="cafe-address">' +
                        '<span class="street">' + this.cafes[i].address + '</span>' +
                        '<span class="city">' + this.cafes[i].city + '</span>' +
                        '<span class="state">' + this.cafes[i].state + '</span>' +
                        '<span class="zip">' + this.cafes[i].zip + '</span>' +
                        '<a href="/cafes/' + this.cafes[i].id + '">Visit</a>'
                        + '</div>'
                        + '</div>';

                    marker.content = contentString;

                    //绑定点击事件到点标记对象，点击打开上面创建的信息窗体
                    // marker.on('click', function () {
                    //     infoWindow.open(this.getMap(), this.getPosition());
                    // });

                    marker.on('click', mapClick);

                    // 将每个点标记放到点标记数组中
                    this.markers.push(marker);

                    function mapClick(mapEvent) {
                        infoWindow.setContent(mapEvent.target.content);
                        infoWindow.open(this.getMap(), this.getPosition());
                    }
                }
                // 将所有点标记显示到地图上
                this.map.add(this.markers);
            },

            // 从地图上清理点标记
            clearMarkers() {
                // 遍历所有点标记并将其设置为 null 从而从地图上将其清除
                for (var i = 0; i < this.markers.length; i++) {
                    this.markers[i].setMap(null);
                }
            },
            processFilters(filters) {
                for (var i = 0; i < this.markers.length; i++) {
                    if (filters.text === ''
                        && filters.roaster === false
                        && filters.brew_methods.length === 0) {
                        this.markers[i].setMap(this.map);
                    } else {
                        var textPassed = false;
                        var brewMethodsPassed = false;
                        var roasterPassed = false;

                        if (filters.roaster && this.processCafeIsRoasterFilter(this.markers[i].getExtData().cafe)) {
                            roasterPassed = true;
                        } else if (!filters.roaster) {
                            roasterPassed = true;
                        }

                        if (filters.text !== '' && this.processCafeTextFilter(this.markers[i].getExtData().cafe, filters.text)) {
                            textPassed = true;
                        } else if (filters.text === '') {
                            textPassed = true;
                        }

                        if (filters.brew_methods.length !== 0 && this.processCafeBrewMethodsFilter(this.markers[i].getExtData().cafe, filters.brew_methods)) {
                            brewMethodsPassed = true;
                        } else if (filters.brew_methods.length === 0) {
                            brewMethodsPassed = true;
                        }

                        if (roasterPassed && textPassed && brewMethodsPassed) {
                            this.markers[i].setMap(this.map);
                        } else {
                            this.markers[i].setMap(null);
                        }
                    }
                }
            },
        },
        mounted() {
            this.map = new AMap.Map('cafe-map', {
                center: [this.latitude, this.longitude],
                zoom: this.zoom,
            });
            // 清除并重构点标记
            this.clearMarkers();
            this.buildMarkers();

            //监听 filters-updated事件过滤点标记
            EventBus.$on('filters-updated', function (filters) {
                this.processFilters(filters);
            }.bind(this));
        },
        watch: {
            // 一旦 cafes 有更新立即重构地图点标记
            cafes() {
                this.clearMarkers();
                this.buildMarkers();
            }
        }
    }
</script>

<style lang="scss">
    @import "~@/abstracts/_variables.scss";

    div#cafe-map-container {
        position: absolute;
        top: 50px;
        left: 0;
        right: 50px;
        bottom: 50px;

        div#cafe-map {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        div.cafe-info-window {
            div.cafe-name {
                display: block;
                text-align: center;
                color: $dark-color;
                font-family: 'Josefin Sans', sans-serif;
            }

            div.cafe-address {
                display: block;
                text-align: center;
                margin-top: 5px;
                color: $grey;
                font-family: 'Lato', sans-serif;

                span.street {
                    font-size: 14px;
                    display: block;
                }

                span.city {
                    font-size: 12px;
                }

                span.state {
                    font-size: 12px;
                }

                span.zip {
                    font-size: 12px;
                    display: block;
                }

                a {
                    color: $secondary-color;
                    font-weight: bold;
                }
            }
        }
    }
</style>
