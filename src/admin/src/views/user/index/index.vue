<template>
  <div class="app-container calendar-list-container">
    <div class="filter-container">
      <el-input @keyup.enter.native="handleFilter" style="width: 200px;" class="filter-item"
                :placeholder="$t('user.nickname')" v-model="listQuery.nickname">
      </el-input>
      <el-select clearable style="width: 90px" class="filter-item" v-model="listQuery.status"
                 :placeholder="$t('common.status')">
        <el-option v-for="item in statusOption" :key="item.key" :label="item.label" :value="item.key">
        </el-option>
      </el-select>
      <el-select clearable style="width: 90px" class="filter-item" v-model="listQuery.role"
                 :placeholder="$t('user.role')">
        <el-option v-for="item in roleOption" :key="item.key" :label="item.label" :value="item.key">
        </el-option>
      </el-select>
      <el-button class="filter-item" type="primary" v-waves icon="el-icon-search" @click="handleFilter">
        {{$t('common.search')}}
      </el-button>
    </div>

    <el-table
      v-loading="listLoading" element-loading-text="给我一点时间"
      :data="list" border fit highlight-current-row
      style="width: 100%">
      <el-table-column
        type="selection"
        width="36">
      </el-table-column>
      <el-table-column prop="username" label="用户名" width="150"></el-table-column>
      <el-table-column prop="nickname" label="昵称" width="200"></el-table-column>
      <el-table-column prop="mobile" label="手机号" width="110"></el-table-column>
      <el-table-column label="状态" width="75">
        <span slot-scope="scope">{{handelStatus(scope.row.status)}}</span>
      </el-table-column>
      <el-table-column label="创建时间" width="180">
        <span slot-scope="scope">{{scope.row.create_time | dateFormat}}</span>
      </el-table-column>
      <el-table-column align="center" label="操作" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="primary" size="mini">编辑</el-button>
        </template>
      </el-table-column>
    </el-table>

    <div class="pagination-container">
      <el-pagination background @size-change="handleSizeChange" @current-change="handleCurrentChange"
                     :current-page.sync="listQuery.page"
                     :page-sizes="[10,20,30,50]" :page-size="listQuery.limit"
                     layout="total, sizes, prev, pager, next, jumper" :total="total">
      </el-pagination>
    </div>
  </div>
</template>

<script>
  import { getUserList, condition } from '@/api/user'
  import waves from '@/directive/waves' // 水波纹指令
  import _ from 'lodash'
  import { selectOption } from '@/utils'

  export default {
    name: 'userList',
    directives: {
      waves
    },
    data() {
      return {
        roles: [],
        status: [],
        list: null,
        total: null,
        listLoading: true,
        listQuery: {
          page: 1,
          limit: 20,
          status: undefined,
          nickname: undefined,
          role: undefined
        },
        statusOption: [],
        roleOption: []
      }
    },
    filters: {
      statusFilter(status) {
        const statusMap = {
          published: 'success',
          draft: 'info',
          deleted: 'danger'
        }
        return statusMap[status]
      }
    },
    created() {
      this.handleSelectOption()
      this.getList()
    },
    methods: {
      handleSelectOption() {
        condition().then(res => {
          this.roles = res.data.role
          this.status = res.data.status
          this.roleOption = selectOption(this.roles)
          this.statusOption = selectOption(this.status)
        })
      },
      getList() {
        this.listLoading = true
        getUserList(this.listQuery).then(response => {
          console.log(response.data)
          this.list = response.data.list
          this.total = response.data.total
          this.listLoading = false
        })
      },
      handleFilter() {
        this.listQuery.page = 1
        this.getList()
      },
      handleSizeChange(val) {
        this.listQuery.limit = val
        this.getList()
      },
      handleCurrentChange(val) {
        this.listQuery.page = val
        this.getList()
      },
      handelRoles(type) {
        return this.roles[type]
      },
      handelStatus(type) {
        console.log(type)
        return this.status[type]
      }
    }
  }
</script>

