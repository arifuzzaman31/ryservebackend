<script>
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import axios from 'axios';
import Multiselect from '@vueform/multiselect'
import Mixin from '../../mixer'
import MediaHelper from '../common/MediaHelper.vue'

export default {
    name:'EditProduct',
    props:['pr_product','attrs'],
    mixins:[Mixin],
    components: {
        QuillEditor,
        Multiselect,
        MediaHelper
    },

    data(){
        return {
            form: {
                id:'',
                product_name: '',
                category: '',
                sub_category: '',
                vendor : [],
                brand : [],
                designer : [],
                embellishment : [],
                making : [],
                lead_time : '',
                season : [],
                variety : [],
                tags : [],
                fit : [],
                artist : [],
                consignment : [],
                ingredient : [],
                flat_colour : [],
                selectedImages : [],
                design_code: '',
                unit: '',
                height: '',
                width: '',
                length: '',
                weight: '',
                care: [],
                vat: '',
                fragile: 'No',
                fragile_charge: 0,
                is_fabric: true,
                fabrics : [],
                is_color: true,
                has_variation: true,
                selectcolours : [],
                is_size: true,
                selectsize : [],
                discount_amount: '',
                discount_type: 1,
                max_amount: 0,
                discount_type: 1,
                description: '',
                attrqty: []
            },
            tages: [],
            allcategories: [],
            allsubcategories: [],
            allfiltersubcategories: [],
            validation_error: {},

        }
    },
    methods: {
        updateForm () {
             axios.put(baseUrl+'product/'+this.form.id,this.form).then(response => {
                console.log(response.data)
                if(response.data.status == 'success'){
                    this.clearForm()
                    this.successMessage(response.data)
                    window.location.href = baseUrl+"product"
                }
            }).catch(e => {
                console.log(e)
                if(e.response.status == 422){
                    this.validation_error = e.response.data.errors;
                    this.validationError()
                }
            })
        },

        addMore(){
            this.form.attrqty.push({colour_id:[],size_id:'',cpu:'',mrp:'',qty:'',sku:''})
        },
        removeCatChild(index) {
            this.form.attrqty.splice(index, 1);
        },

        getCategory() {
             axios.get(baseUrl+'get-category?no_paginate=yes').then(response => {
                const cat = response.data
                let res = cat.filter(data => data.parent_category == 0)
                let subcat = cat.filter(data => data.parent_category != 0)
                this.allcategories.push(...res)
                // console.log(subcat)
                this.allfiltersubcategories.push(...subcat)
                if(this.form.sub_category != ''){
                    this.getSubCategories()
                }
            })
        },
        getColour() {
            try{
                 axios.get(baseUrl+'colour/create?no_paginate=yes').then(response => {
                    // allcolours.value = response.data
                    const opt = []
                    response.data.map(data => {
                        opt.push({'value':data.id,'name':data.color_name})
                    })
                    this.allcolours = opt
                    // console.log(response.data)
                }).catch(error => {
                    console.log(error)
                })
            }catch(error){
                console.log(error)
            }
        },
        getSize() {
            try{
                axios.get(baseUrl+'sizes/create?no_paginate=yes').then(response => {
                    const opt = []
                    response.data.map(data => {
                        opt.push({'value':data.id,'name':data.size_name})
                    })
                    this.allsizes = opt
                }).catch(error => {
                    console.log(error)
                })
            }catch(error){
                console.log(error)
            }
        },
        getFabric(){
            try{
                axios.get(baseUrl+'fabrics/create?no_paginate=yes').then(response => {
                    const opt = []
                    response.data.map(data => {
                        opt.push({'value':data.id,'name':data.fabric_name})
                    })
                    this.allfabrics = opt
                }).catch(error => {
                    console.log(error)
                })
            }catch(error){
                console.log(error)
            }
        },
        getSubCategories(){
            const filterData = (this.allfiltersubcategories).filter((data) => data.parent_category == this.form.category)
            this.allsubcategories = filterData
        },
        clearForm() {
            this.form = {
                product_name: '',
                category: '',
                sub_category: '',
                sku: '',
                vendor : [],
                brand : [],
                designer : [],
                embellishment : [],
                making : [],
                lead_time : '',
                season : [],
                variety : [],
                tags : [],
                fit : [],
                artist : [],
                consignment : [],
                ingredient : [],
                selectedImages : [],
                design_code: '',
                unit: '',
                height: '',
                width: '',
                length: '',
                weight: '',
                care: [],
                vat: 1,
                fragile: 'No',
                fragile_charge: 0,
                is_fabric: true,
                fabrics : [],
                is_color: true,
                has_variation: true,
                selectcolours : [],
                is_size: true,
                selectsize : [],
                discount_amount: '',
                discount_type: 1,
                max_amount: 0,
                discount_type: 1,
                description: '',
                attrqty: []
            },
            this.tages = []
            this.allsubcategories= []
            this.validation_error = {}
        },

        updateMediaModalOpen(){
            $("#pageMediaModal").modal('show');
        },

        selectImage(item){
            if(this.form.selectedImages && this.form.selectedImages.length < 4){
                this.form.selectedImages.push(item)
                new Set([...this.form.selectedImages])
            } else {
                this.validationError({"message":"Maximum 4 File Upload!"})
            }
        }
    },

    mounted(){
        this.getCategory()
        this.form.id = this.pr_product.id
        this.form.product_name = this.pr_product.product_name
        this.form.category = this.pr_product.category_id
        this.form.sub_category = this.pr_product.sub_category_id
        this.form.unit = this.pr_product.unit
        this.form.height = this.pr_product.height
        this.form.width = this.pr_product.width
        this.form.length = this.pr_product.length
        this.form.fragile = this.pr_product.fragile
        this.form.fragile_charge = this.pr_product.fragile_charge
        this.form.has_variation = this.pr_product.has_variation == 1 ? true : false

        const vids = this.pr_product.product_vendor.map(v=> v.id);
        this.form.vendor.push(...vids);
        const brids = this.pr_product.product_brand.map(v=> v.id);
        this.form.brand.push(...brids);
        const dids = this.pr_product.product_designer.map(v=> v.id);
        this.form.designer.push(...dids);
        const emids = this.pr_product.product_embellishment.map(v=> v.id);
        this.form.embellishment.push(...emids);
        const mids = this.pr_product.product_making.map(v=> v.id);
        this.form.making.push(...mids);
        const sids = this.pr_product.product_season.map(v=> v.id);
        this.form.season.push(...sids);
        const vaids = this.pr_product.product_variety.map(v=> v.id);
        this.form.variety.push(...vaids);
        const fids = this.pr_product.product_fit.map(v=> v.id);
        this.form.fit.push(...fids);
        const arids = this.pr_product.product_artist.map(v=> v.id);
        this.form.artist.push(...arids);
        const coids = this.pr_product.product_consignment.map(v=> v.id);
        this.form.consignment.push(...coids);
        const inids = this.pr_product.product_ingredient.map(v=> v.id);
        this.form.ingredient.push(...inids);
        const caids = this.pr_product.product_care.map(v=> v.id);
        this.form.care.push(...caids);
        const faids = this.pr_product.product_fabric.map(v=> v.id);
        this.form.fabrics.push(...faids);

        if(this.pr_product.flat_colour && this.pr_product.flat_colour != ''){
            let in_color = this.pr_product.flat_colour.split(",")
            this.form.flat_colour.push(...in_color);
        }

        let arr = []

        this.pr_product.inventory.forEach((item,ind) => {
            let index = arr.findIndex(tm => (item.sku == tm.sku && item.cpu == tm.cpu && item.mrp == tm.mrp));
            if(index == -1){
                if(item.colour_id > 0 || item.size_id > 0){
                    arr.push({'colour_id':[item.colour_id],'size_id':item.size_id,'cpu':item.cpu,'mrp':item.mrp,'qty':item.stock,'sku':item.sku})
                } else {
                    arr.push({'colour_id':[],'size_id': '','cpu':item.cpu,'mrp':item.mrp,'qty':item.stock,'sku':item.sku})
                }
            } else {
               arr[index].colour_id.push(item.colour_id)
            }
        })
        this.form.attrqty.push(...arr);

        this.form.vat  = this.pr_product.vat_tax_id
        this.form.lead_time  = this.pr_product.lead_time
        this.form.selectedImages = [this.pr_product.image_one,this.pr_product.image_two,this.pr_product.image_three,this.pr_product.image_four].filter(entry => entry != null);
        this.form.design_code = this.pr_product.design_code
        this.form.dimention = this.pr_product.dimension
        this.form.weight = this.pr_product.weight
        if(this.pr_product.tag && this.pr_product.tag.keyword_name != ''){
            let in_tag = this.pr_product.tag.keyword_name.split(",")
            this.tages.push(...in_tag); this.form.tags.push(...in_tag)
        }

        this.form.description = this.pr_product.description

    }
}
</script>
<template>
    <form class="needs-validation" method="post" @submit.prevent="updateForm" id="update-product-form">
        <div class="row">
            <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                <div class="statbox widget box ">
                    <div class="widget-content ">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label for="product-name">Product name</label>
                                <input type="text" class="form-control" :class="validation_error.hasOwnProperty('product_name') ? 'is-invalid' : ''" id="product-name" placeholder="Product name" v-model="form.product_name" >
                                    <div
                                        v-if="validation_error.hasOwnProperty('product_name')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.product_name[0] }}
                                    </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="product-category">Category </label>
                                <select id="product-category" class="form-control" @change="getSubCategories()" v-model="form.category">
                                    <option value="">Choose Category...</option>
                                    <option v-for="(value,index) in allcategories" :value="value.id" :key="index">{{ value.category_name }}</option>
                                </select>
                                <div
                                        v-if="validation_error.hasOwnProperty('category')"
                                        class="text-danger font-weight-bold"
                                    >
                                        {{ validation_error.category[0] }}
                                    </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="product-subcategory">Sub Category</label>
                                <select id="product-subcategory" class="form-control" v-model="form.sub_category">
                                    <option value="">Choose Sub Category...</option>
                                    <option v-for="(value,index) in allsubcategories" :value="value.id" :key="index">{{ value.category_name }}</option>
                                </select>
                                <div
                                    v-if="validation_error.hasOwnProperty('sub_category')"
                                    class="text-danger font-weight-bold"
                                >
                                    {{ validation_error.sub_category[0] }}
                                </div>
                            </div>
                            </div>

                            <div class="row mt-2">
                                <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                                    <div class="widget-content ">
                                        <label for="editor-container">Description</label>
                                        <QuillEditor theme="snow" v-model:content="form.description" contentType="html" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                            <div class="col-md-12 mb-3">
                            <label for="product-Vendor">Vendor</label>
                             <Multiselect
                                v-model="form.vendor"
                                mode="tags"
                                placeholder="Select Vendor"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.vendor"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="product-Brand">Brand</label>
                             <Multiselect
                                v-model="form.brand"
                                mode="tags"
                                placeholder="Select Brand"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.brand"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="product-Designer">Designer</label>
                             <Multiselect
                                v-model="form.designer"
                                mode="tags"
                                placeholder="Select Designer"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.designer"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="product-Embellishment">Embellishment</label>
                             <Multiselect
                                v-model="form.embellishment"
                                mode="tags"
                                placeholder="Select Embellishment"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.embellish"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="product-Making">Making</label>
                             <Multiselect
                                v-model="form.making"
                                mode="tags"
                                placeholder="Select Making"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.making"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="product-Season">Season</label>
                             <Multiselect
                                v-model="form.season"
                                mode="tags"
                                placeholder="Select Season"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.season"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="product-Variety">Variety</label>
                             <Multiselect
                                v-model="form.variety"
                                mode="tags"
                                placeholder="Select Variety"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.variety"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="product-Fit">Fit</label>
                             <Multiselect
                                v-model="form.fit"
                                mode="tags"
                                placeholder="Select Fit"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.fit"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="product-Artist">Artist</label>
                             <Multiselect
                                v-model="form.artist"
                                mode="tags"
                                placeholder="Select Artist"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.artist"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="product-Consignment">Consignment</label>
                             <Multiselect
                                v-model="form.consignment"
                                mode="tags"
                                placeholder="Select Consignment"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.consignment"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="product-Season">Ingredients</label>
                             <Multiselect
                                v-model="form.ingredient"
                                mode="tags"
                                placeholder="Select Ingredients"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.ingredient"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="product-Care">Care</label>
                             <Multiselect
                                v-model="form.care"
                                mode="tags"
                                placeholder="Select Care"
                                track-by="name"
                                label="name"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.care"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tooltips" class="mb-2">
            <div class="statbox widget box ">
                <div class="widget-content ">
                    <div class="row">
                        <div class="col-12 my-2 text-center">
                            <button type="button" class="btn btn-sm btn-info" @click="updateMediaModalOpen()">Upload files</button>
                        </div>
                        <div class="col-md-3 d-flex justify-content-center" v-for="(itm,index) in form.selectedImages" :key="index">
                            <v-lazy-image :src="itm" style="width:80px;height:100px" class="img-fluid rounded" alt="product-image" />
                            <button type="button" @click="() => this.form.selectedImages.splice(index, 1)" class="close text-danger image-close" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
                <div class="statbox widget box ">
                    <div class="widget-content ">
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="design_code">Design Code</label>
                                <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('design_code') ? 'is-invalid' : ''" id="design_code" placeholder="Design Code" v-model="form.design_code">
                                <div
                                    v-if="validation_error.hasOwnProperty('design_code')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.design_code[0] }}
                                    </div>
                            </div>

                            <div class="form-group col-md-4 mb-3">
                                <label for="product-LeadTime">Fragile</label>
                                <select id="product-category" class="form-control form-control-sm" v-model="form.fragile">
                                    <option value="Yes">Fragile</option>
                                    <option value="No">Non-Fragile</option>
                                </select>
                                    <div
                                        v-if="validation_error.hasOwnProperty('fragile')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.fragile[0] }}
                                    </div>
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <label for="product-fragile_charge">Fragile Charge</label>
                                <input type="number" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('fragile_charge') ? 'is-invalid' : ''" id="fragile_charge-name" v-model="form.fragile_charge" >
                                    <div
                                        v-if="validation_error.hasOwnProperty('fragile_charge')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.fragile_charge[0] }}
                                    </div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="weight">Weight (gm)</label>
                                <input type="number" step=any class="form-control form-control-sm" :class="validation_error.hasOwnProperty('weight') ? 'is-invalid' : ''" id="weight" placeholder="Example: 200" v-model="form.weight" >
                                <div
                                        v-if="validation_error.hasOwnProperty('weight')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.weight[0] }}
                                    </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="width">Width</label>
                                <input type="number" step=any class="form-control form-control-sm" id="width" placeholder="Enter Width" v-model="form.width" />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="height">Height</label>
                                <input type="number" step=any class="form-control form-control-sm" id="height" placeholder="Enter Height" v-model="form.height" />
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="length">Length</label>
                                <input type="number" step=any class="form-control form-control-sm" id="length" placeholder="Enter Length" v-model="form.length" />
                            </div>
                            <div class="form-group col-md-2 mb-3">
                                <label for="product-LeadTime">Lead Time</label>
                                <input type="text" class="form-control form-control-sm" :class="validation_error.hasOwnProperty('lead_time') ? 'is-invalid' : ''" id="LeadTime-name" placeholder="Lead Time" v-model="form.lead_time" >
                                    <div
                                        v-if="validation_error.hasOwnProperty('lead_time')"
                                        class="invalid-feedback"
                                    >
                                        {{ validation_error.lead_time[0] }}
                                    </div>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="unit">Unit</label>
                                <input type="text" class="form-control form-control-sm" id="unit" placeholder="Enter Unit" v-model="form.unit" />
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="statbox widget box-shadow">
            <div class="widget-content">
                <div class="form-row">
                    <div class="col-md-12">
                        <label for="product-Tags">VAT</label>
                        <Multiselect
                                v-model="form.vat"
                                placeholder="Select VAT"
                                track-by="name"
                                label="name"
                                :close-on-select="true"
                                :options="attrs.tax"
                                :searchable="true"
                            >
                            <template v-slot:tag="{ option, handleTagRemove, disabled }">
                            <div
                                class="multiselect-tag is-user"
                                :class="{
                                'is-disabled': disabled
                                }"
                            >
                                {{ option.name }}
                                <span
                                v-if="!disabled"
                                class="multiselect-tag-remove"
                                @mousedown.prevent="handleTagRemove(option, $event)"
                                >
                                <span class="multiselect-tag-remove-icon"></span>
                                </span>
                            </div>
                            </template>
                        </Multiselect>
                    </div>
                </div>
            </div>
        </div>

        <div class="statbox widget box-shadow">
            <div class="widget-content">
                <div class="form-row">
                    <div class="col-md-12">
                        <label for="product-Tags">Colour</label>
                        <Multiselect
                                v-model="form.flat_colour"
                                placeholder="Select Colour"
                                track-by="name"
                                label="name"
                                mode="tags"
                                :close-on-select="true"
                                :options="attrs.flat_colour"
                                :searchable="true"
                            >
                            <template v-slot:tag="{ option, handleTagRemove, disabled }">
                            <div
                                class="multiselect-tag is-user"
                                :class="{
                                'is-disabled': disabled
                                }"
                            >
                                {{ option.name }}
                                <span
                                v-if="!disabled"
                                class="multiselect-tag-remove"
                                @mousedown.prevent="handleTagRemove(option, $event)"
                                >
                                <span class="multiselect-tag-remove-icon"></span>
                                </span>
                            </div>
                            </template>
                        </Multiselect>
                    </div>
                </div>
            </div>
        </div>

            <div class="statbox widget box box-shadow mt-2">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex">
                            <h5>Attribute</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="statbox widget box box-shadow" v-if="form.has_variation">
                <div class="widget-content ">
                    <div class="row text-center">
                        <div class="col-3  text-success">
                            <b>Colour</b>
                        </div>
                        <div class="col-2  text-success">
                           <b>Size</b>
                        </div>
                        <div class="col-2  text-success">
                            <b>SKU</b>
                        </div>
                        <div class="col-1  text-success">
                            <b>CPU</b>
                        </div>
                        <div class="col-1  text-success">
                            <b>MRP</b>
                        </div>
                        <div class="col-2  text-success">
                            <b>Qty</b>
                        </div>
                        <div class="col-1  text-danger">
                            <b>Remove</b>
                        </div>
                    </div>
                    <div class="row" v-for="(qt,index) in form.attrqty" :key="index">
                        <div class="form-group col-md-3">
                            <Multiselect
                                v-model="qt.colour_id"
                                placeholder="Select Colour"
                                track-by="name"
                                label="name"
                                mode="tags"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.colour"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>

                        </div>
                        <div class="form-group col-md-2">
                            <select id="product-category" class="form-control" v-model="qt.size_id">
                                <option value="">Choose Size...</option>
                                <option v-for="(value,index) in attrs.size" :value="value.value" :key="index">{{ value.name }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="text"  class="form-control" id="sku" v-model="qt.sku" placeholder="SKU" required>
                        </div>
                        <div class="form-group col-md-1">
                            <input type="text" step=any class="form-control" id="sku" v-model="qt.cpu" placeholder="CPU" required>
                        </div>
                        <div class="form-group col-md-1">
                            <input type="number" step=any class="form-control" id="sku" v-model="qt.mrp" placeholder="MRP" required>
                        </div>
                        <div class="form-group col-md-2">
                            <input type="number" class="form-control" id="qty" v-model="qt.qty" placeholder="qty" required>
                        </div>
                        <div class="form-group col-md-1 text-center" v-if="index != 0">
                            <a
                              href="javascript:void(0)"
                              @click.prevent="removeCatChild(index)"
                              class="mt-5"
                              ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a
                            >
                        </div>
                    </div>
                </div>
                <a
                        href="javascript:void(0)"
                        @click.prevent="addMore()"
                        class="btn btn-warning"
                    >Add More
                    </a>
            </div>

            <div class="statbox widget box box-shadow" v-else>
                <div class="widget-content ">
                    <div class="row text-center">
                        <div class="col-3 text-success">
                            <b>SKU</b>
                        </div>
                        <div class="col-3 text-success">
                            <b>CPU</b>
                        </div>
                        <div class="col-3 text-success">
                            <b>MRP</b>
                        </div>
                        <div class="col-3 text-success">
                            <b>Qty</b>
                        </div>

                    </div>
                    <div class="row" v-for="(qt,index) in form.attrqty" :key="index">
                        <div class="form-group col-md-3">
                            <input type="text"  class="form-control" id="sku" v-model="qt.sku" placeholder="SKU" required>
                        </div>
                        <div class="form-group col-md-3">
                            <input type="number" step=any class="form-control" id="cpue" v-model="qt.cpu" placeholder="CPU" required>
                        </div>
                        <div class="form-group col-md-3">
                            <input type="number" step=any class="form-control" id="mrpe" v-model="qt.mrp" placeholder="MRP" required>
                        </div>
                        <div class="form-group col-md-3">
                            <input type="number"  class="form-control" id="qty" v-model="qt.qty" placeholder="qty" required>
                        </div>

                    </div>
                </div>
            </div>

            <div class="statbox widget box box-shadow">
                <div class="widget-content ">
                    <div class="form-row">

                        <div class="col-md-12 mb-3">
                            <label for="product-Fabric">Composition</label>
                            <Multiselect
                                v-model="form.fabrics"
                                placeholder="Select Fabric"
                                track-by="name"
                                label="name"
                                mode="tags"
                                :close-on-select="false"
                                :search="true"
                                :options="attrs.fabric"
                                :searchable="true"
                                >
                                <template v-slot:tag="{ option, handleTagRemove, disabled }">
                                <div
                                    class="multiselect-tag is-user"
                                    :class="{
                                    'is-disabled': disabled
                                    }"
                                >
                                    {{ option.name }}
                                    <span
                                    v-if="!disabled"
                                    class="multiselect-tag-remove"
                                    @mousedown.prevent="handleTagRemove(option, $event)"
                                    >
                                    <span class="multiselect-tag-remove-icon"></span>
                                    </span>
                                </div>
                                </template>
                            </Multiselect>
                        </div>
                    </div>
            </div>

        </div>
        <div class="statbox widget box box-shadow">
            <div class="widget-content ">
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label for="product-Tags">Tags</label>
                        <Multiselect
                            v-model="form.tags"
                            placeholder="Create Tags"
                            :create-option="true"
                            label="name" track-by="code"
                            mode="tags"
                            :close-on-select="false"
                            :searchable="true"
                            :options="tages"
                            :multiple="true"
                            :taggable="true"
                            @tag="addTag"
                            >
                            <template v-slot:tag="{ option, handleTagRemove, disabled }">
                            <div
                                class="multiselect-tag is-user"
                                :class="{
                                'is-disabled': disabled
                                }"
                            >
                                {{ option.name }}
                                <span
                                v-if="!disabled"
                                class="multiselect-tag-remove"
                                @mousedown.prevent="handleTagRemove(option, $event)"
                                >
                                <span class="multiselect-tag-remove-icon"></span>
                                </span>
                            </div>
                            </template>
                        </Multiselect>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-success mt-1" type="submit">Update</button>
    </form>
        <media-helper :setImg="selectImage">
            <template v-slot:viewimage>
                <div class="col-md-12 d-flex justify-content-center my-2" v-for="(itm,index) in this.form.selectedImages" :key="index">
                    <v-lazy-image :src="itm" style="width:80%;height:90%" class="img-fluid rounded" />
                    <button type="button" @click="() => this.form.selectedImages.splice(index, 1)" class="close text-danger image-close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </template>
        </media-helper>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
.col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
    position: relative;
    width: 100%;
    padding-right: 0px;
    padding-left: 15px;
}
  .multiselect-tag.is-user {
    padding: 5px 8px;
    border-radius: 22px;
    background: #35495e;
    margin: 3px 3px 8px;
  }

  .multiselect-tag.is-user img {
    width: 18px;
    border-radius: 50%;
    height: 18px;
    margin-right: 8px;
    border: 2px solid #ffffffbf;
  }

  .multiselect-tag.is-user i:before {
    color: #ffffff;
    border-radius: 50%;;
  }

  .user-image {
    margin: 0 6px 0 0;
    border-radius: 50%;
    height: 22px;
  }
.image-close {
  position: absolute;
  font-size: 1.9rem;
  z-index: 2;
}

.image-close:hover:before {
  opacity: 1;
  transition: all 200ms ease;
}
</style>
