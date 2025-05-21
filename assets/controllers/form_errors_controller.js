import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('turbo:submit-end', event => {
            const {detail } = event;
            if (detail.success) return;

            const html = detail.fetchResponse.html;
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            this.element.replaceWith(doc.querySelector('form'));
        })
    }
}
