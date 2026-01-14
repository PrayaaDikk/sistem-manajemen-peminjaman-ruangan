import { NavLink } from "react-router-dom";
import { useEffect, useState } from "react";
import profileImg from "../assets/img/profile-picture.png";
import { getUserById } from "../api/userApi";
import type { User } from "../api/userApi";

export default function Sidebar() {
    const userId = 19;

    const [user, setUser] = useState<User | null>(null);

    const loadUser = async () => {
        try {
            const res = await getUserById(userId);
            if (res.data.success) {
                setUser(res.data.data);
            }
        } catch {
            console.error("Gagal mengambil data user");
        }
    };

    useEffect(() => {
        (async () => {
            await loadUser();
        })();
    }, []);

    const linkClass = ({ isActive }: { isActive: boolean }) =>
        `flex items-center px-4 py-3 rounded-lg text-sm transition ${
            isActive
                ? "bg-white/90 text-blue-700 font-medium"
                : "text-gray-600 hover:bg-gray-100"
        }`;

    return (
        <aside className="w-80 min-h-screen bg-[#C6E5FF] border-r px-6 py-14 space-y-10">
            <h1 className="text-lg font-bold leading-snug">
                Sistem Manajemen Peminjaman Ruangan
            </h1>

            {/* Profile */}
            <div className="text-center space-y-3">
                <div className="size-36 rounded-full overflow-hidden mx-auto shadow">
                    <img
                        src={profileImg}
                        alt="Profile"
                        className="w-full h-full object-cover"
                    />
                </div>

                <div>
                    <h1 className="text-lg font-semibold text-gray-800">
                        {user ? user.name : "Loading..."}
                    </h1>
                    <p className="text-sm">
                        {user ? user.email : "Loading..."}
                    </p>
                </div>
            </div>

            {/* Navigation */}
            <nav className="space-y-2">
                <NavLink to="/" className={linkClass}>
                    Daftar Ruangan
                </NavLink>

                <NavLink to="/borrowing" className={linkClass}>
                    Peminjaman
                </NavLink>
            </nav>
        </aside>
    );
}
