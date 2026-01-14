import { useEffect, useState } from "react";
import { myBookings } from "../../api/bookingApi";
import type { Booking } from "../../types/booking";
import { BookingItem } from "../../components/BookingItem";

export default function Borrowing({ userId }: { userId: number }) {
    const [ongoing, setOngoing] = useState<Booking[]>([]);
    const [history, setHistory] = useState<Booking[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        loadBookings();
    }, []);

    const loadBookings = async () => {
        try {
            const res = await myBookings(userId);

            if (!res.data.success) return;

            const data: Booking[] = res.data.data;

            setOngoing(
                data.filter(
                    (b) => b.status === "pending" || b.status === "approved"
                )
            );

            setHistory(
                data.filter(
                    (b) => b.status === "completed" || b.status === "rejected"
                )
            );
        } catch {
            alert("Gagal mengambil data peminjaman");
        } finally {
            setLoading(false);
        }
    };

    if (loading) {
        return <p className="text-gray-500">Memuat data...</p>;
    }

    return (
        <div className="px-10 py-9">
            <div className="space-y-6 mb-6">
                <h1 className="text-3xl font-semibold">Peminjaman Ruangan</h1>
                <div className="h-px bg-[#B5B5C3]"></div>
            </div>

            <h2 className="text-[#009EF7] font-semibold mb-3">
                Sedang berlangsung
            </h2>

            {ongoing.length === 0 ? (
                <p className="text-gray-500 text-sm">
                    Tidak ada peminjaman aktif
                </p>
            ) : (
                ongoing.map((b) => <BookingItem key={b.id} booking={b} />)
            )}

            <h2 className="text-[#009EF7] font-semibold mt-8 mb-3">
                Riwayat peminjaman
            </h2>

            {history.length === 0 ? (
                <p className="text-gray-500 text-sm">
                    Belum ada riwayat peminjaman
                </p>
            ) : (
                history.map((b) => <BookingItem key={b.id} booking={b} />)
            )}
        </div>
    );
}
