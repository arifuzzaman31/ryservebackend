import { ref } from 'vue'
import axios from 'axios'
// import { useRouter } from 'vue-router'

export default function useAttributes() {
    
    const allcolours = ref([])
    const allColoursForSelect = ref([])
    const allcategories = ref([])
    const allsizes = ref([])
    const allSizeForSelect = ref([])
    const allfabrics = ref([])
    const allFabricsForSelect = ref([])

    const errors = ref('')
    // const router = useRouter()

    const getCategory = async () => {
        let response = await axios.get(baseUrl+'category')
        allcategories.value = response.data
    }

    const getColour = async () => {
        let response = await axios.get(baseUrl+'colour/create?no_paginate=yes')
        const opt = []
        response.data.map(data => {
            opt.push({'value':data.id,'name':data.color_name})
        })
        allColoursForSelect.value = opt

    }
    
    const getColourForSelect = async () => {
        getColour()
        const opt = []
        // colours.data.map(data => {
        //     opt.push({'value':data.id,'name':data.color_name})
        // })
        console.log(allcolours.value)
        allColoursForSelect.value = opt
    }
    
    const getSizes = async () => {
        let response = await axios.get(baseUrl+'sizes/create?no_paginate=yes')
        const opt = []
        response.data.map(data => {
            opt.push({'value':data.id,'name':data.size_name})
        })
        allSizeForSelect.value = opt
    }

    const getSizeForSelect = async () => {
        const sizes = getSizes()
        const opt = []
        sizes.data.map(data => {
            opt.push({'value':data.id,'name':data.size_name})
        })
        allSizeForSelect.value = opt
        console.log(sizes)
    }

    const getFabric = async () => {
        let response = await axios.get(baseUrl+'fabrics/create?no_paginate=yes')
        const opt = []
        response.data.map(data => {
            opt.push({'value':data.id,'name':data.fabric_name})
        })
        allFabricsForSelect.value = opt
    }

    const getFabricForSelect = async () => {
        const fabric = getFabric()
        const opt = []
        fabric.data.map(data => {
            opt.push({'value':data.id,'name':data.fabric_name})
        })
        allFabricsForSelect.value = opt
        console.log(fabric)
    }

    // const getCompany = async (id) => {
    //     let response = await axios.get(`/api/companies/${id}`)
    //     company.value = response.data.data
    // }

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
        errors,
        allcategories,
        allColoursForSelect,
        allSizeForSelect,
        allFabricsForSelect,
        getCategory,
        getColour,
        getSizes,
        getFabric

    }
}