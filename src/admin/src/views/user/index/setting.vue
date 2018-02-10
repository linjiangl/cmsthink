<template>
  <div>
    <el-dialog :title="textMap[digTitle]" :visible.sync="dialogTableVisible" @close="handleClose">
      <el-form :model="form" ref="userFrom" :rules="rules" label-width="70px" style='width: 400px; margin-left:50px;'>
        <el-form-item label="用户名" prop="username">
          <el-input v-model="form.username" :disabled="isDisabled"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password" v-if="!id">
          <el-input type="password" v-model="form.password"></el-input>
        </el-form-item>
        <el-form-item label="昵称" prop="nickname">
          <el-input v-model="form.nickname"></el-input>
        </el-form-item>
        <el-form-item label="手机号" prop="mobile">
          <el-input v-model="form.mobile"></el-input>
        </el-form-item>
        <el-form-item label="角色">
          <el-radio-group v-model="form.role">
            <el-radio :label="1">管理</el-radio>
            <el-radio :label="2">普通</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleClose">取 消</el-button>
        <el-button @click="handleSaveData">确定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  import { getUserInfo, register, updateUser } from '@/api/user'

  export default {
    props: ['digTitle', 'id'],
    computed: {
      ...mapGetters([
        'isDialog'
      ])
    },
    data() {
      var checkMobile = (rule, value, callback) => {
        if (value && !/^1\d{10}$/.test(value)) {
          callback(new Error('输入正确的手机号'));
        } else {
          callback();
        }
      };
      return {
        dialogTableVisible: true,
        textMap: {
          update: 'Edit',
          create: 'Create'
        },
        form: {
          username: '',
          nickname: '',
          mobile: '',
          password: '',
          avatar: '',
          role: 2
        },
        rules: {
          username: [
            { required: true, message: '请输入用户名', trigger: 'blur' },
            { min: 3, max: 30, message: '长度在 3 到 30 个字符', trigger: 'blur' }
          ],
          password: [
            { min: 6, max: 30, message: '长度在 6 到 30 个字符', trigger: 'blur' }
          ],
          mobile: [
            { validator: checkMobile, message: '输入正确的手机号码', trigger: 'blur' }
          ],
          nickname: [
            { min: 2, max: 20, message: '长度在 2 到 20 个字符', trigger: 'blur' }
          ],
        },
        isDisabled: false,
        isEditMobile: false
      }
    },
    created () {
      this.handleInfo()
    },
    methods: {
      handleInfo() {
        if (this.id) {
          this.isDisabled = true
          getUserInfo(this.id).then(res => {
            const user = res.data
            this.form.username = user.username
            this.form.nickname = user.nickname
            this.form.mobile = user.mobile
            this.form.role = user.role
          })
        }
      },
      handleClose() {
        this.$store.dispatch('setIsDialog', false)
        this.dialogTableVisible = false
      },
      handleSaveData() {
        const self = this
        this.$refs['userFrom'].validate((valid) => {
          if (valid) {
            if (self.id) {
              self.updateData()
            } else {
              self.createData()
            }
          }
        })
      },
      createData() {
        const self = this
        const data = this.form
        register(data.username, data.password, data.mobile, data.nickname, data.avatar).then(res => {
          this.$notify({
            title: '用户',
            message: '注册成功',
            type: 'success',
            duration: 2000
          })
          self.handleClose()
          self.$emit('addUser')
        })
      },
      updateData() {
        const self = this
        const data = this.form
        data.id = this.id
        updateUser(data).then(res => {
          this.$notify({
            title: '用户',
            message: '更新成功',
            type: 'success',
            duration: 2000
          })
          self.handleClose()
          self.$emit('updateUser')
        })
      }

    }
  }
</script>
