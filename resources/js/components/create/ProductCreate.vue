<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Create Product
                </h4>
            </div>
        </div>
        <form @submit.prevent="createProduct()" class="mt-3">
            <div class="form-group row">
                <label for="Product" class="col-md-1 col-form-label">P/Name</label>
                <div class="col-md-3">
                    <select class="form-control product_name" v-model="productNameId">
                        <option disabled value="">Please select one</option>
                        <option v-for="justProductName in justProductNames" :key="justProductName.id" :value="justProductName.id">{{ justProductName.name }}</option>
                    </select>
                </div>
                <label for="Machine" class="col-md-1 col-form-label">Machine</label>
                <div class="col-md-3">
                    <select class="form-control machine_name" v-model="machineId">
                        <option disabled value="">Please select one</option>
                        <option v-for="justMachine in justMachines" :key="justMachine.id" :value="justMachine.id">{{ justMachine.name }}</option>
                    </select>
                </div>
                <label for="Department" class="col-md-1 col-form-label">Department</label>
                <div class="col-md-2">
                    <select class="form-control department_name" v-model="departmentId">
                        <option disabled value="">Please select one</option>
                        <option v-for="justDepartment in justDepartments" :key="justDepartment.id" :value="justDepartment.id">{{ justDepartment.name }}</option>
                    </select>
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
            <div class="show_products offset-md-1 col-md-10">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr v-for="product in products" :key="product.index" >
                                <td>{{ product.productname.name }}</td>
                                <td>{{ product.machine.name }}</td>
                                <td>{{ product.department.name }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" @click="deleteProduct(product.id)">
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
                products: {},
                productNameId: "",
                machineId: "",
                departmentId:"",
                justProductNames: {},
                justMachines: {},
                justDepartments: {},
            }
        },

        methods: {
            getProducts(){
                axios.get('/get-products',{
                //
                })
                .then(response => {
                //console.log(response);
                this.products = response.data.products;
                    if (this.products){
                        $('.show_products').addClass('show_scroll_bar');
                    }
                })
                .catch(error => {
                    //console.log(error);
                });
            },

            createProduct(){
                if (this.productNameId == "" || this.machineId == "" || this.departmentId == ""){
                    alert("Please select proper item(s).")
                    return;
                }
                axios.post('/create-product',
                {
                    productNameId: this.productNameId,
                    machineId: this.machineId,
                    departmentId: this.departmentId,
                })
                .then(response => {
                    //console.log(response);
                    this.errorMsg = "";
                    this.successMsg = "";
                    this.errorMsg = response.data.errorMsg;
                    this.successMsg = response.data.successMsg;
                    if (!this.errorMsg){
                        this.getProducts(); //display products
                    }
                })
                .catch(error => {
                    this.errorMsg = "";
                    this.successMsg = "";
                    //console.log(error);
                    this.errorMsg = error;
                });
            },

            deleteProduct(productId){
                var message = "Do you want to delete this product?";
                var result = confirm(message);
                if (result == true) {
                    axios.post('/delete-product',
                    {
                        productId: productId,
                    })
                    .then(response => {
                        //console.log(response);
                        this.errorMsg = "";
                        this.successMsg = "";
                        this.errorMsg = response.data.errorMsg;
                        this.successMsg = response.data.successMsg;
                        if (!this.errorMsg){
                            this.product = "";
                            this.getProducts(); //display products
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
            axios.get('/feeder-jsutNames',{
                //
            })
            .then(response => {
                //console.log(response);
                //get 3 objects from ProductController
                this.justProductNames = response.data.justProductNames;
                this.justMachines = response.data.justMachines;
                //this.justDepartments = response.data.justDepartments[0];//매우 중요[0]사용.
                this.justDepartments = response.data.justDepartments;
            })
            .catch(error => {
                //console.log(error);
            });

            this.getProducts();
        },
        mounted() {

        }
    }
</script>
