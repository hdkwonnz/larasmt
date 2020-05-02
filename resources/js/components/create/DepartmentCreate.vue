<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Create Department
                </h4>
            </div>
        </div>
        <form @submit.prevent="createDepartment()" class="mt-3">
            <div class="form-group row">
                <label for="DepartmentName" class="col-md-2 col-form-label">Department Name</label>
                <div class="col-md-4">
                    <input type="text" name="departmentName" v-model="departmentName" class="form-control" required>
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

        <div class="row"><!-- display existing departments -->
            <div class="show_departments offset-md-2 col-md-4">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr v-for="department in departments" :key="department.index" >
                                <td>{{ department.name }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" @click="deleteDepartment(department.id)">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- end table-responsive -->
            </div>
        </div><!-- display existing department -->
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
                departmentName: "",
                departments: {},
            }
        },

        methods: {
            getDepartments(){
                axios.get('/get-departments',{
                //
                })
                .then(response => {
                //console.log(response);
                this.departments = response.data.departments;
                    if (this.departments){
                        $('.show_departments').addClass('show_scroll_bar');
                    }
                })
                .catch(error => {
                    //console.log(error);
                });
            },

            createDepartment(){
                axios.post('/create-department',
                {
                    departmentName: this.departmentName,
                })
                .then(response => {
                    //console.log(response);
                    this.errorMsg = "";
                    this.successMsg = "";
                    this.errorMsg = response.data.errorMsg;
                    this.successMsg = response.data.successMsg;
                    if (!this.errorMsg){
                        this.departmentName = "";
                        this.getDepartments(); //display departments
                    }
                })
                .catch(error => {
                    this.errorMsg = "";
                    this.successMsg = "";
                    //console.log(error);
                    this.errorMsg = error;
                });
            },

            deleteDepartment(departmentId){
                var message = "Do you want to delete this department?";
                var result = confirm(message);
                if (result == true) {
                    axios.post('/delete-department',
                    {
                        departmentId: departmentId,
                    })
                    .then(response => {
                        //console.log(response);
                        this.errorMsg = "";
                        this.successMsg = "";
                        this.errorMsg = response.data.errorMsg;
                        this.successMsg = response.data.successMsg;
                        if (!this.errorMsg){
                            this.departmentName = "";
                            this.getDepartments(); //display departments
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
            this.getDepartments();
        },
        mounted() {

        }
    }
</script>
