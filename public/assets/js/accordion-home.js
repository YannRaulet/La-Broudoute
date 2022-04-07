// cf base.html.twig for js registration
const accordion = document.getElementsByClassName('contentBox');

for (i=0; i < accordion.length; i++) {
    accordion[i].addEventListener('click', function(){
        this.classList.toggle('active')
    })
}