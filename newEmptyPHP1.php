<html>

    <?php
//function printAttendanceList(){
    /*
      include "Dompdf/dompdf_config.inc.php";
      $dompdf = new Dompdf();





      $dompdf->set_option('enable_remote', TRUE);
      $dompdf->load_Html($html);
      $dompdf->set_Paper('A4', 'landscape');
      $dompdf->render();
      $dompdf->stream();


      $file_to_save = '/media/Recibo.pdf';
      file_put_contents($file_to_save, $dompdf->output());
     */


// instantiate and use the dompdf class
    include "Dompdf/dompdf_config.inc.php";
    $dompdf = new Dompdf();
    $html = "
					<html>
					<body>
						Hola mundo !!!
						</body>
					</html>";
    $dompdf->load_html($html);

// (Optional) Setup the paper size and orientation
    $dompdf->set_paper('A4', 'landscape');

// Render the HTML as PDF
    $dompdf->render();

// Output the generated PDF to Browser
    $dompdf->stream('document.pdf');
    $file_to_save = 'media/Recibo.pdf';
    file_put_contents($file_to_save, $dompdf->output());
    ?>


