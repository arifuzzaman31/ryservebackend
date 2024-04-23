<template>
    <div class="text-center" v-if="!loaded">
        <div class="spinner-border text-success align-self-center loader-xl"></div>
    </div>
    <div class="" v-else>
        <div class="row mt-3" style="display: flex; row-gap: 20px" >
            <div class="col-12">
                <h4>No. of Reservation</h4>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-3 rounded" v-for="vl in ryservationData" :key="vl.status">
                <div class="card" style="border-radius: 8px">
                    <div
                        class="card-body d-flex align-items-center"
                        style="
                            height: 100vh;
                            max-height: 143px;
                            column-gap: 20px;
                        "
                    >
                        <div>
                            <span
                                class="rounded-circle"
                                style="
                                    padding: 1rem 0.69rem !important;
                                    background-color: rgba(8, 129, 120, 0.2);
                                "
                            >
                                <i
                                    class="icons icon icon-sm rounded-circle"
                                    style="
                                        background-color: rgb(8 129 120 / 65%);
                                        color: #fff;
                                        padding: 4px;
                                    "
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        height="20"
                                        width="20"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1"
                                        stroke="currentColor"
                                        class="w-6 h-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"
                                        />
                                    </svg>
                                </i>
                            </span>
                        </div>
                        <div class="icons-content">
                            <h6 class="mb-1 card-title">{{ camalizeString(vl.text) }}</h6>
                            <span>{{ vl._count }}</span> <br />
                            <span class="text-sm">
                                Count Total {{ camalizeString(vl.status) }}
                            </span>
                        </div>

                        <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                    </div>
                </div>
            </div>
            <div class="col-12">
                <h4>No. of Guest</h4>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-3 rounded" v-for="vl in guestData" :key="vl.status">
                <div class="card" style="border-radius: 8px">
                    <div
                        class="card-body d-flex align-items-center"
                        style="
                            height: 100vh;
                            max-height: 143px;
                            column-gap: 20px;
                        "
                    >
                        <div>
                            <span
                                class="rounded-circle"
                                style="
                                    padding: 1rem 0.69rem !important;
                                    background-color: rgba(8, 129, 120, 0.2);
                                "
                            >
                                <i
                                    class="icons icon icon-sm rounded-circle"
                                    style="
                                        background-color: rgb(8 129 120 / 65%);
                                        color: #fff;
                                        padding: 4px;
                                    "
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        height="20"
                                        width="20"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1"
                                        stroke="currentColor"
                                        class="w-6 h-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"
                                        />
                                    </svg>
                                </i>
                            </span>
                        </div>
                        <div class="icons-content">
                            <h6 class="mb-1 card-title">{{ vl.text }}</h6>
                            <span>{{ vl._sum.guestNumber }}</span> <br />
                            <span class="text-sm">
                                Count Total {{ vl.text }}
                            </span>
                        </div>

                        <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                    </div>
                </div>
            </div>
            <div
                class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing"
            >
                <div class="widget widget-chart-one">
                    <div class="widget-heading">
                        <ul class="tabs tab-pills">
                            <li>
                                <a
                                    href="javascript:void(0);"
                                    id="tb_1"
                                    class="tabmenu"
                                    >Status</a
                                >
                            </li>
                        </ul>
                    </div>

                    <div class="widget-content">
                        <div class="tabs tab-content">
                            <div id="content_1" class="tabcontent">
                                <customer-of-month :chartData="chartData" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing"
            >
                <div class="widget widget-chart-one">
                    <div class="widget-heading">
                        <ul class="tabs tab-pills">
                            <li>
                                <a
                                    href="javascript:void(0);"
                                    id="tb_1"
                                    class="tabmenu"
                                    >This Year Income</a
                                >
                            </li>
                        </ul>
                    </div>

                    <div class="widget-content">
                        <div class="tabs tab-content">
                            <div id="content_1" class="tabcontent">
                                <customer-of-month :chartData="yearData" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CustomerOfMonth from "./chart/CustomerOfMonth.vue";
import Mixin from '../mixer'
export default {
    mixins:[Mixin],
    components: {
        "customer-of-month": CustomerOfMonth
    },
    data() {
        return {
            ryservationData: null,
            guestData: null,
            chartData: null,
            yearData: null,
            loaded: false,
            url: apiUrl
        };
    },

    methods: {
        makeStatusText(status,from){
            let text;
            switch (status) {
                case "ON_HOLD":
                    text = "Upcoming"
                    break;
                case "CONFIRMED":
                    text = "Waitlist"
                    break;
                case "CANCELED":
                    text = "Canceled"
                    break;
                case "COMPLETED":
                    text = from == 'ryservationData' ? "Completed" : "Visited"
                    break;

                default:
                    break;
            }
            return text
        },
        async getDataStatus() {
            const token = await this.getUserToken()
            await axios.get(`${apiUrl}backendapi/dashboard`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            }).then((response) => {
                this.ryservationData = response.data.statusData.map(obj => ({
                        ...obj,
                        text: this.makeStatusText(obj.status,'ryservationData')
                    }));
                this.guestData = response.data.statusData.map(obj => ({
                        ...obj,
                        status: this.camalizeString(obj.status),
                        text: this.makeStatusText(obj.status,'guestData')
                    }));
                this.chartData = {
                    labels: response.data.statusData.map(
                        (item) => this.camalizeString(item.status)
                    ),
                    datasets: [
                        {
                            label: "Ryservation",
                            data: response.data.statusData.map(
                                (item) => item._count
                            ),
                            backgroundColor: [
                                "rgba(255, 99, 132, 0.2)",
                                "rgba(255, 159, 64, 0.2)",
                                "rgba(255, 205, 86, 0.2)",
                                "rgba(75, 192, 192, 0.2)"
                            ],
                            borderColor: [
                                "rgb(255, 99, 132)",
                                "rgb(255, 159, 64)",
                                "rgb(255, 205, 86)",
                                "rgb(75, 192, 192)"
                            ],
                            borderWidth: 1,
                        },
                    ],
                };

                const mData = Object.keys(response.data.monthData)
                this.yearData = {
                    labels: mData,
                    datasets: [
                        {
                            label: "Income",
                            data: mData.map(v =>  response.data.monthData[v]),
                            backgroundColor: [
                                "rgba(255, 99, 132, 0.2)",
                                "rgba(255, 159, 64, 0.2)",
                                "rgba(255, 205, 86, 0.2)",
                                "rgba(75, 192, 192, 0.2)",
                                "rgba(54, 162, 235, 0.2)",
                                "rgba(153, 102, 255, 0.2)",
                                "rgba(201, 203, 207, 0.2)",
                                "rgba(255, 51, 51, 0.2)",
                                "rgba(51, 153, 255, 0.2)",
                                "rgba(255, 102, 255, 0.2)",
                                "rgba(255, 229, 204, 0.2)",
                                "rgba(102, 255, 102, 0.2)",
                            ],
                            borderColor: [
                                "rgb(255, 99, 132)",
                                "rgb(255, 159, 64)",
                                "rgb(255, 205, 86)",
                                "rgb(75, 192, 192)",
                                "rgb(54, 162, 235)",
                                "rgb(153, 102, 255)",
                                "rgb(201, 203, 207)",
                                "rgb(255, 51, 51)",
                                "rgb(51, 153, 255)",
                                "rgb(255, 102, 255)",
                                "rgb(255, 229, 204)",
                                "rgb(102, 255, 102)",
                            ],
                            borderWidth: 1,
                        },
                    ],
                };
                this.loaded = true;
            });
        }
    },

    mounted() {
        this.getDataStatus()
    },
};
</script>

<style>
.gdar {
    background: linear-gradient(
        linear-gradient(140deg, #6e85b1 0 34%, #d9dce0 -40% 51%)
    ) !important;
}
</style>
