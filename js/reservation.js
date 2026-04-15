/*
    Utilizzo 3 variabili globali per gestire la pagina di prenotazione:
     - selectedDate tiene traccia della data selezionata dall'utente attraverso il calendario
     - shiftData contiene le informazioni sui turni del locale per una certa data
     - data contiene le informazioni sul ristorante
*/
let selectedDate = new Date();
let shiftData = null;
let data = null;

/*
    Chiamata a 'reservation.php' per ottenere i dati del ristorante
*/
async function getResData() {
    try {
        const response = await fetch("../php/reservation.php");
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
                case 'unset_RID':
                    alert("RID non settato");
                    return;
                case 'bad_resid':
                    alert("L'id del ristorante non è valido");
                    return;
                case 'db_error':
                    window.location.href = "../html/error.html";
                    return;
            }
        }

        return jsonData;
    } 
    catch (error) {
        console.error(error);
        window.location.href = "../html/error.html";
    }
}

/*
    Chiamata a 'checkcovers.php' per ottenere le informazioni sui turni
*/
async function getShiftData(id, date, day) {
    try {
        const response = await fetch("../php/checkcovers.php", 
            {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({
               resid: id,
               date: date,
               day: day
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
                case 'bad_input':
                    alert("Input non valido");
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

        return jsonData.data;
    }
    catch (error) {
        console.error(error);
        window.location.href = "../html/error.html";
    }
}

/*
    Chiamata a 'summary.php' per salvare i dati della prenotazione in $_SESSION
*/
async function sendData(resid, date, shift, hour, clients) {
    try {
        const response = await fetch("../php/summary.php", 
            {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({
               resid: resid,
               date: date,
               shift: shift,
               hour: hour,
               clients: clients
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
                case 'bad_input':
                    alert("Input non valido");
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

        window.location.href = "../php/views/summary.php";
    }
    catch (error) {
        console.error(error);
        window.location.href = "../html/error.html";
    }
}

/* -- Funzioni di utilità -- */

/* Formatta year, month e day come 'YYYY-MM-DD' */
function dateFormat(year, month, day) {
    return year + "-" + month + "-" + day;
}

/* Calcola i giorni di apertura durante la settimana per i due turni */
function checkShifts(array) {
    let openingDays = [];
    for (let k = 0; k < array.length; k++) {
        let string = array[k]['Days'];
        for (let i = 0; i < string.length; i++) {
            if (string[i] == "o") {
                openingDays.push(i);
            }
        }
    }
    return openingDays;
}

/* Aggiorna il massimo numero di coperti per input[type='number'] */
function updateNumber(covers) {
    const input = document.getElementById("clients");
    input.setAttribute("max", covers);
}

/* Controlla la validità di input[type='number'] */
function checkClients() {
    const input = document.getElementById("clients");
    return input.validity.valid;
}

/* Funzione che svuota la sezione del calendario */
function removeCalendar() {
    const tab = document.querySelector("table");
    const prev = document.getElementById("prev");
    const next = document.getElementById("next");
    const month = document.getElementById("month");
    const header = document.getElementById("tabHeader");
    const cont = document.getElementById("buttonCont");
    prev.remove();
    next.remove();
    month.remove();
    tab.remove();
    header.remove();
    cont.remove();
}

/* Funzione che muove il calendario un mese indietro */
function prevMonth() {
    let currentDate = new Date();
    if (currentDate.getMonth() == selectedDate.getMonth())
        return;
    if ((currentDate.getMonth() + 1) % 12 == selectedDate.getMonth()) {
        selectedDate = currentDate;
    } else {
        selectedDate = new Date(selectedDate.getFullYear(), selectedDate.getMonth() - 1);
    }
    removeCalendar();
    generateCalendar(data);
}

/* Funzione che muove il calendario un mese avanti */
function nextMonth() {
    selectedDate = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1);
    removeCalendar();
    generateCalendar(data);
}

/* Funzione che genera il calendario a partire dalle informazioni contenute in data */
function generateCalendar(data) {
    const openingDays = checkShifts(data.shifts); 

    let months = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", 
        "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];
    let days = ["lun", "mar", "mer", "gio", "ven", "sab", "dom"];

    const currentDate = selectedDate;
    const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    const startDay = (firstDay.getDay() + 6) % 7;

    /*
        Creazione elementi DOM
    */
    const div = document.getElementById("calendar");
    const tabHeader = document.createElement("div");
    const tab = document.createElement("table");
    const thead = document.createElement("thead");
    const tbody = document.createElement("tbody");
    const trh = document.createElement("tr");
    const prev = document.createElement("button");
    const cont = document.createElement("div");
    /*
        Setting degli attributi
    */
    cont.id = "buttonCont";
    tabHeader.id = "tabHeader";
    prev.id = "prev";
    prev.textContent = "prev";
    prev.classList.add("navButton");
    prev.setAttribute("onclick", "prevMonth()");
    const next = document.createElement("button");
    next.id = "next";
    next.textContent = "next";
    next.classList.add("navButton");
    next.setAttribute("onclick", "nextMonth()");
    const p = document.createElement("p");
    p.id = "month";
    p.textContent = months[currentDate.getMonth()] + " " + currentDate.getFullYear();
    /*
        Append dei nodi
    */
    tabHeader.appendChild(p);
    cont.appendChild(prev);
    cont.appendChild(next);
    tabHeader.appendChild(cont);
    div.appendChild(tabHeader);
    tab.appendChild(thead);
    tab.appendChild(tbody);
    thead.appendChild(trh);
    /*
        Generazione intestazione tabella
    */
    for (let i = 0; i < days.length; i++) {
        const td = document.createElement("td");
        td.textContent = days[i];
        td.classList.add("days");
        trh.appendChild(td);
    }
    /*
        Inserimento dei giorni del mese
    */
    let day = 1;
    for (let i = 0; i < days.length - 1; i++) {
        const tr = document.createElement("tr");
        for (let j = 0; j < days.length; j++) {
            const td = document.createElement("td");
            if (day > lastDay.getDate()) {
                tr.appendChild(td);
                continue;
            }
            if (j < startDay && i == 0) {
                td.textContent = "";
            } else if (day < currentDate.getDate()) {
                td.setAttribute("class", "past");
                td.textContent = day++;
            } else if (!openingDays.includes(j)) {
                td.setAttribute("class", "closed");
                td.textContent = day++;
            } else {
                td.setAttribute("class", "valid");
                td.addEventListener("click", calendarClick);
                td.textContent = day++;
            } 
            tr.appendChild(td);
        }
        tbody.appendChild(tr);
    }
    div.appendChild(tab);
}

/* Funzione che mostra gli orari disponibili */
function showShifts() {
    const mess = document.getElementById("mess");
    mess.textContent = "";
    if (shiftData.hasOwnProperty('Lunch') || shiftData.hasOwnProperty('Dinner')) {
        if (shiftData.hasOwnProperty('Lunch')) {
            let opening = hour(data.shifts[0]['Opening']);
            let closure = hour(data.shifts[0]['Closure']);
            showHours('lunch', opening, closure);
        }
        if (shiftData.hasOwnProperty('Dinner')) {
            let opening = hour(data.shifts[data.shifts.length - 1]['Opening']);
            let closure = hour(data.shifts[data.shifts.length - 1]['Closure']);
            showHours('dinner', opening, closure);
        }
        return;
    }
    mess.textContent = "Non ci sono orari disponibili per il giorno selezionato";
}

/* Funzione che svuota il pannello degli orari */
function removeHours() {
    const lunch = document.getElementById("lunch");
    const dinner = document.getElementById("dinner");
    while (lunch.firstChild) {
        lunch.removeChild(lunch.firstChild);
    }
    while (dinner.firstChild) {
        dinner.removeChild(dinner.firstChild);
    }
}

/* Funzione che mostra gli orari disponibili con un gap di 15 minuti */
function showHours(str, opening, closure) {
    let div;
    if (str === 'lunch') {
        div = document.getElementById("lunch");
    } else {
        div = document.getElementById("dinner");
    }
    let min;
    let num = (closure-1) - opening;
    for (let i = 0; i < num; i++) {
        for (let j = 0; j < 4; j++) {
            switch (j) {
                case 0:
                    min = ":00";
                    break;
                case 1:
                    min = ":15";
                    break;
                case 2:
                    min = ":30";
                    break;
                case 3:
                    min = ":45";
            }
            const cas = document.createElement("div");
            const info = document.createElement("p");
            cas.addEventListener("click", hourClick);
            cas.classList.add("hourPanel");
            cas.dataset.shift = str;
            info.textContent = (opening+i) + min;
            cas.appendChild(info);
            div.appendChild(cas);
        }
    }
}

/* Funzione che estrae l'ora da un formato 'HH:mm' */
function hour(string) {
    return Number(string.match(/^[0-9]+/));
}

/* Funzione che permette di aggiornare il testo informativo del pannello di destra */
function updateText(tcontent, dcontent, fcontent = "") {
    const title = document.getElementById("infoTitle");
    const desc = document.getElementById("infoDesc");
    const foot = document.getElementById("infoFoot");
    title.textContent = tcontent;
    desc.textContent = dcontent;
    foot.textContent = fcontent;
}

function formHandler() {
    const num = document.getElementById("clients");
    if (!num.validity.valid) {
        alert("Il numero inserito supera la capienza massima del locale.");
        return;
    }
    updateText("Seleziona un orario", "Seleziona un orario per procedere al riepilogo");
    const form = document.getElementById("coversForm");
    form.style.display = "none";
    const shifts = document.getElementById("shifts");
    shifts.style.display = "block";
    const clients = Number(num.value);
    checkAvailability(clients);
}

/* Funzione che rimuove elementi da shiftData in base ai posti disponibili */
function checkAvailability(clients) {
    /* Controllo sul giorno corrente */
    let currentDate = new Date();
    if (currentDate.getDate() == selectedDate.getDate()) {
        let currentHour = currentDate.getHours();
        for (let shift of data.shifts) {
            let opening = hour(shift['Opening']);
            let closure = hour(shift['Closure']);
            if (currentHour >= opening || currentHour >= closure) {
                if (shift['Type'] == "0")
                    delete shiftData.Lunch;
                else 
                    delete shiftData.Dinnner;
            }
        }
    }
    /* Controllo sulla capienza */
    const maxCovers = data.restaurant['MaxCovers'];
    const sumLunch = Number(shiftData['Lunch']);
    const sumDinner = Number(shiftData['Dinner']);
    if (sumLunch + clients > maxCovers)
        delete shiftData.Lunch;
    if (sumDinner + clients > maxCovers)
        delete shiftData.Dinner;
    /* Stampo gli orari */
    showShifts();
}

async function hourClick(e) {
    const num = document.getElementById("clients");
    let resid = data.restaurant['Id'];
    let date = selectedDate.toLocaleDateString('sv-SE');
    let shift = e.currentTarget.dataset.shift;
    let hour = e.currentTarget.firstChild.textContent;
    await sendData(resid, date, shift, hour, num.value);
}

/* Funzione chiamata al verificarsi di un click su un giorno del calendario */
async function calendarClick(e) {
    updateText("In quanti siete?", "Inserisci il numero di ospiti");
    /* Gestione colore dell'elemento */
    const prevSel = document.querySelector(".selected");
    if (prevSel) {
        prevSel.classList.remove("selected");
    }
    e.target.classList.add("selected");
    const form = document.getElementById("coversForm");
    form.style.display = "flex";
    const shifts = document.getElementById("shifts");
    shifts.style.display = "none";
    /*
        Aggiungo a selectedDate l'informazione sul giorno selezionato
        prendendo il dato direttamente da e.target
    */
    let year = selectedDate.getFullYear();
    let month = selectedDate.getMonth() + 1;
    let day = e.target.textContent;
    let formattedDate = dateFormat(year, month, day);
    let date = new Date(formattedDate);
    /* Aggiorno selectedDate */
    selectedDate = date;
    let weekDay = (date.getDay() + 6) % 7;
    shiftData = await getShiftData(data.restaurant.Id, formattedDate, weekDay);
    removeHours();
}

async function main() {
    /* Recupero le informazioni sul ristorante dal database */
    data = await getResData();
    /* Aggiorno l'input di tipo number con il massimo numero di coperti */
    updateNumber(data.restaurant['MaxCovers']);
    /* Mostro il calendario */
    generateCalendar(data);
    updateText("Seleziona una data", "Seleziona una data dal calendario a sinistra per continuare");
}