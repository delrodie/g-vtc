import { Controller} from '@hotwired/stimulus';
export default class extends Controller {
    static values={
        url: String,
        type: String,
        periode: String,
    };

    connect() {
        this.element.addEventListener('click', this.affichageList.bind(this));
    }

    disconnect() {
        this.element.addEventListener('click', this.affichageList.bind(this));
    }

    affichageList(e){
        e.preventDefault();
        console.log(this.typeValue);
        console.log(this.periodeValue);
        fetch(this.urlValue, {
            method: 'POST',
            headers:{
                'Content-Type': 'application/json',
                'X-Requested-with': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                type: this.typeValue,
                periode: this.periodeValue
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log(data); // ici tu peux gérer l'affichage
            });
    }
}
