function adjustMainMargin() {
    const header = document.getElementById("gHeader");
    const main = document.getElementById("gMain");
    if (header && main) {
        const headerHeight = header.offsetHeight;
        main.style.marginTop = headerHeight + 20 + "px";
    }
}

// Ajuste au chargement de la page
window.addEventListener("load", adjustMainMargin);

// Réajuste si la fenêtre est redimensionnée
window.addEventListener("resize", adjustMainMargin);

document.addEventListener("turbo:load", function () {
    adjustMainMargin();
});
