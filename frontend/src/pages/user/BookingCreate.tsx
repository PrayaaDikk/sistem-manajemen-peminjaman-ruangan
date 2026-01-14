import { useParams, useNavigate } from "react-router-dom";
import { useState } from "react";
import { createBooking } from "../../api/bookingApi";

export default function BookingCreate() {
    const { roomId } = useParams();
    const { userId } = useParams();
    const navigate = useNavigate();

    const [date, setDate] = useState("");
    const [start, setStart] = useState("");
    const [end, setEnd] = useState("");

    const [error, setError] = useState<string | null>(null);
    const [loading, setLoading] = useState(false);

    const submit = async () => {
        setError(null);
        setLoading(true);

        try {
            await createBooking({
                user_id: Number(userId),
                room_id: Number(roomId),
                booking_date: date,
                start_time: start,
                end_time: end,
            });

            navigate("/borrowing");
        } catch (err: any) {
            setError(
                err?.response?.data?.message ??
                    "Terjadi kesalahan saat membuat booking"
            );
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="p-6">
            <div className="space-y-6 mb-6">
                <h1 className="text-3xl font-semibold">Formulir Peminjaman</h1>
                <div className="h-px bg-[#B5B5C3]"></div>
            </div>

            {/* ERROR MESSAGE */}
            {error && (
                <div className="mb-4 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {error}
                </div>
            )}

            <div className="flex gap-4 mb-6">
                <input
                    type="date"
                    className="border border-[#B5B5C3] rounded-lg px-4 py-2 w-full"
                    value={date}
                    onChange={(e) => setDate(e.target.value)}
                />

                <input
                    type="time"
                    className="border border-[#B5B5C3] rounded-lg px-4 py-2 w-full"
                    value={start}
                    onChange={(e) => setStart(e.target.value)}
                />

                <input
                    type="time"
                    className="border border-[#B5B5C3] rounded-lg px-4 py-2 w-full"
                    value={end}
                    onChange={(e) => setEnd(e.target.value)}
                />
            </div>

            <button
                onClick={submit}
                disabled={loading}
                className={`px-4 py-2 rounded-lg text-white ${
                    loading ? "bg-gray-400 cursor-not-allowed" : "bg-[#009EF7]"
                }`}
            >
                {loading ? "Memproses..." : "Booking ruangan"}
            </button>
        </div>
    );
}
