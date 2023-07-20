<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4>Employee</h4>
                            </div>
                            <div class="col">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-primary  me-md-2" @click="create">Add New employee
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button @click="reload" type="button" class="btn btn-primary">Reload
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-3">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>Search By: </strong> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select v-model="queryField" class="form-control">
                                        <option value="firstname">First Name</option>
                                        <option value="lastname">Last Name</option>
                                        <option value="email">EMAIL</option>
                                        <option value="phone">PHONE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <input type="text" v-model="query" class="form-control" placeholder="Search">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Pagination -->
                        <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5"
                            @paginate="query === '' ? getData() : searchData()"></pagination>
                        <table class="table table-hover table-borderd table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>FIRSTNAME</th>
                                    <th>LASTNAME</th>
                                    <th>EMAIL</th>
                                    <th>PHONE</th>
                                    <th>COMPANY</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(employee, index) in employees" :key="employee.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ employee.firstname }}</td>
                                    <td>{{ employee.lastname }}</td>
                                    <td>{{ employee.email }}</td>
                                    <td>{{ employee.phone }}</td>
                                    <td>
                                        <span v-for="company in companies" :key="company.id" v-bind:value="company.id">
                                            <span v-if="company.id == employee.company_id">{{ company.name }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <button @click="edit(employee)" type="button" class="btn btn-primary">Edit</button>
                                        <button @click="destroy(employee)" type="button"
                                            class="btn btn-danger">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Pagination -->
                        <pagination v-if="pagination.last_page > 1" :pagination="pagination" :offset="5"
                            @paginate="query === '' ? getData() : searchData()"></pagination>
                    </div>
                </div>
            </div>
        </div>
        <vue-progress-bar></vue-progress-bar>
        <vue-snotify></vue-snotify>
        <!-- Button trigger modal -->
        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="createClose">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-danger justify-content-center m-3" role="alert" v-if="errorMessages">
                        <h4 class="alert-heading">Errors</h4>
                        <div v-for="(errorArray, idx) in errorMessages" :key="idx">
                            <span v-for="(allErrors, idxa) in errorArray" :key="idxa">
                                <span>{{ allErrors }} </span>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="store()" method="post">
                            <div class="form-group">
                                <label for="firstname">FirstName</label>
                                <input type="text" class="form-control" v-model="form.firstname" name="firstname"
                                    id="firstname" placeholder="Enter Your firstname" required>
                            </div>
                            <div class="form-group">
                                <label for="lastname">LastName</label>
                                <input type="text" class="form-control" v-model="form.lastname" name="lastname"
                                    id="lastname" placeholder="Enter Your lastname" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" v-model="form.email" name="email" id="email"
                                    placeholder="Enter Your email">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" v-model="form.phone" name="phone" id="phone"
                                    placeholder="Enter Your phone">
                            </div>
                            <div class="form-group" v-if="companies.length > 0">
                                <label for="company_id">Select Company</label>
                                <select name="company_id" id="company_id" class="form-control" v-model="form.company_id">
                                    <option v-for="company in companies" :key="company.id" v-bind:value="company.id">{{
                                        company.name
                                    }}</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    @click="createClose">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Edit Modal -->
        <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="editClose">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="alert alert-danger justify-content-center m-3" role="alert" v-if="errorMessages">
                        <h4 class="alert-heading">Errors</h4>
                        <div v-for="(errorArray, idx) in errorMessages" :key="idx">
                            <span v-for="(allErrors, idxa) in errorArray" :key="idxa">
                                <span>{{ allErrors }} </span>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="update()" method="post">
                            <div class="form-group">
                                <label for="firstname">FirstName</label>
                                <input type="text" class="form-control" v-model="form.firstname" name="firstname"
                                    id="firstname" placeholder="Enter Your firstname" required>
                            </div>
                            <div class="form-group">
                                <label for="lastname">LastName</label>
                                <input type="text" class="form-control" v-model="form.lastname" name="lastname"
                                    id="lastname" placeholder="Enter Your lastname" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" v-model="form.email" name="email" id="email"
                                    placeholder="Enter Your email">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" v-model="form.phone" name="phone" id="phone"
                                    placeholder="Enter Your phone">
                            </div>
                            <div class="form-group" v-if="companies.length > 0">
                                <label for="company_id">Select Company</label>
                                <select name="company_id" id="company_id" class="form-control" v-model="form.company_id">
                                    <option v-for="company in companies" :key="company.id" v-bind:value="company.id">{{
                                        company.name
                                    }}</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                    @click="editClose">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

import Form from 'vform'
export default {
    data() {
        return {
            errorMessages: '',
            query: "",
            queryField: "name",
            employees: [],
            companies: [],
            pagination: {
                current_page: 1,
            },
            form: new Form({
                id: "",
                firstname: "",
                lastname: "",
                email: "",
                phone: "",
                company_id: "",
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getData();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        // console.log('Component mounted.')
        this.getData();
    },
    methods: {
        getData() {
            this.$Progress.start()
            axios.get('/api/employee?page=' + this.pagination.current_page)
                .then(response => {
                    this.employees = response.data.data;
                    this.pagination = response.data.meta;
                    this.companies = response.data.companies;
                    this.$Progress.finish()
                })
                .catch(e => {
                    // console.log(e);
                    this.$Progress.fail()
                })
        },
        searchData() {
            this.$Progress.start()
            axios.get('/api/search/employee' + '/' + this.queryField + '/' + this.query + '?page=' + this.pagination.current_page)
                .then(response => {
                    this.employees = response.data.data;
                    this.pagination = response.data.meta;
                    // console.log(response)
                    this.$Progress.finish()
                })
                .catch(e => {
                    // console.log(e);
                    this.$Progress.fail()
                })
        },
        reload() {
            this.$Progress.start()
            this.getData();
            this.query = "";
            this.queryField = "name";
            this.$snotify.success("Data Successfully Refresh", "Success");
        },
        create() {
            this.form.reset();
            this.form.clear();
            this.errorMessages = '';
            $("#addModal").modal("show");
        },
        createClose() {
            this.form.reset();
            this.form.clear();
            this.errorMessages = '';
            $("#addModal").modal("hide");
        },
        store() {
            this.$Progress.start();
            this.form.busy = true;
            this.form
                .post('/api/employee')
                .then(response => {
                    this.getData();
                    $("#addModal").modal("hide");
                    if (this.form.successful) {
                        this.$Progress.finish();
                        this.$snotify.success("Employee Successfully Saved");
                    } else {
                        this.$Progress.fail();
                        this.$snotify.error(
                            "Something went wrong try again later.",
                            "Error"
                        );
                    }
                    // console.log(response);
                })
                .catch((error) => {
                    // console.log(error.response.data.errors);
                    if(error.response.data){
                        this.errorMessages = error.response.data;
                    }
                    this.$snotify.error(
                        "Something went wrong please check form errors.",
                        "Error"
                    );
                    // console.log('Error: ' + error);
                });
        },
        edit(employee) {
            this.form.reset();
            this.form.clear();
            this.errorMessages = '';
            this.form.fill(employee);
            $("#EditModal").modal("show");
        },
        editClose() {
            this.form.reset();
            this.form.clear();
            this.errorMessages = '';
            $("#EditModal").modal("hide");
        },
        update() {
            this.$Progress.start();
            this.form.busy = true;
            this.form
                .post('/api/employee/' + this.form.id)
                .then(response => {
                    this.getData();
                    $("#EditModal").modal("hide");
                    if (this.form.successful) {
                        this.$Progress.finish();
                        this.$snotify.success("Employee Update Successfully");
                    } else {
                        this.$Progress.fail();
                        this.$snotify.error(
                            "Something went wrong try again later.",
                            "Error"
                        );
                    }
                    // console.log(response);
                })
                .catch((error) => {
                    // console.log(error.response.data.errors);
                    if(error.response.data){
                        this.errorMessages = error.response.data;
                    }
                    this.$snotify.error(
                        "Something went wrong please check form errors.",
                        "Error"
                    );
                    // console.log('Error: ' + error);
                });
        },
        destroy(employee) {
            this.$snotify.clear();
            this.$snotify.confirm(
                'You want to delete Employee : ' + employee.name,
                'Are You Sure ?',
                {
                    showProgressBar: false,
                    closeOnClick: false,
                    pauseOnHover: true,
                    buttons: [
                        {
                            text: 'Yes',
                            action: (toast) => {
                                this.$snotify.remove(toast.id);
                                this.$Progress.start();
                                this.form
                                    .delete('/api/employee/' + employee.id)
                                    .then(response => {
                                        this.getData();
                                        this.$Progress.finish();
                                        this.$snotify.success("Employee Deleted Successfully");
                                        // console.log(response);
                                    })
                                    .catch(function (error) {
                                        // console.log(error);
                                    });
                            },
                            bold: true
                        },
                        // { text: 'No', action: () => console.log('Clicked: No') },
                        // { text: 'Later', action: (toast) => { console.log('Clicked: Later'); this.$snotify.remove(toast.id); } },
                        {
                            text: 'No', action: (toast) => {
                                // console.log('Clicked: No'); 
                                this.$snotify.remove(toast.id);
                            },
                            bold: true
                        },
                    ]
                });
        }

    },
}
</script>
