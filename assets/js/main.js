document.addEventListener("DOMContentLoaded", () => {
    const path = window.location.pathname;

    if (path.includes("login.php")) {
        console.log("Página de login carregada com sucesso!");
    } else {
        let header = document.createElement("header");
        header.setAttribute("class", "header d-flex justify-content-around align-items-center");
        document.body.appendChild(header);

        let title = document.createElement("a");
        title.setAttribute("class", "header-title");
        title.setAttribute("href", "./index.php");
        title.innerText = "AgendaFest";
        header.appendChild(title);

        let div = document.createElement("div");
        div.setAttribute("class", "d-flex justify-content-around align-items-center");
        header.appendChild(div);

        let a_createEvent = document.createElement("a");
        a_createEvent.setAttribute("class", "header-creat-btn");
        a_createEvent.setAttribute("href", "./createEvent.php");
        a_createEvent.innerText = "Criar Evento";
        div.appendChild(a_createEvent);

        let ul = document.createElement("ul");
        ul.setAttribute("class", "d-flex justify-content-around align-items-center m-0 p-0");
        div.appendChild(ul);

        const username = window.username;
        let li = document.createElement("li");
        let a = document.createElement("a");
        let i = document.createElement("i");
        i.setAttribute("class", "bi bi-person-fill");
        a.setAttribute("class", "header-btn");
        a.setAttribute("href", "./login.php");
        a.appendChild(i);
        li.appendChild(a);
        ul.appendChild(li);

        if (username !== null) {
            let p = document.createElement("p");
            p.setAttribute("class", "header-p m-0 p-2");
            p.innerText = `Olá, ${username}`;
            li.appendChild(p);
        } else {
            let p = document.createElement("p");
            p.setAttribute("class", "header-p m-0 p-2");
            p.innerText = "Login";
            li.appendChild(p);
        }
    }
});