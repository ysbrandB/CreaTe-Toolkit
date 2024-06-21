<template>
    <Teleport :to="to" v-if="isMounted" :disabled="disabled"><slot></slot></Teleport>
</template>

<script>
export default {
    name: "MountedTeleport",
    props: ['to', 'disabled'],
    data() {
        return {isMounted: false}
    },
    mounted() {
        this.isMounted = true;
    }
}
</script>
