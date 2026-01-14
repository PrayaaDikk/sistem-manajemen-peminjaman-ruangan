import api from "./axios";
import type { Booking } from "../types/booking";
import axios from "axios";

export interface BookingError {
    success: false;
    code: string;
    message: string;
}

export const createBooking = async (data: {
    user_id: number;
    room_id: number;
    booking_date: string;
    start_time: string;
    end_time: string;
}) => {
    try {
        const res = await api.post("?path=bookings", data);
        return res.data;
    } catch (err: any) {
        if (axios.isAxiosError(err)) {
            throw err.response?.data;
        }
        throw {
            code: "UNKNOWN_ERROR",
            message: "Terjadi kesalahan tidak diketahui",
        };
    }
};

export const myBookings = (userId: number, status?: string) => {
    const params = new URLSearchParams({
        user_id: userId.toString(),
    });

    if (status) {
        params.append("status", status);
    }

    return api.get<{ success: boolean; data: Booking[] }>(
        `?path=bookings/my&${params.toString()}`
    );
};

export const adminBookings = (status?: string) =>
    api.get<{ success: boolean; data: Booking[] }>(
        `?path=bookings${status ? `&status=${status}` : ""}`
    );

export const approveBooking = (id: number) =>
    api.post(`?path=bookings/approve&id=${id}`);

export const rejectBooking = (id: number) =>
    api.post(`?path=bookings/reject&id=${id}`);
