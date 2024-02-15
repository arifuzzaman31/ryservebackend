<script>
import Mixin from "../../mixer"
export default ({
    mixins: [Mixin],
    data(){
        return {
            allImages: [],
            filterdata: {
                imgs: []
            },
            search: '',
            page: 1,
            url: baseUrl
        }
    },
    methods: {
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
                    inline_container: "#widget_container_manager",
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
                    let result = data.info.files
                    result.forEach(asset => {
                        this.filterdata.imgs.push(asset.uploadInfo)
                        this.allImages.data.unshift({'file_link':asset.uploadInfo.secure_url,'product_name':asset.uploadInfo.public_id ,'extension':asset.uploadInfo.format})
                    })
                    this.uploadImage()
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

        getImageData(){
            axios.get(baseUrl+`media-manager/create?page=${this.page}&per_page=10&keyword=${this.search}`)
            .then(result => {
                if(this.page == 1){
                    this.allImages = result.data;
                } else {
                    this.allImages.data.push(...result.data.data)
                }
            })
            .catch(errors => {
                console.log(errors);
            });
        },
        deleteMedia(item) {

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
                    // this.deleteFromCloud(item.cld_public_id)
                    axios.delete(baseUrl+`media-manager/${item.id}`).then(
                        response => {
                            this.getImageData()
                            this.successMessage(response.data)
                        }
                    ). catch(error => {
                        this.validationError()
                    })
                }
            })
        },
        loadMore(){
            this.getImageData(this.page++)
        },
        formReset(){
            this.filterdata.imgs = []

            const myNode = document.getElementById("widget_container_manager");
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
})
</script>

<template>
    <div id="tabsBordered" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12  d-flex justify-content-between">
                        <h4>Media Manager</h4>
                        <button @click="getCloudWidget()" v-if="showPermission.includes('add-media')" class="btn btn-primary mb-2 mr-3">Add File</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="statbox widget">
            <div class="widget-header">
                <div class="row" v-if="allImages.data && allImages.data.length > 0">
                    <div class="col-xl-2 col-md-3 col-sm-6 col-12" v-for="(item,ind) in allImages.data" :key="ind">
                        <div class="card component-card_9 mb-1">
                            <v-lazy-image :src="item.file_link" :alt="item.product_name" :src-placeholder="url+'demo.png'" v-if="item.file_type != 'video'" />
                            <video :src="item.file_link" v-else autoplay muted controls></video>
                            <div class="card-body">
                                <h6 class="card-title">{{ item.product_name }}</h6>
                                <div class="d-flex justify-content-between">
                                    <p class="card-text">{{ item.extension }}</p>
                                    <a href="#" type="button" @click.prevent="deleteMedia(item)" v-if="showPermission.includes('media-delete')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-muted"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button  v-if="allImages.data && allImages.data.length > 0 && page < allImages.last_page" @click="loadMore()" class="btn btn-primary mt-4">Load More</button>
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
                    <div id="widget_container_manager" style="height: 80vh;"></div>
                </div>
            </div>
        </div>
    </div>
</template>
