<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-danger" role="alert" v-if="error !== null">
                    {{ error }}
                </div>

                <div class="card card-default">
                    <div class="card-body">
                        <form>
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
                                        Login
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
import {mapActions} from "vuex";

export default {
    data() {
        return {
            email: "a@abc.org",
            password: "Secret!",
            error: null
        }
    },
    watch: {
        email() {
            this.error = null;
        },
        password() {
            this.error = null;
        }
    },
    methods: {

        ...mapActions("authentication", {
            loginUser: "loginUser"
        }),

        async handleSubmit(e) {
            e.preventDefault()

            let that = this;


            if (this.validateUserInput()) {

                try {
                    await this.loginUser({email: this.email, password: this.password});

                    await this.$router.push({name: 'tasks'})
                } catch (error) {

                    this.error = error;
                }
            }
        },
        validateUserInput() {
            if (this.email === "" || this.password === "") {
                this.error = "Please enter all the required fields"
                return false;
            }

            if (this.password.length < 7) {
                this.error = "The password has to be at least 7 characters";

                return false;
            }

            return true;
        }
    }
}
</script>
