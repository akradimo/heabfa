import axios from 'axios'

export default {
  namespaced: true,

  state: {
    حساب_ها: [],
    حساب_های_والد: [],
    درحال_بارگذاری: false,
    خطا: null
  },

  mutations: {
    تنظیم_حساب_ها(state, حساب_ها) {
      state.حساب_ها = حساب_ها
    },

    تنظیم_حساب_های_والد(state, حساب_ها) {
      state.حساب_های_والد = حساب_ها
    },

    تنظیم_بارگذاری(state, وضعیت) {
      state.درحال_بارگذاری = وضعیت
    },

    تنظیم_خطا(state, خطا) {
      state.خطا = خطا
    },

    اضافه_حساب(state, حساب) {
      state.حساب_ها.push(حساب)
    },

    بروزرسانی_حساب(state, حساب) {
      const index = state.حساب_ها.findIndex(item => item.id === حساب.id)
      if (index !== -1) {
        state.حساب_ها.splice(index, 1, حساب)
      }
    }
  },

  actions: {
    async دریافت_حساب_ها({ commit }) {
      commit('تنظیم_بارگذاری', true)
      try {
        const { data } = await axios.get('/api/accounting/accounts')
        commit('تنظیم_حساب_ها', data.داده)
      } catch (error) {
        commit('تنظیم_خطا', error.message)
        throw error
      } finally {
        commit('تنظیم_بارگذاری', false)
      }
    },

    async ذخیره_حساب({ commit }, داده_حساب) {
      try {
        if (داده_حساب.id) {
          const { data } = await axios.put(`/api/accounting/accounts/${داده_حساب.id}`, داده_حساب)
          commit('بروزرسانی_حساب', data.داده)
        } else {
          const { data } = await axios.post('/api/accounting/accounts', داده_حساب)
          commit('اضافه_حساب', data.داده)
        }
      } catch (error) {
        commit('تنظیم_خطا', error.message)
        throw error
      }
    },

    async دریافت_حساب_های_والد({ commit }) {
      try {
        const { data } = await axios.get('/api/accounting/parent-accounts')
        commit('تنظیم_حساب_های_والد', data.داده)
      } catch (error) {
        commit('تنظیم_خطا', error.message)
        throw error
      }
    }
  },

  getters: {
    حساب_با_شناسه: state => id => {
      return state.حساب_ها.find(حساب => حساب.id === id)
    },

    حساب_های_فعال: state => {
      return state.حساب_ها.filter(حساب => حساب.فعال)
    }
  }
}