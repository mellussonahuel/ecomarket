// import axios from 'axios'
// import Cookies from 'js-cookie'

const permissions = {

  data () {
    return {}
  },
  created: function () {
  },
  methods: {
    can: function (permission) {
      let currentUser = this.$store.state.auth.user
      let can = false
      currentUser.roles.forEach((role) => {
        role.permissions.forEach((userPermission) => {
          if (userPermission.name === permission) {
            can = true
          }
        })
      })
      return can
    },
    hasRole: function (roleName) {
      let currentUser = this.$store.state.auth.user
      let can = false
      currentUser.roles.forEach((role) => {
        if (role.name === roleName) {
          can = true
        }
      })
      return can
    },
    hasAnyRole: function (roles) {
      let currentUser = this.$store.state.auth.user
      let can = false
      currentUser.roles.forEach((role) => {
        if (roles.indexOf(role.name) !== -1) {
          can = true
        }
      })
      return can
    },
    getCurrentRoles: function () {
      let roles = []
      let currentUser = this.$store.state.auth.user
      if (currentUser.roles) {
        currentUser.roles.forEach((role) => {
          roles.push(role.name)
        })
      }
      return roles
    },
    debugMode: function () {
      return process.env.MIX_APP_DEBUG === 'true'
    }
    // loginAs (userId) {
    //   axios.post('/api/login-as', { user_id: userId }).then((response) => {
    //     if (response.data.token) {
    //       Cookies.remove('token')
    //       Cookies.set('token', response.data.token, { expires: response.data.remember ? 365 : null })
    //       window.location.href = '/'
    //     }
    //   }).catch((error) => {
    //     console.log('LOG Error', error)
    //   })
    // },
    // loginAsIcon () {
    //   return '&#8669;'
    // }
  }

}

export default permissions
