import request from '@/utils/request'
import qs from 'qs'

export function login(username, password) {
  return request.base({
    url: '/login',
    method: 'post',
    data: qs.stringify({ username, password })
  });
}

export function getInfo(userId) {
  return request.post('/user/info', { user_id: userId })
}

export function logout() {
  return request.post('/logout');
}
