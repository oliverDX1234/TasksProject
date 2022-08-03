<template>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="alert alert-danger" role="alert" v-if="errors.length">
                    <div class="row" v-for="error in errors">
                        <div class="col-12">
                            {{ error }}<div class="container">
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
                                                    <label for="name" class="col-sm-4 col-form-label text-md-right">Title</label>
                                                    <div class="col-md-6">
                                                        <input id="title" type="text" class="form-control" v-model="title" required
                                                               autofocus autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                                    <div class  ="col-md-6">
                                                        <input id="email" type="email" class="form-control" v-model="email" required
                                                               autofocus autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                                                    <div class="col-md-6">
                                                        <textarea id="description" class="form-control" v-model="description"
                                                                  required autocomplete="off"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group row mb-0">
                                                    <div class="col-md-8 offset-md-4">
                                                        <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                                            Register
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Title</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="title" required
                                           autofocus autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status" class="col-sm-4 col-form-label text-md-right">Status</label>
                                <div class="col-md-6">
                                    <v-select id="status" :options="options"></v-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="user" class="col-sm-4 col-form-label text-md-right">User</label>
                                <div class="col-md-6">
                                    <v-select id="user" :options="users">

                                    </v-select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="expiration" class="col-sm-4 col-form-label text-md-right">Expiration Date</label>
                                <div class="col-md-6">
                                    <datepicker id="expiration" type="email" v-model="expiration" :format="format" required :enableTimePicker="false"></datepicker>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Description</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="description"
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
            title: null,
            expiration: moment().format('DD/MM/YYYY'),
            description: null,
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
        handleSubmit(){

        },
    }
}
</script>

<style scoped>

</style>
