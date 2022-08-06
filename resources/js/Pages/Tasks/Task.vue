<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="alert alert-danger" role="alert" v-if="errors.length">
                    <div class="row" v-for="error in errors">
                        <div class="col-12">
                            {{ error }}
                        </div>
                    </div>
                </div>

                <div class="alert alert-success" role="alert" v-if="successMsg !== null">
                    <div class="row">
                        <div class="col-12">
                            {{ successMsg }}
                        </div>
                    </div>
                </div>

                <div class="card card-default">
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Title *</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="title" required
                                           autofocus autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status" class="col-sm-4 col-form-label text-md-right">Status *</label>
                                <div class="col-md-6">
                                    <v-select id="status" v-model="status" :options="options"></v-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="user" class="col-sm-4 col-form-label text-md-right">User *</label>
                                <div class="col-md-6">
                                    <v-select id="user" :disabled="$store.getters['authentication/loggedUser'].role !== 'admin'" v-model="user" :options="users" label="name">
                                    </v-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="expiration" class="col-sm-4 col-form-label text-md-right">Expiration Date *</label>
                                <div class="col-md-6">
                                    <datepicker id="expiration" :minDate="new Date()" v-model="expiration" :format="format" required :enableTimePicker="false"></datepicker>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Description</label>
                                <div class="col-md-6">
                                    <input id="password" class="form-control" v-model="description"
                                           required autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                        {{ $route.params.id ? "Edit Task" : "Add Task" }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import vSelect from 'vue-select'
import Datepicker from '@vuepic/vue-datepicker';
import 'vue-select/dist/vue-select.css';
import '@vuepic/vue-datepicker/dist/main.css'
import moment from "moment"
import { ref } from 'vue';
import { useToast } from "vue-toastification";

export default {
    name: "AddTask",
    components:{
      Datepicker,
        vSelect
    },
    data(){
        return {
            title: "",
            expiration: moment().format('DD/MM/YYYY'),
            description: "",
            status: null,
            user: this.$store.getters["authentication/loggedUser"],
            errors: [],
            successMsg: null,
            users: [],
            options: [
                "open",
                "cancelled",
                "completed"
            ]
        }
    },
    mounted(){
        this.getUsers();

        if(this.$route.params.id){
            this.getTask();
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
        async handleSubmit(e){

            e.preventDefault()

            let that = this;


            if (this.validateUserInput()) {

                let payload = {
                    title: this.title,
                    status: this.status,
                    user_id: this.user.id,
                    expiration_date: moment(this.expiration).format("DD/MM/YYYY"),
                    description: this.description
                }

                try {
                    let response;
                    if(this.$route.params.id){
                        response = await this.$axios.put("/api/tasks/" + this.$route.params.id, payload);
                    }else{
                        response = await this.$axios.post("/api/tasks", payload);
                    }

                    if(response.status === 200){
                        await this.toast.success(response.data.message);
                    }else{
                        await this.toast.error(response.data.message);
                    }

                    await this.$router.push({name: 'tasks'})
                } catch (error) {

                    this.error = "There was a problem processing the request";
                }
            }
        },

        async getTask(){
            let response = await this.$axios.get("/api/tasks/" + this.$route.params.id);

            this.title = response.data.task.title;
            this.status = response.data.task.status
            this.user = response.data.task.user;
            this.expiration = moment(response.data.task.expiration_date)    .toDate();
            this.description = response.data.task.description;

        },

        async getUsers(){

            let response = await this.$axios.get("/api/users");

            this.users = response.data.users;
        },

        validateUserInput(){
            if (this.title === "" || !this.status || !this.user) {
                this.errors.push("Please enter all the required fields");
                return false;
            }

            return true;
        }
    }
}
</script>

<style scoped>

</style>
