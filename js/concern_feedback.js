$(document).ready(function () {
    const $stars = $('.stars i');
    const $starsNone = $('.rating-box');

    // ---- ---- Stars ---- ---- //
    $stars.each(function (index1, star) {
        $(star).on('click', function () {
            var i = 0;
            $stars.each(function (index2, star) {
                // ---- ---- Active Star ---- ---- //
                if (index1 >= index2) {
                    $(star).addClass('active');
                    i++;
                } else {
                    $(star).removeClass('active');
                }

            });
            $('#rating_value').val(i);
        });
    });

    $('#submit_concern_feedback').click(function () {
        event.preventDefault();

        var rating = $('#rating_value').val();
        var feedback_date = $('#feedback_date').val();
        var id = $('#id').val();

        if (rating == '') {
            $('#ratingCheck').show();
        } else {
            $('#ratingCheck').hide();


            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to Close this Concern?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, close it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: 'concernFile/AddConcernFeedback.php',
                        dataType: 'json',
                        type: 'post',
                        data: { 'id': id, "ratingVal": rating, "feedbackDate": feedback_date },
                        cache: false,
                        success: function (response) {
                            if (response.includes('Concern')) {
                                Swal.fire(
                                    'Deleted!',
                                    'Concern has been Closed Successfully.',
                                    'success'
                                )
                                setTimeout(function () {
                                    window.location = 'edit_concern_feedback';
                                }, 2000)
                            }
                        }
                    })
                }
            })
        }
    });


});//document END;
