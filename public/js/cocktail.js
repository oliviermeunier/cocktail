
function onSubmitCommentForm(event)
{
    event.preventDefault();

    console.log('formulaire soumis !');
}


// document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[name="comment"]');
    form.addEventListener('submit', onSubmitCommentForm);
// })


