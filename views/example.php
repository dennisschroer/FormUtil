<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>FormUtil Example</title>

    <!-- Styles -->
    <!-- The current template uses the Twitter Bootstrap style to style the form -->
    <link href="<?php echo base_url('assets/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/bootstrap-responsive.css'); ?>" rel="stylesheet" type="text/css">
    
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>    

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="container">

        <div class="page-header"><h1>Formutil example</h1></div>
        Page rendered in <strong>{elapsed_time}</strong> seconds, using <strong>{memory_usage}</strong> of memory.
        <div class="row">
            <div class="span8 offset2">
                <h3>Result</h3>
                <div class="well">
                    <?php $this->formutil->generate_form('testform'); ?>
                </div>
            </div>
        </div>

    </div>
    
    <script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.js'); ?>"></script>    
  </body>
</html>
