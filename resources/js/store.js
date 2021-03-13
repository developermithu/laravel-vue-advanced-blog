import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state:{
        deleteModalObj: {
            showDeleteModal: false,
            deleteUrl: '',
            data: null,
            deletingIndex: -1,
            isDeleted: false
        }
    },

    getters: {
        getDeleteModalObj(state) {
            return state.deleteModalObj
        }
    },

    mutations: {
        setDeleteModal(state){
            state.deleteModalObj.showDeleteModal = false
        },
        setDeletingModalObj(state, data){
            state.deleteModalObj = data
        }
    },
    
    actions: {

    }
});