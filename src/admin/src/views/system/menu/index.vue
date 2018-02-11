<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" type="primary" icon="el-icon-edit">创建</el-button>
    </div>

    <tree-table :data="list" border>
      <el-table-column label="Title" width="150" align="left">
        <template slot-scope="scope">
          <span>{{scope.row.title}}</span>
        </template>
      </el-table-column>
      <el-table-column label="Router" width="200" align="left">
        <template slot-scope="scope">
          <span>{{scope.row.router}}</span>
        </template>
      </el-table-column>
      <el-table-column label="Sort" width="80" align="center">
        <template slot-scope="scope">
          <span>{{scope.row.sort}}</span>
        </template>
      </el-table-column>
      <el-table-column label="Status" width="80" align="center">
        <template slot-scope="scope">
          <el-tag :type="status[scope.row.status].tag">{{status[scope.row.status].name}}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Groups" width="250" align="left">
        <template slot-scope="scope">
          <span>{{scope.row.group_name}}</span>
        </template>
      </el-table-column>
      <el-table-column label="Actions" align="center">
        <template slot-scope="scope">
          <el-button-group>
            <el-button type="primary" size="mini">编辑</el-button>
            <el-button type="danger" size="mini" @click="handleUpdate(scope.row, 0)" v-if="scope.row.status == 1">禁用</el-button>
            <el-button type="success" size="mini" @click="handleUpdate(scope.row, 1)" v-else>启用</el-button>
          </el-button-group>
        </template>
      </el-table-column>
    </tree-table>
  </div>
</template>

<script>
  import treeTable from '@/components/TreeTable'
  import { getMenu, updateMenu } from '@/api/system'

  export default {
    components: { treeTable },
    data() {
      return {
        list: [],
        status: {
          0: {'tag': 'danger', 'name': '禁用'},
          1: {'tag': 'success', 'name': '正常'}
        }
      }
    },
    created() {
      this.getList()
    },
    methods: {
      getList() {
        this.listLoading = true
        getMenu().then(res => {
          let items = res.data
          this.list = items.map(v => {
            this.$set(v, 'edit', false)
            v.originalTitle = v.title
            return v
          })
          this.listLoading = false
        })
      },
      handleUpdate(data, status) {
        let save = {
          id: data.id,
          status: status
        }
        updateMenu(save).then(res => {
          data.status = status;
        })
      }
    }
  }
</script>



