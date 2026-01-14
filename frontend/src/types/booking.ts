import type { BookingStatus } from "../utils/bookingStatus";

export interface Booking {
    id: number;
    room_name: string;
    user_name?: string;
    booking_date: string;
    start_time: string;
    end_time: string;
    status: BookingStatus;
}
