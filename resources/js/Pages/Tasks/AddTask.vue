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
                                    <v-select id="user" v-model="user" :options="users" label="name">
                                    </v-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="expiration" class="col-sm-4 col-form-label text-md-right">Expiration Date *</label>
                                <div class="col-md-6">
                                    <datepicker id="expiration" type="email" :minDate="new Date()" v-model="expiration" :format="format" required :enableTimePicker="false"></datepicker>
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
                                        Add Task
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
            user: null,
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
    },
    setup() {
        const date = ref(new Date());

        const format = (date) => {
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();

            return `${day}/${month}/${year}`;
        }

        return {
            date,
            format,
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
                    expiration_date: this.expiration,
                    description: this.description
                }

                try {
                    await this.$axios.post("api/add-user", payload);

                    await this.$router.push({name: 'dashboard'})
                } catch (error) {

                    this.error = "There was a problem processing the request";
                }
            }
        },

        async getUsers(){

            let response = await this.$axios.get("api/users");

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
