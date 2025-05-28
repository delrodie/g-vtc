import {Controller} from '@hotwired/stimulus';
export default class  extends Controller {
    static values={
        url: String,
        csrfToken: String
    };

    connect(){
        this.element.addEventListener('click', this.confirmAndDelete.bind(this));
    }

    disconnect(){
        this.element.removeEventListener('click', this.confirmAndDelete.bind(this));
    }

    confirmAndDelete(e) {
        e.preventDefault();
        if (confirm('Voulez-vous vraiment supprimer cette opération?')){
            this.deleteItem();
        }
    }

    deleteItem() {
        fetch(this.urlValue,{
            method: 'DELETE',
            headers:{
                'X-CSRF-Token': this.csrfTokenValue,
                'Content-Type': 'application/json',
            }
        })
            .then(response => {
                if (response.ok) {
                    this.element.closest('tr').remove();
                } else{
                    console.error('Erreur de suppression: ', response);
                    alert('Une erreur est survenue lors de la suppression');
                }
        } )
            .catch(error => {
                console.error('Erreur de reseau: ', error);
                alert("Erreur de réseau lors de la suppression");
            })
    }
}
