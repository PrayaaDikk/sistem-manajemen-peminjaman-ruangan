import api from "./axios";

export interface User {
    id: number;
    name: string;
    email: string;
}

export const getUserById = (id: number) =>
    api.get<{ success: boolean; data: User }>(`?path=users/show&id=${id}`);
