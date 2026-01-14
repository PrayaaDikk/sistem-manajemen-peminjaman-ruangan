import { BrowserRouter, Routes, Route } from "react-router-dom";
import MainLayout from "./layouts/MainLayout";
import RoomsPage from "./pages/user/RoomsPage";
import Borrowing from "./pages/user/Borrowing";
import BookingCreate from "./pages/user/BookingCreate";
import BookingAdmin from "./pages/admin/BookingAdmin";
import AdminLayout from "./layouts/AdminLayout";

export default function App() {
    return (
        <BrowserRouter>
            <Routes>
                <Route element={<MainLayout />}>
                    <Route path="/" element={<RoomsPage />} />
                    <Route
                        path="/booking/:roomId"
                        element={<BookingCreate />}
                    />
                    <Route path="/borrowing" element={<Borrowing />} />
                </Route>
                <Route element={<AdminLayout />}>
                    <Route path="/admin/booking" element={<BookingAdmin />} />
                </Route>
            </Routes>
        </BrowserRouter>
    );
}
