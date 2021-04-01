/********************************************************/
/*    FONCTIONS                                         */
/********************************************************/
function onSubmitCommentForm(event)
{
    // Annule le comportement par défaut du navigateur (soumission du formulaire)
    event.preventDefault();

    // Récupération des données du formulaire
    const data = new FormData(form);

    // Construire l'URL de la requête AJAX
    const url = new URL(window.location.href);

    // Envoi de la requête avec fetch
    fetch(`/ajax${url.pathname}`, {
        'method': 'post',
        'body': data
    })
    .then(response => response.json())
    .then(handleAjaxResponse);
}

function handleAjaxResponse(data)
{
    if (data.success) {

        // Vider le formulaire
        form.reset();

        // Affichage du message de confirmation
        const div = document.createElement('div');
        div.textContent = data.message;
        div.classList.add('alert', 'alert-success', 'message');
        form.parentNode.insertBefore(div, form);

        // Affichage du commentaire
        const list = document.getElementById('comments-list');
        list.innerHTML = data.comment + list.innerHTML;
    }
    else {

        const errors = data.errors;

        for(const field in errors) {

            const message = errors[field];

            // Affichage du message d'erreur
            const div = document.createElement('div');
            div.textContent = message;
            div.classList.add('alert', 'alert-danger', 'message');

            const input = document.getElementById('comment_' + field);
            input.parentNode.insertBefore(div, input);
        }
    }

    // Disparition des messages au bout d'un certain temps
    const messages = document.querySelectorAll('.message');
    for (const message of messages) {
        setTimeout(function(){
            message.remove();
        }, 3000);
    }
}


/********************************************************/
/*    CODE PRINCIPAL                                    */
/********************************************************/
const form = document.querySelector('form[name="comment"]');
form.addEventListener('submit', onSubmitCommentForm);


