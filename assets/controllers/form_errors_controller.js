import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('turbo:submit-end', event => {
            const {detail } = event;
            if (detail.success) return;

            const html = detail.fetchResponse.html;
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Mise à jour du formulaire
            this.element.replaceWith(doc.querySelector('form'));

            // Mise à jour des flash messages
            const flashMessagesContainer = document.getElementById('flash-messages');
            const newFlashMessages = doc.querySelector('#flash-messages');

            if (flashMessagesContainer && newFlashMessages) {
                flashMessagesContainer.innerHTML = newFlashMessages.innerHTML;
            }
        })
    }
}
