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
      <el-button class="filter-item" style="margin-left: 10px;" @click="handleCreate" type="primary" icon="el-icon-edit">创建</el-button>
    </div>

    <el-table
      v-loading="listLoading" element-loading-text="给我一点时间"
      :data="list" border fit highlight-current-row
      style="width: 100%">
      <el-table-column
        type="selection"
        width="36">
      </el-table-column>
      <el-table-column prop="id" label="ID" align="center"></el-table-column>
      <el-table-column prop="username" label="Username" align="center"></el-table-column>
      <el-table-column prop="nickname" label="Nickname" align="center"></el-table-column>
      <el-table-column label="Mobile" width="200" align="center">
        <template slot-scope="scope">
          <template v-if="scope.row.edit">
            <el-input class="edit-input" size="small" v-model="scope.row.mobile"></el-input>
            <el-button class='cancel-btn' size="small" type="primary" @click="confirmEdit(scope.row)">确认</el-button>
          </template>
          <span v-else @click='scope.row.edit=!scope.row.edit' >{{ scope.row.mobile }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Status">
        <span slot-scope="scope">{{handelStatus(scope.row.status)}}</span>
      </el-table-column>
      <el-table-column label="Role">
        <span slot-scope="scope">{{handelRoles(scope.row.role)}}</span>
      </el-table-column>
      <el-table-column label="CreateTime" width="180">
        <span slot-scope="scope">{{scope.row.create_time | dateFormat}}</span>
      </el-table-column>
      <el-table-column align="center" label="Actions" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="handleEdit(scope.row.id)">编辑</el-button>
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

    <setting :digTitle="digTitle" :id.sync="editId" v-if="isDialog" @addUser="handleFilter" @updateUser="getList"></setting>
  </div>
</template>

<script>
  import { getUserlist, condition, updateUser } from '@/api/user'
  import waves from '@/directive/waves' // 水波纹指令
  import { selectOption } from '@/utils'
  import setting from './setting'
  import { mapGetters } from 'vuex'

  export default {
    name: 'userList',
    components: {
      setting
    },
    directives: {
      waves
    },
    computed: {
      ...mapGetters([
        'isDialog'
      ])
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
        roleOption: [],
        editId: 0,
        digTitle: 'create'
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
        getUserlist(this.listQuery).then(res => {
          this.total = res.data.total
          let items = res.data.list
          this.list = items.map(v => {
            this.$set(v, 'edit', false)
            v.originalMobile = v.mobile
            return v
          })
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
        return this.status[type]
      },
      handleCreate() {
        this.editId = 0
        this.digTitle = 'create'
        this.$store.dispatch('setIsDialog', true)
      },
      handleEdit(id) {
        this.editId = id
        this.digTitle = 'update'
        this.$store.dispatch('setIsDialog', true)
      },
      confirmEdit(row) {
        row.edit = false
        if (row.mobile === row.originalMobile) {
          return false
        }

        let data = {
          id: row.id,
          mobile: row.mobile
        }
        updateUser(data).then(res => {
          row.originalMobile = row.mobile
          this.$message({
            message: '修改成功',
            type: 'success'
          })
        }).catch(err => {
          row.mobile = row.originalMobile
        })
      }
    }
  }
</script>

