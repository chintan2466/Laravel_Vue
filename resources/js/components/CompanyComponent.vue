<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4>Company</h4>
                            </div>
                            <div class="col">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-primary  me-md-2" @click="create">Add New company
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
                                        <option value="name">Name</option>
                                        <option value="email">EMAIL</option>
                                        <option value="website">WEBSITE</option>
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
                                    <th>LOGO</th>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>WEBSITE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(company, index) in companies" :key="company.id">
                                    <td>{{ index + 1 }}</td>
                                    <td v-if="company.logo != null && company.logo !== ''">
                                        <img style="width:120px;height: 120px;" :src="'../storage/company/' + company.logo"
                                            :alt="company.logo">
                                    </td>
                                    <td v-else>
                                        <img style="width:120px;height: 120px;" :src="'../storage/company/defaultLogo.jpg'"
                                            :alt="company.logo">
                                    </td>
                                    <td>{{ company.name }}</td>
                                    <td>{{ company.email }}</td>
                                    <td>{{ company.website }}</td>
                                    <td>
                                        <button @click="edit(company)" type="button" class="btn btn-primary">Edit</button>
                                        <button @click="destroy(company)" type="button"
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
                        <h5 class="modal-title" id="exampleModalLabel">Add Company</h5>
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
                                <label for="name">Name</label>
                                <input type="text" class="form-control" v-model="form.name" name="name" id="name"
                                    placeholder="Enter Your Name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" v-model="form.email" name="email" id="email"
                                    placeholder="Enter Your email">
                            </div>
                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="text" class="form-control" v-model="form.website" name="website" id="website"
                                    placeholder="Enter Your Website">
                            </div>
                            <div class="mt-3">
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <input type="file" @change="onFileChange" class="form-control-file" name="logo"
                                        id="logo" placeholder="logo">
                                    <img class="mt-3" style="width:150px;" :src="logoPreview" alt="" id="addSrc">
                                </div>
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
                        <h5 class="modal-title" id="exampleModalLabel">Edit Company</h5>
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
                                <label for="name">Name</label>
                                <input type="text" class="form-control" v-model="form.name" name="name" id="name"
                                    placeholder="Enter Your Name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" v-model="form.email" name="email" id="email"
                                    placeholder="Enter Your email">
                            </div>
                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="text" class="form-control" v-model="form.website" name="website" id="website"
                                    placeholder="Enter Your website">
                            </div>
                            <div class="mt-3">
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <input type="file" @change="onFileChange" class="form-control-file" name="logo"
                                        id="logo" placeholder="logo">
                                    <img class="mt-3"
                                        v-bind:src="logoPreview == null ? '../storage/company/' + form.logo : logoPreview"
                                        width="100" height="100" id="editSrc" />
                                </div>
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
            logoPreview: null,
            query: "",
            queryField: "name",
            companies: [],
            pagination: {
                current_page: 1,
            },
            form: new Form({
                id: "",
                name: "",
                email: "",
                website: "",
                logo: "",
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
            axios.get('/api/company?page=' + this.pagination.current_page)
                .then(response => {
                    this.companies = response.data.data;
                    this.pagination = response.data.meta;
                    // console.log(response)
                    this.$Progress.finish()
                })
                .catch(e => {
                    // console.log(e);
                    this.$Progress.fail()
                })
        },
        searchData() {
            this.$Progress.start()
            axios.get('/api/search/company' + '/' + this.queryField + '/' + this.query.trim() + '?page=' + this.pagination.current_page)
                .then(response => {
                    this.companies = response.data.data;
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
            this.form.logo = '';
            this.logoPreview = '';
            this.errorMessages = '';
            $("#addSrc").attr('src', '');
            $("#addModal").modal("show");
        },
        createClose() {
            this.form.reset();
            this.form.clear();
            this.form.logo = '';
            this.logoPreview = '';
            this.errorMessages = '';
            $("#addSrc").attr('src', '');
            $("#addModal").modal("hide");
        },
        onFileChange(event) {
            this.form.logo = event.target.files[0];
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                this.logoPreview = reader.result;
            }.bind(this), false);
            if (this.form.logo) {
                if (/\.(jpe?g|png|gif)$/i.test(this.form.logo.name)) {
                    reader.readAsDataURL(this.form.logo);
                } else {
                    this.form.logo = '';
                    this.logoPreview = '../storage/company/defaultLogo.jpg';
                }
            } else {
                this.form.logo = '';
                this.logoPreview = '../storage/company/defaultLogo.jpg';
            }
        },
        store() {
            this.$Progress.start();
            this.form.busy = true;
            this.form
                .post('/api/company')
                .then(response => {
                    this.getData();
                    $("#addModal").modal("hide");
                    if (this.form.successful) {
                        this.$Progress.finish();
                        this.$snotify.success("Company Successfully Saved");
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
                    // console.log(error.response);
                    if (error.response.data) {
                        this.errorMessages = error.response.data;
                    }
                    this.$snotify.error(
                        "Something went wrong please check form errors.",
                        "Error"
                    );
                    // console.log('Error: ' + error);
                });

        },
        edit(company) {
            this.form.reset();
            this.form.clear();
            this.form.logo = '';
            this.logoPreview = '';
            // $("#editSrc").attr('src','');
            this.form.fill(company);
            this.errorMessages = '';
            setTimeout(() => {
                $("#EditModal").modal("show");
                if (company.logo != '') {
                    this.logoPreview = '../storage/company/'+ company.logo;
                } else {
                    this.logoPreview = '../storage/company/defaultLogo.jpg';
                }
            }, 200);


        },
        editClose() {
            this.form.reset();
            this.form.clear();
            this.form.logo = '';
            this.logoPreview = '';
            $("#editSrc").attr('src', '');
            this.errorMessages = '';
            $("#EditModal").modal("hide");
        },
        update() {
            this.$Progress.start();
            this.form.busy = true;
            this.form
                .post('/api/company/' + this.form.id)
                .then(response => {
                    this.getData();
                    $("#EditModal").modal("hide");
                    if (this.form.successful) {
                        this.$Progress.finish();
                        this.$snotify.success("Company Update Successfully");
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
                    if (error.response.data) {
                        this.errorMessages = error.response.data;
                    }
                    this.$snotify.error(
                        "Something went wrong please check form errors.",
                        "Error"
                    );
                    console.log('Error: ' + error);
                });
        },
        destroy(company) {
            this.$snotify.clear();
            this.$snotify.confirm(
                'You want to delete Company : ' + company.name,
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
                                    .delete('/api/company/' + company.id)
                                    .then(response => {
                                        this.getData();
                                        this.$Progress.finish();
                                        this.$snotify.success("Company Deleted Successfully");
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
