<template>
    <div>
        <topbar />
        <div class="container">
            <h1 class="text-center mt-5">Licenses for Analysic Ticketmaster Event</h1>
            <div class="row">
                <div class="offset-xs-1 col-xs-11 offset-md-7 col-md-5 text-right">
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">Search</span>
                        </div>
                        <input type="text" class="form-control" id="validationCustomUsername" v-model="filterKey">
                    </div>
                </div>
            </div>

            <b-table responsive="lg" class="mt-2 table-border" :bordered="true" :filter="filterKey" hover :items="data" :fields="fields">
                <template slot="action" slot-scope="row">
                    <div class="btn-group" role="group">
                        <b-button @click="reNewLicense(row.item.id)" class="btn btn-success">Renew</b-button>
                        <b-button @click="deleteLicense(row.item.id)" class="btn btn-danger">Delete</b-button>
                    </div>
                </template>
            </b-table>
            
            <hr/>
            <div class="row">
                <div class="col-6">
                    <label class="col-xs-4">Period: </label>
                    <select name="period" class="form-control" v-model="newKey.period">
                        <option value=1>1 month</option>
                        <option value=2>2 months</option>
                        <option value=3>3 month</option>
                        <option value=6>6 month</option>
                        <option value=12>1 year</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="col-xs-4">User Email: </label>
                    <input type="email" v-model="newKey.email" name="email" class="form-control col-xs-8" required>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary float-right" @click="generateNewKey">Add New</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Topbar from './Topbar.vue';
export default {
    name: 'Tracker',
    components: {
      'topbar' : Topbar
    },
    data () {
        return {
            filterKey: "",
            newKey: {
                period: "",
                email: "",
            },
            data: [],
            fields: {
                email: {
                    label: 'Email',
                    sortable: true
                },
                license: {
                    label: 'Key',
                    sortable: false
                },
                duration: {
                    label: 'Period',
                    sortable: true
                },
                expire: {
                    label: 'Expire',
                    sortable: true
                },
                'action': {
                    label: 'Action'
                }
            },
        }
    },
    methods: {
        getLicenseInfo () {
            this.$store.dispatch('GET_LICENSES').then(res => {
                this.data = res;
            }, err => {
                var data = err.response.data;
                this.$toastr.warning(data.message);
            })
        },
        deleteLicense(id) {
            if (!confirm("Are you sure delete this license?")) {
                return;
            }
            this.$store.dispatch('DELETE_LICENSE', {id: id}).then(res => {
                this.$toastr.success(res.message);
                this.getLicenseInfo();
            }, err => {
                var data = err.response.data;
                this.$toastr.warning(data.message);
            });
        },
        generateNewKey () {
            this.$store.dispatch('CREATE_LICENSE', this.newKey).then(res => {
                this.$toastr.success(res.message);
                this.getLicenseInfo();
            }, err => {
                var data = err.response.data;
                this.$toastr.warning(data.message);
            });
        },
        reNewLicense (id) {
            this.$store.dispatch('RENEW_LICENSE', {id: id}).then(res => {
                this.$toastr.success(res.message);
                this.getLicenseInfo();
            }, err => {
                var data = err.response.data;
                this.$toastr.warning(data.message);
            });
        }
    },
    computed: {
    },
    mounted() {
        document.body.className = 'removeBackGroundImage';
        this.getLicenseInfo();
    }
}
</script>
<style scoped>
    .table-bordered {
        border: 1px solid #dee2e6 !important;
    }
</style>