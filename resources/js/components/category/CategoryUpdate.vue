<script>
import Mixin from '../../mixer'
import MediaHelper from '../common/MediaHelper.vue'
export default {
    mixins: [Mixin],
    components: {
        MediaHelper
    },
    props: ['categorydata'],
    data(){
        return {
            form: {
                image_one: '',
                type_one: '',
                image_two: '',
                type_two: '',
                image_three: '',
                type_three: '',
                category_feature_image: '',
                imagenumb: '',
                uritype: ''
            },
            url: baseUrl
        }
    },

    methods: {

        setImage(numb,uri,frmt){
            switch (numb) {
                case 'one':
                    this.form.image_one = uri
                    this.form.type_one = frmt
                    break;
                case 'two':
                    this.form.image_two = uri
                    this.form.type_two = frmt
                    break;
                case 'three':
                    this.form.image_three = uri
                    this.form.type_three = frmt
                    break;
                case 'four':
                    this.form.category_feature_image = uri
                    break;

                default:
                    break;
            }

            return true;
        },

        openCatMediaModal(serial){
            this.form.imageuri = ''
            this.form.imagenumb = serial
            $("#pageMediaModal").modal('show');
        },

        selectImage(item){
            const vExt = ['mp4','WebM','mov','avi','mkv','wmv','avchd','flv'];
            let ext = item.split(".").pop();
            var frmt = vExt.includes(ext) ? 'video' : 'image';
            this.form.imageuri = item
            this.form.uritype = frmt
            this.setImage(this.form.imagenumb,item,frmt)
        },

        updateImage(){
            axios.put(baseUrl+'category/'+this.categorydata.id,this.form).then(response => {
                if(response.data.status == 'success'){
                    this.getCatData()
                    this.successMessage(response.data)
                }
            })
        },


        getCatData(){
            axios.get(baseUrl+'category/'+this.categorydata.id).then(response => {
                this.form.image_one = response.data.category_image_one
                this.form.type_one = response.data.type_one
                this.form.image_two = response.data.category_image_two
                this.form.type_two = response.data.type_two
                this.form.image_three = response.data.category_image_three
                this.form.type_three = response.data.type_three
                this.form.category_feature_image = response.data.category_feature_image
            })
        }


    },

    mounted(){
        this.form.image_one = this.categorydata.category_image_one
        this.form.type_one = this.categorydata.type_one
        this.form.image_two = this.categorydata.category_image_two
        this.form.type_two = this.categorydata.type_two
        this.form.image_three = this.categorydata.category_image_three
        this.form.type_three = this.categorydata.type_three
        this.form.category_feature_image = this.categorydata.category_feature_image
    }
}
</script>

<template>
<div id="tabsBordered" class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="d-flex flex-column justify-content-center align-items-center" >
                    <div class="icon-container mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg><span class="icon-name text-info"> Image Should be (1440 x 900) px, Ratio (16:9)</span>
                    </div>
                    <input type="submit" class="btn btn-info btn-block mb-4 mr-2" style="width: 50%;" @click="openCatMediaModal('one')" value="File Upload" />
                    <v-lazy-image class="mr-3" width="600" v-if="form.type_one != 'video'" :src="form.image_one" alt="cat image one" :src-placeholder="url+'demo.png'" />
                    <video :src="form.image_one" width="320" height="240" v-else autoplay muted controls class="controlss"></video>
                </div>
            </div>
        </div>
        <div class="statbox widget mt-2">
            <div class="widget-header">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <div class="icon-container mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg><span class="icon-name text-info"> Image Should be (720 x 828) px, Ratio (16:24)</span>
                        </div>
                        <input type="submit" class="btn btn-info btn-block mb-4 mr-2" style="width: 50%;" @click="openCatMediaModal('two')" value="File Upload" />
                        <v-lazy-image class="mr-3" v-if="form.type_two != 'video'" width="600" :src="form.image_two" alt="cat image two" :src-placeholder="url+'demo.png'" />
                        <video :src="form.image_two" width="320" height="240" v-else autoplay muted controls class="controlss"></video>
                    </div>
                </div>
            </div>
        <div class="statbox widget mt-2">
            <div class="widget-header">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <div class="icon-container mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg><span class="icon-name text-info"> Image Should be (720 x 828) px, Ratio (16:24)</span>
                    </div>
                    <input type="submit" class="btn btn-info btn-block mb-4 mr-2" style="width: 50%;" @click="openCatMediaModal('three')" value="File Upload" />
                    <v-lazy-image class="mr-3" width="600" v-if="form.type_three != 'video'" :src="form.image_three" alt="cat image three" :src-placeholder="url+'demo.png'" />
                    <video :src="form.image_three" width="320" height="240" v-else autoplay muted controls class="controlss"></video>
                </div>
            </div>
        </div>

        <div class="statbox widget mt-2">
            <div class="widget-header">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <div class="icon-container mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg><span class="icon-name text-info"> Category Feature Image Should be (720 x 828) px, Ratio (16:24)</span>
                    </div>
                    <input type="submit" class="btn btn-info btn-block mb-4 mr-2" style="width: 50%;" @click="openCatMediaModal('four')" value="File Upload" />
                    <v-lazy-image class="mr-3" width="600" :src="form.category_feature_image" alt="cat feature image" :src-placeholder="url+'demo.png'" />
                </div>
            </div>
        </div>
        <button type="submit" @click="updateImage()" class="btn btn-success my-2">Update</button>
    </div>

    <media-helper :setImg="selectImage">
        <template v-slot:viewimage>
            <div class="col-md-12 d-flex justify-content-center my-2">
                <v-lazy-image :src="form.imageuri" class="card-img-top" v-if="form.uritype != 'video'" :alt="form.imageuri" :src-placeholder="url+'demo.png'" />
                <video :src="form.imageuri" v-else autoplay muted controls class="controlss"></video>
            </div>
        </template>
    </media-helper>

</template>
<style scoped>
.controlss{
    width: 80% !important;
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
  min-height: 90%;
  border-radius: 0;
  bottom: 0;
}
</style>
