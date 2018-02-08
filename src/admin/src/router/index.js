import Vue from 'vue'
import Router from 'vue-router'

const _import = require('./_import_' + process.env.NODE_ENV)
// in development-env not use lazy-loading, because lazy-loading too many pages will cause webpack hot update too slow. so only in production use lazy-loading;
// detail: https://panjiachen.github.io/vue-element-admin-site/#/lazy-loading

Vue.use(Router)

/* Layout */
import Layout from '../views/layout/Layout'

/** note: submenu only apppear when children.length>=1
 *   detail see  https://panjiachen.github.io/vue-element-admin-site/#/router-and-nav?id=sidebar
 **/

/**
 * hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
 *                                if not set alwaysShow, only more than one route under the children
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noredirect           if `redirect:noredirect` will no redirct in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']     will control the page roles (you can set multiple roles)
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar,
    noCache: true                if fasle ,the page will no be cached(default is false)
  }
 **/
export const constantRouterMap = [
  { path: '/login', component: _import('login/index'), hidden: true },
  { path: '/404', component: _import('error/404'), hidden: true },
  { path: '/401', component: _import('error/401'), hidden: true },
  {
    path: '',
    component: Layout,
    redirect: 'dashboard',
    children: [{
      path: 'dashboard',
      component: _import('dashboard/index'),
      name: 'dashboard',
      meta: { title: 'dashboard', icon: 'dashboard', noCache: true }
    }]
  }
]

export default new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRouterMap
})

export const asyncRouterMap = [
  {
    path: '/system',
    redirect: '/system/setting',
    component: Layout,
    name: 'system',
    meta: { icon: 'peoples', title: 'system', roles: ['admin'] },
    children: [
      {
        path: '/system/permission',
        redirect: '/system/permission/group-rule',
        component: _import('system/permission/index'),
        name: 'permission',
        meta: { title: 'permission' , icon: 'tab'},
        children: [
          { path: 'group', component: _import('system/permission/group/index'), name: 'permissionGroup', meta: { title: 'permissionGroup' }},
          { path: 'group-user', component: _import('system/permission/groupUser/index'), name: 'permissionGroupUser', meta: { title: 'permissionGroupUser' }, hidden: true},
          { path: 'group-rule', component: _import('system/permission/groupRule/index'), name: 'permissionGroupRule', meta: { title: 'permissionGroupRule' }, hidden: true},
          { path: 'rule', component: _import('system/permission/rule/index'), name: 'permissionRule', meta: { title: 'permissionRule' }},
        ]
      },
      { path: 'menu', component: _import('system/menu/index'), name: 'menu', meta: { title: 'menu' }},
      { path: 'setting', component: _import('system/setting/index'), name: 'systemSetting', meta: { title: 'systemSetting' }},
      { path: 'label', component: _import('system/label/index'), name: 'label', meta: { title: 'label' }}
    ]
  },

  {
    path: '/user',
    component: Layout,
    redirect: '/user/index',
    name: 'user',
    meta: { icon: 'peoples', title: 'user'},
    children: [
      { path: 'index', component: _import('user/index/index'), name: 'userIndex', meta: { title: 'userIndex'}},
      { path: 'message', component: _import('user/message/index'), name: 'userMessage', meta: { title: 'userMessage' }},
    ]
  },

  {
    path: '/post',
    component: Layout,
    redirect: '/post/index',
    name: 'post',
    meta: { icon: 'peoples', title: 'post'},
    children: [
      { path: 'index', component: _import('post/index/index'), name: 'postIndex', meta: { title: 'postIndex' }},
      { path: 'category', component: _import('post/category/index'), name: 'postCategory', meta: { title: 'postCategory' }},
    ]
  },

  { path: '*', redirect: '/404', hidden: true }
]
