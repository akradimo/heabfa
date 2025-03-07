// آدرس: resources/js/store/modules/document.js

import axios from 'axios'

export default {
  namespaced: true,

  state: {
    اسناد: [],
    سند_جاری: null,
    درحال_بارگذاری: false,
    خطا: null
  },

  mutations: {
    تنظیم_اسناد(state, اسناد) {
      state.اسناد = اسناد;
    },

    تنظیم_سند_جاری(state, سند) {
      state.سند_جاری = سند;
    },

    تنظیم_بارگذاری(state, وضعیت) {
      state.درحال_بارگذاری = وضعیت;
    },

    تنظیم_خطا(state, خطا) {
      state.خطا = خطا;
    },

    اضافه_سند(state, سند) {
      state.اسناد.unshift(سند);
    },

    بروزرسانی_سند(state, سند_بروز) {
      const index = state.اسناد.findIndex(سند => سند.id === سند_بروز.id);
      if (index !== -1) {
        state.اسناد.splice(index, 1, سند_بروز);
      }
    },

    حذف_سند(state, id) {
      state.اسناد = state.اسناد.filter(سند => سند.id !== id);
    }
  },

  actions: {
    async دریافت_اسناد({ commit }, پارامترها = {}) {
      commit('تنظیم_بارگذاری', true);
      try {
        const { data } = await axios.get('/api/accounting/documents', { params: پارامترها });
        commit('تنظیم_اسناد', data.داده);
        return data.داده;
      } catch (error) {
        commit('تنظیم_خطا', error.message);
        throw error;
      } finally {
        commit('تنظیم_بارگذاری', false);
      }
    },

    async ذخیره_سند({ commit }, داده_سند) {
      try {
        let response;
        if (داده_سند.id) {
          response = await axios.put(`/api/accounting/documents/${داده_سند.id}`, داده_سند);
          commit('بروزرسانی_سند', response.data.داده);
        } else {
          response = await axios.post('/api/accounting/documents', داده_سند);
          commit('اضافه_سند', response.data.داده);
        }
        return response.data.داده;
      } catch (error) {
        commit('تنظیم_خطا', error.message);
        throw error;
      }
    },

    async حذف_سند({ commit }, id) {
      try {
        await axios.delete(`/api/accounting/documents/${id}`);
        commit('حذف_سند', id);
      } catch (error) {
        commit('تنظیم_خطا', error.message);
        throw error;
      }
    },

    async تایید_سند({ commit }, id) {
      try {
        const { data } = await axios.patch(`/api/accounting/documents/${id}/approve`);
        commit('بروزرسانی_سند', data.داده);
        return data.داده;
      } catch (error) {
        commit('تنظیم_خطا', error.message);
        throw error;
      }
    },

    async رد_سند({ commit }, { id, دلیل }) {
      try {
        const { data } = await axios.patch(`/api/accounting/documents/${id}/reject`, { دلیل });
        commit('بروزرسانی_سند', data.داده);
        return data.داده;
      } catch (error) {
        commit('تنظیم_خطا', error.message);
        throw error;
      }
    }
  },

  getters: {
    اسناد_در_انتظار_تایید: state => {
      return state.اسناد.filter(سند => سند.وضعیت === 1);
    },
    
    اسناد_تایید_شده: state => {
      return state.اسناد.filter(سند => سند.وضعیت === 2);
    },
    
    اسناد_رد_شده: state => {
      return state.اسناد.filter(سند => سند.وضعیت === 3);
    }
  }
}