<template>

    <div class="container">

        <div class="row">

            <div class="col-12">
                <button @click="$router.push('/add-task')" class="btn btn-success">Add Task</button>
                <hr>
            </div>

            <div class="col-12 mt-3">

                <h5>All tasks</h5>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "Tasks"
}
</script>

<style scoped>

</style>
