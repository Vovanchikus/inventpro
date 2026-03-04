const API_BASE_URL = "https://inventpro.local/api/v1";

async function login(login, password) {
    const response = await fetch(`${API_BASE_URL}/auth/login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ login, password }),
    });

    if (!response.ok) {
        throw new Error("Login failed");
    }

    const payload = await response.json();
    return payload.data;
}

async function loadProducts(accessToken, page = 1) {
    const response = await fetch(
        `${API_BASE_URL}/products?page=${page}&per_page=20`,
        {
            headers: {
                Authorization: `Bearer ${accessToken}`,
                Accept: "application/json",
            },
        },
    );

    if (!response.ok) {
        throw new Error("Products request failed");
    }

    const payload = await response.json();
    return payload.data;
}

export { login, loadProducts };
