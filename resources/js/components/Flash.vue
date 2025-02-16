<template>
    <div class="alert alert-success alert-flash position-fixed end-0 bottom-0 mb-2 me-2"
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
