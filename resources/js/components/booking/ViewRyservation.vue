<script>
import axios from 'axios';
import Mixin from '../../mixer'

export default {
    mixins: [Mixin],
    components:{

    },
    data() {
        return {
            bookings: [],
            modify: {
                id: '',
                guestNumber: 0,
                tableId: '',
                amount: 0,
                vat: 0,
                slot: '',
                discount: 0,
                grandTotal: 0,
                comment: '',
                status: ''
            },
            tables:[],
            subassetescomponent: [],
            bookingData: {
                user: {
                    firstName: '',
                    lastName: '',
                    phoneNumber: '',
                    havingBusiness: false,
                    userType: 'CUSTOMER'
                },
                subAssetCompId: '',
                tableId: '',
                startDate: '',
                endDate: '',
                slot: '',
                status: 'CONFIRMED',
                guestNumber: 1
            },
            slotten: '',
            pickslot: [],
            currentPage: 1,
            perPage: 10,
            lastPage: 0,
            filterdata : {
                startDate: '',
                endDate: '',
                slot: '',
                status: '',
                isEvent: '',
                search: ''
            },
            url: baseUrl,
            isLoading: false,
            isSubmiting: false,
            validation_error: {},
        }
    },
    methods: {
        async prevData() {
            if (this.currentPage == 1) return;
            this.currentPage -= this.perPage
            this.getBooking()
        },
        async nextData() {
            if ((Math.ceil(this.currentPage / this.perPage)) >= this.lastPage) return;
            this.currentPage += this.perPage
            this.getBooking()
        },
        async getBooking() {
            try {
                this.isLoading = true
                const token = await this.getUserToken()
                await axios.get(`${apiUrl}backendapi/booking?skiped=${this.currentPage}&status=${this.filterdata.status}&event=${this.filterdata.isEvent}&startDate=${this.filterdata.startDate}&endDate=${this.filterdata.endDate}&per_page=${this.perPage}`, {
                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                    })
                    .then(response => {
                        this.bookings = response.data.data
                        this.lastPage = response.data.pagination.total
                        // console.log(response.data)
                    }).catch(error => {
                        console.log(error)
                    })
                    this.isLoading = false
            } catch (e) {
                console.log(e)
            }
        },
        async getTable(subAssetCompId){
            this.tables = []
            const token = await this.getUserToken()
            await axios.get(`${apiUrl}backendapi/table?subAssetCompId=${subAssetCompId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(
                    response => {
                        if (response.status == 200) {
                            this.tables = response.data
                        }
                    }
                )
        },
        async updateStatus(ryserve) {
            this.pickslot = []
            this.tables = []
            let day = ["sunday","monday","tuesday","wednesday","thursday","friday","saturday"][new Date(ryserve.startDate).getDay()]
            console.log(day)
            let tbl = await this.subassetescomponent.find(dt => dt.id == ryserve.subAssetCompId);
            const foundData = tbl.slot.find(dayData => dayData[day]);
            if(foundData){
                this.pickslot = [...foundData[day]];
            }
            this.tables = tbl.tables ?? []
            this.modify.id = ryserve.id
            this.modify.comment = ryserve.comment
            this.modify.slot = ryserve.slot
            this.modify.guestNumber = ryserve.guestNumber
            this.modify.tableId = ryserve.tableId ?? ''
            this.modify.amount = ryserve.amount
            this.modify.status = ryserve.status
            $("#updateBooking").modal('show');
        },
        async updateBookingStatus() {
            try {
                this.isSubmiting = true
                const token = await this.getUserToken()
                this.modify.grandTotal = Number(this.modify.amount+this.modify.vat-this.modify.discount)
                await axios.put(`${apiUrl}backendapi/booking?id=${this.modify.id}`,this.modify, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(
                    response => {
                        if (response.status == 200) {
                            this.successMessage({ status: 'success', message: 'Booking Status Updated' })
                            $("#updateBooking").modal('hide');
                            this.getBooking()
                        }
                        this.clearForm()
                    }
                ).catch(e => {
                    if (e.response.status == 422) {
                        this.validation_error = e.response.data.errors;
                        this.validationError();
                    }
                    if (e.response.status == 400) {
                        this.validation_error = e.response.data;
                        this.validationError(e.response.data);
                    }
                })
                this.isSubmiting = false
            } catch (e) {
                console.log(e.response)
            }
        },
        async getSubAssetComp(){
            try{
                const token = await this.getUserToken()
                // console.log(token)
                await axios.get(`${apiUrl}backendapi/sub-asset-component`,{
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => {
                    this.subassetescomponent = response.data
                }).catch(error => {
                    console.log(error)
                })
            }catch(e){
                console.log(e)
            }
        },
        async createBooking(){
            try {
                this.isSubmiting = true
                const token = await this.getUserToken()
                this.bookingData.endDate = this.bookingData.startDate
                await axios.post(`${apiUrl}backendapi/booking`,this.bookingData, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                }).then(
                    response => {
                        // console.log(response.data)
                        if (response.status == 201) {
                            this.successMessage({ status: 'success', message: 'Booking Created Successful' })
                            $("#createBookingModal").modal('hide');
                            this.getBooking()
                        }
                        this.clearForm()
                    }
                ).catch(e => {
                    // console.log(e.response.data)
                    if (e.response.status == 400) {
                        this.validation_error = e.response.data;
                        this.validationError(e.response.data);
                    }
                })
                this.isSubmiting = false
            } catch (e) {
                console.log(e.response)
            }
        },
        async setData(){
            this.pickslot = []
            this.tables = []
            this.bookingData.tableId = ''
            let day = ["sunday","monday","tuesday","wednesday","thursday","friday","saturday"][new Date(this.bookingData.startDate).getDay()]
            if(!this.slotten?.id) return ;
            let tbl = await this.subassetescomponent.find(dt => dt.id == this.slotten.id);
            this.tables = tbl.tables
            this.bookingData.subAssetCompId = this.slotten.id
            const foundData = this.slotten.slot.find(dayData => dayData[day]);
            this.pickslot = [...foundData[day]];
        },
        async filterClear(){
            this.filterdata = {
                startDate: '',
                endDate: '',
                slot: '',
                status: '',
                isEvent: '',
                search: ''
            }
            this.getBooking()
        },
        async filterSubmit(){
            this.currentPage = 1,
            this.perPage = 10,
            await this.getBooking()
        },
        async clearForm() {
            this.bookingData = {
                user: {
                    firstName: '',
                    lastName: '',
                    phoneNumber: '',
                    havingBusiness: false,
                    userType: 'CUSTOMER'
                },
                subAssetCompId: '',
                tableId: '',
                startDate: '',
                endDate: '',
                slot: '',
                status: 'CONFIRMED',
                guestNumber: 1
            }
            this.modify = {
                id: '',
                guestNumber: 0,
                tableId: '',
                amount: 0,
                vat: 0,
                discount: 0,
                grandTotal: 0,
                comment: '',
                status: ''
            },
            this.slotten = ''
            this.pickslot = [],
            this.isLoading = false
            this.isSubmiting = false
            this.filterdata = {
                startDate: '',
                endDate: '',
                slot: '',
                status: '',
                isEvent: '',
                search: ''
            }
        },

    },
    mounted() {
        this.getBooking()
        this.getSubAssetComp()
    }
}
</script>

<template>
    <div class="row">
        <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 d-flex justify-content-between">
                            <h4>Reservation</h4>
                            <button class="btn btn-primary mb-2 mr-3" data-toggle="modal" data-target="#createBookingModal">Add New</button>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area text-center" v-if="isLoading">
                    <div class="spinner-border text-success align-self-center loader-xl"></div>
                </div>
                <div class="widget-content widget-content-area" v-else>
                    <div class="row mb-2">
                        <div class="col-md-2 col-lg-2 col-12">
                            <select id="product-camp" class="form-control  form-control-sm" v-model="filterdata.status">
                                <option selected="" value="">Choose...</option>
                                <option value="CONFIRMED">Confirmed</option>
                                <option value="DEACTIVE">Deactive</option>
                                <option value="ON_HOLD">On Hold</option>
                                <option value="CANCELED">Canceled</option>
                                <option value="COMPLETED">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-lg-2 col-12">
                            <select id="product-camp" class="form-control  form-control-sm" v-model="filterdata.isEvent">
                                <option selected="" value="">All</option>
                                <option value="Regular">Regular</option>
                                <option value="event">Event</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-lg-3 col-12">
                            <input type="text" onfocus="(this.type='date')" v-model="filterdata.startDate" class="form-control form-control-sm" placeholder="Start Date">
                        </div>
                        <div class="col-md-3 col-lg-3 col-12">
                            <input type="text" onfocus="(this.type='date')" v-model="filterdata.endDate" class="form-control form-control-sm" placeholder="End Date">
                        </div>

                        <div class="col-md-2 col-lg-1 col-12">
                            <button type="button" class="btn btn-info" @click.prevent="filterSubmit()">Filter</button>
                        </div>
                        <div class="col-md-2 col-lg-1 col-12">
                            <button type="button" class="btn btn-danger" @click.prevent="filterClear()">CLEAR</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-4">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Property Name</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Guest</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(ryserve,index) in bookings" :key="ryserve.id">
                                <tr>
                                    <td>{{ index+1 }}</td>
                                    <td>{{ ryserve.subAssetComponent.listingName }}</td>
                                    <td>{{ ryserve.customerName }}</td>
                                    <td>{{ ryserve.phoneNumber }}</td>
                                    <td class="text-center">{{ ryserve.guestNumber }}</td>
                                    <td>{{ dateToString(ryserve.startDate) }}</td>
                                    <td>{{ ryserve.slot }}</td>
                                    <td>{{ ryserve.bookingType }}</td>
                                    <td class="text-center">
                                       <span v-if="ryserve.status == 'CONFIRMED'" class="badge badge-success">Confirmed</span>
                                        <span v-if="ryserve.status == 'DEACTIVE'" class="badge badge-warning">Deactive</span>
                                        <span v-if="ryserve.status == 'ON_HOLD'" class="badge badge-light">On Hold</span>
                                        <span v-if="ryserve.status == 'CANCELED'" class="badge badge-danger">Canceled</span>
                                        <span v-if="ryserve.status == 'COMPLETED'" class="badge badge-info">Completed</span>
                                    </td>
                                    <td>
                                    <ul class="table-controls d-flex justify-content-around">
                                        <li><a href="javascript:void(0);" @click="updateStatus(ryserve)" type="button" title="Update Status"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                        <!-- <li><a href="javascript:void(0);"  title="View"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-warning"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg><span class="icon-name"></span>
                                                            </a></li>
                                        <li><a href="javascript:void(0);" title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a></li> -->
                                        <!-- <li><a href="asset" title="Asset">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                        </a></li> -->
                                    </ul>
                                </td>
                                </tr>
                            </template>
                    </tbody>
                </table>
                    </div>
                    <div class="paginating-container pagination-solid">
                        <ul class="pagination">
                            <li class="prev"><a href="javascript:void(0);" type="button" @click="prevData()">Prev</a></li>
                            <li class="next"><a href="javascript:void(0);" type="button" @click="nextData()">Next</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- for Edit -->
        <div id="updateBooking" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> Update Booking Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clearForm">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="widget-content-area">
                            <form @submit.prevent="updateBookingStatus()">
                                <div class="form-row">
                                <div class="col-12">
                                    <label for="Guest">Guest</label>
                                    <input type="number"  class="form-control form-control-sm" id="Guest" v-model="modify.guestNumber" placeholder="Guest Number" required>
                                </div>
                                <div class="col-12 mt-2">
                                <label for="status">Status</label>
                                    <select id="status" class="form-control" v-model="modify.status">
                                        <option value="CONFIRMED">CONFIRMED</option>
                                        <option value="ON_HOLD">ON_HOLD</option>
                                        <option value="COMPLETED">COMPLETED</option>
                                        <option value="DEACTIVE">DEACTIVE</option>
                                        <option value="CANCELED">CANCELED</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-2">
                                    <label for="table">Assign Table</label>
                                    <select id="table" class="form-control" v-model="modify.tableId">
                                        <option value="">Choose Table</option>
                                        <option v-for="value in tables" :value="value.id" :key="value.id">Capacity: {{ value.capacity }}-{{ value.position }}-{{ value.type }}</option>
                                    </select>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="upslottime">Slot</label>
                                    <select id="upslottime" class="form-control form-control-sm" v-model="modify.slot">
                                        <option value="">Choose Slot</option>
                                        <option v-for="value in pickslot" :value="value.slottime" :key="value.slottime">{{ value.slottime }}</option>
                                    </select>
                                </div>
                                <div class="col-12" v-show="modify.status == 'COMPLETED'">
                                    <label for="Amount">Amount</label>
                                    <input type="number"  class="form-control form-control-sm" id="Amount" v-model="modify.amount" placeholder="Amount" required>
                                </div>
                                <div class="col-12  mt-2">
                                    <label for="Comment">Comment</label>
                                    <textarea id="Comment" class="form-control form-control-sm" v-model="modify.comment" rows="5" placeholder="Write Comment"></textarea>
                                </div>
                            </div>

                                <div class="modal-footer md-button">
                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="clearForm"></i> Discard</button>
                                    <button  type="submit" class="btn btn-primary">
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
        <!-- for create a booking -->
        <div id="createBookingModal" class="modal animated fadeInUp custo-fadeInUp" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="widget-content-area">
                        <form @submit.prevent="createBooking()">
                            <div class="form-row">
                            <div class="col-6">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control form-control-sm" id="firstName" v-model="bookingData.user.firstName" placeholder="Enter First Name" required>
                            </div>
                            <div class="col-6">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control form-control-sm" id="lastName" v-model="bookingData.user.lastName" placeholder="Enter Last Name" required>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="phoneNumber">Phone</label>
                                <input type="text" class="form-control form-control-sm" id="phoneNumber" v-model="bookingData.user.phoneNumber" placeholder="Customer Phone Number" required>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="date">Date</label>
                                <input type="date" @input="setData()" class="form-control form-control-sm" id="date" v-model="bookingData.startDate" placeholder="Select Date" required>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="rastaurant">Select Restaurant</label>
                                <select id="rastaurant" class="form-control" v-model="slotten" @change="setData()">
                                    <option value="">Choose Restaurant</option>
                                    <option v-for="value in subassetescomponent" :value="value" :key="value.id">{{ value.listingName }}</option>
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="table-rest">Select Table</label>
                                <select id="table-rest" class="form-control" v-model="bookingData.tableId">
                                    <option value="">Choose Table</option>
                                    <option v-for="value in tables" :value="value.id" :key="value.id">Capacity: {{ value.capacity }}-{{ value.position }}-{{ value.type }}</option>
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                            <label for="slottime">Slot</label>
                                <select id="slottime" class="form-control form-control-sm" v-model="bookingData.slot">
                                    <option value="">Choose Slot</option>
                                    <option v-for="value in pickslot" :value="value.slottime" :key="value.slottime">{{ value.slottime }}</option>
                                </select>
                            </div>
                            <div class="col-6 mt-3">
                                <label for="GuestNumber">Guest</label>
                                <input type="number"  class="form-control form-control-sm" id="GuestNumber" v-model="bookingData.guestNumber" placeholder="Guest Number" required>
                            </div>

                        </div>

                            <div class="modal-footer md-button mt-2">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="clearForm"></i> Discard</button>
                                <button  type="submit" class="btn btn-primary">
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

<style scoped>
.paginating-container {
    display: flex;
    justify-content: center;
    margin-bottom: 0;
}

.paginating-container .prev svg,
.paginating-container .next svg {
    width: 18px;
    height: 18px;
    vertical-align: text-bottom;
}

.paginating-container .pagination {
    margin-bottom: 0;
}

.paginating-container li {
    padding: 10px 0;
    font-weight: 600;
    color: #3b3f5c;
    border-radius: 4px;
}

.paginating-container li a {
    padding: 10px 15px;
    font-weight: 600;
    color: #3b3f5c;
}

.paginating-container li:not(:last-child) {
    margin-right: 4px;
}

.pagination-solid li {
    background-color: #e0e6ed;
}
.pagination-solid li:hover a {
    color: #1b55e2;
}
.pagination-solid li.active {
    background-color: #1b55e2 !important;
    color: #fff;
}

.pagination-solid li a.active:hover,
.pagination-solid li.active a {
    color: #fff;
}
.pagination-solid .prev {
    background-color: #e0e6ed;
}
.pagination-solid .prev:hover {
    background-color: #1b55e2;
}
.pagination-solid .prev:hover a,
.pagination-solid .prev:hover svg {
    color: #fff;
}
.pagination-solid .next {
    background-color: #e0e6ed;
}
.pagination-solid .next:hover {
    background-color: #1b55e2;
}
.pagination-solid .next:hover a,
.pagination-solid .next:hover svg {
    color: #fff;
}
.loader-btn {
  width: 0.9rem;
  height: 0.9rem;
  border-width: 0.6em; }
  .loader-xl {
    width: 5rem; /* adjust the width as needed */
    height: 5rem; /* adjust the height as needed */
  }
</style>
