<?php if(isset($_SESSION['unique_id'])) $id = $_SESSION['unique_id']; ?>

<script>
	
var _wasPageCleanedUp = false;
function pageCleanup()
{
    if (!_wasPageCleanedUp)
    {
        $.ajax({
            type: 'GET',
            async: false,
            url: 'php/set-offline.php?id=<?php echo $id; ?>',
            success: function ()
            {
                _wasPageCleanedUp = true;
                console.log(123);
            }
        });
    }
}


$(window).on("unload", function (e)
{
    pageCleanup();
});

window.addEventListener('offline', function(e) {
	pageCleanup();
});;

</script>
