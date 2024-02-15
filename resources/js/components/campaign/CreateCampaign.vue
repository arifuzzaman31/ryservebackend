<script>
import Mixin from '../../mixer'
import Multiselect from '@vueform/multiselect'
const date = new Date();
let day = date.getDate();
let month = date.getMonth() + 1;
let year = date.getFullYear();
export default {
    mixins:[Mixin],
    components: {
        Multiselect
    },
    data(){
        return {
            form:{
                campaign_name: '',
                campaign_title: '',
                campaign_banner_default: '',
                campaign_meta_image: '',
                start_at: `${day}-${month}-${year}`,
                expire_at: `${day+1}-${month}-${year}`,
                status: true
            },
            allcampaign: [],
            addcamp:{
                camp: {},
                discount: {
                    type: 'flat',
                    amount: '',
                    max_amount: ''
                },
                product: []
            },
            page_obj: {
                page: 1,
                curr_page: '',
                last_page: ''
            },
            page: 1,

            allproduct: [],
            validation_error: {},
        }
    },
    methods:{
        saveData() {

               axios.post(baseUrl+'campaign',this.form).then(
                  response => {
                    this.successMessage(response.data)
                    this.formReset()
                    this.getCampaignList()
                  }
              ). catch(error => {
                    this.validation_error = error.response.data.errors;

                    this.validationError()
              })

        },
        getProduct() {
            try{
                 axios.get(baseUrl+`get-product?page=${this.page_obj.this.page}&per_page=2&discount=yes&per_page=2`)
                .then(response => {
                    if(response.data.current_page > 1){
                        response.data.data.map(data => {
                            this.allproduct.push(data)
                        })
                    } else {
                        this.allproduct = response.data.data
                        this.page_obj.last_page = response.data.last_page
                    }
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },
        getMore(){
            this.page_obj.page++
            this.getProduct()
        },
        saveCampData(){

        },
        addToCamp(prod){
            this.addcamp.product.push(prod)

            this.allproduct = this.allproduct.filter(item => item.id !== prod.id);
        },
        remToCamp(prod){
            this.allproduct.push(prod)
            this.addcamp.product = this.addcamp.product.filter(item => item.id !== prod.id);
        },
        limitText (count) {
            return `and ${count} other products`
        },
        asyncFind (query) {
            // this.isLoading = true
            if(query.length < 3) return ;
            axios.get(baseUrl+'get-product/search?keyword='+query)
            .then(response => {
                if(response.data.status === 'not-found')
                {
                    this.allproduct = [];
                }
                else
                {
                    // this.allproduct = response.data
                    const opt = []
                        response.data.map(data => {
                            opt.push({'value':data.id,'name':data.product_name,'mrp_price':data.mrp_price})
                        })
                    this.allproduct = opt
                }
            })
        },
        clearAll () {
            this.addcamp.product = [];
        },
        getCampaignList() {
            axios.get(baseUrl+`get-campaign?no_paginate=yes`).then(
                  response => {
                    this.allcampaign = response.data;
                  }
              )

        },
        discount(type, discount, main_amount) {
            if (type === "2") {
                return parseFloat(((discount / 100) * main_amount)).toFixed(2);
            } else {
                return parseFloat(discount).toFixed(2);
            }
        },
        formReset(){
            this.validation_error = {};
            this.form = {
                campaign_name: '',
                campaign_title: '',
                campaign_banner_default: '',
                campaign_meta_image: '',
                start_at: `${day}-${month}-${year}`,
                expire_at: `${day}-${month}-${year}`,
                status: true
            }
        }
    },
    mounted(){
        this.getCampaignList()
        this.getProduct()
        window.flatpickr(document.getElementById('basicFlatpickr'));
        window.flatpickr(document.getElementById('basicFlatpickr2'));
    }
}
</script>

<template>
<div>
    <div class="row">
        <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <form @submit.prevent="saveCampData()">
                        <div class="form-row mb-4">
                            <div class="col-md-4 col-12 mb-2">
                                <label for="Campaign_name">Campaign Name</label>
                                <input type="text" v-model="form.campaign_name" id="Campaign_name" class="form-control" placeholder="Campaign name">
                                <span
                                    v-if="validation_error.hasOwnProperty('campaign_name')"
                                    class="text-danger"
                                >
                                    {{ validation_error.campaign_name[0] }}
                                </span>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                                <label for="Campaign_name">Campaign Start Date</label>
                                <input id="basicFlatpickr" v-model="form.start_at" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date.." />
                                <span
                                    v-if="validation_error.hasOwnProperty('start_at')"
                                    class="text-danger"
                                >
                                    {{ validation_error.start_at[0] }}
                                </span>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                                <label for="Campaign_name">Campaign End Date</label>
                                <input id="basicFlatpickr2" v-model="form.expire_at" class="form-control flatpickr flatpickr-input active" type="text" placeholder="Select Date.." />
                                <span
                                    v-if="validation_error.hasOwnProperty('expire_at')"
                                    class="text-danger"
                                >
                                    {{ validation_error.expire_at[0] }}
                                </span>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                                <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                                    <input v-model="form.status" type="checkbox" :checked="form.status" id="siz-status">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <input type="submit" name="time" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

<div class="row">
    <div id="tooltips" class="col-lg-12 layout-spacing col-md-12">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <div class="row">
                    <div class="form-group col-md-3 col-lg-3 col-12">
                        <label for="campaign-id">Select Campaign </label>
                        <select id="campaign-id" class="form-control" v-model="addcamp.camp">
                            <option selected="">Choose...</option>
                            <option v-for="(value,index) in allcampaign" :value="value" :key="index">{{ value.campaign_name }}</option>
                        </select>
                        <div class="mt-1" v-if="addcamp.camp && addcamp.camp.id">
                            <h6>Start_at : {{  dateToString(addcamp.camp.campaign_start_date) }}  </h6>
                            <h6>Expire_at : {{  dateToString(addcamp.camp.campaign_expire_date) }}</h6>
                        </div>
                    </div>
                    <div class="form-group col-md-3 col-lg-3 col-12">
                        <label for="campaign-disc">Discount Type </label>
                        <select id="campaign-id" class="form-control" v-model="addcamp.discount.type">
                            <option value="flat">FLAT</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3 col-lg-3 col-12">
                        <label for="campaign-disc-amount">Discount Amount </label>
                        <input type="number" class="form-control" placeholder="Amount">
                    </div>
                    <div class="form-group col-md-3 col-lg-3 col-12" v-if="addcamp.discount.type == 'percentage'">
                        <label for="campaign-disc-amount">Maximum Amount </label>
                        <input type="number" class="form-control" placeholder="Maximum Amount">
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div id="tooltips" class="col-lg-6 layout-spacing col-md-6">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <div class="row">
                    <div class="col-md-4 col-lg-3 col-6 mb-2" v-for="prod in allproduct" :key="prod.id">
                        <div class="card">
                            <div class="card-img">
                                <img width="90" :src="prod.product_image" />
                            </div>
                            <div class="card-body">
                                <b>{{prod.product_name}}</b>
                                <p>{{ formatPrice(prod.mrp_price) }}</p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="javasript:void(0)" type="button" @click="addToCamp(prod)" class="btn-default">Add</a>
                            </div>
                        </div>
                    </div>

                </div>
                <button type="button" v-if="page_obj.page < page_obj.last_page" @click="getMore()" class="btn-default">Load More</button>
            </div>
        </div>
    </div>
    <div id="tooltips" class="col-lg-6 layout-spacing col-md-6">
        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <div class="row">
                    <div class="col-md-4 col-lg-3 col-6 mb-2" v-for="prod in addcamp.product" :key="prod.id">
                        <div class="card">
                            <div class="card-img">
                                <img width="90" :src="prod.product_image" />
                            </div>
                            <div class="card-body">
                                <b>{{prod.product_name}}</b>
                                <p>{{ formatPrice(prod.mrp_price) }}</p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="javasript:void(0)" type="button" @click="remToCamp(prod)" class="btn-default">Remove</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
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
  </style>
