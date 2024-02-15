<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import Mixin from '../../mixer';
import ProductDetail from './ProductDetail';
const date = new Date();
let day = date.getDate();
let month = date.getMonth() + 1;
let year = date.getFullYear();

export default {
    mixins:[Mixin],
    components:{
        Bootstrap4Pagination,
        ProductDetail
    },
    data(){
        return {
            allproduct: [],
            url: baseUrl,
            allcategories: [],
            allsubcategories: [],
            allfiltersubcategories: [],
            keyword: '',
            filterdata: {
                category: '',
                subcategory: '',
                camp_id: '',
                per_page: 10
            },
            isCheckAll: false,
            addTocamp: {
                campaign: '',
                discount_type: 'flat',
                type: 'campaign',
                discount_amount: '',
                max_amount: '',
                product:[],
            },
            campProd: [],
            allcampaign: [],
            allsection: [],
            campaign:{
                campaign_name: '',
                campaign_title: '',
                campaign_banner_default: '',
                campaign_meta_image: '',
                start_at: `${day}-${month}-${year}`,
                expire_at: `${day+1}-${month}-${year}`,
                status: true
            },
            singleproduct: null,
            file: null,
            file_from: '',
            limit: 3,
            keepLength: false,
            button_name: 'Upload',
            validation_error: {},
            url: baseUrl
        }
    },
    methods: {
        getProduct(page = 1) {
            try{
                axios.get(baseUrl+`get-product?page=${page}&per_page=${this.filterdata.per_page}&camp_id=${this.filterdata.camp_id}&category=${this.filterdata.category}&subcategory=${this.filterdata.subcategory}&keyword=${this.keyword}`)
                .then(response => {
                    this.allproduct = response.data

                    if(this.isCheckAll){
                        const ids = response.data.data.map(v=> v.id);
                        this.addTocamp.product.push(...ids);
                    }
                    // [...new Set(this.addTocamp.product)]
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },

        searchProduct(){
            if(this.keyword.length < 3) return ;
            this.getProduct()
        },

        getCategory() {
            axios.get(baseUrl+'get-category?no_paginate=yes').then(response => {
                    let res = response.data.filter(data => data.parent_category == 0)
                    let subcat = response.data.filter(data => data.parent_category !== 0)
                    this.allcategories = res
                    this.allfiltersubcategories = subcat
                })
        },
        getSubCategories() {
            const filterData = (this.allfiltersubcategories).filter((data) => data.parent_category == this.filterdata.category)
            this.allsubcategories = filterData
            if(filterData.length == 0) this.getProduct()
        },

        getCampaign(){
            axios.get(baseUrl+`get-campaign?no_paginate=yes&status=active`)
            .then(response => {
                this.allcampaign = response.data
            }).catch(error => {
                console.log(error)
            })
        },
        getHomeSection(){
            axios.get(baseUrl+`get-page-section?no_paginate=yes&status=active`)
            .then(response => {
                this.allsection = response.data
            }).catch(error => {
                console.log(error)
            })
        },

        filterClear(){
            this.filterdata = {
                category: '',
                subcategory: '',
                camp_id: '',
                per_page: 10
            },
            this.addTocamp = {
                campaign: '',
                discount_type: 'flat',
                type: 'campaign',
                discount_amount: '',
                max_amount: '',
                product:[],
            }
            this.keyword= ''
            this.allsubcategories = []
            this.getProduct()
        },
        toggleWhatsNew(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "This Product Affected in Whats New!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Do it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.get(baseUrl+`product-whats-new/${id}`).then(
                        response => {
                            this.getProduct()
                            this.successMessage(response.data)
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            })
        },

        deleteProduct(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(baseUrl+`product/${id}`).then(
                        response => {
                            this.getProduct()
                            this.successMessage(response.data)
                        }
                    ). catch(error => {
                        console.log(error)
                    })
                }
            })
        },
        openCampModal(){
            if(this.addTocamp.product.length < 1){
                alert('Please, Select Product');
                return ;
            }
            this.validation_error = {}
            $("#addToCampModal").modal('show')
        },

        getProductById(id){
            axios.get(baseUrl+'product/'+id)
            .then(response => {
                this.singleproduct = response.data
            }).catch(error => {
                console.log(error)
            })
        },

        showProdDetail(product){
            this.getProductById(product.id)
            $("#ProductDetails").modal('show')
        },

        addToCampaign(){
            axios.post(baseUrl+'add-to-campaign',this.addTocamp)
            .then(response => {
                $("#addToCampModal").modal('hide')
                this.filterClear()
                this.successMessage(response.data)
            }).catch(error => {
                console.log(error)
                if(error.response.status == '422'){
                    this.validation_error = error.response.data.errors;
                    // this.validationError()
                }
            })
        },

        handleBulkFileUpload(){
            // console.log(this.$refs.bulkfile);
            this.file = this.$refs.bulkfile.files[0];
            this.file_from = 'bulkUpload';
            document.getElementById("excel-file").innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> ' + this.file.name;
        },

        handleStockFileUpload(){
            // console.log(this.$refs.stockfile);
            this.file = this.$refs.stockfile.files[0];
            this.file_from = 'stockUpdate';
            document.getElementById("excel-file").innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> ' + this.file.name;
        },

        uploadExcel(){
            this.button_name = "Uploading...";
            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('file_from', this.file_from);
            axios.post(baseUrl+'product-import',
                formData,
                {
                    headers : {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(response => {
                if(response.data.status === 'success'){
                    this.successMessage(response.data);
                    $('#'+this.file_from).modal('hide');
                    formData = new FormData();
                    this.button_name = "Upload";
                    this.file = null;
                    this.file_from = ''
                }
                else
                {
                    this.successMessage(response.data);
                    formData = new FormData();
                    this.button_name = "Upload";
                    this.file = null;
                    this.file_from = ''
                }
            })
            .catch(err => {
                    if (err.response.status == 422)
                    {
                        this.validation_error = err.response.data.errors;
                        this.validationError();
                    }
                    else
                    {
                        this.successMessage(err);
                    }
                    formData = new FormData();
                    this.button_name = "Upload";
                    this.file = null;
                    this.file_from = ''
                }
            );
        },

        selectAll(){

            this.isCheckAll = !this.isCheckAll;
            this.addTocamp.product = [];
            if(this.isCheckAll){ // Check all
                const ids = this.allproduct.data.map(v => v.id)
                this.addTocamp.product = ids
            }
        },
    },
    mounted(){
        this.getProduct()
        this.getCategory()
        this.getCampaign()
        this.getHomeSection()
    },
    computed: {
        showPermission() {
            return window.userPermission;
        }
    }
}
</script>
<style scoped>
.hide {
    visibility: hidden;
}
.col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
    position: relative;
    width: 100%;
    padding-right: 15px;
    /* padding-left: 15px; */
}
</style>
<template>
    <div class="container-fluid mt-3">
        <div id="toggleAccordion">
            <div class="card">
                <div class="card-header" id="headingOne1">
                    <section class="mb-0 mt-0">
                    <div role="menu" class="collapsed" data-toggle="collapse" data-target="#defaultAccordionOne" aria-expanded="false" aria-controls="defaultAccordionOne">
                        Collapsible Group Item  <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                    </div>
                    </section>
                </div>

                <div id="defaultAccordionOne" class="collapse" aria-labelledby="headingOne1" data-parent="#toggleAccordion" style="">
                    <div class="card-body">
                        <div class="statbox widget box box-shadow mb-4" style="font-size: small;">
                            <div class="widget-header">
                                <div class="row d-flex align-items-center">
                                    <div class="col-md-2 col-lg-2 col-4 mb-3">
                                        <label for="search">Per-Page</label>
                                        <select id="product-perpage" class="form-control form-control-sm" @change="getProduct()" v-model="filterdata.per_page">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-12 mb-3">
                                        <label for="search">Search</label>
                                        <input type="text" @keyup="searchProduct()" v-model="keyword" class="form-control form-control-sm" id="search" placeholder="Search by Name & sku" >
                                    </div>
                                    <div class="col-md-4 col-lg-6 col-12 mb-3">
                                        <label for="max_amount">Campaign</label>
                                        <select id="product-camp" class="form-control form-control-sm" @change="getProduct()" v-model="filterdata.camp_id">
                                            <option selected="" value="">Choose...</option>
                                            <option v-for="(value,index) in allcampaign" :value="value.id" :key="index">{{ value.campaign_name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-lg-5 col-12 mb-3">
                                        <label for="max_amount"></label>
                                        <select id="product-category" class="form-control form-control-sm" @change="getSubCategories()" v-model="filterdata.category">
                                            <option selected="" value="">Category</option>
                                            <option v-for="(value,index) in allcategories" :value="value.id" :key="index">{{ value.category_name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 col-lg-5 col-12 mb-3">
                                        <label for="max_amount"></label>
                                        <select id="product-subcategory" class="form-control form-control-sm" @change="getProduct()" v-model="filterdata.subcategory">
                                            <option selected="" value="">Sub Category</option>
                                            <option v-for="(value,index) in allsubcategories" :value="value.id" :key="index">{{ value.category_name }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-lg-2 col-12">
                                        <button type="button" class="btn btn-danger btn-md w-100" @click="filterClear()" style="height:90vh;max-height:42px;">CLEAR</button>
                                    </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                        <div class="col mx-1">
                                            <button type="button" v-if="showPermission.includes('add-to-campaign')" class="btn btn-success btn-md w-100" @click="openCampModal()">Product Add</button>
                                        </div>
                                        <div class="col">
                                            <button type="button" v-if="showPermission.includes('bulk-upload')" class="btn btn-primary btn-md w-100 " data-toggle="modal" data-target="#bulkUpload">Bulk Upload</button>
                                        </div>

                                        <div class="col">
                                            <a type="button" :href="url+'all-attr-download'" class="btn btn-info btn-md w-100">Attr Sheet</a>
                                        </div>
                                        <div class="col">
                                            <a type="button" v-if="showPermission.includes('stock-sheet')" :href="url+'product-stock-download'" class="btn btn-secondary btn-md w-100">Stock Sheet</a>
                                        </div>
                                        <div class="col mr-1">
                                            <a type="button" v-if="showPermission.includes('stock-sheet')"  data-toggle="modal" data-target="#stockUpdate" class="btn btn-dark btn-md w-100">Stock Update</a>
                                        </div>
                                        <div class="col mr-1">
                                            <a type="button" :href="url+'product-bulk-download'" class="btn btn-warning btn-md w-100">Bulk Download</a>
                                        </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                <thead>
                    <tr>
                        <th class="checkbox-column">
                            <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                <input type="checkbox" @click="selectAll()" v-model="isCheckAll" class="new-control-input todochkbox" id="todoAll">
                                <span class="new-control-indicator"></span>
                            </label>
                        </th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Design Code</th>
                        <!-- <th class="text-center">What's New</th> -->
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(product) in allproduct.data" :key="product.id" >
                        <tr>
                            <td class="checkbox-column">
                                <label class="new-control new-checkbox checkbox-primary" style="height: 18px; margin: 0 auto;">
                                    <input type="checkbox" multiple :name="product.product_name" v-model="addTocamp.product" :value="product.id" class="new-control-input todochkbox" id="todo-1">
                                    <span class="new-control-indicator"></span>
                                </label>
                            </td>
                            <td>
                                <p class="mb-0">{{ product.product_name }}</p>
                            </td>
                            <td>{{ product.category.category_name }}</td>
                            <td>{{ product.subcategory.category_name }}</td>
                            <td>{{ product.design_code}}</td>
                            <!-- <td class="text-center">
                                <a href="javascript:void(0);" @click="toggleWhatsNew(product.id)" data-toggle="tooltip" data-placement="top" title="Make Change"><span class="badge shadow-none" :class="product.is_new == 1 ? 'outline-badge-info':'outline-badge-danger'">{{ product.is_new == 1 ? 'Enable' : 'Disable' }}</span></a>
                            </td> -->
                            <td class="text-center">
                                <span class="badge shadow-none" :class="product.status == 1 ? 'outline-badge-info':'outline-badge-danger'">{{ product.status ? 'Active' : 'Deactive' }}</span>
                            </td>
                            <td class="text-center">
                                <ul class="table-controls d-flex justify-content-around">
                                    <li><a href="javascript:void(0);" @click="showProdDetail(product)" data-toggle="tooltip" data-placement="top" title="View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings text-primary"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg></a> </li>
                                    <li v-if="showPermission.includes('product-edit')"><a :href="url+'product/'+product.id+'/edit'" data-toggle="tooltip" data-placement="top" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                    <li v-if="showPermission.includes('product-delete')"><a href="javascript:void(0);" @click="deleteProduct(product.id)" data-toggle="tooltip" data-placement="top" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>
                                </ul>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    <Bootstrap4Pagination
        :data="allproduct"
        @pagination-change-page="getProduct"
        :limit="limit"
        :keep-length="keepLength"
    />
    <product-detail v-if="singleproduct" :product="singleproduct"></product-detail>

    <div id="addToCampModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Add</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="widget-content-area">
                        <form @submit.prevent="addToCampaign()">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control tagging" id="type" v-model="addTocamp.type">
                                    <option value="campaign">Campaign</option>
                                    <option value="section">Home Section</option>
                                </select>
                            </div>
                            <div class="form-group" v-if="addTocamp.type == 'campaign'">
                                <label for="camp_name">Campaign Name</label>
                                    <select id="camp_name" class="form-control" v-model="addTocamp.campaign">
                                        <option value="">Choose...</option>
                                        <option v-for="(value,index) in allcampaign" :value="value.id" :key="index">{{ value.campaign_name }}</option>
                                    </select>
                                <span
                                    v-if="validation_error.hasOwnProperty('campaign')"
                                    class="text-danger"
                                >
                                    Campaign is required
                                </span>
                            </div>
                            <div class="form-group" v-else>
                                <label for="sectoin_name">Section Name</label>
                                    <select id="sectoin_name" class="form-control" v-model="addTocamp.campaign">
                                        <option value="">Choose...</option>
                                        <option v-for="(sect,index) in allsection" :value="sect.id" :key="index">{{ sect.section_name }}</option>
                                    </select>
                                <span
                                    v-if="validation_error.hasOwnProperty('campaign')"
                                    class="text-danger"
                                >
                                Section Name is required
                                </span>
                            </div>

                               <!-- <div class="form-group">
                                    <label for="discount_amount">Discount Amount</label>
                                    <input type="number" v-model="addTocamp.discount_amount" class="form-control" id="discount_amount" placeholder="Discount Amount" >
                                    <span
                                        v-if="validation_error.hasOwnProperty('discount_amount')"
                                        class="text-danger"
                                    >
                                        {{ validation_error.discount_amount[0] }}
                                    </span>
                                </div>

                                <div class="form-group mt-1">
                                    <label for="discount_type">Discount Type</label>
                                    <select class="form-control tagging" id="discount_type" v-model="addTocamp.discount_type">
                                        <option value="flat">FLAT</option>
                                        <option value="percentage">%</option>
                                    </select>
                                    <span
                                        v-if="validation_error.hasOwnProperty('discount_type')"
                                        class="text-danger"
                                    >
                                        {{ validation_error.discount_type[0] }}
                                    </span>
                                </div>
                                <div class="form-group mt-1" v-if="addTocamp.discount_type == 'percentage'">
                                    <label for="max_amount">Maximum</label>
                                    <input type="number" class="form-control" id="max_amount" placeholder="Maximum amount" v-model="addTocamp.max_amount" >
                                </div> -->

                            <div class="modal-footer md-button">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bulkUpload" tabindex="-1" role="dialog" aria-labelledby="bulkUploadLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkUploadLabel">Bulk Upload</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form">
                                <div class="form-group">
                                    <p>Upload Product According To Excel Format</p> <br>
                                    <span class="btn btn-primary btn-file">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                        <input type="file" id="bulkfile" ref="bulkfile" v-on:change="handleBulkFileUpload()"/>
                                    </span><br>
                                    <span class="fileinput-new mt-2" id="excel-file"></span>
                                </div>
                                <span
                                    v-if="validation_error.hasOwnProperty('file')"
                                    class="text-danger"
                                >
                                    {{ validation_error.file[0] }}
                                </span>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                    <button type="button" class="btn btn-primary" @click="uploadExcel">{{ button_name }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="stockUpdate" tabindex="-1" role="dialog" aria-labelledby="stockUpdateLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockUpdateLabel">Stock Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form">
                                <div class="form-group">
                                    <p>Upload Stock Update To Excel Format</p> <br>
                                    <span class="btn btn-primary btn-file">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                        <input type="file" id="stockfile" ref="stockfile" v-on:change="handleStockFileUpload()"/>
                                    </span><br>
                                    <span class="fileinput-new mt-2" id="excel-file"></span>
                                </div>
                                <span
                                    v-if="validation_error.hasOwnProperty('file')"
                                    class="text-danger"
                                >
                                    {{ validation_error.file[0] }}
                                </span>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                    <button type="button" class="btn btn-primary" @click="uploadExcel">{{ button_name }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

