import axios from 'axios'
import { Message, MessageBox } from 'element-ui'
import store from '@/store'
import { getToken } from '@/utils/auth'
import qs from 'qs'

// create an axios instance
const service = axios.create({
  baseURL: process.env.BASE_API,
  withCredentials: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/x-www-form-urlencoded'
  },
  timeout: 30000
})

// request interceptor
service.interceptors.request.use(config => {
  if (store.getters.token) {
    if (config.method === 'post') {
      if (config.data) {
        config.data.auth_token = getToken()
      } else {
        config.data = {auth_token: getToken()}
      }
    } else {
      if (config.params) {
        config.params.auth_token = getToken()
      } else {
        config.params = {auth_token: getToken()}
      }
    }
  }
  if (config.data) {
    config.data = qs.stringify(config.data)
  }
  return config
}, error => {
  return Promise.reject(error)
})

// respone interceptor
service.interceptors.response.use(
  response => {
    if (response.status >= 200 && response.status < 300) {
      return response
    } else {
      Message({
        message: '异常错误',
        type: 'error',
        duration: 3000
      })
      return Promise.reject('error')
    }
  },
  error => {
    let err = error.response
    if (err.status === 401) {
      MessageBox.alert('验证已过期,请重新登录', '确定登出', {
        confirmButtonText: '重新登录',
        type: 'warning'
      }).then(() => {
        store.dispatch('FedLogOut').then(() => {
          location.reload();
        });
      })
    } else {
      Message({
        message: err.data.error,
        type: 'error',
        duration: 3000
      })
    }
    return Promise.reject(err)
  })

export default {
  base(obj) {
    return service.request(obj)
  },
  get(url, params) {
    return service.request({ url: url, params: params })
  },
  delete(url) {
    return service.request({ method: 'delete', url: url })
  },
  head(url) {
    return service.request({ method: 'head', url: url })
  },
  options(url) {
    return service.request({ method: 'options', url: url })
  },
  post(url, data) {
    return service.request({ method: 'post', url: url, data: data })
  },
  put(url, data) {
    return service.request({ method: 'put', url: url, data: data })
  },
  patch(url, data) {
    return service.request({ method: 'patch', url: url, data: data })
  }
}
