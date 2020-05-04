<template>
    <div class="page">
        <form>
            <div class="grid-container">
                <div class="grid-x grid-padding-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <label>名称
                            <input type="text" placeholder="咖啡店名" v-model="name">
                        </label>
                        <span class="validation" v-show="!validations.name.is_valid">{{ validations.name.text }}</span>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>网址
                            <input type="text" placeholder="网址" v-model="website">
                        </label>
                        <span class="validation"
                              v-show="!validations.website.is_valid">{{ validations.website.text }}</span>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>简介
                            <input type="text" placeholder="简介" v-model="description">
                        </label>
                    </div>
                </div>
                <div class="grid-x grid-padding-x" v-for="(location, key) in locations">
                    <div class="large-12 medium-12 small-12 cell">
                        <h3>位置</h3>
                    </div>
                    <div class="large-6 medium-6 small-12 cell">
                        <label>位置名称
                            <input type="text" placeholder="位置名称" v-model="locations[key].name">
                        </label>
                    </div>
                    <div class="large-6 medium-6 small-12 cell">
                        <label>详细地址
                            <input type="text" placeholder="详细地址" v-model="locations[key].address">
                        </label>
                        <span class="validation" v-show="!validations.locations[key].address.is_valid">{{ validations.locations[key].address.text }}</span>
                    </div>
                    <div class="large-6 medium-6 small-12 cell">
                        <label>城市
                            <input type="text" placeholder="城市" v-model="locations[key].city">
                        </label>
                        <span class="validation" v-show="!validations.locations[key].city.is_valid">{{ validations.locations[key].city.text }}</span>
                    </div>
                    <div class="large-6 medium-6 small-12 cell">
                        <label>省份
                            <input type="text" placeholder="省份" v-model="locations[key].state">
                        </label>
                        <span class="validation" v-show="!validations.locations[key].state.is_valid">{{ validations.locations[key].state.text }}</span>
                    </div>
                    <div class="large-6 medium-6 small-12 cell">
                        <label>邮编
                            <input type="text" placeholder="邮编" v-model="locations[key].zip">
                        </label>
                        <span class="validation" v-show="!validations.locations[key].zip.is_valid">{{ validations.locations[key].zip.text }}</span>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <label>支持的冲泡方法</label>
                        <span class="brew-method" v-for="brewMethod in brewMethods">
                        <input v-bind:id="'brew-method-'+brewMethod.id+'-'+key" type="checkbox"
                               v-bind:value="brewMethod.id"
                               v-model="locations[key].methodsAvailable">
                        <label v-bind:for="'brew-method-'+brewMethod.id+'-'+key">{{ brewMethod.method }}</label>
                    </span>
                    </div>

                    <div class="large-12 medium-12 small-12 cell">
                        <tags-input :unique="key"></tags-input>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <a class="button" v-on:click="removeLocation(key)">移除位置</a>
                    </div>
                </div>
                <div class="grid-x grid-padding-x">
                    <div class="large-12 medium-12 small-12 cell">
                        <a class="button" v-on:click="addLocation()">新增位置</a>
                    </div>
                    <div class="large-12 medium-12 small-12 cell">
                        <a class="button" v-on:click="submitNewCafe()">提交表单</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import TagsInput from "../components/global/forms/TagsInput";
    import {EventBus} from "../event-bus";

    export default {
        name: "NewCafe.vue",
        components: {
            TagsInput,
        },
        data: function () {
            return {
                // name: '',
                // address: '',
                // city: '',
                // state: '',
                // zip: '',
                // validations:
                // //其中 is_valid 字段标识是否验证成功，而 text 字段标识验证文本。
                //     {
                //         name: {
                //             is_valid: true,
                //             text: '',
                //         },
                //         address: {
                //             is_valid: true,
                //             text: '',
                //         },
                //         city: {
                //             is_valid: true,
                //             text: ''
                //         },
                //         state: {
                //             is_valid: true,
                //             text: ''
                //         },
                //         zip: {
                //             is_valid: true,
                //             text: ''
                //         }
                //     }

                name: '',
                locations: [],//用于存放所有新增的位置字段数据
                website: '',
                description: '',
                roaster: false,
                validations: {
                    name: {
                        is_valid: true,
                        text: '',
                    },

                    locations: [],//包含每个位置字段的验证规则
                    oneLocation: { //确保咖啡店至少包含一个位置信息
                        is_valid: true,
                        text: '',
                    },
                    website: {
                        is_valid: true,
                        text: '',
                    }
                }
            }
        },
        created() {
            this.addLocation();
        },
        computed: {
            brewMethods() {
                return this.$store.getters.getBrewMethods;
            },
            addCafeStatus() {
                return this.$store.getters.getCafeAddStatus;
            },
        },
        methods: {
            submitNewCafe() {
                if (this.validationNewCafe()) {
                    this.$store.dispatch('addCafe', {
                        // name: this.name,
                        // address: this.address,
                        // city: this.city,
                        // state: this.state,
                        // zip: this.zip,
                        name: this.name,
                        locations: this.locations,
                        website: this.website,
                        description: this.description,
                        roaster: this.roaster
                    });
                }
            },
            addLocation() {
                //将一个位置对象推送到 locations 字段,其中包含名称、地址、城市、省份和邮编以及有效的冲泡方法数组
                this.locations.push(
                    {
                        name: '',
                        address: '',
                        city: '',
                        state: '',
                        zip: '',
                        methodsAvailable: [],
                        tags: '',
                    });

                //然后将位置对象中的某些字段验证规则推送到 validations.locations 字段
                // 我们在验证规则中去掉了 name 和 methodsAvailable 属性，
                // 这是因为对 name 字段而言，如果空的话，我们将使用咖啡店已经存在的名称字段
                // 并且这个字段也不是必需的
                // 对 methodsAvailable 字段而言，当添加咖啡店时，你可能还不知道所有的冲泡方法
                this.validations.locations.push({
                    address: {
                        is_valid: true,
                        text: '',
                    },
                    city: {
                        is_valid: true,
                        text: ''
                    },
                    state: {
                        is_valid: true,
                        text: ''
                    },
                    zip: {
                        is_valid: true,
                        text: ''
                    }
                });
            },
            removeLocation(key) {
                this.locations.splice(key, 1);
                this.validations.locations.splice(key, 1);
            },

            validationNewCafe: function () {
                let validNewCafeForm = true;
                //
                // // 确保 name 字段不为空
                // if (this.name.trim() === '') {
                //     validNewCafeForm = false;
                //     this.validations.name.is_valid = false;
                //     this.validations.name.text = "请输入咖啡店的名字";
                // } else {
                //     this.validations.name.is_valid = true;
                //     this.validations.name.text = '';
                // }
                //
                // // 确保 address 字段不为空
                // if (this.address.trim() === '') {
                //     validNewCafeForm = false;
                //     this.validations.address.is_valid = false;
                //     this.validations.address.text = '请输入咖啡店的地址!';
                // } else {
                //     this.validations.address.is_valid = true;
                //     this.validations.address.text = '';
                // }
                //
                // //  确保 city 字段不为空
                // if (this.city.trim() === '') {
                //     validNewCafeForm = false;
                //     this.validations.city.is_valid = false;
                //     this.validations.city.text = '请输入咖啡店所在城市!';
                // } else {
                //     this.validations.city.is_valid = true;
                //     this.validations.city.text = '';
                // }
                //
                // //  确保 state 字段不为空
                // if (this.state.trim() === '') {
                //     validNewCafeForm = false;
                //     this.validations.state.is_valid = false;
                //     this.validations.state.text = '请输入咖啡店所在省份!';
                // } else {
                //     this.validations.state.is_valid = true;
                //     this.validations.state.text = '';
                // }
                //
                // // 确保 zip 字段不为空且格式正确
                // if (this.zip.trim() === '' || !this.zip.match(/(^\d{6}$)/)) {
                //     validNewCafeForm = false;
                //     this.validations.zip.is_valid = false;
                //     this.validations.zip.text = '请输入有效的邮编地址!';
                // } else {
                //     this.validations.zip.is_valid = true;
                //     this.validations.zip.text = '';
                // }
                //
                // return validNewCafeForm;

                for (var index in this.locations) {

                    if (this.locations.hasOwnProperty(index)) {

                        // 确保地址字段不为空
                        if (this.locations[index].address.trim() === '') {
                            validNewCafeForm = false;
                            this.validations.locations[index].address.is_valid = false;
                            this.validations.locations[index].address.text = '请输入咖啡店的地址';
                        } else {
                            this.validations.locations[index].address.is_valid = true;
                            this.validations.locations[index].address.text = '';
                        }

                        // 确保城市字段不为空
                        if (this.locations[index].city.trim() === '') {
                            validNewCafeForm = false;
                            this.validations.locations[index].city.is_valid = false;
                            this.validations.locations[index].city.text = '请输入咖啡店的城市';
                        } else {
                            this.validations.locations[index].city.is_valid = true;
                            this.validations.locations[index].city.text = '';
                        }

                        // 确保省份字段不为空
                        if (this.locations[index].state.trim() === '') {
                            validNewCafeForm = false;
                            this.validations.locations[index].state.is_valid = false;
                            this.validations.locations[index].state.text = '请输入咖啡店的省份';
                        } else {
                            this.validations.locations[index].state.is_valid = true;
                            this.validations.locations[index].state.text = '';
                        }

                        // 确保邮编字段不为空
                        if (this.locations[index].zip.trim() === '' || !this.locations[index].zip.match(/(^\d{6}$)/)) {
                            validNewCafeForm = false;
                            this.validations.locations[index].zip.is_valid = false;
                            this.validations.locations[index].zip.text = '请输入咖啡店的有效邮编';
                        } else {
                            this.validations.locations[index].zip.is_valid = true;
                            this.validations.locations[index].zip.text = '';
                        }
                    }
                }

                return validNewCafeForm;
            },
            clearForm() {
                this.name = '';
                this.locations = [];
                this.website = '';
                this.description = '';
                this.roaster = false;
                this.validations = {
                    name: {
                        is_valid: true,
                        text: '',
                    },
                    locations: [],
                    oneLocation: {
                        is_valid: true,
                        text: '',
                    },
                    website: {
                        is_valid: true,
                        text: '',
                    },
                };
                //清理完表单数据信息后 调用 this.addLocation() 添加一个新的位置信息到表单
                this.addLocation();

                //清理tags输入
                EventBus.$emit('clear-tags');
            },


        },
        watch: {
            'addCafeStatus': function () {
                if (this.addCafeStatus === 2) {
                    //添加成功
                    this.clearForm();

                    $('#cafe-added-successfully').show().delay(5000).fadeOut();
                }

                if (this.addCafeStatus === 3) {
                    //添加失败
                    $('#cafe-added-unsuccessfully').show().delay(5000).fadeOut();
                }
            }
        },
        mounted() {
            EventBus.$on('tags-edited', function (tagsAdded) {
                this.locations[tagsAdded.unique].tags = tagsAdded.tags;
            }.bind(this));
        },

    }
</script>

<style scoped>

</style>
