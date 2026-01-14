import type { Room } from "../types/room";

interface Props {
    room: Room;
    onBook: () => void;
}

export default function RoomCard({ room, onBook }: Props) {
    const statusMap = {
        available: {
            label: "Tersedia",
            className: "bg-[#C5FFCA] text-[#00AE1C]",
        },
        booked: {
            label: "Dipesan",
            className: "bg-[#FFC5C5] text-[#DE2828]",
        },
    };

    const status = statusMap[room.status];

    return (
        <div
            onClick={room.status === "available" ? onBook : undefined}
            className={`bg-[#F5F8FA] rounded-lg p-4 flex justify-between items-center transition-all ${
                room.status === "available"
                    ? "cursor-pointer hover:bg-[#ebf0f3] active:scale-[0.98]"
                    : "cursor-default"
            }`}
        >
            <div>
                <h3 className="font-semibold text-gray-900">
                    {room.room_name}
                </h3>
                <p className="text-sm text-gray-500">{room.location}</p>
            </div>

            <div className="flex items-center">
                <span
                    className={`px-3 py-1 text-xs rounded font-medium ${status.className}`}
                >
                    {status.label}
                </span>
            </div>
        </div>
    );
}
