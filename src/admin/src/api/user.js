import request from '@/utils/request'

export function register(username, password, mobile, nickname, avatar) {
  return request.post('user/register', { username, password, mobile, nickname, avatar })
}

export function getUserlist(param) {
  return request.post('user/lists', param)
}

export function getUserInfo(user_id) {
  return request.post('user/info', { user_id })
}

export function updateUser(data) {
  return request.post('user/update', data)
}

export function condition() {
  return request.get('user/condition')
}

