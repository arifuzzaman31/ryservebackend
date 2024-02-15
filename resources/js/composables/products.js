import { ref } from 'vue'
import axios from 'axios'

export default function useProduct() {
    
    const allproducts = ref([])
    const product = ref([])
    const errors = ref('')


    const getAllProduct = async (page = 1) => {
        let response = await axios.get(baseUrl+`get-product?page=${page}`)
        allproducts.value = response.data
    }

    const getProduct = async (id) => {
        let response = await axios.get(baseUrl+`product/${id}`)
        product.value = response.data
        // console.log(response.data)
    }

    // const storeCompany = async (data) => {
    //     errors.value = ''
    //     try {
    //         await axios.post('/api/companies', data)
    //         await router.push({ name: 'companies.index' })
    //     } catch (e) {
    //         if (e.response.status === 422) {
    //             for (const key in e.response.data.errors) {
    //                 errors.value = e.response.data.errors
    //             }
    //         }
    //     }

    // }

    // const updateCompany = async (id) => {
    //     errors.value = ''
    //     try {
    //         await axios.patch(`/api/companies/${id}`, company.value)
    //         await router.push({ name: 'companies.index' })
    //     } catch (e) {
    //         if (e.response.status === 422) {
    //             for (const key in e.response.data.errors) {
    //                 errors.value = e.response.data.errors
    //             }
    //         }
    //     }
    // }

    return {
        allproducts,
        product,
        errors,
        getAllProduct,
        getProduct
    }
}