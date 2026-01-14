export interface Room {
    id: number;
    room_name: string;
    capacity: number;
    location: "Lantai 1" | "Lantai 2" | "Lantai 3";
    status: "available" | "booked";
}
