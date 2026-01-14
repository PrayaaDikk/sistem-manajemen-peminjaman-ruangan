import { useEffect, useState } from "react";
import {
    adminBookings,
    approveBooking,
    rejectBooking,
} from "../../api/bookingApi";
import type { Booking } from "../../types/booking";

export default function BookingAdmin() {
    const [data, setData] = useState<Booking[]>([]);

    const load = () =>
        adminBookings("pending").then((res) => setData(res.data.data));

    useEffect(() => {
        load();
    }, []);

    return (
        <div>
            <h2 className="text-xl font-bold mb-4">Approval Booking</h2>

            {data.map((b) => (
                <div key={b.id} className="border p-3 mb-2">
                    <p>
                        {b.user_name} - {b.room_name}
                    </p>
                    <p>
                        {b.booking_date} | {b.start_time}-{b.end_time}
                    </p>

                    <button
                        onClick={() => approveBooking(b.id).then(load)}
                        className="bg-green-600 text-white px-2 py-1 mr-2"
                    >
                        Approve
                    </button>

                    <button
                        onClick={() => rejectBooking(b.id).then(load)}
                        className="bg-red-600 text-white px-2 py-1"
                    >
                        Reject
                    </button>
                </div>
            ))}
        </div>
    );
}
