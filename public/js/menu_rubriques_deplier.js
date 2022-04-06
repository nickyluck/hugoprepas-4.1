var deplier = function (event) {
    event.preventDefault()
    var ul = this.nextElementSibling
    ul.classList.toggle('off')
    var img = this.previousElementSibling
    if (img.getAttribute('src') == '/img/deplierhaut.gif') {
        img.setAttribute('src', '/img/deplierbas.gif')
    } else {
        img.setAttribute('src', '/img/deplierhaut.gif')
    }
}

var classes = document.querySelectorAll('span.classe')
for (var i = 0; i < classes.length; i++) {
    classes[i].addEventListener('click', deplier)
}
