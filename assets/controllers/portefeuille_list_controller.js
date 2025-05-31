import { Controller } from '@hotwired/stimulus'

export default class extends Controller {


    static targets = ["result", "sections"]
    affichageList(e) {
        e.preventDefault()

        const url = e.currentTarget.dataset.portefeuilleListUrlValue;
        const type = e.currentTarget.dataset.portefeuilleListTypeValue;
        const periode = e.currentTarget.dataset.portefeuilleListPeriodeValue;

        console.log({url, type, periode})
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                type,
                periode
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Erreur réseau")
                }
                console.log(response)
                return response.text() // 👈 car Symfony retourne un fragment HTML
            })
            .then(html => {
                // console.log('HTML reçu :', html);
                this.resultTarget.innerHTML = html

            })
            .catch(error => {
                this.resultTarget.innerHTML = `<div class="alert alert-danger">Erreur : ${error.message}</div>`
            })
    }

}
