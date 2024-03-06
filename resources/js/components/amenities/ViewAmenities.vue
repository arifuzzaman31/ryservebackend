<script>
import axios from "axios";
import Mixin from "../../mixer";
export default {
    mixins: [Mixin],
    components: {},

    data() {
        return {
            amenities: [],
            updateAmenity: {
                id: '',
                name: '',
                price: 0,
                icon: '',
                status: ''
            },
            url: baseUrl,
            isSubmiting: false,
            validation_error: {},
        };
    },
    methods: {
        async prevData() {
            if (this.currentPage == 1) return;
            this.currentPage -= this.perPage
            this.getAmenities()
        },
        async nextData() {
            if ((Math.ceil(this.currentPage / this.perPage)) >= this.lastPage) return;
            this.currentPage += this.perPage
            this.getAmenities()
        },
        async getAmenities() {
            try {
                const token = await this.getUserToken()
                await axios
                    .get(`${apiUrl}backendapi/amenities`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.amenities = response.data;
                        // console.log(response.data)
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } catch (e) {
                console.log(e);
            }
        },
        async deleteItem(id){
            const token = await this.getUserToken()
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
                  axios.delete(apiUrl+`backendapi/amenities?id=${id}`,{
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    }).then(
                      response => {
                          this.successMessage({status:'success', message:response.data.message})
                          this.getAmenities()
                      }
                  ). catch(error => {
                    this.validationError({status:'error',message:'Something went wrong!'});
                  })
              }
          })
        },
        async updateAmenities(){
            try {
                const token = await this.getUserToken()
                await axios
                    .put(`${apiUrl}backendapi/amenities?id=${this.updateAmenity.id}`,this.updateAmenity ,{
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        if (response.status == 200) {
                            this.successMessage({ status: 'success', message: 'Amenities Updated' })
                            $("#updateAmenities").modal('hide');
                            this.getAmenities()
                        }
                        this.clearForm()
                    })
                    .catch((error) => {
                        if (error.response.status == 400) {
                        this.validation_error = error.response.data;
                        this.validationError(error.response.data);
                    }
                    });
            } catch (e) {
                console.log(e);
            }
        },
        async editItem(data){
            this.clearForm()
            this.updateAmenity.id = data.id
            this.updateAmenity.name = data.name
            this.updateAmenity.price = 0
            this.updateAmenity.icon = data.icon
            this.updateAmenity.status = data.status.toString()
            $("#updateAmenities").modal('show');
        },
        async openCreateModal(){
            this.updateAmenity.id = ''
            this.clearForm()
            this.updateAmenity.status = 'true'
            $("#updateAmenities").modal('show');
        },
        async storeAmenity(){
            try {
                const token = await this.getUserToken()
                await axios
                    .post(`${apiUrl}backendapi/amenities`,this.updateAmenity ,{
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        if (response.status == 201) {
                            this.successMessage({ status: 'success', message: 'Amenities Created' })
                            $("#updateAmenities").modal('hide');
                            this.getAmenities()
                        }
                        this.clearForm()
                    })
                    .catch((error) => {
                        if (error.response.status == 400) {
                        this.validation_error = error.response.data;
                        this.validationError(error.response.data);
                    }
                    });
            } catch (e) {
                console.log(e);
            }
        },
        async clearForm(){
            this.updateAmenity = {
                id: '',
                name: '',
                price: 0,
                icon: '',
                status: ''
            }
        }
    },
    computed: {},
    mounted() {
        this.getAmenities();
    },
};
</script>

<template>
    <div class="row">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div
                            class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between"
                        >
                            <h4>Amenities</h4>
                            <a
                                href="javascript:void(0)"
                                type="button"
                                @click="openCreateModal()"
                                class="btn btn-primary mb-2 mr-3"
                            >
                                Create Amenities
                            </a>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Icon</th>
                                    <!-- <th>Price</th> -->
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template
                                    v-for="(amenity, index) in amenities"
                                    :key="amenity.id"
                                >
                                    <tr>
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ amenity.name }}</td>
                                        <td><img :src="amenity.icon" style="max-height:30px" class="img-thumbnail" /></td>
                                        <!-- <td>{{ amenity.price }}</td> -->
                                        <td class="text-center">
                                            {{amenity.status == 1 ? 'Active' : 'Deactive'}}
                                        </td>
                                        <td>
                                            <ul class="table-controls d-flex justify-content-around">
                                                <li><a href="javascript:void(0);"  @click="editItem(amenity)"  type="button" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                                <!-- <li><a href="javascript:void(0);"  title="View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-warning"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg><span class="icon-name"></span>
                                                                    </a></li> -->
                                                <li><a href="javascript:void(0);" @click="deleteItem(amenity.id)" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <!-- <div class="paginating-container pagination-solid">
                        <ul class="pagination">
                            <li class="prev"><a href="javascript:void(0);" type="button" @click="prevData()">Prev</a></li>
                            <li class="next"><a href="javascript:void(0);" type="button" @click="nextData()">Next</a></li>
                        </ul>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- for Edit -->
        <div id="updateAmenities" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Amenities</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content-area">
                            <form role="form">
                                <div class="form-row">
                                <div class="col-12">
                                    <label for="ami-name">Name</label>
                                    <input type="text"  class="form-control form-control-sm" id="ami-name" v-model="updateAmenity.name" placeholder="Amenity Name" required>
                                </div>
                                <div class="col-12">
                                    <label for="ami-icon">Icon</label>
                                    <input type="text"  class="form-control form-control-sm" id="ami-icon" v-model="updateAmenity.icon" placeholder="Amenity Icon" required>
                                </div>
                                <div class="col-12 mt-2">
                                <label for="status">Status</label>
                                    <select id="status" class="form-control" v-model="updateAmenity.status">
                                        <option value="true">Active</option>
                                        <option value="false">Deactive</option>
                                    </select>
                                </div>

                                </div>

                                <div class="modal-footer md-button">
                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="clearForm"></i> Discard</button>
                                    <button v-if="updateAmenity.id !== ''"  type="submit" @click.prevent="updateAmenities()" class="btn btn-primary">
                                        <div v-if="isSubmiting" class="spinner-grow text-white align-self-center loader-btn"></div>
                                        Update</button>
                                    <button  type="submit" v-else @click.prevent="storeAmenity()" class="btn btn-success">
                                        <div v-if="isSubmiting" class="spinner-grow text-white align-self-center loader-btn"></div>
                                        Submit</button>
                                    <!-- <button type="submit" class="btn btn-primary">Submit</button> -->

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
