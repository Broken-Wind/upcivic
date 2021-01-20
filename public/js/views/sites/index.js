
document.addEventListener('DOMContentLoaded', function() {
    $('.select-area-button').on('click', function (event) {
        const site = sites.find(site => site.id == $(this).data('site-id'));
        const updateAction = updateSiteAreaUrl + "/" + site.id + "/areas";
        $('.area-radio').prop("checked", false);
        $('.area-radio[value="' + site.area_id + '"]').prop("checked", true);
        $('#select-area-modal-title').html(site.name);
        $('#update-site-area-form').attr('action', updateAction);
    });
});
