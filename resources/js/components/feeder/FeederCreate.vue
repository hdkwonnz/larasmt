<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Create Feeders
                </h4>
            </div>
        </div>
        <form @submit.prevent="checkFeeder()" class="mt-3">
            <div class="form-group row">
                <label for="Product" class="col-md-1 col-form-label">Product</label>
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
                    <button type="submit" class="btn btn-md btn-danger submit_btn">
                        Confirm
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

        <form v-if="feederSw" @submit.prevent="createFeeder()" class="mt-3">
            <div class="form-group row">
                <label for="Feeder #" class="col-md-1 col-form-label">Feeder #</label>
                <div class="col-md-1">
                    <input type="number" name="feederNumber" v-model="feederNumber" class="form-control" min="1" max="300" required>
                </div>
                <label for="Qty" class="col-md-1 col-form-label">Qty/PCB</label>
                <div class="col-md-1">
                    <input type="number" name="qty" v-model="qty" class="form-control" min="1" required>
                </div>
                <label for="Part #" class="col-md-1 col-form-label">Part #</label>
                <div class="col-md-3">
                    <input type="text" name="partNumber" v-model="partNumber" class="form-control" required>
                </div>
                <label for="Position" class="col-md-1 col-form-label">F/Position</label>
                <div class="col-md-2">
                    <select class="form-control" v-model="feederPosition" :required="true">
                        <option v-for="option in options" :key="option.index" :value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-md btn-primary">
                        Click
                    </button>
                </div>
            </div>
        </form>

        <!-- Modal -->
        <div class="modal fade partModal-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="offset-md-5 col-md-3">
                            <h4 class="modal-title" id="exampleModalLabel">Create Part</h4>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body offset-md-2 col-md-10">
                        <!-- form -->
                        <form @submit.prevent="createPart()" id="partForm" class="mt-4">
                            <div class="form-group row">
                                <label for="ownPartNumber" class="col-md-2 col-form-label">OwnPartNumber</label>
                                <div class="col-md-6">
                                    <input id="ownPartNumber" type="text" class="form-control" name="ownPartNumber" v-model="ownPartNumber" required>
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
                            <div class="offset-md-2 col-md-6 ">
                                <span class="modalMessage"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-md-2 col-md-6">
                                <span class="modalError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
            </div>
            </div>
        </div> <!-- end of modal -->

        <!-- display existing feeders -->
        <div class="row">
            <div class="show_feeders col-md-12">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr v-for="feeder in feeders" :key="feeder.index" >
                                <td>
                                    <strong>{{ feeder.feeder_number}}</strong> : <span class="text-danger">{{ feeder.position}}</span>
                                </td>
                                <td>{{ feeder.qty }}</td>
                                <td>{{ feeder.part.own_partnumber}}</td>
                                <td>{{ feeder.part.vendor_partnumber  }}</td>
                                <td>{{ feeder.part.value }}</td>
                                <!-- <td>{{ feeder.part.description }}</td> -->
                                <td class="text-center">
                                    <a href="javascript:void(0)" @click="deleteFeeder(feeder.id)">
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
                options: [
                    { text: 'Front', value: 'F' },
                    { text: 'Rear', value: 'R' },
                ],
                // feederPosition: "F",
                qty: "",
                feederPosition: "",
                partDescription: "",
                partValue: "",
                vendorPartNumber: "",
                ownPartNumber: "",
                feederSw: false,
                noPartSw: false,
                partNumber: "",
                feederNumber: "",
                successMsg: null,
                errorMsg: null,
                product: {},
                productId: "",
                // parts: {},
                feeders: {},
                productNameId: "",
                machineId: "",
                departmentId:"",
                justProductNames: {},
                justMachines: {},
                justDepartments: {},
            }
        },

        methods: {
            deleteFeeder(feederId){
                //alert("feederId =   " + feederId);
                var message = "Do you want to delete this feeder?";
                var result = confirm(message);
                if (result == true) {
                    axios.post('/delete-feeder',
                    {
                        feederId: feederId,
                    })
                    .then(response => {
                        //console.log(response);
                        this.successMsg = "";
                        this.errorMsg = "";
                        this.successMsg = response.data.successMsg;
                        this.errorMsg = response.data.errorMsg;
                        if (!this.errorMsg){
                            this.getFeeders();
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

            getFeeders(){
                axios.get('/get-feeders', {
                    params: {productId: this.productId}
                })
                .then(response => {
                    //console.log(response);
                    this.feeders = response.data.feeders;
                    if (this.feeders){
                        $('.show_feeders').addClass('show_scroll_bar');
                    }
                });
            },

            checkFeeder(){
                if (this.productNameId == "" || this.machineId == "" || this.departmentId == ""){
                    this.successMsg = "";
                    this.errorMsg = "";
                    this.errorMsg = "Please, select proper item(s)."
                    return;
                }
                axios.post('/check-feeder',
                {
                    productNameId: this.productNameId,
                    machineId: this.machineId,
                    departmentId: this.departmentId
                })
                .then(response => {
                    //console.log(response);
                    this.errorMsg = response.data.errorMsg;
                    if (!this.errorMsg){
                        //this.parts = response.data.parts;
                        this.product = response.data.product
                        this.productId = this.product.id;
                        this.getFeeders();
                        $('.submit_btn').attr("disabled","disabled");
                        $('.product_name').attr("disabled","disabled");
                        $('.machine_name').attr("disabled","disabled");
                        $('.department_name').attr("disabled","disabled");
                        this.feederSw = true;
                        this.feederPosition = 'F';
                    }
                })
                .catch(error => {
                    //console.log(error);
                    this.errorMsg = error;
                });
            },

            createFeeder(){
                axios.post('/create-feeder',
                {
                    productId: this.product.id,
                    feederNumber: this.feederNumber,
                    partNumber: this.partNumber,
                    feederPosition: this.feederPosition,
                    qty: this.qty,
                })
                .then(response => {
                    //console.log(response);
                    this.noPartSw = response.data.noPartSw;
                    if (this.noPartSw == true){
                        this.ownPartNumber = this.partNumber;
                        $('.partModal-modal-xl').modal('show'); //show modal to create part
                        // for autofocus
                        $(document).ready(function() {
                            $('.partModal-modal-xl').on('shown.bs.modal', function() {
                            $('#vendorPartNumber').trigger('focus');
                            });
                        })
                    } else{
                        this.errorMsg = response.data.errorMsg;
                        this.successMsg = response.data.successMsg;
                        if (!this.errorMsg){
                            this.feederNumber = "";
                            this.partNumber = "";
                            this.qty = "";
                            this.getFeeders(); //display feeders
                        }
                    }
                })
                .catch(error => {
                    //console.log(error);
                    this.errorMsg = error;
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
                    $('.modalMessage').html("");//clear field
                    $('.modalError').html("");//clear field
                    if (response.data.successMsg){
                        $('.modalMessage').append(response.data.successMsg).css('color','blue');
                        //setTimeout(() => {  $('.partModal-modal-xl').modal('hide'); }, 1000);//auto hide
                        this.ownPartNumber = "";
                        this.vendorPartNumber = "";
                        this.partValue = "";
                        this.partDescription = "";
                    }else{
                        $('.modalError').append(response.data.errorMsg).css('color','red')
                    }
                })
                .catch(error => {
                    //console.log(error);
                    $('.modalError').html("");//clear field
                    $('.modalMessage').html("");//clear field
                    $('.modalError').append(error).css('color','red');
                    // console.log(error.response)
                    // console.log(error.response.status);
                });
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
                this.justDepartments = response.data.justDepartments;
            })
            .catch(error => {
                //console.log(error);
            });
        },

        mounted() {

        }
    }
</script>
