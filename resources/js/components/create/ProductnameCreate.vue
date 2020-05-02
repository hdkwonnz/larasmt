<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Create Productname
                </h4>
            </div>
        </div>
        <form @submit.prevent="createProductname()" class="mt-3">
            <div class="form-group row">
                <label for="ProductName" class="col-md-2 col-form-label">Product Name</label>
                <div class="col-md-4">
                    <input type="text" name="productName" v-model="productName" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-md btn-primary">
                        Click
                    </button>
                </div>
            </div>
        </form>

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

        <div class="row"><!-- display existing productnames -->
            <div class="show_productnames offset-md-2 col-md-4">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr v-for="productname in productnames" :key="productname.index" >
                                <td>{{ productname.name }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" @click="deleteProductname(productname.id)">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- end table-responsive -->
            </div>
        </div><!-- display existing productname -->
    </div>
</template>

<script>
    export default {
        props:['user_email',
                'user_name'
        ],

        data(){
            return{
                successMsg: null,
                errorMsg: null,
                productnName: "",
                productnames: {},
            }
        },

        methods: {
            getProductnames(){
                axios.get('/get-productnames',{
                //
                })
                .then(response => {
                //console.log(response);
                this.productnames = response.data.productnames;
                    if (this.productnames){
                        $('.show_productnames').addClass('show_scroll_bar');
                    }
                })
                .catch(error => {
                    //console.log(error);
                });
            },

            createProductname(){
                axios.post('/create-productname',
                {
                    productName: this.productName,
                })
                .then(response => {
                    //console.log(response);
                    this.errorMsg = "";
                    this.successMsg = "";
                    this.errorMsg = response.data.errorMsg;
                    this.successMsg = response.data.successMsg;
                    if (!this.errorMsg){
                        this.productName = "";
                        this.getProductnames(); //display productnames
                    }
                })
                .catch(error => {
                    this.errorMsg = "";
                    this.successMsg = "";
                    //console.log(error);
                    this.errorMsg = error;
                });
            },

            deleteProductname(productnameId){
                var message = "Do you want to delete this product name?";
                var result = confirm(message);
                if (result == true) {
                    axios.post('/delete-productname',
                    {
                        productnameId: productnameId,
                    })
                    .then(response => {
                        //console.log(response);
                        this.errorMsg = "";
                        this.successMsg = "";
                        this.errorMsg = response.data.errorMsg;
                        this.successMsg = response.data.successMsg;
                        if (!this.errorMsg){
                            this.productName = "";
                            this.getProductnames(); //display productnames
                        }
                    })
                    .catch(error => {
                        this.errorMsg = "";
                        this.successMsg = "";
                        //console.log(error);
                        this.errorMsg = error;
                    });
                }
            }
        },

        created() {
            this.getProductnames();
        },
        mounted() {

        }
    }
</script>
