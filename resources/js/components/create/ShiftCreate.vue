<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Create ShiftWork
                </h4>
            </div>
        </div>
        <form @submit.prevent="createShift()" class="mt-3">
            <div class="form-group row">
                <label for="shiftName" class="col-md-2 col-form-label">Shift Name</label>
                <div class="col-md-4">
                    <input type="text" name="shiftName" v-model="shiftName" class="form-control" required>
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

        <div class="row"><!-- display existing shifts -->
            <div class="show_shifts offset-md-2 col-md-4">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr v-for="shift in shifts" :key="shift.index" >
                                <td>{{ shift.name }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" @click="deleteShift(shift.id)">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- end table-responsive -->
            </div>
        </div><!-- display existing shift -->
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
                shiftName: "",
                shifts: {},
            }
        },

        methods: {
            getShifts(){
                axios.get('/get-shifts',{
                //
                })
                .then(response => {
                //console.log(response);
                this.shifts = response.data.shifts;
                    if (this.shifts){
                        $('.show_shifts').addClass('show_scroll_bar');
                    }
                })
                .catch(error => {
                    //console.log(error);
                });
            },

            createShift(){
                axios.post('/create-shift',
                {
                    shiftName: this.shiftName,
                })
                .then(response => {
                    //console.log(response);
                    this.errorMsg = "";
                    this.successMsg = "";
                    this.errorMsg = response.data.errorMsg;
                    this.successMsg = response.data.successMsg;
                    if (!this.errorMsg){
                        this.shiftName = "";
                        this.getShifts(); //display shifts
                    }
                })
                .catch(error => {
                    this.errorMsg = "";
                    this.successMsg = "";
                    //console.log(error);
                    this.errorMsg = error;
                });
            },

            deleteShift(shiftId){
                var message = "Do you want to delete this shift work?";
                var result = confirm(message);
                if (result == true) {
                    axios.post('/delete-shift',
                    {
                        shiftId: shiftId,
                    })
                    .then(response => {
                        //console.log(response);
                        this.errorMsg = "";
                        this.successMsg = "";
                        this.errorMsg = response.data.errorMsg;
                        this.successMsg = response.data.successMsg;
                        if (!this.errorMsg){
                            this.shiftName = "";
                            this.getShifts(); //display shifts
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
            this.getShifts();
        },
        mounted() {

        }
    }
</script>
