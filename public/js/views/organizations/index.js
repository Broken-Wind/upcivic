
document.addEventListener('DOMContentLoaded', function() {
    $('.select-area-button').on('click', function (event) {
        const organization = organizations.find(organization => organization.id == $(this).data('organization-id'));
        const updateAction = updateOrganizationAreaUrl + "/" + organization.id + "/areas";
        $('.area-radio').prop("checked", false);
        $('.area-radio[value="' + organization.area_id + '"]').prop("checked", true);
        $('#select-area-modal-title').html(organization.name);
        $('#update-site-area-form').attr('action', updateAction);
    });
});
