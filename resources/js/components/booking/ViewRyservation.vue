<script>
import axios from 'axios';
import Mixin from '../../mixer'
export default {
    mixins: [Mixin],

    data() {
        return {
            bookings: [],
            modify: {
                id: '',
                guestNumber: '',
                amount: 0,
                vat: 0,
                discount: 0,
                grandTotal: 0,
                comment: '',
                status: ''
            },
            currentPage: 1,
            perPage: 15,
            lastPage: 0,
            url: baseUrl,
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
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                await axios.get(`${apiUrl}backendapi/booking?skiped=${this.currentPage}&per_page=${this.perPage}`, {
                        headers: {
                            'Authorization': `Bearer ${token.token}`
                        }
                    })
                    .then(response => {
                        this.bookings = response.data.data
                        this.lastPage = response.data.pagination.total
                        // console.log(response.data)
                    }).catch(error => {
                        console.log(error)
                    })
            } catch (e) {
                console.log(e)
            }
        },

        async updateStatus(ryserve) {
            this.modify.id = ryserve.id
            this.modify.comment = ryserve.comment
            this.modify.guestNumber = ryserve.guestNumber
            this.modify.amount = ryserve.amount
            this.modify.status = ryserve.status
            $("#updateBooking").modal('show');
        },
        async updateBookingStatus() {
            try {
                const tok = localStorage.getItem('authuser')
                const token = JSON.parse(tok)
                this.modify.grandTotal = Number(this.modify.amount+this.modify.vat-this.modify.discount)
                axios.put(`${apiUrl}backendapi/booking?id=${this.modify.id}`,this.modify, {
                    headers: {
                        'Authorization': `Bearer ${token.token}`
                    }
                }).then(
                    response => {
                        console.log(response.data)
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
                })
            } catch (e) {
                console.log(e.response)
            }
        },
        async clearForm() {
            this.modify = {
                id: '',
                guestNumber: '',
                amount: 0,
                vat: 0,
                discount: 0,
                grandTotal: 0,
                comment: '',
                status: ''
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
                            <h4>Ryservation</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="formReset">
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
                                <div class="col-12">
                                    <label for="Amount">Amount</label>
                                    <input type="number"  class="form-control form-control-sm" id="Amount" v-model="modify.amount" placeholder="Amount" required>
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
                                <div class="col-12  mt-2">
                                    <label for="Comment">Comment</label>
                                    <textarea id="Comment" class="form-control form-control-sm" v-model="modify.comment" rows="5" placeholder="Write Comment"></textarea>
                                </div>
                            </div>

                                <div class="modal-footer md-button">
                                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12" @click="formReset"></i> Discard</button>

                                    <button type="submit" class="btn btn-primary">Submit</button>

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
</style>
