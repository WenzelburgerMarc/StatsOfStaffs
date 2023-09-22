import './bootstrap';
import '@fortawesome/fontawesome-free/css/all.css'
import nightwind from 'nightwind/helper';

window.addEventListener('DOMContentLoaded', function () {
    const toggler = document.getElementById("darkmode-toggler");
    const sunIcon = toggler.querySelector(".fa-sun");
    const moonIcon = toggler.querySelector(".fa-moon");
    const htmlElement = document.documentElement;

    function setDarkMode(isDarkMode) {
        if (isDarkMode) {
            htmlElement.classList.add("dark");
            sunIcon.style.transform = "translateX(-30px)";
            moonIcon.style.transform = "translateX(0)";
            sunIcon.classList.remove("active");
            moonIcon.classList.add("active");
            nightwind.enable(true)
        } else {
            htmlElement.classList.remove("dark");
            sunIcon.style.transform = "translateX(0)";
            moonIcon.style.transform = "translateX(30px)";
            sunIcon.classList.add("active");
            moonIcon.classList.remove("active");
            nightwind.enable(false)
        }
    }

    const isDarkMode = htmlElement.classList.contains("dark");
    setDarkMode(isDarkMode);

    toggler.addEventListener("click", function () {
        const isDarkMode = !htmlElement.classList.contains("dark");
        setDarkMode(isDarkMode);
    });


});

