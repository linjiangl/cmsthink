import request from '@/utils/request'

export function loginByUsername(username, password) {
  return request.post('login', { username, password })
}

export function logout() {
  return request.post('logout')
}

export function getUserInfo() {
  return request.post('user/info')
}

