console.log("🧪 window.username:", window.username);

document.addEventListener("DOMContentLoaded", () => {
    const path = window.location.pathname;

    if (path.includes("login.php") || path.includes("signup.php")) {
        return;
    }

    const username = window.username;
    const isLoggedIn = username && username !== 'null';

    const header = document.createElement("header");
    header.setAttribute("class", "header navbar navbar-expand-lg px-3");
    document.body.prepend(header);

    const title = document.createElement("a");
    title.className = "header-title navbar-brand m-0";
    title.href = "./index.php";
    title.innerText = "AgendaFest";
    header.appendChild(title);

    const toggleButton = document.createElement("button");
    toggleButton.type = "button";
    toggleButton.className = "navbar-toggler";
    toggleButton.setAttribute("data-bs-toggle", "collapse");
    toggleButton.setAttribute("data-bs-target", "#menu");

    const span = document.createElement("span");
    span.className = "navbar-toggler-icon";
    toggleButton.appendChild(span);
    header.appendChild(toggleButton);

    const menuDiv = document.createElement("div");
    menuDiv.className = "collapse navbar-collapse";
    menuDiv.id = "menu";
    header.appendChild(menuDiv);

    const nav = document.createElement("ul");
    nav.className = "navbar-nav d-flex flex-row flex-lg-row align-items-center gap-3 m-0";
    menuDiv.appendChild(nav);

    if (!path.includes("createEvent.php")) {
        const liCreate = document.createElement("li");
        liCreate.className = "creatBtn nav-item";

        const a_createEvent = document.createElement("a");
        a_createEvent.className = "btn";
        a_createEvent.href = isLoggedIn ? "./createEvent.php" : "./login.php";
        a_createEvent.innerText = "Criar Evento";

        liCreate.appendChild(a_createEvent);
        nav.appendChild(liCreate);
    }

    const liUser = document.createElement("li");
    liUser.className = "userBtn nav-item";

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
    a.appendChild(p);
    nav.appendChild(liUser);

    if (!path.includes("profile.php") && !path.includes("editEvent.php") && !path.includes("login.php") && !path.includes("signup.php") && !path.includes("forgotPass.php") && !path.includes("eventos.php")) {
        const footer = document.createElement("footer");
        footer.className = "footer";
        document.body.appendChild(footer);

        const pFooter = document.createElement("p");
        pFooter.className = "text-center m-0";
        pFooter.innerText = "Criado por Elias Lima Leite e Guilherme da Costa Claudino";
        footer.appendChild(pFooter);
    }
});

const deleteMenu = document.querySelector(".delete-menu");

function showdeleteMenu(id) {
    const modal = document.getElementById(`deleteModal-${id}`);
    if (modal) {
        modal.classList.add('active');
    }
}
function hidedeleteMenu(id) {
    const modal = document.getElementById(`deleteModal-${id}`);
    if (modal) {
        modal.classList.remove('active');
    }
}