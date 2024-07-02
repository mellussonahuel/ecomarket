import store from '~/store'

export default async (to, from, next) => {
  if (!store.getters['auth/check']) {
    next({ name: 'login', query:{ redirect : from.name === 'website.cart' ? 'website.checkout' : null} })
  } else {
    next()
  }
}
