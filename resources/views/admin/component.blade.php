<?php
?>
<template id="button-counter">
    <div>
        <button v-on:click="count++">You clicked me @{{ count }} times.</button>
        @{{ title }}
        <el-input v-model="title1"></el-input>
    </div>
</template>
<script>
    Vue.component('button-counter', {
        template: '#button-counter',
        props: {
            title: String
        },
        data: function () {
            return {
                count: 0,
                title1: ''
            }
        },
        watch: {
            title1: function (newVal, oldVal) {
                this.$emit('update-title', newVal)
            }
        }
    })
</script>
