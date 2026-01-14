import { Outlet } from "react-router-dom";
import Sidebar from "../components/Sidebar";

export default function MainLayout({ userId }: { userId: number }) {
    return (
        <div className="flex bg-white min-h-screen">
            <Sidebar userId={userId} />
            <main className="flex-1 p-8">
                <Outlet />
            </main>
        </div>
    );
}
