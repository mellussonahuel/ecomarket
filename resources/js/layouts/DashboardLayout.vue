<template>
    <div class="admin-layout">
        <SidebarDashboard/>
        <div class="admin-content">
         
            <div class="container-fluid">
                <child/>
                
            </div>
           
        </div>
        
    </div>

</template>

<script>
    import NavbarDashboard from './partials/NavbarDashboard'
    import SidebarDashboard from './partials/SidebarDashboard'
   
    
   

    export default {
        name: 'DashboardLayout',

        components: {
            SidebarDashboard,
        },

        data: () => ({
            appName: window.config.appName,
        }),

        watch: {
            showNotifications () {
          
            }
        }

    }
</script>
