<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h4>
                    Users Profile
                </h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="alert alert-danger" v-if="errorMsg">
                    <span >{{ errorMsg }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="alert alert-success" v-if="successMsg">
                    <span >{{ successMsg }}</span>
                </div>
            </div>
        </div>

        <!-- display existing parts -->
        <div class="row mt-3">
            <div class="show_users col-md-11">
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr v-for="user in users" :key="user.index" >
                                <td><strong>{{ user.name}}({{ user.id }})</strong></td>
                                <td>{{ user.email  }}</td>
                                <td>{{ user.role }}</td>
                                <td>{{ user.email_verified_at | myDate }}</td>
                                <!-- myDate => momentjs => resources/js/app.js -->
                                <td>{{ user.created_at | myDate }}</td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" @click="showUserModal(user.id,user.name,user.role,user.email_verified_at)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- end table-responsive -->
            </div>
        </div><!-- display existing feeders -->

        <!-- Modal -->
        <div class="modal fade editUserModal-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="offset-md-5 col-md-3">
                            <h4 class="modal-title" id="exampleModalLabel">Edit user</h4>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body offset-md-2 col-md-10">
                        <div class="form-group row">
                            <label for="currentUserName" class="col-md-2 col-form-label">User Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control user_name" :value="currentUserName" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="currentUserRole" class="col-md-2 col-form-label">User Role</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control user_role" :value="currentUserRole" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="currentVerifiedAt" class="col-md-2 col-form-label">Verified At</label>
                            <div class="col-md-6">
                                <!-- myDate => momentjs => resources/js/app.js -->
                                <input type="text" class="form-control user_verified" :value="currentVerifiedAt | myDate" readonly>
                            </div>
                        </div>
                        <!-- form -->
                        <form id="edituserForm" @submit.prevent="editUser()" class="mt-4">
                            <div class="form-group row">
                                <label for="UserRole" class="col-md-2 col-form-label">User Role</label>
                                <div class="col-md-3">
                                    <select class="form-control" v-model="userRole" :required="true">
                                        <option v-for="option in options" :key="option.index" :value="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="verifiedAt" class="col-md-2 col-form-label">Verified At</label>
                                <div class="col-md-6">
                                    <input id="verifiedAt" type="datetime-local" class="form-control" v-model="verifiedAt">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="checkBox" class="col-md-2 col-form-label">Clear Verified At</label>
                                <div class="col-md-1">
                                    <input type="checkbox" class="form-control" v-model="isChecked" true-value="yes" false-value="no">
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
                            <div class="offset-md-2 col-md-6">
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
                    { text: 'admin', value: 'admin' },
                    { text: 'editor', value: 'editor' },
                    { text: 'user', value: 'user' },
                ],
                isChecked: "",
                currentUserName: "",
                currentUserRole: "",
                currentVerifiedAt: "",
                verifiedAt: "",
                userRole: "",
                selectedUserId: "",
                modalMessage: null,
                modalError: null,
                successMsg: null,
                errorMsg: null,
                users: {},
            }
        },

        methods: {
            editUser(){
                //alert(this.selectedUserId);
                var message = "Do you want to edit this user?";
                var result = confirm(message);
                if (result == true) {
                    axios.post('/edit-user',
                    {
                        userId: this.selectedUserId,
                        userRole: this.userRole,
                        verifiedAt: this.verifiedAt,
                        isChecked: this.isChecked,
                    })
                    .then(response => {
                        //console.log(response);
                        $('.modalMessage').html("").removeClass('alert').removeClass('alert-success');
                        $('.modalError').html("").removeClass('alert').removeClass('alert-danger');
                        this.modalError = response.data.modalError;
                        if (this.modalError){
                            $('.modalError').append(this.modalError).addClass('alert').addClass('alert-danger')
                        }else{
                            this.modalMessage = response.data.modalMessage;
                            $('.modalMessage').append(this.modalMessage).addClass('alert').addClass('alert-success');
                            this.getUsers();
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

            showUserModal(userId,userName,userRole,userVerfied){
                $('.editUserModal-modal-xl').modal('show'); //The class of the modal to show
                //$('.user_role').val(userRole);
                this.currentUserRole = userRole;
                this.currentVerifiedAt = userVerfied;
                this.currentUserName = userName;
                this.userRole = userRole;
                this.isChecked = 'no';
                this.verifiedAt = null;
                this.selectedUserId = userId;
            },

            getUsers(){
                axios.get('/get-users', {

                })
                .then(response => {
                    //console.log(response);
                    this.users = response.data.users;
                    if (this.users){
                        $('.show_users').addClass('show_scroll_bar');
                    }
                });
            },
        },

        created() {
                this.getUsers();
        },

        mounted() {

        }
    }
</script>
