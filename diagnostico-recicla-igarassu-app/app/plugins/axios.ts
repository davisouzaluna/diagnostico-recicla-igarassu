import axios from 'axios';


export default defineNuxtPlugin(() => {
    const api = axios.create({
        baseURL: import.meta.env.VITE_API_URL ?? "http://localhost:8000"
    })
  return {
    provide: {
      axios: api
    }
  }
})