import type { Booking } from "../types/booking";

interface Props {
    booking: Booking;
}

const statusColor = (status: string) => {
    switch (status) {
        case "booking":
        case "approved":
            return "bg-yellow-100 text-yellow-700";
        case "completed":
            return "bg-blue-100 text-blue-700";
        default:
            return "bg-gray-200 text-gray-600";
    }
};

export default function BookingCard({ booking }: Props) {
    return (
        <div className="flex justify-between items-center bg-gray-50 rounded-xl p-4 mb-3">
            <div>
                <h3 className="font-semibold">{booking.room_name}</h3>
                <p className="text-sm text-gray-500">
                    {new Date(booking.booking_date).toLocaleDateString("id-ID")}
                </p>
            </div>

            <div className="flex items-center gap-4">
                <span className="text-sm">
                    {booking.start_time.slice(0, 5)} WITA
                </span>
                <span
                    className={`px-3 py-1 rounded-full text-xs font-medium ${statusColor(
                        booking.status
                    )}`}
                >
                    {booking.status}
                </span>
            </div>
        </div>
    );
}
