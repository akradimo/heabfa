<template>
    <v-card class="account-form">
      <v-card-title class="headline primary white--text">
        {{ isEdit ? 'ویرایش حساب' : 'ایجاد حساب جدید' }}
      </v-card-title>
  
      <v-card-text class="mt-4">
        <v-form ref="form" v-model="isValid">
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.code"
                label="کد حساب *"
                :rules="rules.code"
                :disabled="isEdit"
                dir="ltr"
                outlined
                dense
              ></v-text-field>
            </v-col>
  
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.title"
                label="عنوان حساب *"
                :rules="rules.title"
                outlined
                dense
              ></v-text-field>
            </v-col>
  
            <v-col cols="12" md="6">
              <v-select
                v-model="form.type"
                :items="accountTypes"
                label="نوع حساب *"
                :rules="rules.type"
                outlined
                dense
              ></v-select>
            </v-col>
  
            <v-col cols="12" md="6">
              <v-select
                v-model="form.parentId"
                :items="parentAccounts"
                item-text="title"
                item-value="id"
                label="حساب والد"
                :disabled="!canSelectParent"
                outlined
                dense
                clearable
              >
                <template v-slot:item="{ item }">
                  {{ item.code }} - {{ item.title }}
                </template>
              </v-select>
            </v-col>
  
            <v-col cols="12">
              <v-textarea
                v-model="form.description"
                label="توضیحات"
                rows="3"
                outlined
                dense
              ></v-textarea>
            </v-col>
  
            <v-col cols="12" md="6">
              <v-switch
                v-model="form.isActive"
                label="حساب فعال است"
                color="success"
              ></v-switch>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
  
      <v-divider></v-divider>
  
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="grey darken-1"
          text
          @click="$emit('cancel')"
          :disabled="isSaving"
        >
          انصراف
        </v-btn>
        <v-btn
          color="primary"
          :loading="isSaving"
          :disabled="!isValid"
          @click="save"
        >
          <v-icon left>{{ isEdit ? 'mdi-content-save' : 'mdi-plus' }}</v-icon>
          {{ isEdit ? 'بروزرسانی' : 'ایجاد' }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </template>
  
  <script>
  import { mapActions, mapState } from 'vuex'
  
  export default {
    name: 'AccountForm',
  
    props: {
      account: {
        type: Object,
        default: null
      }
    },
  
    data() {
      return {
        isSaving: false,
        isValid: false,
        form: {
          code: '',
          title: '',
          type: '',
          parentId: null,
          description: '',
          isActive: true
        },
        accountTypes: [
          'دارایی',
          'بدهی',
          'درآمد',
          'هزینه',
          'سرمایه'
        ],
        rules: {
          code: [
            v => !!v || 'کد حساب الزامی است',
            v => /^\d{3,10}$/.test(v) || 'کد حساب باید بین 3 تا 10 رقم باشد',
            v => !this.existingCodes.includes(v) || 'این کد قبلاً استفاده شده است'
          ],
          title: [
            v => !!v || 'عنوان حساب الزامی است',
            v => v.length >= 3 || 'عنوان حساب باید حداقل 3 حرف باشد'
          ],
          type: [
            v => !!v || 'نوع حساب الزامی است'
          ]
        }
      }
    },
  
    computed: {
      ...mapState('accounting', ['parentAccounts', 'existingCodes']),
  
      isEdit() {
        return !!this.account
      },
  
      canSelectParent() {
        return !this.isEdit || !this.subAccounts?.length
      }
    },
  
    watch: {
      account: {
        handler(newVal) {
          if (newVal) {
            this.form = { ...newVal }
          } else {
            this.$refs.form?.reset()
          }
        },
        immediate: true
      }
    },
  
    methods: {
      ...mapActions('accounting', ['saveAccount', 'fetchParentAccounts']),
  
      async save() {
        if (!this.$refs.form.validate()) return
  
        this.isSaving = true
        try {
          const saveData = {
            ...this.form,
            companyId: this.$store.state.company.activeCompany?.id
          }
  
          await this.saveAccount(saveData)
          
          this.$toast.success(this.isEdit ? 'حساب با موفقیت بروزرسانی شد' : 'حساب با موفقیت ایجاد شد')
          this.$emit('save')
        } catch (error) {
          this.$toast.error(error.response?.data?.message || 'خطا در ذخیره حساب')
        } finally {
          this.isSaving = false
        }
      }
    },
  
    async created() {
      await this.fetchParentAccounts()
    }
  }
  </script>
  
  <style lang="scss" scoped>
  .account-form {
    .v-card-title {
      font-family: 'IRANSans' !important;
    }
  
    .v-card-text {
      padding-top: 20px;
    }
  
    .v-text-field.v-text-field--enclosed {
      margin: 0;
      padding: 0;
    }
  }
  </style>