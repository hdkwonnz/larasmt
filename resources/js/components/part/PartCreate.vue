<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Create Parts
                </h4>
            </div>
        </div>
        <!-- form -->
        <form @submit.prevent="createPart()" id="partForm" class="mt-4">
            <div class="form-group row">
                <label for="ownPartNumber" class="col-md-2 col-form-label">OwnPartNumber</label>
                <div class="col-md-6">
                    <input id="ownPartNumber" type="text" class="form-control" name="ownPartNumber" v-model="ownPartNumber" required autofocus>
                </div>
            </div>
            <div class="form-group row">
                <label for="vendorPartNumber" class="col-md-2 col-form-label">VendorPartNumber</label>
                <div class="col-md-6">
                    <input id="vendorPartNumber" type="text" class="form-control" name="vendorPartNumber" v-model="vendorPartNumber" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="partValue" class="col-md-2 col-form-label">PartValue</label>
                <div class="col-md-6">
                    <input id="partValue" type="text" class="form-control" name="partValue" v-model="partValue" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="partDescription" class="col-md-2 col-form-label">PartDescription</label>
                <div class="col-md-6">
                    <input id="partDescription" type="text" class="form-control" name="partDescription" v-model="partDescription" required>
                </div>
            </div>
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
        <div class="row">
            <div class="show_parts col-md-10 offset-md-1">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr v-for="part in parts" :key="part.index" >
                                <td><strong>{{ part.own_partnumber}}</strong></td>
                                <td>{{ part.vendor_partnumber  }}</td>
                                <td>{{ part.value }}</td>
                                <td>{{ part.description }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" @click="deletePart(part.id)">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
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
        props:['user_email',
                'user_name'
        ],

        data(){
            return{
                partDescription: "",
                partValue: "",
                vendorPartNumber: "",
                ownPartNumber: "",
                successMsg: null,
                errorMsg: null,
                parts: {},
            }
        },

        methods: {
            deletePart(partId){
                //alert(partId);
                var message = "Do you want to delete this part?";
                var result = confirm(message);
                if (result == true) {
                    axios.post('/delete-part',
                    {
                        partId: partId,
                    })
                    .then(response => {
                        //console.log(response);
                        this.successMsg = "";
                        this.errorMsg = "";
                        this.successMsg = response.data.successMsg;
                        this.errorMsg = response.data.errorMsg;
                        if (!this.errorMsg){
                            this.getParts();
                        }
                    })
                    .catch(error => {
                        //console.log(error);
                        this.successMsg = "";
                        this.errorMsg = "";
                        this.errorMsg = error;
                    });
                }
            },

            getParts(){
                axios.get('/get-parts', {
                    params: {productId: this.productId}
                })
                .then(response => {
                    //console.log(response);
                    this.parts = response.data.parts;
                    if (this.parts){
                        $('.show_parts').addClass('show_scroll_bar');
                    }
                });
            },

            createPart(){
                axios.post('/create-part',
                {
                    ownPartNumber: this.ownPartNumber,
                    vendorPartNumber: this.vendorPartNumber,
                    partValue: this.partValue,
                    partDescription: this.partDescription
                })
                .then(response => {
                    //console.log(response);
                    this.errorMsg = response.data.errorMsg;
                    this.successMsg = response.data.successMsg;
                    if (!this.errorMsg){
                        this.ownPartNumber = "";
                        this.vendorPartNumber = "";
                        this.partValue = "";
                        this.partDescription = "";
                        this.getParts();
                    }
                })
                .catch(error => {
                    //console.log(error);
                    this.errorMsg = error;
                });
            }
        },

        created() {
            this.getParts();
        },

        mounted() {

        }
    }
</script>
