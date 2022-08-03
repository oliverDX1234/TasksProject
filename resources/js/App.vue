<template>


    <div class="navigation mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div v-if="$store.getters['authentication/isAuthenticated']">
                        <div class="d-flex justify-content-between">
                            <div class="nav-left d-flex">
                                <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
                                <router-link to="/tasks" class="nav-item nav-link">Tasks</router-link>
                            </div>

                            <div class="nav-right d-flex">
                                <p class="text-white mx-1">{{ $store.getters['authentication/loggedUser'].name }}</p>
                                <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">
                                    <font-awesome-icon class="text-white" icon="fa-solid fa-arrow-right-from-bracket"/>
                                </a>
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
import {mapActions} from "vuex"

export default {
    name: "App",
    methods: {

        ...mapActions("authentication", {
            logoutUser: "logoutUser"
        }),

        async logout(e) {
            e.preventDefault()

            await this.logoutUser();
            await this.$router.push("/");
        }
    },
}
</script>
