import axios from "axios";

const api = axios.create({
    baseURL: "http://sistem-manajemen-peminjaman-ruangan.test/backend/public/",
    withCredentials: false,
    headers: {
        "Content-Type": "application/json",
    },
});

export default api;
