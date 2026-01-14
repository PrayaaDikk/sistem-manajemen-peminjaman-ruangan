export type BookingStatus = "pending" | "approved" | "rejected" | "completed";

export const bookingStatusLabel: Record<BookingStatus, string> = {
    pending: "Menunggu persetujuan",
    approved: "Disetujui",
    rejected: "Ditolak",
    completed: "Selesai",
};

export const bookingStatusClass: Record<BookingStatus, string> = {
    pending: "bg-yellow-100 text-yellow-700",
    approved: "bg-blue-100 text-blue-700",
    rejected: "bg-red-100 text-red-700",
    completed: "bg-green-100 text-green-700",
};
