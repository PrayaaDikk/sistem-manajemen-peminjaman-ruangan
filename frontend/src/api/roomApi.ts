import axios from "./axios";
import type { Room } from "../types/room";

export const getRooms = async (): Promise<Room[]> => {
    const res = await axios.get("/?path=rooms");
    return res.data.data;
};
