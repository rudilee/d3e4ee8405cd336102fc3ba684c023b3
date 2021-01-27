import Swal from "sweetalert2";

(function () {
    let sendEmailForm = document.getElementById('send-email-form');
    sendEmailForm.onsubmit = function () {
        const emailData = new FormData(sendEmailForm);
        const token = window.localStorage.getItem('token');

        fetch(`http://${window.location.hostname}/api/emails`, {
            method: 'POST',
            headers: {
                'Authorization': `bearer ${token}`
            },
            body: emailData
        }).then(function (response) {
            if (response.ok) {
                Swal.fire('Email sent successfully');
                sendEmailForm.reset();
            } else {
                Swal.fire('Email not sent');
            }
        }).catch(function (reason) {
            Swal.fire(reason);
        });

        return false;
    };

    Swal.fire({
        title: 'Login Form',
        html: `
        <form class="pure-form pure-form-aligned" id="login-form">
            <input type="text" id="username" name="username" placeholder="Username" required maxlength="50">
            <br>
            <br>
            <input type="password" id="password" name="password" placeholder="Password" required>
        </form>
        `,
        confirmButtonText: 'Login',
        focusConfirm: false,
        preConfirm: () => {
            const username = Swal.getPopup().querySelector('#username').value;
            const password = Swal.getPopup().querySelector('#password').value;

            if (!username || !password) {
                Swal.showValidationMessage('Please enter valid username & password');
            }

            return fetch(`http://${window.location.hostname}/login`, {
                method: 'POST',
                body: new FormData(document.getElementById('login-form')),
            });
        }
    }).then(function (result) {
        if (result.value.ok) {
            const token = result.value.headers.get('X-API-KEY');
            window.localStorage.setItem('token', token);
        }

        Swal.fire(result.value.ok ? 'Login success' : 'Login failed');
    });
})();