<template>
    <div id="login-modal" v-show="show" @click="show=false">
        <div class="login-box">
            <a href="/auth/github" @click.stop="">
                <img src="/storage/img/github-login.png">
            </a>
        </div>
    </div>
</template>

<script>
    import {EventBus} from "../../event-bus";

    export default {
        name: "LoginModal",
        data() {
            return {
                show: false,
            }
        },
        mounted() {
            EventBus.$on('prompt-login', function () {
                this.show = true;
            }.bind(this));
        },

    }
</script>

<style lang="scss">
    div#login-modal {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, .6);

        div.login-box {
            max-width: 370px;
            min-width: 320px;
            padding: 0 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            -webkit-box-shadow: 0 1px 3px rgba(50, 50, 50, 0.08);
            box-shadow: 0 1px 3px rgba(50, 50, 50, 0.08);
            -webkit-border-radius: 4px;
            border-radius: 4px;
            font-size: 16px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);

            a {
                display: block;
                margin: auto;
                width: 230px;
                margin-top: 10px;
                margin-bottom: 10px;
            }
        }
    }
</style>
