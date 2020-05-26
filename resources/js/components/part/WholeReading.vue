<template>
    <div>
        <!--show orders and select-->
        <div v-if="hideSelectSw">
            <div class="row">
                <div class="col-md-12">
                    <h4>
                        Check All Parts
                    </h4>
                </div>
            </div>

            <form @submit.prevent="initialReading()" class="mt-3" >
                <div class="form-group row">
                    <label for="orderNmber" class="col-md-2 col-form-label">Order Numbers</label>
                    <div class="col-md-6">
                        <select class="form-control" v-model="orderNumber">
                            <option disabled value="">Please select one</option>
                            <option v-for="order in orders" :key="order.id" :value="order.order_number">{{ order.order_number }} - {{ order.product_name }} - {{ order.department_name }} - {{ order.shift_name }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-5">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-primary w-100">
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
        </div><!--end of show orders and select-->

        <!--wholeReading-->
        <div v-if="showWholeReadingSw">
            <!-- head -->
            <div class="row bg-success pt-2 top_menu">
                <div class="col-md-3">
                    <h2 class="">
                        {{ orderFeeder.product_name }}
                    </h2>
                </div>
                <div class="col-md-2">
                    <h2 class="">
                        {{ orderFeeder.department_name }}
                    </h2>
                </div>
                <div class="col-md-2">
                    <h2 class="">
                        {{ orderFeeder.shift_name }}
                    </h2>
                </div>
                <div class="col-md-2">
                    <h2 class="blinking text-danger">
                        Running
                    </h2>
                </div>
                <div class="col-md-3">
                    <h2 class="">
                        Order#: {{ orderFeeder.order_number }}
                    </h2>
                </div>
            </div><!-- end of head -->

            <!-- body -->
            <div class="row mt-5">
                <!-- Prev -->
                <div class="col-md-2">
                    <div class="alert-success pt-3" v-if="previous">
                        <div class="text-center bg-success text-white">
                            <span class="">Previous #</span>
                        </div>
                        <div class="text-center mt-2">
                            <span class="">{{ previous.machine_name }}</span>
                        </div>
                        <div class="display-4 text-center">
                            <span class="">{{ previous.feeder_number }}</span>
                            <span>{{ previous.position }}</span>
                        </div>
                        <!--  previous form -->
                        <form @submit.prevent="previousReading()">
                            <div class="form-group row mt-5 pb-3">
                                <div class="offset-md-2 col-md-8">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Prev
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- end of Prev -->

                <!-- Current -->
                <!-- to protect right click on mouse => oncontextmenu="return false;" -->
                <div oncontextmenu="return false;" class="col-md-8">
                    <div class="alert alert-light">
                        <div class="text-center bg-dark text-white">
                            <span class="">Current #</span>
                        </div>
                        <div class="mt-3">
                            <h1 class="machine_name">{{ orderFeeder.machine_name }}</h1>
                        </div>
                        <div class="display-4 text-center">
                            <span class="bg-dark text-white">{{ orderFeeder.feeder_number }}</span>
                            <span class="bg-dark text-white">{{ orderFeeder.position }}</span>
                        </div>
                        <div class="form-group row">
                            <label for="ownPartNumber" class="col-md-2 col-form-label">Part Number</label>
                            <div class="col-md-10">
                                <span class="form-control own_partnumber bg-secondary text-white"><strong>{{ orderFeeder.own_partnumber }}</strong></span>
                            </div>
                        </div>

                        <!-- currentReading form -->
                        <form @submit.prevent="currentReading()" class="mt-4">
                            <div class="form-group row">
                                <label for="partNumber" class="col-md-2 col-form-label">Part Number</label>
                                <div class="col-md-10">
                                    <input id="partNumber" type="text" class="form-control" name="partNumber" v-model="partNumber" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row mt-5">
                                <div class="col-md-10 offset-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Click
                                    </button>
                                </div>
                            </div>
                        </form>
                        <!-- error message-->
                        <div class="row">
                            <div class="offset-md-2 col-md-6 ">
                                <span class="text-success display-4">
                                    {{ currentMessage }}
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-md-2 col-md-6">
                                <span class="text-danger display-4">
                                    {{ currentError }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end of Current -->

                <!-- Next -->
                <div class="col-md-2">
                    <div class="alert-success pt-3" v-if="next">
                        <div class="text-center bg-danger text-white">
                            <span class="">Next #</span>
                        </div>
                        <div class="text-center mt-2">
                            <span class="">{{ next.machine_name }}</span>
                        </div>
                        <div class="display-4 text-center">
                            <span class="">{{ next.feeder_number }}</span>
                            <span>{{ next.position }}</span>
                        </div>
                        <!-- next form -->
                        <form @submit.prevent="nextReading()">
                            <div class="form-group row mt-5 pb-3">
                                <div class="offset-md-2 col-md-8">
                                    <button type="submit" class="btn btn-primary next_button w-100">
                                        Next
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- end of Next -->
            </div><!-- end of body -->
        </div><!--end of wholeReading-->
    </div>
</template>

<script>
    export default {
        props:['user_email',
                'user_name'
        ],

        data(){
            return{
                currentMessage: "",
                currentError: "",
                partNumber: "",
                showWholeReadingSw: false,
                hideSelectSw: true,
                orderId: "",
                orderNumber: "",
                feederId: "",
                successMsg: null,
                errorMsg: null,
                orders: {},
                orderFeeder: {},
                previous: {},
                next: {},
                nextId: "",
                nextOrderNumber: "",
                previousId: "",
                previousOrderNumber: "",
            }
        },

        methods: {
            getOrders(){
                axios.get('/part/getOrders',{
                //
                })
                .then(response => {
                //console.log(response);
                this.orders = response.data.orders;
                })
                .catch(error => {
                    //console.log(error);
                });
            },

            initialReading(){
                if (!this.orderNumber){
                    this.errorMsg = "";
                    this.successMsg = "";
                    this.errorMsg = "Please, select order number."
                    return;
                }
                axios.get('/wholeReading', {
                    params: {
                                orderNumber: this.orderNumber
                            }
                })
                .then(response => {
                    //console.log(response);
                    this.errorMsg = "";
                    this.successMsg = "";
                    if (response.data.errorMsg){
                        this.errorMsg = response.data.errorMsg;
                    }else{
                        this.orderFeeder = response.data.orderFeeder;
                        this.feederId = this.orderFeeder.id;
                        this.previous = response.data.previous;
                        if (this.previous){
                            this.previousId = this.previous.id;
                            this.previousOrderNumber = this.previous.order_number;
                        }
                        this.next = response.data.next;
                        if (this.next){
                            this.nextId = this.next.id;
                            this.nextOrderNumber = this.next.order_number;
                        }
                        this.hideSelectSw = false;
                        this.showWholeReadingSw = true;
                    }
                })
                .catch(error => {
                    //console.log(error);
                    this.errorMsg = "";
                    this.successMsg = "";
                    this.errorMsg = error;
                })
            },

            currentReading(){
                axios.post('/refill',
                {
                    scannedType: 'whole',
                    feederId: this.feederId,
                    partNumber: this.partNumber
                })
                .then(response => {
                    //console.log(response);
                    this.currentMessage = "";
                    this.currentError = "";
                    if (response.data == "good"){
                        this.currentMessage = "OK";
                        this.partNumber = "";
                        setTimeout(() => {  $('.refillModal-modal-xl').modal('hide'); }, 1000);//auto hide
                        $('.next_button').trigger('click');//go to next
                    }else{
                        this.currentError = "NOK";
                    }
                })
                .catch(error => {
                    //console.log(error);
                    this.currentMessage = "";
                    this.currentError = "";
                    this.currentError = error;
                });
            },

            nextReading(){
                axios.get('/wholeReading', {
                    params: {
                                orderNumber: this.nextOrderNumber,
                                orderId: this.nextId
                            }
                })
                .then(response => {
                    //console.log(response);
                    this.orderFeeder = response.data.orderFeeder;
                    this.feederId = this.orderFeeder.id;
                    this.previous = response.data.previous;
                    if (this.previous){
                        this.previousId = this.previous.id;
                        this.previousOrderNumber = this.previous.order_number;
                    }
                    this.next = response.data.next;
                    if (this.next){
                        this.nextId = this.next.id;
                        this.nextOrderNumber = this.next.order_number;
                    }
                    this.hideSelectSw = false;
                    this.showWholeReadingSw = true;
                })
                .catch(error => {
                    //console.log(error);
                })
            },

            previousReading(){
                axios.get('/wholeReading', {
                    params: {
                                orderNumber: this.previousOrderNumber,
                                orderId: this.previousId
                            }
                })
                .then(response => {
                    //console.log(response);
                    this.orderFeeder = response.data.orderFeeder;
                    this.feederId = this.orderFeeder.id;
                    this.previous = response.data.previous;
                    if (this.previous){
                        this.previousId = this.previous.id;
                        this.previousOrderNumber = this.previous.order_number;
                    }
                    this.next = response.data.next;
                    if (this.next){
                        this.nextId = this.next.id;
                        this.nextOrderNumber = this.next.order_number;
                    }
                    this.hideSelectSw = false;
                    this.showWholeReadingSw = true;
                })
                .catch(error => {
                    //console.log(error);
                })
            },
        },

        created() {
            this.getOrders();
        },
        mounted() {

        }
    }
</script>
