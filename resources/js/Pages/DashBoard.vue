<template>
    <div>
        Welcome {{ $store.getters["authentication/loggedUser"].name }}
    </div>
</template>

<script>
export default {
    name: "Dashboard",
    data() {
        return {
            name: null,
        }
    },
}
</script>
