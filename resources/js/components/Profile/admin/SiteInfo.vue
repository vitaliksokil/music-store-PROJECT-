<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-primary">
                    <tbody>
                    <tr v-for="(value,key) in siteInfo">
                        <th>{{key}}</th>
                        <td>{{value}}</td>

                    </tr>
                    </tbody>
                </table>
                <button class="btn btn-primary" :title="'Edit'"
                        data-toggle="modal" data-target="#editMode"

                >
                    <i class="fas fa-edit"></i> Edit site info
                </button>

                <div class="modal fade" id="editMode" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">Edit site info</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="submitEditInfo">

                                    <div class="form-group" v-for="(value,key) in siteInfo">
                                        <label :for="key">{{key}}</label>
                                        <input v-if="key !== 'logo'" type="text" class="form-control" :id="key"
                                               v-model="siteInfo[key]" :class="{'is-invalid':errors[key] }">
                                        <input v-else type="file" class="form-control" id="logo"
                                              @change="onAttachmentChange" :class="{'is-invalid':errors[key] }">
                                        <p style="color:red" v-if="errors[key]">{{errors[key][0]}}</p>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SiteInfo",
        data() {
            return {
                siteInfo: {},
                errors:{},

            }
        },
        methods: {
            getSiteInfo() {
                this.axios.get('/api/site-info').then((response) => {
                    this.siteInfo = response.data;
                    delete this.siteInfo.id;
                })
            },
            submitEditInfo(){
                this.$Progress.start();
                this.axios.put('/api/site-info',this.siteInfo).then((response)=>{
                    let message = response.data.message;
                    let messageType = response.data.messageType;
                    if (messageType === 'success') {
                        this.$Progress.finish();
                        Swal.fire({
                            position: 'top-end',
                            type: messageType,
                            title: message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                        $('#editMode').modal('hide');
                        Event.$emit('siteInfoUpdated');
                    }else{
                        this.$Progress.fail();
                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                }).catch(({response})=>{
                    this.$Progress.fail();
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                    this.errors = response.data.errors;
                })
            },
            onAttachmentChange(e) {
                let file = e.target.files[0];
                let reader = new FileReader();
                if(!file.type.match(/image/)){
                    this.errors.logo[0]='File should be an image';
                    this.siteInfo.logo = '';
                    return ;
                }
                if(file.size/1024 > 2048){
                    this.errors.logo[0]= 'Size should be less then 2048KB';
                    this.siteInfo.logo = '';
                    return ;
                }
                let ap = this;
                reader.onloadend = function (file) {
                    ap.siteInfo.logo = reader.result;
                };
                reader.readAsDataURL(file);
            },
        },
        mounted() {
            this.getSiteInfo();
            let si = this;
            $('#editMode').on('hide.bs.modal', function (e) {
                si.getSiteInfo();
                $('#logo').val('');
            })
        }
    }
</script>

<style scoped>

</style>
