<template>
    <section class="hold-transition skin-yellow sidebar-mini">
        <div class="wrapper">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <div class="user-name">
                        <!-- Sidebar user panel (optional) -->
                        <div class="pull-left info">
                            <p>name</p>
                            <!-- Status -->
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <!-- Optionally, you can add icons to the links -->
                        <li class="active"><a href="#"><i class="fa fa-link"></i> <span>Link</span></a></li>
                        <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
                                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="#">Link in level 2</a></li>
                                <li><a href="#">Link in level 2</a></li>
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

    }
</script>

<style scoped>

</style>
