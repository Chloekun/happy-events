// Simple calendar helper (placeholder)
function initCalendar(selector) {
    var el = document.querySelector(selector);
    if(!el) return;
    el.innerHTML = '<input type="date">';
}

document.addEventListener('DOMContentLoaded', function(){
    initCalendar('.date-picker');
});
