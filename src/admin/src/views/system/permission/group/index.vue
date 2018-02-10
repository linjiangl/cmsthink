<template>
  <div class="app-container calendar-list-container">
    <el-table :data="list" v-loading.body="listLoading" border fit highlight-current-row style="width: 100%">

      <el-table-column align="center" label="ID" width="100">
        <template slot-scope="scope">
          <span>{{scope.row.id}}</span>
        </template>
      </el-table-column>

      <el-table-column width="120" align="center" label="PID">
        <template slot-scope="scope">
          <span>{{scope.row.pid}}</span>
        </template>
      </el-table-column>

      <el-table-column width="300" label="Title" align="center">
        <template slot-scope="scope">
          <template v-if="scope.row.edit">
            <el-input class="edit-input" size="small" v-model="scope.row.title"></el-input>
            <el-button class='cancel-btn' size="small" icon="el-icon-refresh" type="warning" @click="cancelEdit(scope.row)">cancel</el-button>
          </template>
          <span v-else @click='scope.row.edit=!scope.row.edit' >{{ scope.row.title }}</span>
        </template>
      </el-table-column>

      <el-table-column width="240" align="center" label="Name">
        <template slot-scope="scope">
          <span>{{scope.row.name}}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions">
        <template slot-scope="scope">
          <!--<el-button v-if="scope.row.edit" type="success" @click="confirmEdit(scope.row)" size="small" icon="el-icon-circle-check-outline">Ok</el-button>-->
          <!--<el-button v-else type="primary" @click='scope.row.edit=!scope.row.edit' size="small" icon="el-icon-edit">Edit</el-button>-->
          <el-button-group>
            <router-link :to="{name: 'permissionGroupUser', params: {groupId: scope.row.id}}" target="_blank"><el-button type="primary" size="small">用户</el-button></router-link>
          </el-button-group>
        </template>
      </el-table-column>

    </el-table>
  </div>
</template>

<script>
  import { getAuthGroup } from '@/api/system'

  export default {
    name: 'inlineEditTable',
    data() {
      return {
        list: [],
        listLoading: true,
        listQuery: {
          page: 1,
          limit: 10
        }
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
      this.getList()
    },
    methods: {
      getList() {
        this.listLoading = true
        getAuthGroup().then(res => {
          let items = res.data
          this.list = items.map(v => {
            this.$set(v, 'edit', false)
            v.originalTitle = v.title
            return v
          })
          this.listLoading = false
        })
      },
      cancelEdit(row) {
        row.title = row.originalTitle
        row.edit = false
//        this.$message({
//          message: 'The title has been restored to the original value',
//          type: 'warning'
//        })
      },
      confirmEdit(row) {
        row.edit = false
        row.originalTitle = row.title
        this.$message({
          message: 'The title has been edited',
          type: 'success'
        })
      }
    }
  }
</script>

