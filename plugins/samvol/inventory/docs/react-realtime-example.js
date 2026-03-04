import Echo from "laravel-echo";
import Pusher from "pusher-js";

export function createInventoryRealtimeClient({
    apiBaseUrl,
    wsHost,
    wsPort,
    wsKey,
    token,
    organizationId,
    onChanged,
}) {
    window.Pusher = Pusher;

    const echo = new Echo({
        broadcaster: "pusher",
        key: wsKey,
        wsHost,
        wsPort,
        forceTLS: false,
        disableStats: true,
        enabledTransports: ["ws", "wss"],
        authEndpoint: `${apiBaseUrl}/realtime/auth`,
        auth: {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        },
    });

    echo.private(`org.${organizationId}.inventory`).listen(
        ".inventory.entity.changed",
        (event) => {
            onChanged?.(event);
        },
    );

    return echo;
}
