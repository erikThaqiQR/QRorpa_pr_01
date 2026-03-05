
<script>
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 3,
        spaceBetween: 10,
        breakpoints: {
            // when window width is >= 320px
            320: {
            slidesPerView: 3,
            spaceBetween: 10
            },
            // when window width is >= 480px
            480: {
            slidesPerView: 3,
            spaceBetween: 10
            },
            // when window width is >= 640px
            640: {
            slidesPerView: 4,
            spaceBetween: 10
            }
        }
    });

    $('.prodsFoto').each(function() {
        $(this).hide();
    });

    $('.katPics').each(function() {
        $(this).hide();
    });
    function oneVisitToProd(pId){
        $.ajax({
            url: '{{ route("produktet.newClick") }}',
            method: 'post',
            data: {
                id: pId,
                _token: '{{csrf_token()}}'
            },
            success: () => {},
            error: (error) => {console.log(error);}
        });
    }
</script>
