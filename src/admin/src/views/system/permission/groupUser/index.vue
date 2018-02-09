<template>
  <div class="app-container calendar-list-container">
    <el-table :data="list" v-loading.body="listLoading" border fit highlight-current-row style="width: 100%">

      <el-table-column
        type="index"
        width="80"
        align="center">
      </el-table-column>
      <el-table-column
        property="group_id"
        label="GroupId"
        width="120"
        align="center">
      </el-table-column>
      <el-table-column
        property="user_id"
        label="UserId"
        width="120"
        align="center">
      </el-table-column>
      <el-table-column align="center" label="GroupTitle" width="150">
        <template slot-scope="scope">
          <span>{{scope.row.auth_group.title}}</span>
        </template>
      </el-table-column>
      <el-table-column width="300" align="center" label="Nickname">
        <template slot-scope="scope">
          <span>{{scope.row.user.nickname}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Actions">
          <el-button-group slot-scope="scope">
            <el-button type="danger" icon="el-icon-delete" size="small" v-if="scope.row.user_id > 1"></el-button>
          </el-button-group>
      </el-table-column>

    </el-table>
  </div>
</template>

<script>
  import { getAuthGroupUser } from '@/api/system'

  export default {
    name: 'inlineEditTable',
    data() {
      return {
        list: [],
        listLoading: true,
        groupId: 0
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
        this.groupId = this.$route.params.groupId
        getAuthGroupUser(this.groupId).then(res => {
          this.list = res.data
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

