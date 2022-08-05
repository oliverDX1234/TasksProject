<template>

    <div class="container">

        <div class="row">

            <div class="col-12">
                <button @click="$router.push('/add-task')" class="btn btn-success">Add Task</button>
                <hr>
            </div>

            <div class="col-12 mt-3">

                <h5>All tasks</h5>

                <v-client-table ref="myTable" :data="data" :columns="columns" :options="options" @input="e=>data=e">
                    <template v-slot:expiration_date="{row,update}">
                        <datepicker id="expiration" :clearable="false" :minDate="new Date()" v-model="row.expiration_date" :format="format" :enableTimePicker="false" @update:modelValue="update" @closed="updateFunction(row.id, row.expiration_date)"></datepicker>
                    </template>

                </v-client-table>

            </div>

        </div>
    </div>
</template>

<script>
import {ServerTable} from 'v-tables-3';
import Datepicker from '@vuepic/vue-datepicker';
import { ref } from 'vue';
import moment from "moment";
import { useToast } from "vue-toastification";

export default {
    name: "Tasks",
    components:{
        ServerTable,
        Datepicker
    },
    data(){
        return {
            data:[],
            columns: ["id", "title", "status", "user_id", "expiration_date", "description"],
            options:{
                editableColumns:['expiration_date']
            }
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
    methods:{
        async getTasks(){
            this.$refs.myTable.setLoadingState(true);

            let response = await this.$axios.get("api/tasks");

            this.data = response.data.tasks;
            this.$refs.myTable.setLoadingState(false);
        },
        async updateFunction(id, value){
           let response = await this.$axios.put("api/tasks/" + id, {
                value: moment(value).format('DD/MM/YYYY')
            });

           if(response){
               this.toast.success(response.data.message);
           }

        }
    },
    mounted(){
        this.getTasks();
    }
}
</script>

<style scoped>

</style>
