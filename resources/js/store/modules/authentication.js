import axios from "axios";

export default {

    namespaced: true,
    state() {

        return {
            loggedUser: JSON.parse(localStorage.getItem('loggedUser')) || null,
            isAuthenticated: localStorage.getItem('isAuthenticated') || false
        }
    },
    mutations: {
        LOGIN_USER(state, payload) {

            state.loggedUser = payload;
            state.isAuthenticated = true;
        },

        LOGOUT_USER(state) {
            state.loggedUser = null;
            state.isAuthenticated = false;
        }
    },

    getters: {
        isAuthenticated(state) {
            return state.isAuthenticated;
        },

        loggedUser(state){
            return state.loggedUser;
        }
    },

    actions: {
        async loginUser(context, payload) {

            await axios.get('/sanctum/csrf-cookie').then(async () => {
                try{
                    let response = await axios.post('api/login', {
                        email: payload.email,
                        password: payload.password
                    })

                    if (response.data.success) {
                        context.commit("LOGIN_USER", response.data.user);

                        localStorage.setItem("loggedUser", JSON.stringify(response.data.user));
                        localStorage.setItem("isAuthenticated", true);
                    } else {
                        throw new Error("Wrong login credentials");
                    }
                }catch(err){
                    const error = new Error(err.message)
                    error.code = 400
                    throw error;
                }


            })
        },

        async logoutUser(context) {

            await axios.get('/sanctum/csrf-cookie').then( async response => {

                try{
                    await axios.post('/api/logout')

                    context.commit("LOGOUT_USER");

                    localStorage.removeItem("loggedUser");
                    localStorage.removeItem("isAuthenticated");
                }catch{
                    let error = new Error("Problem with request");
                    error.code = 400;

                    return error;
                }

            })
        }
    }
}
