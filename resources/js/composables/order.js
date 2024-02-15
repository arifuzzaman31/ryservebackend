import { ref } from 'vue'
import axios from 'axios'

export default function useOrder() {
    const allorders = ref([])
    const singleOrderDetail = ref([])
    const userOrders = ref([])
    const errors = ref('')

    const getAllOrder = async (page = 1) => {
        let response = await axios.get(baseUrl+`get-order?page=${page}&per_page=10`)
        allproducts.value = response.data
    }

    const getSingleOrderDetails = async (id) => {
        let response = await axios.get(baseUrl+`orders-details/${id}`)
        singleOrderDetail.value = response.data
    }

    const getUserOrder = async (id,page) => {
        let response = await axios.get(baseUrl+`get-user-order/${id}?page=${page}&per_page=1`)
        userOrders.value = response.data
    }

    return {
        allorders,
        userOrders,
        singleOrderDetail,
        errors,
        getAllOrder,
        getUserOrder,
        getSingleOrderDetails
    }
}