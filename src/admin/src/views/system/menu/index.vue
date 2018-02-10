<template>
  <div class="app-container">

    <tree-table :data="list" :columns="columns" border></tree-table>

  </div>
</template>

<script>
  import treeTable from '@/components/TreeTable'
  import { getMenu } from '@/api/system'


  export default {
    components: { treeTable },
    data() {
      return {
        columns: [
          {
            text: 'Title',
            value: 'title',
            width: 200
          },
          {
            text: 'PID',
            value: 'pid'
          },
          {
            text: 'Router',
            value: 'router'
          },
          {
            text: 'Status',
            value: 'status'
          },
          {
            text: 'GroupIds',
            value: 'auth_group_ids'
          }
        ],
        list: []
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

          console.log(this.list)
        })
      }
    }
  }
</script>



