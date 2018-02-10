import request from '@/utils/request'

export function getAuthGroup()
{
  return request.post('system/getAuthGroup')
}

export function getAuthGroupUser(groupId)
{
  return request.post('system/getAuthGroupUser', {group_id: groupId})
}

export function getMenu()
{
  return request.post('system/getMenus')
}
