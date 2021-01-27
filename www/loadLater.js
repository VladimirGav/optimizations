<script type="application/javascript">
    // Загрузить содержимое <noscript class="loadLater"></noscript>  после загрузки страницы
    document.addEventListener("DOMContentLoaded", function(event) {
        var loadLater = document.querySelector('.loadLater');
        var head = document.querySelector('head');
        if(loadLater && head){
            head.insertAdjacentHTML('beforeend', loadLater.innerHTML);
        }
    });
</script>