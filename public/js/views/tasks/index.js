
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('approve-program').addEventListener('click', function () {
        $('#approve-program-modal').modal();
    });

    $('.edit-task-assignments').on('click',  function() {
        let taskId = $(this).attr('data-task-id');
        const task = tasks.find(task => task.id == taskId);
        $('.assignment-checkbox').each(function (i, box) {
            console.log('-------------------------');
            console.log(task.assigned_to_organizations);
            console.log($(box).val());
            console.log(task.assigned_to_organizations.includes($(box).val()));
            if (task.assigned_to_organizations.includes($(box).val())) {
                $(box).prop('checked', true);
            } else {
                $(box).prop('checked', false);
            }
        });
        $('#assignTaskId').val(taskId);
        $('#task-assignments-modal').modal();
    })
});
