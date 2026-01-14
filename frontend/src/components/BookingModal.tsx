import { useState } from "react";
import { createBooking } from "../api/bookingApi";

interface Props {
    roomId: number;
    onClose: () => void;
    onSuccess?: () => void;
}

export default function BookingModal({ roomId, onClose, onSuccess }: Props) {
    const [date, setDate] = useState("");
    const [start, setStart] = useState("");
    const [end, setEnd] = useState("");
    const [note, setNote] = useState("");
    const [loading, setLoading] = useState(false);

    const submit = async () => {
        if (!date || !start || !end) {
            alert("Tanggal dan waktu wajib diisi");
            return;
        }

        try {
            setLoading(true);

            await createBooking({
                user_id: 22, // nanti dari auth
                room_id: roomId,
                booking_date: date,
                start_time: start,
                end_time: end,
            });

            alert("Booking berhasil diajukan");
            onSuccess?.();
            onClose();
        } catch {
            alert("Gagal membuat booking");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div className="bg-white rounded-xl w-full max-w-3xl p-8">
                {/* Header */}
                <h2 className="text-2xl font-semibold mb-6">
                    Form Peminjaman Ruangan
                </h2>

                {/* Form */}
                <div className="grid grid-cols-3 gap-6">
                    {/* Tanggal */}
                    <div>
                        <label className="text-sm text-gray-600">
                            Tanggal Peminjaman
                        </label>
                        <input
                            type="date"
                            className="w-full mt-2 px-4 py-3 rounded-lg bg-gray-50 border"
                            value={date}
                            onChange={(e) => setDate(e.target.value)}
                        />
                    </div>

                    {/* Waktu mulai */}
                    <div>
                        <label className="text-sm text-gray-600">
                            Waktu Mulai
                        </label>
                        <input
                            type="time"
                            className="w-full mt-2 px-4 py-3 rounded-lg bg-gray-50 border"
                            value={start}
                            onChange={(e) => setStart(e.target.value)}
                        />
                    </div>

                    {/* Waktu selesai */}
                    <div>
                        <label className="text-sm text-gray-600">
                            Waktu Selesai
                        </label>
                        <input
                            type="time"
                            className="w-full mt-2 px-4 py-3 rounded-lg bg-gray-50 border"
                            value={end}
                            onChange={(e) => setEnd(e.target.value)}
                        />
                    </div>
                </div>

                {/* Keperluan */}
                <div className="mt-6">
                    <label className="text-sm text-gray-600">
                        Keperluan Peminjaman
                    </label>
                    <textarea
                        className="w-full mt-2 px-4 py-3 rounded-lg bg-gray-50 border min-h-[120px]"
                        placeholder="Tuliskan keterangan peminjaman"
                        value={note}
                        onChange={(e) => setNote(e.target.value)}
                    />
                </div>

                {/* Action */}
                <div className="flex gap-4 mt-8">
                    <button
                        onClick={submit}
                        disabled={loading}
                        className="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    >
                        Pinjam Kelas
                    </button>

                    <button
                        onClick={onClose}
                        className="bg-blue-100 text-blue-600 px-6 py-3 rounded-lg"
                    >
                        Reset
                    </button>
                </div>
            </div>
        </div>
    );
}
