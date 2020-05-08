<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Create Machine
                </h4>
            </div>
        </div>
        <form @submit.prevent="createMachine()" class="mt-3">
            <div class="form-group row">
                <label for="MachineAndModel" class="col-md-3 col-form-label">Machine And Model Name</label>
                <div class="col-md-4">
                    <input type="text" name="machineName" v-model="machineName" class="form-control" required>
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

        <div class="row"><!-- display existing machines -->
            <div class="show_machines offset-md-2 col-md-6">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr v-for="machine in machines" :key="machine.index" >
                                <td>{{ machine.name }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" @click="deleteMachine(machine.id)">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- end table-responsive -->
            </div>
        </div><!-- display existing machines -->
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
                machineName: "",
                machines: {},
            }
        },

        methods: {
            getMachines(){
                axios.get('/get-machines',{
                //
                })
                .then(response => {
                //console.log(response);
                this.machines = response.data.machines;
                    if (this.machines){
                        $('.show_machines').addClass('show_scroll_bar');
                    }
                })
                .catch(error => {
                    //console.log(error);
                });
            },

            createMachine(){
                axios.post('/create-machine',
                {
                    machineName: this.machineName,
                })
                .then(response => {
                    //console.log(response);
                    this.errorMsg = "";
                    this.successMsg = "";
                    this.errorMsg = response.data.errorMsg;
                    this.successMsg = response.data.successMsg;
                    if (!this.errorMsg){
                        this.machineName = "";
                        this.getMachines(); //display machines
                    }
                })
                .catch(error => {
                    this.errorMsg = "";
                    this.successMsg = "";
                    //console.log(error);
                    this.errorMsg = error;
                });
            },

            deleteMachine(machineId){
                var message = "Do you want to delete this machine?";
                var result = confirm(message);
                if (result == true) {
                    axios.post('/delete-machine',
                    {
                        machineId: machineId,
                    })
                    .then(response => {
                        //console.log(response);
                        this.errorMsg = "";
                        this.successMsg = "";
                        this.errorMsg = response.data.errorMsg;
                        this.successMsg = response.data.successMsg;
                        if (!this.errorMsg){
                            this.machineName = "";
                            this.getMachines(); //display machines
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
            this.getMachines();
        },
        mounted() {

        }
    }
</script>
