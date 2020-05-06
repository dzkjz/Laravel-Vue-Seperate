/*
 |-------------------------------------------------------------------------------
 | VUEX modules/users.js
 |-------------------------------------------------------------------------------
 | The Vuex data store for the users
 */

import UserAPI from '../api/user.js';

export const users = {
    /*
     Defines the state being monitored for the module.
     */
    state: {
        user: {},
        userLoadStatus: 0,
        userUpdateStatus: 0,
    },

    /*
     Defines the actions used to retrieve the data.
     */
    actions: {
        loadUser({commit}) {
            commit('setUserLoadStatus', 1);

            UserAPI.getUser()
                // 加载成功
                .then(function (response) {
                    commit('setUser', response.data);
                    commit('setUserLoadStatus', 2);
                })
                // 加载失败
                .catch(function () {
                    commit('setUser', {});
                    commit('setUserLoadStatus', 3);
                });
        },

        editUser({commit, state, dispatch}, data) {
            commit('setUpdateUserStatus', 1);
            UserAPI.putUpdateUser(data.profile_visibility, data.favorite_coffee, data.flavor_notes, data.city, data.state)
                .then(function (response) {
                    commit('setUpdateUserStatus', 2);
                    //重新加载user
                    dispatch('loadUser');
                })
                .catch(function (e) {
                    commit('setUpdateUserStatus', 3);
                });
        },

        /*
         Logs out a user and clears the status and user pieces of
         state.
         */
        logoutUser({commit}) {
            commit('setUserLoadStatus', 0);
            commit('setUser', {});
        }
    },

    /*
     Defines the mutations used
     */
    mutations: {
        /*
         Sets the user load status
         */
        setUserLoadStatus(state, status) {
            state.userLoadStatus = status;
        },

        /*
         Sets the user
         */
        setUser(state, user) {
            state.user = user;
        },


        setUpdateUserStatus(state, status) {
            state.userUpdateStatus = status;
        },
    },

    /*
     Defines the getters used by the module.
     */
    getters: {
        /*
         Returns the user load status.
         */
        getUserLoadStatus(state) {
            return function () {
                return state.userLoadStatus;
            }
        },

        /*
         Returns the user.
         */
        getUser(state) {
            return state.user;
        },

        getUserUpdateStatus(state) {
            return state.userUpdateStatus;
        },
    }
}
