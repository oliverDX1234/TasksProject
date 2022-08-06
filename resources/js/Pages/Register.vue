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
                                <label for="name" class="col-sm-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" v-model="name" required
                                           autofocus autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" v-model="email" required
                                           autofocus autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="password"
                                           required autocomplete="off">
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
</template>

<script>
export default {
    data() {
        return {
            name: "",
            email: "",
            password: "",
            errors: [],
            successMsg: null
        }
    },
    watch:{
        name(){
            this.errors = [];
        },
        email(){
            this.errors = [];
        },
        password(){
            this.errors = [];
        }
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault()

            let that = this;
            this.errors = [];

            if (this.validateInput()) {
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('api/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            if (response.data.success) {
                                this.successMsg = "The user was successfully registered"

                                setTimeout(() => {
                                    window.location.href = "/"
                                }, 3000)

                            } else {
                                this.errors.push(response.data.message);
                            }
                        })
                        .catch(function () {
                            that.error = "There was a problem processing the request";
                        });
                })
            }
        },
        validateInput() {

            let flag = true;

            if(this.name === "" || this.email === "" || this.password === ""){
                this.errors.push("Please enter all the required fields");

                flag = false;
            }

            if(this.email && !this.validateEmail()){
                this.errors.push("The email format is not correct");

                flag = false;
            }

            if(this.password && this.password.length < 7){
                this.errors.push("The password must be at least 7 characters");

                flag = false;
            }

            return flag;
        },
        validateEmail() {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email);
        }
    }
}
</script>
