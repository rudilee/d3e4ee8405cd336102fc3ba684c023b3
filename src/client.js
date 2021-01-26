(function () {
    let sendEmailForm = document.getElementById('send-email-form');
    sendEmailForm.onsubmit = function () {
        let emailData = new FormData(sendEmailForm);
        fetch(`http://${window.location.hostname}/api/emails`, {
            method: 'POST',
            body: emailData
        }).then(function () {
            window.alert('Email sent successfully');
            sendEmailForm.reset();
        }).catch(function (reason) {
            window.alert(reason);
        });

        return false;
    };
})();