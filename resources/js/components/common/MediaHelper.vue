<template>
  <div id="pageMediaModal" class="modal animated fadeInUp custo-fadeInBottom" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Media</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="widget-content">
                        <div class="statbox widget">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-md-9 border-right" style="height: 75vh;overflow-y: auto;">
                                        <div class="row" v-if="allImages.data && allImages.data.length > 0">
                                            <div class="col-xl-2 col-md-3 col-sm-6 col-12" v-for="(item,ind) in allImages.data" :key="ind">
                                                <div class="card component-card_2 mb-1">
                                                    <a href="#" type="button" @click="setImg(item.file_link)">
                                                        <v-lazy-image class="card-img-top" :src="item.file_link" :alt="item.product_name" :src-placeholder="url+'demo.png'" v-if="item.file_type != 'video'" />
                                                        <video :src="item.file_link" v-else autoplay muted controls class="controlss"></video>
                                                        <div class="card-body">
                                                            <h6 class="card-title">{{ item.product_name }}</h6>
                                                            <p class="card-text">{{ item.extension }}</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <button v-if="allImages.data && allImages.data.length > 0 && page < allImages.last_page" @click="loadMore()" class="btn btn-primary mt-4">Load More</button>
                                    </div>
                                    <div class="col-md-3"  style="max-height: 75vh;overflow-y: auto;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button @click="getCloudWidget()" v-if="showPermission.includes('add-media')" class="btn btn-primary btn-block mb-2 mr-3">Add File</button>
                                                <input type="text" @keyup="searchMedia()" v-model="media_keyword" class="form-control" id="search" placeholder="Search by Name" />
                                            </div>
                                            <slot name="viewimage"></slot>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="mt-2 d-flex justify-content-center">
                            <button class="btn btn-info" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Select & Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="openCldWgt" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ml-3">Cloudinary Media Widget</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"  @click="formReset">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <div id="widget_container" style="height: 80vh;"></div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {setImg:Function},
    data(){
        return {
            filterdata: {
                imgs: []
            },
            allImages: [],
            media_keyword: '',
            page: 1,
            url: baseUrl
        }
    },
    methods: {
        getImageData(){
            axios.get(baseUrl+`media-manager/create?page=${this.page}&per_page=12&keyword=${this.media_keyword}`)
            .then(result => {
                if(this.page == 1){
                    this.allImages = result.data;
                } else {
                    this.allImages.data.push(...result.data.data)
                }
                // this.allImages.data.map(itm => [...itm,{isSelected:true}])
            })
            .catch(errors => {
                console.log(errors);
            });
        },
        getCloudWidget(){
            this.formReset()
            $("#openCldWgt").modal('show');
            const _this = this;
            window.ml = cloudinary.openMediaLibrary(
                {
                    cloud_name: clName,
                    api_key: clPreset,
                    remove_header: true,
                    multiple: true,
                    max_files: "4",
                    insert_caption: "Insert",
                    inline_container: "#widget_container",
                    default_transformations: [[]],
                    button_class: "myBtn",
                    button_caption: "Select Image or Video"
                },
                {
                    insertHandler: function (data) {
                    data.assets.forEach(asset => {
                        _this.filterdata.imgs.push(asset)
                        _this.allImages?.data.unshift({'file_link':asset.secure_url,'product_name':asset.public_id ,'extension':asset.format})
                     })
                     _this.uploadImage()
                    }
                },
            );
            ml.on("upload", (data) => {
                if (data.event === "queues-end") {
                    // log the first uploaded file's public_id:
                    let result = data.info.files
                    result.forEach(asset => {
                        this.filterdata.imgs.push(asset.uploadInfo)
                        this.allImages.data.unshift({'file_link':asset.uploadInfo.secure_url,'product_name':asset.uploadInfo.public_id ,'extension':asset.uploadInfo.format})
                    })
                    // this.uploadImage()
                }
            });
            ml.on("delete", (data) => {
                data.assets.forEach(asset => {
                    this.filterdata.imgs.push(asset.public_id)
                })
                this.destroyImage();
            });
        },
        uploadImage(){
            axios.post(baseUrl+'media-manager',this.filterdata).then(response => {
                if(response.data.status == 'success'){
                    // this.successMessage(response.data)
                    this.filterdata.imgs = []
                }
            })
        },
        destroyImage(){
            axios.put(baseUrl+'media-manager/1',this.filterdata).then(response => {
                console.log(response.data)
            }).catch(e => {
                console.log(e)
            })
        },

        searchMedia(){
            if(this.media_keyword.length > 3) return;
            this.getImageData()
        },
        loadMore(){
            this.getImageData(this.page++)
        },
        formReset(){
            this.filterdata.imgs = []

            const myNode = document.getElementById("widget_container");
                while (myNode.firstChild) {
                    myNode.removeChild(myNode.lastChild);
                }
        }
    },
    mounted(){
        this.getImageData()
    },
    computed: {
        showPermission() {
            return window.userPermission;
        }
    }
}
</script>

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
.component-card_selected {
    box-shadow: 4px 6px 10px -3px #bfc9d4;
}
.modal-content {
  height: auto;
  min-width: 100%;
  min-height: 100%;
  border-radius: 0;
  bottom: 0;
}
</style>
