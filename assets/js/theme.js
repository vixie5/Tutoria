function setCookie(nom, valeur, jours) {
    const d = new Date();
    d.setTime(d.getTime() + (jours*24*60*60*1000));
    const expire = "expires=" + d.toUTCString();
    document.cookie = nom + "=" + valeur + ";" + expire + ";path=/";
}

function getCookie(nom) {
    const cnom = nom + "=";
    const decodedCookie = decodeURIComponent(document.cookie);
    const ca = decodedCookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(cnom) === 0) {
            return c.substring(cnom.length, c.length);
        }
    }
    return "";
}

function setLightTheme() {
    document.body.className = 'light-theme';
    setCookie('theme', 'light-theme', 7);
}

function setDarkTheme() {
    document.body.className = 'dark-theme';
    setCookie('theme', 'dark-theme', 7);
}

function applyTheme() {
    const theme = getCookie('theme');
    if (theme === 'dark-theme') {
        setDarkTheme();
    } else {
        setLightTheme();
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    applyTheme();
});
