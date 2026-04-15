/*
    Funzione chiamata alla pressione del button 'Mostra prenotazioni'
*/
function showInput(id) {
    /* Rimuovo eventuali prenotazioni dal pannello centrale */
    const list = document.getElementById("showReserv");
    while (list.firstChild) {
        list.removeChild(list.firstChild);
    }
    /* Rimuovo l'input di tipo 'date' */
    const old = document.getElementById("reservDate");
    const lab = document.getElementById("infoLabel");
    if (old)
        old.remove();
    if (lab)
        lab.remove();
    /* Ristampo l'input di tipo 'date' */
    const div = document.getElementById("showInput");
    const label = document.createElement("label");
    const date = document.createElement("input");
    label.textContent = "Seleziona una data per continuare";
    label.setAttribute("class", "infoLabel");
    label.id = "infoLabel";
    date.setAttribute("type", "date");
    date.id = "reservDate";
    /* Le prenotazioni vengono caricare quando viene modificato l'input 'date' */
    date.addEventListener("change", (e) => {getReserv(Number(id), e)});
    div.appendChild(label);
    div.appendChild(date);
}

/*
    Funzione che richiede al server la lista delle prenotazioni dati id del ristorante e data
*/
async function getReserv(resid, e) {
    if (e.target.value == "")
        return;
    try {
        const response = await fetch("../php/reservationlist.php", 
            {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({
               resid: resid,
               date: e.target.value
            })
        });
        if (!response.ok) throw new Error('HTTP error');

        const jsonData = await response.json();
        if (!jsonData.success) {
            switch (jsonData.error) {
                case 'unauthorized_user':
                    window.location.href = "index.php";
                    return;
                case 'not_logged':
                    window.location.href = "login.php";
                    return;
                case 'bad_date':
                    alert("La data inserita non è valida");
                    return;
                case 'bad_resid':
                    alert("L'id del ristorante non è valido");
                    return;
                case 'db_error':
                    window.location.href = "../html/error.html";
                    return;
            }
        }
        /* Il server restituisce una lista per il pranzo e una per la cena */
        showReserv(jsonData.lunch, jsonData.dinner);
    }
    catch (error) {
        console.error(error);
        window.location.href = "../html/error.html";
    }
}

/*
    Stampa della lista divisa tra pranzo e cena
*/
function showReserv(lunch, dinner) {
    const div = document.getElementById("showReserv");
    while (div.firstChild) {
        div.removeChild(div.firstChild);
    }
    /* Se entrambe le liste sono null non è stato aggiunto alcun turno */
    if (!lunch && !dinner) {
        const mess = document.createElement("p");
        mess.textContent = "Non sono stati aggiunti turni per il locale selezionato";
        div.appendChild(mess);
        return;
    }
    if (lunch) {
        const type0 = document.createElement("p");
        type0.setAttribute("class", "intText");
        if (lunch.length == 0) {
            type0.textContent = "Nessuna prenotazione presente per pranzo.";
        }
        else {
            type0.textContent = "Pranzo";
        }
        div.appendChild(type0);
        let lunchSum = 0;
        for (let row of lunch) {
            const p = document.createElement("p");
            p.textContent = " - " + row['Name'] + " " + row['Surname'] + "; Per: " + row['Covers'] + "; Ora: " + row['Hour'];
            div.appendChild(p);
            lunchSum += Number(row['Covers']);
        }
        const tot0 = document.createElement("p");
        tot0.textContent = "Totale coperti: " + lunchSum;
        tot0.setAttribute("class", "sum");
        div.appendChild(tot0);
    }
    if (dinner) {
        const type1 = document.createElement("p");
        type1.setAttribute("class", "intText");
        if (dinner.length == 0) {
            type1.textContent = "Nessuna prenotazione presente per cena.";
        }
        else {
            type1.textContent = "Cena";
        }
        div.appendChild(type1);
        let dinnerSum = 0;
        for (let row of dinner) {
            const p = document.createElement("p");
            p.textContent = " - " + row['Name'] + " " + row['Surname'] + "; Per: " + row['Covers'] + "; Ora: " + row['Hour'];
            div.appendChild(p);
            dinnerSum += Number(row['Covers']);
        }
        const tot1 = document.createElement("p");
        tot1.textContent = "Totale coperti: " + dinnerSum;
        tot1.setAttribute("class", "sum");
        div.appendChild(tot1);
    }
}

/*
    Funzione che recupera la lista delle eccezioni dati l'id del ristorante e il tipo di turno
*/
async function getExceptions(event) {
    try {
        const response = await fetch("../php/getexceptions.php", 
            {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({
               resid: event.target.dataset.resid,
               shift: event.target.dataset.shift
            })
        });
        if (!response.ok) throw new Error('HTTP error');

        const jsonData = await response.json(); 
        if (!jsonData.success) {
            switch (jsonData.error) {
                case 'unauthorized_user':
                    window.location.href = "index.php";
                    return;
                case 'not_logged':
                    window.location.href = "login.php";
                    return;
                case 'bad_resid':
                    alert("L'id del ristorante non è valido");
                    return;
                case 'bad_shift':
                    alert("Turno non valido");
                    return;
                case 'db_error':
                    window.location.href = "../html/error.html";
                    return;
            }
        }

        /* Stampa informazioni nel pannello delle eccezioni */
        const form = document.getElementById("exform");
        const info = document.getElementById("info");
        const button = document.getElementById("exbutton");
        button.dataset.resid = event.target.dataset.resid;
        button.dataset.shift = event.target.dataset.shift;
        str = (event.target.dataset.shift == '0')? "Pranzo" : "Cena";
        info.textContent = "Ristorante: " + event.target.dataset.resname + ", " + str;
        form.style.display = "flex";

        showExceptions(jsonData.data);
    }
    catch (error) {
        console.error(error);
        window.location.href = "../html/error.html";
    }
}

/*
    Funzione che stampa la lista delle eccezioni
*/
function showExceptions(exceptions) {
    const div = document.getElementById("exlist");
    while (div.firstChild) {
        div.removeChild(div.lastChild);
    }
    if (exceptions.length == 0) {
        const p = document.createElement("p");
        p.textContent = "Nessuna eccezione impostata per questo turno.";
        div.appendChild(p);
        return;
    }
    let i = 1;
    for (let exception of exceptions) {
        const panel = document.createElement("div");
        panel.setAttribute("class", "exrow");
        const p = document.createElement("p");
        const img = document.createElement("img");
        const url = "removeexception.php?id=" + exception['Id'];
        img.setAttribute("src", "../images/trash.svg");
        img.setAttribute("class", "remove");
        img.setAttribute("alt", "rimuovi");
        img.setAttribute("data-url", url);
        img.addEventListener("click", (e) => {window.location.href = e.target.dataset.url;});
        p.textContent = i++ + ") " + exception['Date'];
        panel.appendChild(p);
        panel.appendChild(img);
        div.appendChild(panel);
    }
}

async function addException(event) {
    const input = document.getElementById("exdata");
    if (event.target.dataset.resid == "" || event.target.dataset.shift == "" || input.value == "") {
        console.log("Input non valido");
        return;
    }
    try {
        const response = await fetch("../php/addexception.php", 
            {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({
               resid: event.target.dataset.resid,
               shift: event.target.dataset.shift,
               date: input.value
            })
        });
        if (!response.ok) throw new Error('HTTP error');

        const jsonData = await response.json(); 
        if (!jsonData.success) {
            switch (jsonData.error) {
                case 'unauthorized_user':
                    window.location.href = "index.php";
                    return;
                case 'not_logged':
                    window.location.href = "login.php";
                    return;
                case 'bad_date':
                    alert("La data inserita non è valida");
                    return;
                case 'bad_resid':
                    alert("L'id del ristorante non è valido");
                    return;
                case 'bad_shift':
                    alert("Turno non valido");
                    return;
                case 'repeated_exception':
                    alert("Hai già aggiunto un eccezione per questa data");
                    return;
                case 'db_error':
                    window.location.href = "../html/error.html";
                    return;
            }
        }
        
        location.reload();
    }
    catch (error) {
        console.error(error);
        window.location.href = "../html/error.html";
    }
}

/*
    Funzione che setta l'id del ristorante in $_SESSION e successivamente indirizza alla pagina addshift.php
*/
async function setResId(id) {
    try {
        const response = await fetch("../php/setrestaurantid.php", 
            {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({
               resid: id
            })
        });
        if (!response.ok) throw new Error('HTTP error');

        const jsonData = await response.json();
        if (!jsonData.success) {
            switch (jsonData.error) {
                case 'not_logged':
                    window.location.href = "login.php";
                    return;
                case 'bad_resid':
                    alert("L'id del ristorante non è valido");
                    return;
                case 'db_error':
                    window.location.href = "../html/error.html";
                    return;
            }
        }

        window.location.href = "addshift.php"; 
    }
    catch (error) {
        console.error(error);
        window.location.href = "../html/error.html";
    }
}

function removeRest(event) {
    if (confirm("Attenzione! La rimozione del ristorante comporterà la rimozione di tutte le prenotazioni associate e delle eventuali informazioni sui turni, continuare?")) {
        window.location.href = event.target.dataset.url;
    }
}

function init() {
    const images = document.querySelectorAll("img");
    for (let image of images) {
        image.addEventListener("click", (e) => {
            if (e.target.className == "remove") {
                if (!confirm("Attenzione! La rimozione del turno comporterà la perdita delle eccezioni aggiunte, continuare?")) {
                    return;
                }
            }
            if (e.target.dataset.url)
                window.location.href = e.target.dataset.url;
        });
    }
}