import { bookingStatusClass, bookingStatusLabel } from "../utils/bookingStatus";
import type { Booking } from "../types/booking";
import { dateFormatter, hourFormatter } from "../utils/helper";

interface Props {
    booking: Booking;
}

export function BookingItem({ booking }: Props) {
    return (
        <div className="w-full overflow-x-auto">
            <table className="w-full border-separate border-spacing-y-3 min-sm:table-fixed">
                <tbody>
                    <tr className="bg-[#F5F8FA]">
                        <td className="px-6 py-4 rounded-l-lg">
                            <p className="font-semibold text-gray-900 truncate">
                                {booking.room_name}
                            </p>
                        </td>

                        <td className="w-1/4 px-6 py-4">
                            <p className="font-medium text-[#1F1F1F99] whitespace-nowrap">
                                {dateFormatter(booking.booking_date)}
                            </p>
                        </td>

                        <td className="w-1/4 px-6 py-4">
                            <p className="font-medium text-[#1F1F1F99] whitespace-nowrap">
                                {hourFormatter(booking.start_time)} WITA
                            </p>
                        </td>

                        <td className="w-[145px] px-6 py-4 rounded-r-lg text-right">
                            <div className="flex justify-end">
                                <span
                                    className={`inline-block px-3 py-1 rounded text-sm font-medium whitespace-nowrap text-center ${
                                        bookingStatusClass[booking.status]
                                    }`}
                                >
                                    {bookingStatusLabel[booking.status]}
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    );
}
