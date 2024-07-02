function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

let AuthRoutes = [
  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },
]

let AppChildrenSharedPaths = [
  { path: '', redirect: { name: 'website.home' } },
  { path: 'home', name: 'website.home', component: page('website/home.vue') },
  { path: 'perfil', name: 'website.profile', component: page('website/profile.vue') },
  { path: 'contacto', name: 'website.contact', component: page('website/contact.vue') },
  { path: 'quienes-somos', name: 'website.about', component: page('website/about.vue') },
  { path: 'shop/:page?/:term?', name: 'website.shop', component: page('website/shop.vue')},
  { path: 'favoritos', name: 'website.favorites', component: page('website/favorites.vue') },
  { path: 'combos-y-promos/:page?', name: 'website.promos', component: page('website/shop.vue') },
  { path: 'como-comprar', name: 'website.how.to', component: page('website/howToBuy.vue') },
  { path: 'medios-de-pago', name: 'website.payments.options', component: page('website/paymentsOptions.vue') },
  { path: 'envios', name: 'website.ships', component: page('website/ships.vue') },
  { path: 'mi-carrito', name: 'website.cart', component: page('website/cart.vue') },
  { path: 'resultado/:result', name: 'website.payment.result', component: page('website/payment-result.vue') },
  { path: 'newsletter/:email', name: 'website.subscripte.newsletter', component: page('website/subscribeNewsletter.vue') },
  { path: 'check-out', name: 'website.checkout', component: page('website/checkout.vue') },
  { path: 'detalle-producto/:slug', name: 'website.productdetail', component: page('website/productdetail.vue') }
  
];


let DashboardRoutes = [
  /**
   * Dashboard Area
   */
  {
      path: '/dashboard',
      name: 'dashboard.index',
      component: page('dashboard/index.vue'),
      children: [
          { path: 'products', name: 'dashboard.products', component: page('dashboard/products.vue') },
          { path: 'reports', name: 'dashboard.reports', component: page('dashboard/Reports.vue') },
          { path: 'sales', name: 'dashboard.sales', component: page('dashboard/sales.vue') },
          { path: 'users', name: 'dashboard.users', component: page('dashboard/users.vue') },
          { path: 'settings',
          name: 'dashboard.settings',
          component: page('dashboard/settings.vue'),
          children: [
              { path: 'categories', name: 'dashboard.settings.categories', component: page('dashboard/settings/categories.vue') },
              { path: 'newsletters', name: 'dashboard.settings.newsletter', component: page('dashboard/settings/newsletter.vue') },
              { path: 'coupons', name: 'dashboard.settings.coupons', component: page('dashboard/settings/coupons.vue') },
              { path: 'locations', name: 'dashboard.settings.locations', component: page('dashboard/settings/locations.vue') },
              { path: 'import-exports', name: 'dashboard.settings.importsExports', component: page('dashboard/settings/ExportImports.vue') },
          ]
      },
         
      ]
  },
]

let ErrorRoutes = [
  /**
   * Default 404
   */
  { path: '*', component: page('errors/404.vue') }
]

let Routes = [
  ...AuthRoutes,
  ...DashboardRoutes,
  {
      path: '/',
      component: page('website/index.vue'),
      children: AppChildrenSharedPaths
  },
  ...ErrorRoutes
]
export default Routes
