!function(){let t=document.getElementById("send-email-form");t.onsubmit=function(){let e=new FormData(t);return fetch(`http://${window.location.hostname}/api/emails`,{method:"POST",body:e}).then((function(){window.alert("Email sent successfully"),t.reset()})).catch((function(t){window.alert(t)})),!1}}();
//# sourceMappingURL=client.a1927748.js.map
