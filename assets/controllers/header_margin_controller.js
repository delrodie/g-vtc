import {Controller} from '@hotwired/stimulus';

export default class HeaderController extends Controller {
    connect() {
        this.adjustMainMargin();
        window.addEventListener('resize', this.adjustMainMargin);
    }
    disconnect() {
        window.removeEventListener('resize', this.adjustMainMargin);
    }

    adjustMainMargin() {
        const header = document.getElementById("gHeader");
        const main = document.getElementById("gMain");
        if (header && main) {
            const headerHeight = header.offsetHeight;
            main.style.marginTop = headerHeight + 20 + "px";
        }
    }
}
