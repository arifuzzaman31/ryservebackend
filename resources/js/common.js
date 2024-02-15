import { ref } from 'vue'

export default function getCategories() {
    const categoriesData = ref({});

    const category = async() => {
        try{
            await axios.get(baseUrl+'category').then(response => {
                categoriesData.value = response.data
            }).catch(error => {
                console.log(error)
            })
        }catch(error){
            console.log(error)
        }
    }

    return {
        category,categoriesData
    }
}

 