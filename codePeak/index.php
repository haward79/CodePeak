<?php

    /*
     *  Redirect to index page(bulletin) with get variables from source page.
     */

    header('Location: bulletin/index.php?'.$_SERVER['QUERY_STRING']);

