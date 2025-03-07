<template>
    <div class="حساب-لیست rtl">
      <div class="عنوان-صفحه">
        <h2>لیست حساب‌ها</h2>
        <v-btn color="primary" @click="ایجاد_حساب_جدید">
          حساب جدید
          <v-icon right>mdi-plus</v-icon>
        </v-btn>
      </div>
  
      <v-data-table
        :headers="ستون‌ها"
        :items="حساب‌ها"
        :loading="درحال_بارگذاری"
        :search="جستجو"
        class="elevation-1"
        :items-per-page="15"
        :footer-props="{
          'items-per-page-text': 'تعداد در هر صفحه',
          'items-per-page-options': [15, 30, 50, 100]
        }"
      >
        <template v-slot:top>
          <v-row>
            <v-col cols="12" sm="6" md="4">
              <v-text-field
                v-model="جستجو"
                label="جستجو در حساب‌ها..."
                prepend-icon="mdi-magnify"
                clearable
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="4">
              <v-select
                v-model="فیلتر_نوع_حساب"
                :items="انواع_حساب"
                label="فیلتر بر اساس نوع حساب"
                clearable
              ></v-select>
            </v-col>
          </v-row>
        </template>
  
        <template v-slot:item.عملیات="{ item }">
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                small
                icon
                color="info"
                v-bind="attrs"
                v-on="on"
                @click="ویرایش_حساب(item)"
              >
                <v-icon small>mdi-pencil</v-icon>
              </v-btn>
            </template>
            <span>ویرایش حساب</span>
          </v-tooltip>
  
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                small
                icon
                color="error"
                v-bind="attrs"
                v-on="on"
                @click="حذف_حساب(item.id)"
                :disabled="!امکان_حذف_حساب(item)"
              >
                <v-icon small>mdi-delete</v-icon>
              </v-btn>
            </template>
            <span>حذف حساب</span>
          </v-tooltip>
        </template>
  
        <template v-slot:item.فعال="{ item }">
          <v-chip
            :color="item.فعال ? 'success' : 'error'"
            small
            label
          >
            {{ item.فعال ? 'فعال' : 'غیرفعال' }}
          </v-chip>
        </template>
  
        <template v-slot:item.مانده="{ item }">
          <span :class="{ 'red--text': item.مانده < 0, 'green--text': item.مانده > 0 }">
            {{ formatNumber(item.مانده) }}
          </span>
        </template>
      </v-data-table>
  
      <v-dialog v-model="نمایش_فرم" max-width="800px">
        <account-form
          :حساب="حساب_انتخاب_شده"
          @ذخیره="ذخیره_حساب"
          @انصراف="نمایش_فرم = false"
        />
      </v-dialog>
    </div>
  </template>
  
  <script>
  import { mapActions, mapState } from 'vuex';
  import AccountForm from './AccountForm.vue';
  import { formatNumber } from '@/utils/numbers';
  
  export default {
    name: 'AccountList',
    
    components: {
      AccountForm
    },
  
    data() {
      return {
        درحال_بارگذاری: false,
        جستجو: '',
        نمایش_فرم: false,
        حساب_انتخاب_شده: null,
        فیلتر_نوع_حساب: null,
        ستون‌ها: [
          { text: 'کد', value: 'کد', align: 'center', width: '120px' },
          { text: 'عنوان', value: 'عنوان', align: 'right' },
          { text: 'نوع حساب', value: 'نوع_حساب', align: 'center' },
          { text: 'مانده', value: 'مانده', align: 'left' },
          { text: 'وضعیت', value: 'فعال', align: 'center' },
          { text: 'عملیات', value: 'عملیات', align: 'center', sortable: false, width: '100px' }
        ],
        انواع_حساب: [
          'دارایی',
          'بدهی',
          'درآمد',
          'هزینه',
          'سرمایه'
        ]
      };
    },
  
    computed: {
      ...mapState('حسابداری', ['حساب‌ها']),
      
      حساب‌های_فیلتر_شده() {
        if (!this.فیلتر_نوع_حساب) return this.حساب‌ها;
        return this.حساب‌ها.filter(حساب => حساب.نوع_حساب === this.فیلتر_نوع_حساب);
      }
    },
  
    methods: {
      ...mapActions('حسابداری', ['دریافت_حساب‌ها', 'حذف_حساب']),
  
      formatNumber,
  
      ایجاد_حساب_جدید() {
        this.حساب_انتخاب_شده = null;
        this.نمایش_فرم = true;
      },
  
      ویرایش_حساب(حساب) {
        this.حساب_انتخاب_شده = { ...حساب };
        this.نمایش_فرم = true;
      },
  
      async حذف_حساب(id) {
        if (!await this.$confirm('آیا از حذف این حساب اطمینان دارید؟')) return;
        
        this.درحال_بارگذاری = true;
        try {
          await this.حذف_حساب(id);
          this.$toast.success('حساب با موفقیت حذف شد');
          await this.دریافت_حساب‌ها();
        } catch (error) {
          this.$toast.error(error.response?.data?.پیام || 'خطا در حذف حساب');
        } finally {
          this.درحال_بارگذاری = false;
        }
      },
  
      امکان_حذف_حساب(حساب) {
        return !حساب.زیرمجموعه‌ها?.length && !حساب.تراکنش‌ها?.length;
      },
  
      ذخیره_حساب() {
        this.نمایش_فرم = false;
        this.دریافت_حساب‌ها();
      }
    },
  
    async created() {
      this.درحال_بارگذاری = true;
      try {
        await this.دریافت_حساب‌ها();
      } catch (error) {
        this.$toast.error('خطا در دریافت لیست حساب‌ها');
      } finally {
        this.درحال_بارگذاری = false;
      }
    }
  };
  </script>
  
  <style lang="scss" scoped>
  .حساب-لیست {
    padding: 20px;
  
    .عنوان-صفحه {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
  
    .v-data-table {
      background: white;
      border-radius: 8px;
    }
  }
  </style>