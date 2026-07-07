<footer>

    <div class="container">

        <p>&copy; <?php echo date("Y"); ?> KABINE38 - Sport. Menschen. Region.</p>

    </div>

</footer>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>

const swiper = new Swiper('.heroSwiper', {

    loop:true,

    autoplay:{
        delay:5000,
        disableOnInteraction:false,
    },

    pagination:{
        el:'.swiper-pagination',
        clickable:true,
    },

    navigation:{
        nextEl:'.swiper-button-next',
        prevEl:'.swiper-button-prev',
    },

});

</script>

</body>
</html>