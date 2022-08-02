<template>


    <div class="navigation mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div v-if="isLoggedIn">
                        <div class="d-flex justify-content-between">
                            <div class="nav-left d-flex">
                                <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
                            </div>

                            <div class="nav-right d-flex">
                                <p class="text-white mx-1">{{ user.name }}</p>
                                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout"><font-awesome-icon class="text-white" icon="fa-solid fa-arrow-right-from-bracket" /></a>
                            </div>
                        </div>
                    </div>
                    <!-- for non-logged user-->
                    <div v-else>
                        <div class="d-flex justify-content-between">
                            <div class="nav-left">
                                <router-link to="/" class="nav-item nav-link">Home</router-link>
                            </div>

                            <div class="nav-right d-flex">
                                <router-link to="/register" class="nav-item nav-link">Register
                                </router-link>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <router-view/>

</template>

<script>
export default {
    name: "App",
    data() {
        return {
            isLoggedIn: false,
            user: null
        }
    },
    created() {
        if (window.Laravel.isLoggedin) {
            this.isLoggedIn = true;
            this.user = window.Laravel.user;
        }
    },
    methods: {
        logout(e) {
            e.preventDefault()
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/logout')
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = "/"
                        } else {
                            console.log(response)
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}
</script>
