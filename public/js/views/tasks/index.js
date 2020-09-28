
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('approve-program').addEventListener('click', function () {
        $('#approve-program-modal').modal();
    });

    $('.edit-task-assignments').on('click',  function(event) {
        const elem = event.target;
        console.log(elem);
        $('#task-assignments-modal').modal();
    })
});
