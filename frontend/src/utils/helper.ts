export const dateFormatter = (tanggalString: string): string => {
    if (!tanggalString) return "-";

    const date = new Date(tanggalString);

    return new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
    }).format(date);
};

export const hourFormatter = (jamString: string): string => {
    if (!jamString) return "-";

    const [jam, menit, detik] = jamString.split(":");
    const date = new Date();
    date.setHours(parseInt(jam), parseInt(menit), parseInt(detik));

    const formattedTime = new Intl.DateTimeFormat("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
    }).format(date);

    return `${formattedTime.replace(":", ".")}`;
};
