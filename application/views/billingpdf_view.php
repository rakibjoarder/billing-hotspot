
<?php

require_once($template_name);

echo $this->fpdf->Output('Invoice-#'.$id_invoice.'.pdf','I');

 ?>
