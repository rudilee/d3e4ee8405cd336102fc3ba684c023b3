export class EmailForm extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = '<h1>Form to send Email</h1>';
    }
}
