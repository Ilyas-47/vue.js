import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

export default new Echo({
  broadcaster: 'pusher',
  key: 'your_pusher_key',
  cluster: 'mt1',
  forceTLS: true,
  encrypted: true,
  authEndpoint: 'http://127.0.0.1:8000/broadcasting/auth',
  auth: {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`
    }
  }
})
