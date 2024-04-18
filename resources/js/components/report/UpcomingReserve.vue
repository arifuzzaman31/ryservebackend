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
                await axios.get(`${apiUrl}backendapi/report?skiped=${this.currentPage}&status=CONFIRMED&event=${this.filterdata.isEvent}&startDate=${this.filterdata.startDate}&endDate=${this.filterdata.endDate}&per_page=${this.perPage}`, {
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
                            <h4>Upcomming Reserve</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area text-center" v-if="isLoading">
                    <div class="spinner-border text-success align-self-center loader-xl"></div>
                </div>
                <div class="widget-content widget-content-area" v-else>
                    <div class="row mb-2">
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
                                    <th>Slot</th>
                                    <th>No. of Guests</th>
                                    <th>Assigned Table</th>
                                    <th>Special Requests</th>
                                    <th>Pre Order</th>
                                    <th>Membership Type</th>
                                    <th>Reference</th>
                                    <th>Remarks </th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody v-if="bookings && bookings.length > 0">
                                <template v-for="(ryserve,index) in bookings" :key="ryserve.id">
                                <tr>
                                    <td>{{ index+1 }}</td>
                                    <td>{{ ryserve.subAssetComponent.listingName }}</td>
                                    <td>{{ ryserve.customerName }}</td>
                                    <td>{{ ryserve.phoneNumber }}</td>
                                    <td>{{ ryserve.slot }}</td>
                                    <td class="text-center">{{ ryserve.guestNumber }}</td>
                                    <td>{{ ryserve?.table?.type }} - {{ ryserve?.table?.capacity }}</td>
                                    <td>{{ ryserve?.customerRequest }}</td>
                                    <td></td>
                                    <td>Regular</td>
                                    <td>Ryserved Apps</td>
                                    <td>{{ ryserve?.comment }}</td>
                                    <td class="text-center">
                                       <span v-if="ryserve.status == 'CONFIRMED'" class="badge badge-success">Confirmed</span>
                                        <span v-if="ryserve.status == 'DEACTIVE'" class="badge badge-warning">Deactive</span>
                                        <span v-if="ryserve.status == 'ON_HOLD'" class="badge badge-light">On Hold</span>
                                        <span v-if="ryserve.status == 'CANCELED'" class="badge badge-danger">Canceled</span>
                                        <span v-if="ryserve.status == 'COMPLETED'" class="badge badge-info">Completed</span>
                                    </td>
                                </tr>
                                </template>
                            </tbody>
                            <tbody v-else>
                                <tr class="text-center text-bold">
                                    <td colspan="13">No Data Found</td>
                                </tr>
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
</style>
