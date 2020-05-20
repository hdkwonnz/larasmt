<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Search Part-Products
                </h4>
            </div>
        </div>
        <!-- form -->
        <form @submit.prevent="partProduct()" id="partForm" class="mt-4">
            <div class="form-group row">
                <label for="ownPartNumber" class="col-md-2 col-form-label">OwnPartNumber</label>
                <div class="col-md-6">
                    <input id="ownPartNumber" type="text" class="form-control" name="ownPartNumber" v-model="ownPartNumber" required autofocus>
                </div>
            </div>
            <!-- <div class="form-group row">
                <label for="vendorPartNumber" class="col-md-2 col-form-label">VendorPartNumber</label>
                <div class="col-md-6">
                    <input id="vendorPartNumber" type="text" class="form-control" name="vendorPartNumber" v-model="vendorPartNumber" required>
                </div>
            </div> -->
            <div class="form-group row mt-5">
                <div class="col-md-6 offset-md-2">
                    <button type="" class="btn btn-primary w-100">
                        Click
                    </button>
                </div>
            </div>
        </form> <!-- end of form -->

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" v-if="errorMsg">
                    <span >{{ errorMsg }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" v-if="successMsg">
                    <span >{{ successMsg }}</span>
                </div>
            </div>
        </div>

        <!-- display existing parts -->
        <div class="row mb-2" v-if="partSw">
            <div class="offset-md-1 col-md-3">
                <input type="text" class="form-control" v-model="part.own_partnumber" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" v-model="part.vendor_partnumber" readonly>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" v-model="part.value" readonly>
            </div>
        </div>
        <div class="row">
            <div class="show_parts col-md-10 offset-md-1">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr v-for="product in products" :key="product.index" >
                                <td><strong>{{ product.productname.name}}</strong></td>
                                <td>{{ product.machine.name  }}</td>
                                <td>{{ product.department.name }}</td>
                                <td>{{ product.feeder_number}}</td>
                                <td>{{ product.feeder_position}}</td>
                                <td>{{ product.feeder_qty}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- end table-responsive -->
            </div>
        </div><!-- display existing feeders -->
    </div>
</template>

<script>
    export default {
        // props:['user_email',
        //         'user_name'
        // ],

        data(){
            return{
                partSw: false,
                // vendorPartNumber: "",
                ownPartNumber: "",
                successMsg: null,
                errorMsg: null,
                products: {},
                part: {},
            }
        },

        methods: {
            partProduct(){
                axios.post('/partProduct',
                {
                    ownPartNumber: this.ownPartNumber,
                    // vendorPartNumber: this.vendorPartNumber,
                })
                .then(response => {
                    //console.log(response);
                    this.errorMsg = response.data.errorMsg;
                    this.successMsg = response.data.successMsg;
                    if (!this.errorMsg){
                        this.part = response.data.part;
                        this.products = response.data.products;
                        this.partSw = true;
                        $('.show_parts').addClass('show_scroll_bar');
                    }else{
                        this.part = "";
                        this.products = "";
                        this.partSw = false;
                        $('.show_parts').removeClass('show_scroll_bar');
                    }
                })
                .catch(error => {
                    //console.log(error);
                    this.errorMsg = "";
                    this.errorMsg = error;
                    //this.errorMsg = error.response.data.message;
                });
            }
        },

        created() {

        },

        mounted() {

        }
    }
</script>
