<template>
    <section class="hold-transition skin-yellow sidebar-mini">
        <div class="wrapper">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <li>
                            <router-link :to="{name:'profile'}">
                                <!-- Sidebar user panel (optional) -->

                                <div class="pull-left info user-name ">
                                    <p><i class="fas fa-user"></i> {{user.name}} </p>
                                    <!-- Status -->
                                    <a><i class="fa fa-circle text-success"></i> Online</a>
                                </div>
                            </router-link>
                        </li>
                        <li class="header">MAIN NAVIGATION</li>

                        <!-- Optionally, you can add icons to the links -->
                        <li>
                            <router-link :to="{name:'profile-settings'}"><i class="fas fa-cog cyan"></i> <span>Profile settings</span>
                            </router-link>
                        </li>
                        <li>

                        </li>
                        <li v-if="$gate.isAdmin()">
                            <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                               aria-controls="collapseExample" @click.prevent="isOpenMenu = !isOpenMenu">
                                <i class="fas fa-cogs orange"></i>
                                <span>Products management</span>
                                <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right" :class="{'rotate':isOpenMenu}"></i>
                                      </span>
                            </a>
                            <ul class="collapse sidebar-menu" id="collapseExample">
                                <li>
                                    <router-link :to="{name:'profile-products'}"><i class="fas fa-wrench pink"></i>
                                        <span>Products</span>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link :to="{name:'profile-add-product'}"><i class="fas fa-plus green"></i>
                                        <span>Add product</span>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content">
                    <div class="container" v-if="this.$route.fullPath ==='/profile'">
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table table-primary">
                                    <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{user.name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{user.email}}</td>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <td v-if="user.type == '1'">Admin</td>
                                        <td v-else>User</td>
                                    </tr>
                                    <tr>
                                        <th>Account created at</th>
                                        <td>{{user.created_at}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <router-view :userData="user"></router-view>
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->


            <!-- Add the sidebar's background. This div must be placed
                 immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>

        <!-- ./wrapper --></section>
</template>

<script>

    export default {
        name: "Profile",
        data() {
            return {
                isOpenMenu:false,
                user: {
                    name: '',
                    email: '',
                    type: '',
                    created_at: '',
                },
            }
        },
        methods: {
            getUser() {
                this.axios.get('/api/get-user').then(({data}) => {
                    this.user.name = data.name;
                    this.user.email = data.email;
                    this.user.type = data.type;
                    this.user.created_at = data.created_at;
                });
            },
        },
        mounted() {
            this.getUser();
        },


    }
</script>

<style scoped>
    .router-link-exact-active {
        border-left: 3px solid transparent;
        border-left-color: #f39c12 !important;

    }

    .user-name {
        white-space: normal;
    }
    .management-menu{
        padding-left: 5px;
        background: rgba(255,255,255,0.1);
    }
    .rotate{
        transform: rotate(-90deg);
    }
</style>
