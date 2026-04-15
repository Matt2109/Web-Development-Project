function goBack() {
    history.back();
}

/*
    Chiamata a 'addreservation.php' per l'aggiunta di una prenotazione
*/
async function confirmReservation() {
    try {
        const response = await fetch("../addreservation.php");
        if (!response.ok) throw new Error('HTTP error');

        const jsonData = await response.json();
        if (!jsonData.success) {
            window.location.href = "../../html/error.html";
            return;
        }

        window.location.href = "../index.php";
    } 
    catch (error) {
        console.error(error);
        window.location.href = "../../html/error.html";
    }
}