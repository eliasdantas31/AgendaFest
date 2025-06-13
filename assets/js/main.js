console.log("🧪 window.username:", window.username);

document.addEventListener("DOMContentLoaded", () => {
    const path = window.location.pathname;

    if (path.includes("login.php") || path.includes("signup.php")) {
        return;
    }

    const username = window.username;
    const isLoggedIn = username && username !== 'null';

    // Header principal como navbar
    const header = document.createElement("header");
    header.setAttribute("class", "header navbar navbar-expand-lg bg-light px-3");
    document.body.prepend(header);

    // Título / logo
    const title = document.createElement("a");
    title.className = "navbar-brand";
    title.href = "./index.php";
    title.innerText = "AgendaFest";
    header.appendChild(title);

    // Botão hamburguer (mobile)
    const toggleButton = document.createElement("button");
    toggleButton.type = "button";
    toggleButton.className = "navbar-toggler";
    toggleButton.setAttribute("data-bs-toggle", "collapse");
    toggleButton.setAttribute("data-bs-target", "#menu");

    const span = document.createElement("span");
    span.className = "navbar-toggler-icon";
    toggleButton.appendChild(span);
    header.appendChild(toggleButton);

    // Menu colapsável
    const menuDiv = document.createElement("div");
    menuDiv.className = "collapse navbar-collapse justify-content-end";
    menuDiv.id = "menu";
    header.appendChild(menuDiv);

    // Container interno do menu
    const nav = document.createElement("ul");
    nav.className = "navbar-nav d-flex flex-row flex-lg-row align-items-center gap-3 m-0";
    menuDiv.appendChild(nav);

    // Botão "Criar Evento"
    if (!path.includes("createEvent.php")) {
        const liCreate = document.createElement("li");
        liCreate.className = "nav-item";

        const a_createEvent = document.createElement("a");
        a_createEvent.className = "btn text-white";
        a_createEvent.href = isLoggedIn ? "./createEvent.php" : "./login.php";
        a_createEvent.innerText = "Criar Evento";

        liCreate.appendChild(a_createEvent);
        nav.appendChild(liCreate);
    }

    // Login/Profile
    const liUser = document.createElement("li");
    liUser.className = "nav-item d-flex align-items-center";

    const a = document.createElement("a");
    a.className = "nav-link";
    a.href = isLoggedIn ? "./profile.php" : "./login.php";

    const i = document.createElement("i");
    i.className = "bi bi-person-fill fs-4";
    a.appendChild(i);

    const p = document.createElement("p");
    p.className = "m-0 ps-2";
    p.innerText = isLoggedIn ? `Olá, ${username}` : "Login";

    liUser.appendChild(a);
    liUser.appendChild(p);
    nav.appendChild(liUser);
});