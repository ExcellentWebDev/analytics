<template>
    <div>
        <topbar />
        <go-top :size="40" :right="30" :bottom="50"></go-top>
        <div class="container-fulid">
            <div role="tablist">
                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button block href="#" v-b-toggle.accordion-1 variant="info" class="text-left">Search Options</b-button>
                    </b-card-header>
                    <b-collapse id="accordion-1" :visible="visible" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>
                                <div class="row mt-2 mb-2">
                                    <div class="col-3">
                                        <label>Event Name: </label>
                                        <input type="text" class="searchOption" v-model="searchOption.event_name" @keyup.enter="searchEvents">
                                    </div>
                                    <div class="col-3">
                                        <label>Event Venue: </label>
                                        <input type="text" class="searchOption" v-model="searchOption.event_venue" @keyup.enter="searchEvents">
                                    </div>
                                    <div class="col-3">
                                        <label>Event Date: </label>
                                        <input type="date" class="searchOption" v-model="searchOption.event_date" />
                                    </div>
                                    <div class="col-3">
                                        <b-button variant="success" @click="searchEvents">Search</b-button>
                                    </div>
                                </div>
                            </b-card-text>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </div>
            <div class="row p-2">
                <div class="col-12">
                    <table class="table table-bordered">
                        <thead>
                            <th>Date</th>
                            <th>Event Name</th>
                            <th>Venue</th>
                            <th>Analytics</th>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in (searchData)" :key="index">
                                <td>{{item.event_date}}</td>
                                <td><a href="javascript:void(0)" @click="openEventPage(item.url)">{{item.event_name}}</a></td>
                                <td>{{item.event_venue}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-info" @click="showInfo(item.event_key)">View</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Topbar from './Topbar.vue';

import GoTop from '@inotom/vue-go-top';

export default {
    name: 'Tracker',
    components: {
        Topbar, GoTop
    },
    data () {
        return {
            searchData: [],
            searchOption: {
                event_date: "",
                event_name: "",
                event_venue:"",
            },
            visible: true
        }
    },
    methods: {
        openEventPage(url) {
            window.open(url, "_blank")
        },
        searchEvents() {
            if (!this.searchOption.event_name && !this.searchOption.event_venue && !this.searchOption.event_date) {
                this.$toastr.warning("Please input your search option");
                return;
            }
            this.$store.dispatch("SEARCH_EVENTS", {params: this.searchOption }).then(res => {
                this.searchData = res;
                this.visible = false;
            }, err => {
                var error = err.response.data;
                this.$toastr.warning(error.message);
            })
        },
        showInfo(key) {
            window.open("http://ticketmaster.quiktix.co/#/ticket/" + key + "/0");
        }
    },
    mounted() {
        document.body.className = 'removeBackGroundImage';
    }
}
</script>
<style scoped>
    .searchOption {
        background: #fff;
        width: 150px;
        height: 30px;
        padding: 2px;
    }
    .searchOption:focus {
        border-color: #37c369;
    }
</style>
