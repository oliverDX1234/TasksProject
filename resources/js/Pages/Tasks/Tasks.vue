<template>

    <div class="container">

        <div class="row">

            <div class="col-12">
                <button @click="$router.push('/add-task')" class="btn btn-success">Add Task</button>
                <hr>
            </div>

            <div class="col-md-8 col-12 mt-3">

                <h5>All tasks</h5>

                <v-client-table @pagination="changePage" @limit="limitUpdate" ref="myTable" :data="data" :columns="columns" :options="options" @input="e=>data=e">

                    <template v-slot:expiration_date="{row,update}">
                        <datepicker id="expiration" :clearable="false" :minDate="new Date()"
                                    v-model="row.expiration_date" :format="format" :enableTimePicker="false"
                                    @update:modelValue="update"
                                    @closed="updateFunction(row.id, row.expiration_date)"></datepicker>
                    </template>

                    <template v-slot:actions="{row, updated}">
                        <div class="d-flex">
                            <div>
                                <button @click="removeItem(row.id)">
                                    <font-awesome-icon icon="fa-solid fa-remove"/>
                                </button>
                            </div>
                            <div class="mx-1" @click="editItem(row.id)">
                                <button>
                                    <font-awesome-icon icon="fa-solid fa-edit"/>
                                </button>
                            </div>
                        </div>
                    </template>

                </v-client-table>

            </div>

            <div class="col-md-4 col-12 mt-5 mb-5">
                <div id="dropzone" class="dropzone">
                    <h5>Drop items here to complete them</h5>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import {ServerTable} from 'v-tables-3';
import Datepicker from '@vuepic/vue-datepicker';
import {ref} from 'vue';
import moment from "moment";
import {useToast} from "vue-toastification";

export default {
    name: "Tasks",
    components: {
        ServerTable,
        Datepicker
    },
    data() {
        return {
            data: [],
            columns: ["id", "title", "status", "user_id", "expiration_date", "description", "actions"],
            options: {
                editableColumns: ['expiration_date']
            },
            currentPage: 1,
            limit: 10
        }
    },
    setup() {
        const date = ref(new Date());

        const toast = useToast();

        const format = (date) => {
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();

            return `${day}/${month}/${year}`;
        }

        return {
            date,
            format,
            toast
        }
    },
    methods: {
        async getTasks() {
            this.$refs.myTable.setLoadingState(true);

            let response = await this.$axios.get("api/tasks");

            this.data = response.data.tasks;
            this.$refs.myTable.setLoadingState(false);
        },
        async updateFunction(id, value) {
            let response = await this.$axios.put("api/tasks/" + id, {
                expiration: moment(value).format('DD/MM/YYYY')
            });

            if (response.status === 200) {
                this.toast.success(response.data.message);
            }

        },

        limitUpdate(value){
            this.limit = value;
        },

        changePage(value){
            this.currentPage = value;

            this.updateDraggable();
        },

        async removeItem(id) {
            let response = await this.$axios.delete("api/tasks/" + id);

            if (response.status === 200) {

                let index = this.data.findIndex(x => x.id === id);

                if (index !== -1) {
                    this.data.splice(index, 1);
                }

                this.toast.success(response.data.message);
            }
        },

        editItem(id) {
            this.$router.push("/edit-task/" + id);
        },

        async moveItem(id){

            let response = await this.$axios.post("/api/move-task", {
                id: this.data[id].id
            });

            if( response.status === 200){

                let index = this.data.findIndex(x => x.id === response.data.task);

                if(index !== -1){
                    this.data.splice(index, 1);

                    this.toast.success(response.data.message);
                }else{
                    this.toast.error("There was an error with your request");
                }
            }else{
                this.toast.error(response.data.message);
            }
        },

        updateDraggable(){
            let rows = document.querySelector("tbody").children;

            [...rows].forEach((x, index) => {
                x.setAttribute("ref", (this.currentPage - 1) * this.limit + index);
            });
        },

        setDraggable() {

            setTimeout(() => {
                let rows = document.querySelector("tbody").children;

                [...rows].forEach((x, index) => {


                    x.setAttribute("draggable", true);
                    x.setAttribute("ref", (this.currentPage - 1) * this.limit + index);
                    x.classList.add("cursor-pointer");

                    x.addEventListener("dragstart", function(e){
                        e.dataTransfer.setData("text", e.currentTarget.getAttribute("ref"));
                        e.dataTransfer.effectAllowed = "move";

                        setTimeout((target) => target.classList.add("invisible"), 0, e.currentTarget)
                    })

                    x.addEventListener("dragend", function(e){

                        setTimeout((target) => target.classList.remove("invisible"), 0, e.currentTarget)
                    })
                });

                let dropzone = document.getElementById("dropzone");

                dropzone.addEventListener("dragover", (e) => {

                    e.preventDefault();
                    setTimeout((target) =>{
                        target.classList.add("dragOver")
                    } , 0, e.currentTarget)
                });

                dropzone.addEventListener("drop", (e) => {
                    e.preventDefault();

                    this.moveItem(e.dataTransfer.getData('text'));

                    setTimeout((target) => target.classList.remove("dragOver"), 0, e.currentTarget)
                });


                dropzone.addEventListener("dragleave", (e) => {

                    setTimeout((target) => target.classList.remove("dragOver"), 0, e.currentTarget)
                });

            }, 1000)
        }
    },
    mounted() {
        this.getTasks();

        this.setDraggable();
    }
}
</script>

<style scoped>

</style>
