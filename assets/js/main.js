console.log("🧪 window.username:", window.username);

document.addEventListener("DOMContentLoaded", () => {
    const path = window.location.pathname;

    if (path.includes("login.php") || path.includes("signup.php")) {
        return;
    }

    const username = window.username;
    const isLoggedIn = username && username !== 'null';

    const header = document.createElement("header");
    header.setAttribute("class", "header d-flex justify-content-around align-items-center");
    document.body.prepend(header);

    const title = document.createElement("a");
    title.className = "header-title";
    title.href = "./index.php";
    title.innerText = "AgendaFest";
    header.appendChild(title);

    const div = document.createElement("div");
    div.className = "d-flex justify-content-around align-items-center";
    header.appendChild(div);

    const a_createEvent = document.createElement("a");
    a_createEvent.className = "header-creat-btn";
    a_createEvent.href = isLoggedIn ? "./createEvent.php" : "./login.php";
    a_createEvent.innerText = "Criar Evento";
    div.appendChild(a_createEvent);

    const ul = document.createElement("ul");
    ul.className = "d-flex justify-content-around align-items-center m-0 p-0";
    div.appendChild(ul);

    const li = document.createElement("li");
    const a = document.createElement("a");
    const i = document.createElement("i");
    i.className = "bi bi-person-fill";
    a.className = "header-btn";
    a.href = isLoggedIn ? "./profile.php" : "./login.php";
    a.appendChild(i);

    const p = document.createElement("p");
    p.className = "header-p m-0 p-2";
    p.innerText = isLoggedIn ? `Olá, ${username}` : "Login";

    li.appendChild(a);
    li.appendChild(p);
    ul.appendChild(li);
});