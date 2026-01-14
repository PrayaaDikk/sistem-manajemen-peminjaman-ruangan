import { useEffect, useState } from "react";
import { getRooms } from "../../api/roomApi";
import type { Room } from "../../types/room";
import RoomCard from "../../components/RoomCard";
import { useNavigate } from "react-router-dom";

export default function RoomsPage() {
    const [rooms, setRooms] = useState<Room[]>([]);
    const [search, setSearch] = useState("");
    const [floor, setFloor] = useState<string | null>(null);

    const navigate = useNavigate();

    useEffect(() => {
        getRooms().then(setRooms);
    }, []);

    const filteredRooms = rooms.filter((room) => {
        const matchSearch = room.room_name
            .toLowerCase()
            .includes(search.toLowerCase());

        const matchFloor = floor ? room.location === floor : true;

        return matchSearch && matchFloor;
    });

    return (
        <div className="p-6">
            <div className="space-y-6">
                <h1 className="text-3xl font-semibold">Daftar Ruangan</h1>
                <div className="h-px bg-[#B5B5C3] "></div>

                <input
                    type="text"
                    placeholder="Cari ruangan..."
                    className="border border-[#B5B5C3] rounded-lg px-4 py-2 w-full max-w-md mb-4"
                    value={search}
                    onChange={(e) => setSearch(e.target.value)}
                />
            </div>

            <div className="flex gap-2 mb-6">
                {["Lantai 1", "Lantai 2", "Lantai 3"].map((l) => (
                    <button
                        key={l}
                        onClick={() => setFloor(l)}
                        className={`px-4 py-2 rounded text-sm ${
                            floor === l
                                ? "bg-[#009EF7] text-white"
                                : "bg-[#C6E5FF] text-[#009EF7]"
                        }`}
                    >
                        {l}
                    </button>
                ))}
                <button
                    onClick={() => setFloor(null)}
                    className="px-4 py-2 text-sm text-gray-600"
                >
                    Semua
                </button>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {filteredRooms.map((room) => (
                    <RoomCard
                        key={room.id}
                        room={room}
                        onBook={() => navigate(`/booking/${room.id}`)}
                    />
                ))}
            </div>
        </div>
    );
}
