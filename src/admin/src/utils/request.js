import axios from 'axios'
import { Message, MessageBox } from 'element-ui'
import store from '../store'
import { getToken } from '@/utils/auth'
import qs from 'qs'

// 创建axios实例
const service = axios.create({
  baseURL: process.env.BASE_API, // api的base_url
  timeout: 30000, // 请求超时时间
  withCredentials: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/x-www-form-urlencoded'
  }
})

axios.interceptors.request.use(function (config) {
  // Do something before request is sent
  return config;
}, function (error) {
  // Do something with request error
  return Promise.reject(error);
});

// respone拦截器
service.interceptors.response.use(
  response => {
    if (response.status >= 200 && response.status < 300) {
      return response
    } else {
      console.log(response)
      Message({
        message: '未知错误:' + response.status,
        type: 'error'
      })
      return Promise.reject('error')
    }
  },
  error => {
    console.log(error.response)// for debug

    let errCode = error.response.status
    let errMsg = error.response.data.error

    // auth_toke: 失效,需要重新登录获取
    if (errCode === 401) {
      MessageBox.confirm('你已被登出，可以取消继续留在该页面，或者重新登录', '确定登出', {
        confirmButtonText: '重新登录',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        store.dispatch('FedLogOut').then(() => {
          location.reload()// 为了重新实例化vue-router对象 避免bug
        })
      }).catch(() => {

      })
      return false;
    } else {
      Message({
        message: errMsg,
        type: 'error'
      })

      return Promise.reject(error.response)
    }
  }
)


export default {
  base(obj) {
    return service(obj)
  },
  get(url, params) {
    return service({ url: url, params: params })
  },
  delete(url) {
    return service({ method: 'delete', url: url })
  },
  head(url) {
    return service({ method: 'head', url: url })
  },
  options(url) {
    return service({ method: 'options', url: url })
  },
  post(url, data) {
    if (data) {
      data.auth_token = getToken()
    } else {
      data = { auth_token: getToken() }
    }
    return service({ method: 'post', url: url, data: qs.stringify(data) })
  },
  put(url, data) {
    return service({ method: 'put', url: url, data: data })
  },
  patch(url, data) {
    return service({ method: 'patch', url: url, data: data })
  }
}
