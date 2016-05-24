<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/php/utils/ErrorUtils.php';
    
    ErrorUtils::redirectToCustomErrorPage("This is a custom error message for testing purposes...", "Custom Error Test");
?>

This should never be sent to the client...

<script>
    alert("If this message popped up, the custom error redirect didn't work.");
</script>