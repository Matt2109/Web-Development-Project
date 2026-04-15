/*
    Inizializzaione eventListener per la ricerca del ristorante
*/
function init() {
    const search = document.getElementById("search");
    search.addEventListener("keyup", check);
}

/*
    Funzione che mostra o nasconde i panel dei ristoranti a seconda
    della stringa immessa nella barra di ricerca
*/
function check(e) {
    const div = document.getElementById("restSection");
    let text = e.target.value;
    let restaurants = div.children;
    for (let restaurant of restaurants) {
        let name = restaurant.querySelector("p");
        if (!name) 
            continue;
        name = name.textContent;
        if (name.toLowerCase().includes(text.toLowerCase())) {
            restaurant.style.display = "flex";
        } else {
            restaurant.style.display = "none";
        }
    }
}

/*
    Chiamata a 'setrestaurantid.php' che salva in $_SESSION l'id del ristorante selezionato
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

        window.location.href = "../html/reservation.html"; 
    }
    catch (error) {
        console.error(error);
        window.location.href = "../html/error.html";
    }
}

/*
    Chiamata a 'removereserv.php' per la rimozione di una prenotazione
*/
async function removeReserv(event) {
    try {
        const response = await fetch("../php/removereserv.php", 
            {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json'
            },
            body: JSON.stringify({
               id: Number(event.target.parentElement.id)
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
                case 'bad_id':
                    alert("L'id della prenotazione non è valido");
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