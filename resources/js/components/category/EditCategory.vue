<script>
import Mixin from '../../mixer'
export default {
    mixins: [Mixin],
    data(){
        return {
            allcategories: [],
            allsubcategories: [],
            allfiltersubcategories: [],
            filterdata: {
                category: {},
                subcategory: {}
            },
            up_show: false,
            form: {
                image_one: '',
                image_two: '',
                image_three: '',
                image_four: ''
            },
            url: baseUrl
        }
    },
    methods: {
        getCategory() {
            axios.get(baseUrl+'get-category?no_paginate=yes').then(response => {
                    let res = response.data.filter(data => data.parent_category == 0)
                    let subcat = response.data.filter(data => data.parent_category !== 0)
                    this.allcategories = res
                    this.allfiltersubcategories = subcat
                })
        },

        updateImage(){
            axios.post(baseUrl+'update-category-image',).then(response => {
                if(response.data.status == 'success'){
                    this.successMessage(response.data)
                }
            })
        },
    },
    mounted(){
        this.getCategory()
    }
}
</script>

<template>
<div class="">
    <div class="statbox widget mt-2">
        <div class="widget-header">
            <div class="row">
                <div class="col-12">
                    <div class="icon-container mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg><span class="icon-name text-info"> Image Should be (1440 x 900) px, Ratio (16:9)</span>
                    </div>
                    <input type="submit" class="btn btn-info btn-block mb-4 mr-2" @click="openUploadModal('one')" value="File Upload" />
                    <v-lazy-image width="600" :src="form.category_image_one" alt="cat image one" :src-placeholder="url+'demo.png'" />
                </div>
            </div>
        </div>
    </div>
    <div class="statbox widget mt-2 d-flex justify-content-center">
        <div class="widget-header">
                <div class="col-12">
                    <div class="icon-container mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg><span class="icon-name text-info"> Image Should be (720 x 828) px, Ratio (16:24)</span>
                    </div>
                    <input type="submit" class="btn btn-info btn-block mb-4 mr-2" @click="openUploadModal('two')" value="File Upload" />
                    <v-lazy-image width="600" :src="form.category_image_two" alt="cat image one" :src-placeholder="url+'demo.png'" />
                </div>
            </div>
        </div>
    <div class="statbox widget mt-2">
        <div class="widget-header">
            <div class="col-12">
                <div class="icon-container mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg><span class="icon-name text-info"> Image Should be (720 x 828) px, Ratio (16:24)</span>
                </div>
                <input type="submit" class="btn btn-info btn-block mb-4 mr-2" @click="openUploadModal('three')" value="File Upload" />
                <v-lazy-image width="600" :src="form.category_image_three" alt="cat image one" :src-placeholder="url+'demo.png'" />
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>
.controless{
    width: 60% !important;
}
</style>
