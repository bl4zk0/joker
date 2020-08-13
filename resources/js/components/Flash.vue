<template>
    <div class="alert alert-success alert-flash"
         role="alert"
         v-show="show">
        <i class="fas fa-exclamation-circle"></i> <span v-text="this.message"></span>
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                show: true
            }
        },

        created() {
            this.hide();
        },

        methods: {
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 4000);
            }
        },
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
