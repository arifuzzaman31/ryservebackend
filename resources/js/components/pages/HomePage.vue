<script>
import Mixin from '../../mixer'
import MediaHelper from '../common/MediaHelper.vue'
export default {
  components: { MediaHelper },
    mixins: [Mixin],
    data() {
        return {
            form: {
                section_name: '',
                banner: [{banner_uri: '',file_type: '',back_link: '',name:''}],
                pattern: 'single',
                use_for: 'campaign',
                precedence: 1,
                status: 1
            },
            updateFormdata: {
                category: '',
                categoryname: '',
                subcategory: '',
                subcategoryObj: '',
                imageuri: '',
                imagenumb: '',
                back_url: '',
                state: 'home',
                selectLink: 'category',
            },
            url: baseUrl,
            allcategories: [],
            allsubcategories: [],
            campaigns: [],
            target_state: '',
            allfiltersubcategories: [],
            validation_error: {},
        }
    },

    methods: {
            createSection(){
                this.form.banner.map((v,i) => {
                    const vExt = ['mp4','WebM','mov','avi','mkv','wmv','avchd','flv'];
                    let ext = v.banner_uri.split(".").pop();
                    var frmt = vExt.includes(ext) ? 'video' : 'image';
                    this.form.banner[i].file_type = frmt
                })
                axios.post(baseUrl+'create-home-section',this.form).then(response => {
                    if(response.data.status == 'success'){
                        // console.log(response.data)
                    //     this.getHomeData()
                        this.clearFilter()
                        this.successMessage(response.data)
                        window.location.href = 'home-page'
                    }
                })
            },

            // setImage(numb,uri){
            //     switch (numb) {
            //         case 'one':
            //             this.form.image_one = uri
            //             this.updateFormdata.imageuri = uri
            //             this.updateFormdata.imagenumb = 'one'
            //             break;
            //         case 'two':
            //             this.form.image_two = uri
            //             this.updateFormdata.imageuri = uri
            //             this.updateFormdata.imagenumb = 'two'
            //             break;
            //         case 'three':
            //             this.form.image_three = uri
            //             this.updateFormdata.imageuri = uri
            //             this.updateFormdata.imagenumb = 'three'
            //             break;

            //         case 'four':
            //             this.form.image_four = uri
            //             this.updateFormdata.imageuri = uri
            //             this.updateFormdata.imagenumb = 'four'
            //             break;

            //         case 'five':
            //             this.form.image_five = uri
            //             this.updateFormdata.imageuri = uri
            //             this.updateFormdata.imagenumb = 'five'
            //             break;

            //         default:
            //             break;
            //     }
            // },

            // updateImage(){
            //     axios.post(baseUrl+'update-home-image',this.updateFormdata).then(response => {
            //         if(response.data.status == 'success'){
            //             this.getHomeData()
            //             this.clearFilter()
            //             this.successMessage(response.data)
            //         }
            //     })
            // },

            getHomeData(){
                axios.get(baseUrl+'get-home-pagedata').then(response => {
                    this.form.image_one = response.data.image_one
                    this.form.image_two = response.data.image_two
                    this.form.image_three = response.data.image_three
                    this.form.image_four = response.data.image_four
                    this.form.image_five = response.data.image_five

                    this.form.back_url_two = response.data.back_url_two
                    this.form.back_url_three = response.data.back_url_three
                    this.form.back_url_four = response.data.back_url_four
                    this.form.back_url_five = response.data.back_url_five
                    this.form.back_url_six = response.data.back_url_six
                })
            },
            setStateNumb(numb){
                this.updateFormdata.category = ''
                this.updateFormdata.categoryname = ''
                this.updateFormdata.subcategory = ''
                this.updateFormdata.imagenumb = numb
            },

            getCategory() {
                axios.get(baseUrl+'get-category?no_paginate=yes').then(response => {
                        let res = response.data.filter(data => data.parent_category == 0)
                        let subcat = response.data.filter(data => data.parent_category != 0)
                        this.allcategories = res
                        this.allfiltersubcategories = subcat
                    })
            },
            getSubCategories() {
                this.form.banner[this.updateFormdata.imagenumb].back_link = ''
                this.form.banner[this.updateFormdata.imagenumb].name = ''
                const updateFormData = (this.allfiltersubcategories).filter((data) => data.parent_category == this.updateFormdata.category.id)
                this.allsubcategories = updateFormData
                // this.form.banner[this.updateFormdata.imagenumb].back_link = this.updateFormdata.category.slug+'/'+this.updateFormdata.subcategory
                // this.form.banner[this.updateFormdata.imagenumb].name = this.updateFormdata.category.category_name
            },

            getCampaign(){
                try{
                    axios.get(baseUrl+`get-campaign?status=yes&no_paginate=yes`)
                    .then(response => {
                        this.campaigns = response.data
                    }).catch(error => {
                        console.log(error)
                    })
                }catch(e){
                    console.log(e)
                }
            },

            clearFilter(){
                this.form = {
                    section_name: '',
                    banner: [{banner_uri: '',file_type: '',back_link: '',name:''}],
                    pattern: 'single',
                    use_for: 'campaign',
                    precedence: 1,
                    status: 1
                }
                this.updateFormdata = {
                    category: '',
                    categoryname: '',
                    subcategory: '',
                    imageuri: '',
                    imagenumb: '',
                    back_url: '',
                    state: 'home',
                    selectLink: 'category'
                }
                this.allsubcategories = []
            },

        openPageMediaModal(serial){
            this.updateFormdata.imagenumb = serial
            $("#pageMediaModal").modal('show');
        },

        selectImage(item){
            this.updateFormdata.imageuri = item
            this.form.banner[this.updateFormdata.imagenumb].banner_uri = item
            const vExt = ['mp4','WebM','mov','avi','mkv','wmv','avchd','flv'];
            let ext = item.split(".").pop();
            var frmt = vExt.includes(ext) ? 'video' : 'image';
            this.form.banner[this.updateFormdata.imagenumb].file_type = frmt
            // this.setImage(this.updateFormdata.imagenumb,item)
        },

        setLink(){
            if(this.form.use_for == 'campaign'){
                this.form.banner[this.updateFormdata.imagenumb].back_link = 'campaign/cat='+this.updateFormdata.subcategory.id+'&cat_name='+this.updateFormdata.subcategory.slug
                this.form.banner[this.updateFormdata.imagenumb].name = this.updateFormdata.subcategory.campaign_name
            }
            if(this.form.use_for == 'category'){
                if(this.updateFormdata.subcategoryObj != ''){
                    const catName = this.updateFormdata.subcategoryObj.category_name.replace(/[^a-zA-Z ]/g, "");
                    this.form.banner[this.updateFormdata.imagenumb].name = catName
                    this.form.banner[this.updateFormdata.imagenumb].back_link = 'products/'+this.updateFormdata.subcategoryObj.slug+'?cat='+this.updateFormdata.category.id+'&sub_cat='+this.updateFormdata.subcategoryObj.id
                } else {
                    const catName = this.updateFormdata.category.category_name.replace(/[^a-zA-Z ]/g, "");
                    this.form.banner[this.updateFormdata.imagenumb].name = catName
                    this.form.banner[this.updateFormdata.imagenumb].back_link = 'products?cat='+this.updateFormdata.category.id+'&cat_name='+this.updateFormdata.category.slug
                }
            }
            this.updateFormdata.subcategoryObj = ''
            this.updateFormdata.imageuri = ''
            this.updateFormdata.imagenumb = ''
            this.updateFormdata.subcategory = ''
            this.updateFormdata.category = ''
        },

        makeDesign(){
            if(this.form.use_for == 'category'){
                this.getCategory()
            }
            if(this.form.use_for == 'campaign'){
                this.getCampaign()
            }
            if(this.form.pattern == 'double'){
                this.form.banner.push({banner_uri: '',file_type: '',back_link: '',name:''})
            }
        }
    },
    mounted(){
        // this.getHomeData()
    },
    computed: {
        showPermission() {
            return window.userPermission;
        }
    }
}
</script>

<template>
    <div id="tabsBordered" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Home Page</h4>
                    </div>
                </div>
            </div>
            <div class="widget-content widget-content-area vertical-border-pill">
                <div class="row mb-4 mt-3">
                    <div class="col-sm-3 col-12">
                        <div>
                            <p class="text-success" style="font-size:17px">Create Section</p>
                                <div class="my-2">
                                    <label for="set-name">Section Name</label>
                                    <input type="text" id="set-name" class="form-control" placeholder="Enter Section name" v-model="form.section_name" />
                                </div>
                                <div class="my-2">
                                    <label for="pattern">Select Pattern</label>
                                    <select id="pattern" class="form-control" v-model="form.pattern">
                                        <option selected="single" value="single">Single</option>
                                        <option value="double">Double</option>
                                    </select>
                                </div>
                                <div class="my-2">
                                    <label for="use-for">Banner Use For</label>
                                    <select id="use-for" class="form-control" v-model="form.use_for">
                                        <option selected="category" value="category">Category</option>
                                        <option value="campaign">Campaign</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="my-2">
                                    <label for="set-precedence">Set Precedense</label>
                                    <input type="number" id="set-precedence" class="form-control" v-model="form.precedence" />
                                </div>

                            <button type="button" @click="makeDesign()" class="btn btn-info btn-block my-2">Make</button>
                        </div>
                        <div style="margin-top: 30%;" v-if="allcategories.length > 0 || campaigns.length > 0">
                            <p class="text-success" style="font-size:17px">Create Back Link</p>
                            <div class="my-2">
                                <select id="select-item" class="form-control" v-model="updateFormdata.imagenumb">
                                    <option selected="" value="">Choose Link</option>
                                    <option v-for="(value,index) in form.banner" :value="index" :key="index">Back Link No-{{ ++index }}</option>
                                </select>
                            </div>
                            <div v-if="form.use_for == 'category'">
                                <div class="my-2">
                                    <select id="product-category" class="form-control" @change="getSubCategories()" v-model="updateFormdata.category">
                                        <option selected="" value="">Choose Category</option>
                                        <option v-for="(value,index) in allcategories" :value="value" :key="index">{{ value.category_name }}</option>
                                    </select>
                                </div>
                                <div>
                                    <select id="product-updateFormdata" class="form-control" v-model="updateFormdata.subcategoryObj">
                                        <option value="">Choose...</option>
                                        <option v-for="(value,index) in allsubcategories" :value="value" :key="index">{{ value.category_name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div v-if="form.use_for == 'campaign'">
                                <div class="my-2">
                                    <select id="campaign" class="form-control" v-model="updateFormdata.subcategory">
                                        <option selected="" value="">Choose Campaign</option>
                                        <option v-for="(value,index) in campaigns" :value="value" :key="index">{{ value.campaign_name }}</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" @click="setLink()" class="btn btn-info btn-block my-2">SET LINK</button>
                        </div>
                    </div>


                    <div class="col-sm-6 border-left col-12" v-if="allcategories.length > 0 || campaigns.length > 0 || form.use_for=='other'">
                        <div class="tab-content" id="v-border-pills-tabContent">
                            <div class="tab-pane fade active show bordered my-2" v-for="(bann,ind) in form.banner" :key="ind" id="v-border-pills-home" role="tabpanel" aria-labelledby="v-border-pills-home-tab">
                                <input type="submit" v-if="showPermission.includes('page-update')" class="btn btn-info btn-block mb-4 mr-2 controlss" @click="openPageMediaModal(ind)" value="File Upload" />
                                <video :src="bann.banner_uri" autoplay v-if="bann.file_type == 'video'" muted controls class="controlss"></video>
                                <v-lazy-image class="mr-3 controlss" v-else :src="bann.banner_uri" alt="Home image two" :src-placeholder="url+'demo.png'" />
                                <p>Back Url: domain/{{ bann.back_link }}</p>
                                <hr />
                            </div>
                        </div>
                        <form @submit.prevent="createSection()" method="post">
                            <button type="submit" class="btn btn-success btn-block my-2">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <media-helper :setImg="selectImage">
            <template v-slot:viewimage>
                <div class="col-md-12 d-flex justify-content-center my-2">
                    <v-lazy-image :src="updateFormdata.imageuri" class="card-img-top" :alt="updateFormdata.imageuri" :src-placeholder="url+'demo.png'" v-if="updateFormdata.imagenumb != 'one'" />
                    <video :src="updateFormdata.imageuri" v-else autoplay muted controls class="controlss"></video>
                </div>
            </template>
        </media-helper>
    </div>
</template>

<style scoped>
.controlss{
    width: 100% !important;
}
.modal-dialog {
  min-width: 92%;
  height: 80%;
  bottom: 0;
  padding: 0;
  top:40;
}

.modal-content {
  height: auto;
  min-width: 100%;
  min-height: 100%;
  border-radius: 0;
  bottom: 0;
}
</style>
