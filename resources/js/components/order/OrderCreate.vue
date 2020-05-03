<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Publish Order
                </h4>
            </div>
        </div>
        <form @submit.prevent="createOrder()" class="mt-3">
            <div class="form-group row">
                <label for="Product" class="col-md-1 col-form-label">Product</label>
                <div class="col-md-4">
                    <select class="form-control product_name" v-model="productNameId">
                        <option disabled value="">Please select one</option>
                        <option v-for="justProductName in justProductNames" :key="justProductName.id" :value="justProductName.id">{{ justProductName.name }}</option>
                    </select>
                </div>
                <label for="Department" class="col-md-1 col-form-label">Department</label>
                <div class="col-md-2">
                    <select class="form-control department_name" v-model="departmentId">
                        <option disabled value="">Please select one</option>
                        <option v-for="justDepartment in justDepartments" :key="justDepartment.id" :value="justDepartment.id">{{ justDepartment.name }}</option>
                    </select>
                </div>
                <label for="shiftWork" class="col-md-2 col-form-label text-right">Shift Work</label>
                <div class="col-md-2">
                    <select class="form-control shift_name" v-model="shiftId">
                        <option disabled value="">Please select one</option>
                        <option v-for="justShift in justShifts" :key="justShift.id" :value="justShift.id">{{ justShift.name }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="orderNumber" class="col-md-2 col-form-label text-right">Order Number</label>
                <div class="col-md-3">
                    <input type="text" name="orderNumber" class="form-control" v-model="orderNumber" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-md btn-primary submit_btn">
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

        <!-- display existing feeders -->
        <div class="row">
            <div class="show_orders offset-md-1 col-md-10 offset-md-1">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr v-for="order in orders" :key="order.index" >
                                <td>
                                    <strong>{{ order.order_number}}</strong>
                                </td>
                                <td>{{ order.status }}</td>
                                <td>{{ order.product_name}}</td>
                                <td>{{ order.department_name  }}</td>
                                <td>{{ order.shift_name }}</td>
                                <td>{{ order.author }}</td>
                                <td>{{ order.created_at | myDate}}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" @click="showOrderModal(order.id,order.order_number,order.status,order.department_id)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    /
                                    <a href="javascript:void(0)" @click="deleteOrder(order.id)">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- end table-responsive -->
            </div>
        </div><!-- display existing feeders -->

        <!-- Modal -->
        <div class="modal fade editOrderModal-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="offset-md-5 col-md-3">
                            <h4 class="modal-title" id="exampleModalLabel">Edit Order Status</h4>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body offset-md-2 col-md-10">
                        <div class="form-group row">
                            <label for="orderNumber" class="col-md-2 col-form-label">Order Number</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control order_number" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selectedOrderStatus" class="col-md-2 col-form-label">Order Status</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control order_status" value="" readonly>
                            </div>
                        </div>
                        <!-- form -->
                        <form id="editOrderForm" @submit.prevent="editOrder()" class="mt-4">
                            <div class="form-group row">
                                <label for="Status" class="col-md-2 col-form-label">Order Status</label>
                                <div class="col-md-3">
                                    <select class="form-control" v-model="orderStatus" :required="true">
                                        <option v-for="option in options" :key="option.index" :value="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-5">
                                <div class="col-md-6 offset-md-2">
                                    <button type="" class="btn btn-primary w-100">
                                        Click
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="offset-md-2 col-md-8 ">
                                <span class="modalMessage"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-md-2 col-md-8 ">
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
                    { text: 'activ', value: 'active' },
                    { text: 'waiting', value: 'waiting' },
                    { text: 'pending', value: 'pending' },
                    { text: 'finished', value: 'finished' },
                ],
                selectedDepartmentId: "",
                selectedOrderId: "",
                selectedOrderStatus: "",
                orderStatus: "",
                orders:{},
                successMsg: null,
                errorMsg: null,
                modalMessage: null,
                modalError: null,
                productNameId: "",
                departmentId:"",
                shiftId: "",
                orderNumber: "",
                justProductNames: {},
                justMachines: {},
                justDepartments: {},
                justShifts: {},
            }
        },

        methods: {
            deleteOrder(orderId){
                //alert("orderId =   " + orderId);
                var message = "Do you want to delete this order?";
                var result = confirm(message);
                if (result == true) {
                    axios.post('/delete-order',
                    {
                        orderId: orderId,
                    })
                    .then(response => {
                        //console.log(response);
                        this.successMsg = "";
                        this.errorMsg = "";
                        this.successMsg = response.data.successMsg;
                        this.errorMsg = response.data.errorMsg;
                        if (!this.errorMsg){
                            this.getOrders();
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

            editOrder(){
                //alert(this.selectedOrderId);
                var message = "Do you want to edit this order?";
                var result = confirm(message);
                if (result == true) {
                    axios.post('/edit-order',
                    {
                        orderId: this.selectedOrderId,
                        orderStatus: this.orderStatus,
                        departmentId: this.selectedDepartmentId,
                    })
                    .then(response => {
                        //console.log(response);
                        $('.modalError').html("").removeClass('alert').removeClass('alert-danger');
                        $('.modalMessage').html("").removeClass('alert').removeClass('alert-success');
                        this.modalError = response.data.modalError;
                        this.modalMessage = response.data.modalMessage;
                        if (this.modalError){
                            $('.modalError').append(this.modalError).addClass('alert').addClass('alert-danger');
                        }else{
                            $('.modalMessage').append(this.modalMessage).addClass('alert').addClass('alert-success');
                            this.getOrders();
                        }
                    })
                    .catch(error => {
                        //console.log(error);
                        $('.modalMessage').html("").removeClass('alert').removeClass('alert-success');
                        $('.modalError').html("").removeClass('alert').removeClass('alert-danger');
                        $('.modalError').append(error).addClass('alert').addClass('alert-danger');
                    });
                }
            },

            showOrderModal(orderId,orderNumber,selectedOrderStatus,selectedDepartmentId){
                //alert(orderId);
                $('.editOrderModal-modal-xl').modal('show'); //The class of the modal to show
                $('.order_number').val(orderNumber);
                $('.order_status').val(selectedOrderStatus);
                this.orderStatus = selectedOrderStatus;
                this.selectedOrderId = orderId;
                this.selectedDepartmentId = selectedDepartmentId;
            },

            getOrders(){
                axios.get('/get-orders', {
                    //
                })
                .then(response => {
                    //console.log(response);
                    this.orders = response.data.orders;
                    if (this.orders){
                        $('.show_orders').addClass('show_scroll_bar');
                    }
                });
            },

            createOrder(){
                if (this.productNameId == "" || this.departmentId == "" || this.shiftId == ""){
                    alert("Please select proper item(s).");
                    return;
                }
                axios.post('/make-order',
                {
                    productNameId: this.productNameId,
                    departmentId: this.departmentId,
                    shiftId: this.shiftId,
                    orderNumber: this.orderNumber,
                })
                .then(response => {
                    //console.log(response);
                    this.errorMsg = response.data.errorMsg;
                    this.successMsg = response.data.successMsg;
                    if (!this.errorMsg){
                        this.orderNumber = "";
                        this.partNumber = "";
                        this.getOrders(); //display orders
                    }
                })
                .catch(error => {
                    //console.log(error);
                    this.errorMsg = error;
                });
            },
        },

        created() {
            axios.get('/order-justNames',{
                //
            })
            .then(response => {
                //console.log(response);
                //get 3 objects from ProductController
                this.justProductNames = response.data.justProductNames;
                this.justShifts = response.data.justShifts;
                this.justDepartments = response.data.justDepartments;
            })
            .catch(error => {
                console.log(error);
            });

            this.getOrders();
        },

        mounted() {

        }
    }
</script>
