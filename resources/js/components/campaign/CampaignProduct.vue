<script>
import axios from 'axios';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import Mixin from '../../mixer';
const date = new Date();
let day = date.getDate();
let month = date.getMonth() + 1;
let year = date.getFullYear();

export default {
    props:['campaigndata'],
    mixins:[Mixin],
    components:{
        Bootstrap4Pagination
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
                camp_id: '',
                per_page: 10
            },
            isCheckAll: false,
            addTocamp: {
                campaign: '',
                discount_type: 'flat',
                discount_amount: '',
                max_amount: '',
                product:[],
            },
            campProd: [],
            product : {
                id: '',
                product_sku: [],
            },
            allcampaign: [],
            campaign:{
                campaign_name: '',
                campaign_title: '',
                campaign_banner_default: '',
                campaign_meta_image: '',
                start_at: `${day}-${month}-${year}`,
                expire_at: `${day+1}-${month}-${year}`,
                status: true
            },
            validation_error: {},
            file: null,
            limit: 3,
            keepLength: false,
            url: baseUrl
        }
    },
    methods: {
        getProduct(page = 1) {
            try{
                axios.get(baseUrl+`product-by-campaign/${this.campaigndata.id}?page=${page}&per_page=${this.filterdata.per_page}&keyword=${this.keyword}`)
                .then(response => {
                    // const res = response.data.filter((i,item) => item.campaign_id == this.campaigndata.id)
                    this.allproduct = response.data
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

        handleBulkDiscFileUpload(){
            // console.log(this.$refs.bulkfile);
            this.file_from = 'discountUpdate';
            this.file = this.$refs.bulkDiscfile.files[0];
            document.getElementById("excel-file-disc").innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> ' + this.file.name;
        },

        uploadFile(){
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

        filterClear(){
            this.filterdata = {
                category: '',
                camp_id: '',
                per_page: 10
            },
            this.keyword= ''
            this.allsubcategories = []
            this.getProduct()
        },
         getProductSku(product){
            this.product.id = product.id
            this.product.product_sku = []
            try{
                let arr = []
                product.inventory.forEach((item) => {
                    arr.push({'cpu':item.cpu,'mrp':item.mrp,'type':'campaign',
                        'discount_type':item.discount? item.discount.discount_type:'remove','sku':item.sku,
                        'discount':item.discount?item.discount.discount_amount:0,
                        'max_amount':item.discount?item.discount.max_amount:0
                    })
                })
                this.product.product_sku.push(...arr)
                $("#createDiscountModal").modal('show')

            }catch(e){
                console.log(e)
            }
        },
        storeDiscount(){
            try{
                axios.post(baseUrl+`store-discount`,this.product)
                .then(response => {
                    this.successMessage(response.data)
                    $("#createDiscountModal").modal('hide')
                    window.location.href = window.location.href;
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },
        singleRemove(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "Product won't be able to this campaign!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(baseUrl+`remove-product-camp`,{'camp_id':this.campaigndata.id,'product':[id]}).then(
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
                text: "Product won't be able to this campaign!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(baseUrl+`remove-product-camp`,{'camp_id':id,'product':this.addTocamp.product}).then(
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
        removeFromCamp(){
            if(this.addTocamp.product.length < 1){
                alert('Please, Select Product');
                return ;
            }
            this.deleteProduct(this.campaigndata.id)
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
</style>
<template>
    <div class="container-fluid mt-3">
        <div class="statbox widget box box-shadow mb-4">
            <!-- <div class="col">
                <button type="button" class="btn btn-primary btn-md w-100 " data-toggle="modal" data-target="#discountUpdate">Bulk Upload</button>
            </div> -->
            <div class="widget-header">
                <div class="row">
                    <div class="col-md-2 col-lg-1 col-4 mb-3">
                        <label for="search">Per-Page</label>
                        <select id="product-perpage" class="form-control" @change="getProduct()" v-model="filterdata.per_page">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3 col-12 mb-3">
                        <label for="search">Search</label>
                        <input type="text" @keyup="searchProduct()" v-model="keyword" class="form-control" id="search" placeholder="Search by Name & sku" >
                    </div>

                    <div class="col-md-4 col-lg-3 col-12 mt-4">
                        <button type="button" class="btn btn-danger btn-sm" @click="filterClear()">CLEAR</button>
                        <button type="button" v-if="showPermission.includes('product-remove-campaign')" class="btn btn-success btn-sm ml-2" @click="removeFromCamp()">Remove From Campaign</button>
                    </div>

                    <div class="col-md-4 col-lg-3 col-12">
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4" v-if="allproduct.data && allproduct.data.length > 0" >
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
                        <th class="text-center">Status</th>
                        <th class="text-center" v-if="showPermission.includes('product-remove-campaign')" >Action</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(product,index) in allproduct.data" :key="index" >
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
                            <td class="text-center">
                                <span class="badge shadow-none" :class="product.status == 1 ? 'outline-badge-info':'outline-badge-danger'">{{ product.status == 1 ? 'Active' : 'Deactive' }}</span>
                            </td>
                            <td class="d-flex justify-content-around" v-if="showPermission.includes('product-remove-campaign')" >
                                <a type="button" title="Remove" @click="singleRemove(product.id)" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                                <a type="button" title="Add Discount" @click="getProductSku(product)" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-percent text-warning"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>
                                </a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div v-else class="text-center">
                <h4>No Product Found</h4>
            </div>
        </div>
    <Bootstrap4Pagination
        :data="allproduct"
        @pagination-change-page="getProduct"
        :limit="limit"
        :keep-length="keepLength"
    />

    <div id="createDiscountModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Discount</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content widget-content-area">
                            <form @submit.prevent="storeDiscount()">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content ">

                                        <div class="row text-center">
                                            <div class="col-2 text-success">
                                                <b>SKU</b>
                                            </div>
                                            <div class="col-2 text-success">
                                                <b>CPU</b>
                                            </div>
                                            <div class="col-2 text-success">
                                                <b>MRP</b>
                                            </div>
                                            <div class="col-2 text-success">
                                                <b>Type</b>
                                            </div>
                                            <div class="col-2 text-success">
                                                <b>Discount</b>
                                            </div>
                                            <div class="col-2 text-success">
                                                <b>Max Amount</b>
                                            </div>

                                        </div>
                                        <div class="row" v-for="(qt,index) in product.product_sku" :key="index">
                                            <div class="form-group col-md-2">
                                                <input type="text"  class="form-control  form-control-sm" id="sku" v-model="qt.sku" placeholder="SKU" disabled>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <input type="number" step=any class="form-control  form-control-sm" id="cpue" v-model="qt.cpu" placeholder="CPU" disabled>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <input type="number" step=any class="form-control  form-control-sm" id="mrpe" v-model="qt.mrp" placeholder="MRP" disabled>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <div class="form-group">
                                                    <select id="campaign-id" class="form-control  form-control-sm" v-model="qt.discount_type">
                                                        <option value="flat">FLAT</option>
                                                        <option value="percentage">%</option>
                                                        <option value="remove">No Discount</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <input type="number"  class="form-control  form-control-sm" id="qty" v-model="qt.discount" placeholder="amount">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <input type="number"  class="form-control form-control-sm" id="qty" v-model="qt.max_amount" placeholder="Max Amount">
                                            </div>

                                        </div>
                                    </div>
                                </div>
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

    <div class="modal fade" id="discountUpdate" tabindex="-1" role="dialog" aria-labelledby="discountUpdateLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountUpdateLabel">Bulk Discount Upload</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form">
                                <div class="form-group">
                                    <p>Upload Excel Format</p> <br>
                                    <span class="btn btn-primary btn-file">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                        <input type="file" id="bulkDiscfile" ref="bulkDiscfile" v-on:change="handleBulkDiscFileUpload()"/>
                                    </span><br>
                                    <span class="fileinput-new mt-2" id="excel-file-disc"></span>
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
                    <button type="button" class="btn btn-primary" @click="uploadFile">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
