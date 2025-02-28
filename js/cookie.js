document.addEventListener("DOMContentLoaded", function () {
    const cookieBanner = document.querySelector(".dt-cookie-consent");
    const acceptButton = document.querySelector(".accept-cookies");
    const rejectButton = document.querySelector(".reject-cookies");
    const cookieName = "cookieConsent";

    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + value + "; path=/" + expires;
    }

    function getCookie(name) {
        const cookies = document.cookie.split("; ");
        for (let i = 0; i < cookies.length; i++) {
            const [cookieName, cookieValue] = cookies[i].split("=");
            if (cookieName === name) {
                return cookieValue;
            }
        }
        return null;
    }

    if (getCookie(cookieName)) {
        cookieBanner.style.display = "none";
    }

    acceptButton.addEventListener("click", function (event) {
        event.preventDefault();
        setCookie(cookieName, "accepted", 365); // Aceito por 1 ano
        cookieBanner.style.display = "none";
    });

    rejectButton.addEventListener("click", function (event) {
        event.preventDefault();
        setCookie(cookieName, "rejected", 365); // Rejeitado por 1 ano
        cookieBanner.style.display = "none";
    });
});
